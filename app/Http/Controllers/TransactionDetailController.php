<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Service;
use Exception;

class TransactionDetailController extends Controller
{
    // GET /api/transactions/{id}/details
    public function index($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $details = TransactionDetail::with('service')->where('id_transaksi', $id)->get();

            return response()->json([
                'status' => true,
                'data' => [
                    'transaction' => $transaction,
                    'details' => $details
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /api/transactions/{id}/details/{detailId}
    public function show($id, $detailId)
    {
        try {
            $detail = TransactionDetail::with('service')
                ->where('id_transaksi', $id)
                ->where('id', $detailId)
                ->firstOrFail();

            return response()->json(['status' => true, 'data' => $detail], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Detail tidak ditemukan'], 404);
        }
    }

    // POST /api/transactions/{id}/details
    public function store(Request $request, $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->status !== 'pending') {
                return response()->json(['status' => false, 'message' => 'Transaksi sudah dibayar atau dibatalkan'], 400);
            }

            $data = $request->validate([
                'id_service' => 'required|exists:services,id',
                'harga_jual' => 'required|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'catatan_perubahan' => 'nullable|string'
            ]);

            $subtotal = $data['harga_jual'] - ($data['diskon'] ?? 0);

            $detail = TransactionDetail::create([
                'id_transaksi' => $id,
                'id_service' => $data['id_service'],
                'harga_jual' => $data['harga_jual'],
                'diskon' => $data['diskon'] ?? 0,
                'subtotal' => $subtotal,
                'catatan_perubahan' => $data['catatan_perubahan'] ?? null,
            ]);

            // Update total transaksi
            $transaction->total = TransactionDetail::where('id_transaksi', $id)->sum('subtotal');
            $transaction->save();

            return response()->json(['status' => true, 'message' => 'Item berhasil ditambahkan', 'data' => $detail], 201);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateHargaModal(Request $request,  $detailId)
    {
        try {
            $detail = TransactionDetail::findOrFail($detailId);

            $data = $request->validate([
                'harga_modal' => 'required|numeric|min:0',
            ]);

            $detail->update([
                'harga_modal' => $data['harga_modal'],
            ]);

            return response()->json(['status' => true, 'message' => 'Harga modal berhasil diperbarui', 'data' => $detail], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // PUT /api/transactions/{id}/details/{detailId}
    public function update(Request $request, $id, $detailId)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $detail = TransactionDetail::findOrFail($detailId);

            if ($transaction->status !== 'pending') {
                return response()->json(['status' => false, 'message' => 'Tidak bisa update item pada transaksi non-pending'], 400);
            }

            $data = $request->validate([
                'harga_jual' => 'required|numeric|min:0',
                'harga_modal' => 'nullable|numeric|min:0',
                'catatan_perubahan' => 'nullable|string'
            ]);

            $subtotal = $data['harga_jual'];

            $detail->update([
                'harga_jual' => $data['harga_jual'],
                'subtotal' => $subtotal,
                'catatan_perubahan' => $data['catatan_perubahan'] ?? null,
            ]);

            $transaction->total = TransactionDetail::where('id_transaksi', $id)->sum('subtotal');
            $transaction->save();

            return response()->json(['status' => true, 'message' => 'Item berhasil diperbarui', 'data' => $detail], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/transactions/{id}/details/{detailId}
    public function destroy($id, $detailId)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $detail = TransactionDetail::findOrFail($detailId);

            if ($transaction->status !== 'pending') {
                return response()->json(['status' => false, 'message' => 'Tidak bisa hapus item pada transaksi non-pending'], 400);
            }

            $detail->delete();
            $transaction->total = TransactionDetail::where('id_transaksi', $id)->sum('subtotal');
            $transaction->save();

            return response()->json(['status' => true, 'message' => 'Item berhasil dihapus', 'data' => $transaction], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
