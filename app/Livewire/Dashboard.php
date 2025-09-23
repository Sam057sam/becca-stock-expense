<?php

namespace App\Livewire;

use App\Models\CompanySetting;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'products' => Product::count(),
            'warehouses' => Warehouse::count(),
            'units' => Unit::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
        ];

        $lowStock = Product::with('warehouse')
            ->orderBy('stock_quantity')
            ->take(6)
            ->get();

        $recentProducts = Product::with(['unit', 'warehouse'])
            ->latest()
            ->take(5)
            ->get();

        $company = CompanySetting::first();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'lowStock' => $lowStock,
            'recentProducts' => $recentProducts,
            'company' => $company,
        ])->layout('layouts.app', [
            'title' => 'Dashboard',
            'header' => 'Dashboard',
            'subheader' => 'Snapshot of your stock and expense operations',
        ]);
    }
}