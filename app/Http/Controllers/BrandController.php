<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::with('devices')->get();
            return response()->json($brands, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data brand', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $brand = Brand::with('devices')->findOrFail($id);
            return response()->json($brand, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Brand tidak ditemukan', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:100',
                'negara_asal' => 'nullable|string|max:100'
            ]);

            $brand = Brand::create($request->all());
            return response()->json(['message' => 'Brand berhasil dibuat', 'data' => $brand], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal membuat brand', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->update($request->all());
            return response()->json(['message' => 'Brand berhasil diperbarui', 'data' => $brand], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui brand', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();
            return response()->json(['message' => 'Brand berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal menghapus brand', 'error' => $e->getMessage()], 500);
        }
    }
}
