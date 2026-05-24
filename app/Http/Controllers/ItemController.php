<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Helpers\CodeGenerator;
use App\Models\Category;
use App\Models\Item;
use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
{
    $query = Item::with('category', 'unit');

    if ($request->filled('category_id'))
        $query->where('category_id', $request->category_id);

    if ($request->filled('status')) {
        if ($request->status === 'no stock')
            $query->where('current_stock', 0);
        elseif ($request->status === 'low stock')
            $query->whereColumn('current_stock', '<=', 'reorder_level')
                  ->where('current_stock', '>', 0);
        elseif ($request->status === 'enough stock')
            $query->whereColumn('current_stock', '>', 'reorder_level');
    }

    if ($request->filled('search'))
        $query->where('name', 'like', '%' . $request->search . '%');

    $items      = $query->orderBy('name')->paginate(20)->withQueryString();
    $categories = Category::orderBy('name')->get();
    $units      = UnitOfMeasure::orderBy('name')->get(); // ← add this

    return view('items.index', compact('items', 'categories', 'units')); // ← add units here
}

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units      = UnitOfMeasure::orderBy('name')->get();
        return view('items.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:items,name',
            'category_id'   => 'required|exists:categories,id',
            'unit_id'       => 'required|exists:units_of_measure,id',
            'reorder_level' => 'required|numeric|min:0',
            'description'   => 'nullable|string|max:500',
        ]);

        $item = Item::create([
            'name'          => $request->name,
            'sku'           => 'TEMP-' . time(),
            'category_id'   => $request->category_id,
            'unit_id'       => $request->unit_id,
            'reorder_level' => $request->reorder_level,
            'description'   => $request->description,
        ]);

        // Generate SKU now that we have the real ID
        DB::table('items')->where('id', $item->id)->update([
            'sku' => CodeGenerator::sku($item->category->name, $item->id),
        ]);

        return redirect()->route('items.index')->with('success', 'Item added.');
    }

    public function show(Item $item)
    {
        $item->load('category', 'unit', 'stockMovements.user', 'suppliers');
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();
        $units      = UnitOfMeasure::orderBy('name')->get();
        return view('items.edit', compact('item', 'categories', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:items,name,' . $item->id,
            'category_id'   => 'required|exists:categories,id',
            'unit_id'       => 'required|exists:units_of_measure,id',
            'reorder_level' => 'required|numeric|min:0',
            'description'   => 'nullable|string|max:500',
        ]);

        $item->update($request->only(
            'name', 'category_id', 'unit_id',
            'reorder_level', 'description'
        ));

        return redirect()->route('items.index')->with('success', 'Item updated.');
    }

public function destroy(Item $item)
{
    try {
        $item->delete();
        return redirect()->route('items.index')
                         ->with('success', 'Item deleted successfully.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            return redirect()->route('items.index')
                             ->with('error', 'Cannot delete "'.$item->name.'" — it has stock-out or purchase records attached to it.');
        }
        throw $e;
    }
}
}