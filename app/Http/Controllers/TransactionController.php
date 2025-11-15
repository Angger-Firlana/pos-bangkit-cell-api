<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Transaction::with('operator');

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            return response()->json(['success' => true, 'data' => $query->get()]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $trx = Transaction::with(['details.variant.device', 'details.variant.service'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $trx]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'id_operator' => 'required|exists:users,id',
                'customer_name' => 'required|string',
                'customer_phone' => 'nullable|string',
                'keluhan' => 'nullable|string',
                'metode_pembayaran' => 'nullable|string',
                'details' => 'required|array|min:1',
            ]);

            $trx = Transaction::create([
                'id_operator' => $validated['id_operator'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'keluhan' => $validated['keluhan'] ?? null,
                'jumlah_bayar' => $request['jumlah_bayar'] ?? null,
                'kembalian' => $request['kembalian'] ?? null,
                'qris_reference' => $request['qr_reference']??null,
                'metode_pembayaran' => $validated['metode_pembayaran'] ?? 'cash',
                'status' => 'pending',
            ]);

            $total = 0;
            foreach ($validated['details'] as $detail) {
                $total += $detail['harga'];
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'device_service_variant_id' => $detail['device_service_variant_id'],
                    'harga' => $detail['harga'],
                ]);
            }

            $trx->update(['total' => $total]);
            DB::commit();

            return response()->json(['success' => true, 'data' => $trx->load('details.variant')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $trx = Transaction::findOrFail($id);
            $trx->update(['status' => $request->status]);
            return response()->json(['success' => true, 'data' => $trx]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $trx = Transaction::findOrFail($id);
            $trx->delete();
            return response()->json(['success' => true, 'message' => 'Transaction deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $trx = Transaction::withTrashed()->findOrFail($id);
            $trx->forceDelete();
            return response()->json(['success' => true, 'message' => 'Transaction permanently deleted.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            $trx = Transaction::withTrashed()->findOrFail($id);
            $trx->restore();
            return response()->json(['success' => true, 'message' => 'Transaction restored successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
