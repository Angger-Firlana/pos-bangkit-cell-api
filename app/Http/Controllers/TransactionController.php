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
            $query = Transaction::with(['details.variant.device', 'details.variant.service']);

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
                'user_id' => 'required|exists:users,id',
                'device_id' => 'required|exists:devices,id',
                'customer_name' => 'required|string',
                'customer_phone' => 'nullable|string',
                'keluhan' => 'nullable|string',
                'payment_method' => 'nullable|string',
                'details' => 'required|array|min:1',
            ]);

            $trx = Transaction::create([
                'user_id' => $validated['user_id'],
                'device_id' => $validated['device_id'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'keluhan' => $validated['keluhan'] ?? null,
                'payment_method' => $validated['payment_method'] ?? 'cash',
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

            $trx->update(['total_harga' => $total]);
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
}
