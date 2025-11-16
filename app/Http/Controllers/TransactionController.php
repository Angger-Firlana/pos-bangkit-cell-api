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

    public function updateStatusAndPayment(Request $request, $id)
    {
        try {
            $trx = Transaction::findOrFail($id);
            $trx->update([
                'status' => $request->status,
                'jumlah_bayar' => $request->jumlah_bayar ?? $trx->jumlah_bayar,
                'kembalian' => $request->kembalian ?? $trx->kembalian,
                'qris_reference' => $request->qris_reference ?? $trx->qris_reference,
                'metode_pembayaran' => $request->metode_pembayaran ?? $trx->metode_pembayaran,

            ]);
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

    public function getMargin()
    {
        try {
            $margins = TransactionDetail::select(
                'transaction_details.id',
                'device_service_variants.tipe_part as variant_name',
                'services.nama as service_name',
                'devices.model as device_name',
                'transaction_details.harga as harga_jual',
                'transaction_details.harga_modal',
                DB::raw('(transaction_details.harga - transaction_details.harga_modal) as margin')
            )
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.created_at', '>=', now()->subMonth())
            ->join('device_service_variants', 'transaction_details.device_service_variant_id', '=', 'device_service_variants.id')
            ->join('services', 'device_service_variants.service_id', '=', 'services.id')
            ->join('devices', 'device_service_variants.device_id', '=', 'devices.id')
            ->get();

            return response()->json(['success' => true, 'data' => $margins]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
