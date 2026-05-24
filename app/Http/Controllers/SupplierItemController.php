<?php
namespace App\Http\Controllers;
use App\Models\Supplier;
use App\Models\SupplierItem;
use App\Models\Item;
use Illuminate\Http\Request;

class SupplierItemController extends Controller
{
    public function index(Supplier $supplier)
    {
        $supplier->load('items.unit');
        return view('supplier-items.index', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier)
    {
        $request->validate([
            'item_id'    => 'required|exists:items,id',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        // Prevent duplicate
        $exists = $supplier->supplierItems()
                           ->where('item_id', $request->item_id)
                           ->exists();
        if ($exists) {
            return back()->withErrors(['item_id' => 'This item is already linked to this supplier.']);
        }

        $supplier->supplierItems()->create([
            'item_id'    => $request->item_id,
            'unit_price' => $request->unit_price,
        ]);

        return back()->with('success', 'Item linked to supplier.');
    }

    public function update(Request $request, Supplier $supplier, SupplierItem $supplierItem)
    {
        $request->validate(['unit_price' => 'nullable|numeric|min:0']);
        $supplierItem->update(['unit_price' => $request->unit_price]);
        return back()->with('success', 'Price updated.');
    }

    public function destroy(Supplier $supplier, SupplierItem $supplierItem)
    {
        $supplierItem->delete();
        return back()->with('success', 'Item removed from supplier.');
    }
}