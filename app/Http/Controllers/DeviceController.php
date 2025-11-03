<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Device::query();

            if ($request->has('search')) {
                $search = $request->search;
                $query->where('merek', 'like', "%$search%")
                      ->orWhere('model', 'like', "%$search%");
            }

            return response()->json(['success' => true, 'data' => $query->get()]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $device = Device::with(['variants.service'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $device]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'merek' => 'required|string',
                'model' => 'required|string',
                'tipe' => 'nullable|string'
            ]);
            $device = Device::create($validated);
            return response()->json(['success' => true, 'data' => $device], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $device = Device::findOrFail($id);
            $device->update($request->only(['merek', 'model', 'tipe']));
            return response()->json(['success' => true, 'data' => $device]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Device::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Device deleted']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
