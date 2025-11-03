<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DeviceServiceVariant;
use App\Models\PriceLog;
use Illuminate\Http\Request;

class DeviceServiceVariantController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = DeviceServiceVariant::with(['device', 'service']);
            
            if ($request->has('device_id')) {
                $query->where('device_id', $request->device_id);
            }

            if ($request->has('service_id')) {
                $query->where('service_id', $request->service_id);
            }

            return response()->json(['success' => true, 'data' => $query->get()]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $variant = DeviceServiceVariant::with(['device', 'service'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $variant]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'device_id' => 'required|exists:devices,id',
                'service_id' => 'required|exists:services,id',
                'tipe_part' => 'nullable|string',
                'harga_min' => 'required|numeric|min:0',
                'harga_max' => 'required|numeric|min:0|gte:harga_min',
                'catatan' => 'nullable|string',
            ]);

            $variant = DeviceServiceVariant::create($validated);
            return response()->json(['success' => true, 'data' => $variant], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $variant = DeviceServiceVariant::findOrFail($id);

            PriceLog::create([
                'device_service_variant_id' => $variant->id,
                'user_id' => auth()->id(),
                'old_harga_min' => $variant->harga_min,
                'old_harga_max' => $variant->harga_max,
                'new_harga_min' => $request->harga_min,
                'new_harga_max' => $request->harga_max,
                'tipe_part' => $variant->tipe_part,
            ]);

            $variant->update($request->only(['harga_min', 'harga_max', 'tipe_part', 'catatan']));
            return response()->json(['success' => true, 'data' => $variant]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DeviceServiceVariant::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Variant deleted']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
