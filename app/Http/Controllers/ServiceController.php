<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Service::query();
            if ($request->has('search')) {
                $query->where('nama', 'like', "%{$request->search}%");
            }
            return response()->json(['success' => true, 'data' => $query->get()]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $service = Service::with('variants.device')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $service]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string',
                'deskripsi' => 'nullable|string'
            ]);

            $service = Service::create($validated);
            return response()->json(['success' => true, 'data' => $service], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->update($request->only(['nama', 'deskripsi']));
            return response()->json(['success' => true, 'data' => $service]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Service::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Service deleted']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
