<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Device;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    //
    public function index(){
        $services = Service::count();
        $brands = Brand::count();
        $devices = Device::count();
        $transactions = Transaction::where('status', 'pending')->count();
        $users = User::count();
        $totalRevenue = Transaction::where('status', 'success')->sum('total');
        $salesData = Transaction::selectRaw('DATE(created_at) as date, SUM(total) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        $deviceDistribution = Device::join('brands', 'devices.brand_id', '=', 'brands.id')
            ->select('brands.nama as name', DB::raw('COUNT(devices.id) as value'))
            ->groupBy('brands.id', 'brands.nama')
            ->get();
        $recentActivities = Transaction::where('created_at', '>=', now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'total_services' => $services,
            'total_brands' => $brands,
            'total_devices' => $devices,
            'total_transactions' => $transactions,
            'total_users' => $users,
            'total_revenue' => $totalRevenue,
            'sales_data' => $salesData,
            'device_distribution' => $deviceDistribution->toArray(),
            'recent_activities' => $recentActivities
        ]);
    }


     public function report()
    {
        // Total revenue & transaksi sukses
        $totalRevenue = Transaction::where('status', 'success')->sum('total');
        $totalTransactions = Transaction::where('status', 'success')->count();

        // Data penjualan per bulan
        $salesData = Transaction::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month_number,
                DATE_FORMAT(created_at, "%b %Y") as month,
                SUM(total) as revenue,
                COUNT(*) as transactions
            ')
            ->where('status', 'success')
            ->groupBy('year', 'month_number', 'month')
            ->orderBy('year')
            ->orderBy('month_number')
            ->get();

        // Data performa per service
        $servicePerformance = TransactionDetail::select(
                'services.nama as service',
                DB::raw('SUM(transaction_details.harga) as revenue'),
                DB::raw('COUNT(transaction_details.id) as transactions')
            )
            ->join('device_service_variants', 'device_service_variants.id', '=', 'transaction_details.device_service_variant_id')
            ->join('services', 'services.id', '=', 'device_service_variants.service_id')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.status', 'success')
            ->groupBy('services.nama')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Transaction report fetched successfully',
            'data' => [
                'salesData' => $salesData,
                'servicePerformance' => $servicePerformance,
                'totalRevenue' => $totalRevenue,
                'totalTransactions' => $totalTransactions,
            ]
        ]);
    }
}
