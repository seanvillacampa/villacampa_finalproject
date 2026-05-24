<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockOut;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index(Request $request)
{
    $query = StockOut::with('item.unit', 'user', 'animal');

    if ($request->filled('reason'))
        $query->where('reason', 'like', '%' . $request->reason . '%');
    if ($request->filled('item_id'))
        $query->where('item_id', $request->item_id);
    if ($request->filled('from'))
        $query->whereDate('date', '>=', $request->from);
    if ($request->filled('to'))
        $query->whereDate('date', '<=', $request->to);

    $stockOuts = $query->latest('date')->paginate(20)->withQueryString();
    $items     = Item::with('unit', 'category')->orderBy('name')->get(); // ← add 'category'
    $animals   = Animal::with('breed')                                   // ← add this
                    ->where('status', 'active')
                    ->orderBy('tag_number')
                    ->get();

    return view('stock-outs.index', compact('stockOuts', 'items', 'animals'));
}
    public function store(Request $request)
    {
        $request->validate([
            'item_id'   => 'required|exists:items,id',
            'reason'    => 'required|string|max:255',
            'quantity'  => 'required|numeric|min:0.01',
            'date'      => 'required|date|before_or_equal:today',
            'animal_id' => 'nullable|exists:animals,id',
            'notes'     => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $item = Item::findOrFail($request->item_id);

            if ($item->current_stock < $request->quantity) {
                throw new \Exception(
                    "Insufficient stock for {$item->name}. Available: {$item->current_stock} {$item->unit->abbreviation}."
                );
            }

            StockOut::create([
                'item_id'   => $request->item_id,
                'user_id'   => auth()->id(),
                'animal_id' => $request->animal_id,
                'reason'    => $request->reason,
                'quantity'  => $request->quantity,
                'notes'     => $request->notes,
                'date'      => $request->date,
            ]);

            DB::table('items')
                ->where('id', $item->id)
                ->decrement('current_stock', $request->quantity);
        });

        return redirect()->route('stock-outs.index')
                         ->with('success', 'Stock-out recorded successfully.');
    }

    public function show(StockOut $stockOut)
    {
        $stockOut->load('item.unit', 'user', 'animal');
        return view('stock-outs.show', compact('stockOut'));
    }

public function destroy(StockOut $stockOut)
{
    try {
        DB::transaction(function () use ($stockOut) {
            DB::table('items')
                ->where('id', $stockOut->item_id)
                ->increment('current_stock', $stockOut->quantity);
            $stockOut->delete();
        });
        return redirect()->route('stock-outs.index')
                         ->with('success', 'Stock-out deleted and stock restored.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            return redirect()->route('stock-outs.index')
                             ->with('error', 'Cannot delete this stock-out record — it has dependent records attached to it.');
        }
        throw $e;
    }
}
}