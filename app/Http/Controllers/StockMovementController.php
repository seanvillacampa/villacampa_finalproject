<?php
namespace App\Http\Controllers;
use App\Helpers\CodeGenerator;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('item', 'user');

        if ($request->filled('type'))
            $query->where('type', $request->type);
        if ($request->filled('item_id'))
            $query->where('item_id', $request->item_id);
        if ($request->filled('from'))
            $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('created_at', '<=', $request->to);

        $movements = $query->latest()->paginate(20)->withQueryString();
        $items     = Item::orderBy('name')->get();
        return view('stock-movements.index', compact('movements', 'items'));
    }

    public function create()
    {
        $items = Item::with('unit')->orderBy('name')->get();
        return view('stock-movements.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id'     => 'required|exists:items,id',
            'type'        => 'required|in:stock_in,stock_out',
            'quantity'    => 'required|numeric|min:0.01',
            'expiry_date' => 'nullable|date',
            'reason'      => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $item = Item::findOrFail($request->item_id);

            if ($request->type === 'stock_out') {
                if ($item->current_stock < $request->quantity) {
                    throw new \Exception(
                        "Insufficient stock. Available: {$item->current_stock} {$item->unit->abbreviation}"
                    );
                }
                DB::table('items')
                    ->where('id', $item->id)
                    ->decrement('current_stock', $request->quantity);
            } else {
                DB::table('items')
                    ->where('id', $item->id)
                    ->increment('current_stock', $request->quantity);
            }

            StockMovement::create([
                'item_id'     => $request->item_id,
                'user_id'     => auth()->id(),
                'type'        => $request->type,
                'quantity'    => $request->quantity,
                'lot_number'  => $request->type === 'stock_in'
                                    ? CodeGenerator::lotNumber()
                                    : null,
                'expiry_date' => $request->expiry_date,
                'reason'      => $request->reason,
            ]);
        });

        return redirect()->route('stock-movements.index')
                         ->with('success', 'Stock movement recorded.');
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load('item.unit', 'user');
        return view('stock-movements.show', compact('stockMovement'));
    }
}