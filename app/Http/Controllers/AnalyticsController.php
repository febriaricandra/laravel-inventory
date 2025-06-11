<?php

namespace App\Http\Controllers;

use App\Models\TransactionProduct;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // json
        $totalTransactions = $this->getTotalTransactions();
        $productMovement = $this->getProductMovement();
        $supplierStats = $this->getSupplierStats();
        $userActivity = $this->getUserActivity();

        return response()->json([
            'total_transactions' => $totalTransactions,
            'product_movement' => $productMovement,
            'supplier_stats' => $supplierStats,
            'user_activity' => $userActivity,
        ]);
    }

    private function getTotalTransactions()
    {
        return TransactionProduct::select(
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month'),
            'type',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('COUNT(*) as transaction_count')
        )
            ->groupBy('month', 'type')
            ->orderBy('month', 'desc')
            ->get();
    }

    private function getProductMovement()
    {
        return TransactionProduct::select(
            'product_id',
            DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as net_movement')
        )
            ->groupBy('product_id')
            ->with('product')
            ->get();
    }

    private function getSupplierStats()
    {
        return TransactionProduct::select(
            'supplier_id',
            DB::raw('COUNT(*) as delivery_count'),
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->where('type', 'in')
            ->whereNotNull('supplier_id')
            ->groupBy('supplier_id')
            ->with('supplier')
            ->get();
    }

    private function getUserActivity()
    {
        return TransactionProduct::select(
            'user_id',
            DB::raw('COUNT(*) as transaction_count')
        )
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user')
            ->get();
    }
}
