<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PriceLog;

class PriceLogController extends Controller
{
    public function index()
    {
        try {
            $logs = PriceLog::with(['variant.device', 'variant.service', 'user'])
                ->orderByDesc('changed_at')
                ->get();

            return response()->json(['success' => true, 'data' => $logs]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $log = PriceLog::with(['variant.device', 'variant.service', 'user'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $log]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
