<?php
namespace App\Http\Controllers;
use App\Models\Animal;
use App\Models\AnimalHealthLog;
use App\Models\Item;
use App\Models\StockOut;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
             'totalItems'      => Item::count(),
    'lowStockItems'   => Item::whereColumn('current_stock', '<=', 'reorder_level')
                             ->where('current_stock', '>', 0)->count(),
    'noStockItems'    => Item::where('current_stock', 0)->count(),
    'totalAnimals'    => Animal::where('status', 'active')->count(),
    'totalSuppliers'  => Supplier::count(),

    'recentStockOuts' => StockOut::with('item.unit', 'user', 'animal')
                             ->latest()->take(6)->get(),

    'lowStockAlerts'  => Item::with('unit')
                             ->whereColumn('current_stock', '<=', 'reorder_level')
                             ->orderBy('current_stock')
                             ->take(5)->get(),

    'upcomingHealth'  => AnimalHealthLog::with('animal')
                             ->whereNotNull('next_schedule_date')
                             ->where('next_schedule_date', '>=', now())
                             ->where('next_schedule_date', '<=', now()->addDays(7))
                             ->orderBy('next_schedule_date')
                             ->take(5)->get(),

    'expiringItems'   => collect(),
        ]);
    }
}