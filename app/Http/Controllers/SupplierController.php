<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchaseHistories');
        if ($request->filled('search'))
            $query->where('name', 'like', '%' . $request->search . '%');
        $suppliers = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        return view('suppliers.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'contact_person'   => 'required|string|max:255',
            'email'            => 'nullable|email|max:255',
            'phone'            => 'required|string|max:50',
            'street_address'   => 'required|string|max:255',
            'barangay_address' => 'required|string|max:255',
            'city_address'     => 'required|string|max:255',
            'province_address' => 'required|string|max:255',
            'items'            => 'nullable|array',
            'items.*.id'       => 'exists:items,id',
            'items.*.price'    => 'nullable|numeric|min:0',
        ]);

        $supplier = Supplier::create($request->only(
            'name', 'contact_person', 'email', 'phone',
            'street_address', 'barangay_address',
            'city_address', 'province_address'
        ));

        if ($request->filled('items')) {
            $sync = [];
            foreach ($request->items as $entry) {
                $sync[$entry['id']] = ['unit_price' => $entry['price'] ?? null];
            }
            $supplier->items()->sync($sync);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier added.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('items.unit', 'purchaseHistories.user');
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $items = Item::orderBy('name')->get();
        return view('suppliers.edit', compact('supplier', 'items'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'contact_person'   => 'required|string|max:255',
            'email'            => 'nullable|email|max:255',
            'phone'            => 'required|string|max:50',
            'street_address'   => 'required|string|max:255',
            'barangay_address' => 'required|string|max:255',
            'city_address'     => 'required|string|max:255',
            'province_address' => 'required|string|max:255',
            'items'            => 'nullable|array',
            'items.*.id'       => 'exists:items,id',
            'items.*.price'    => 'nullable|numeric|min:0',
        ]);

        $supplier->update($request->only(
            'name', 'contact_person', 'email', 'phone',
            'street_address', 'barangay_address',
            'city_address', 'province_address'
        ));

        if ($request->filled('items')) {
            $sync = [];
            foreach ($request->items as $entry) {
                $sync[$entry['id']] = ['unit_price' => $entry['price'] ?? null];
            }
            $supplier->items()->sync($sync);
        } else {
            $supplier->items()->detach();
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated.');
    }

public function destroy(Supplier $supplier)
{
    try {
        $supplier->delete();
        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier deleted successfully.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            return redirect()->route('suppliers.index')
                             ->with('error', 'Cannot delete "'.$supplier->name.'" — it has purchase records attached to it.');
        }
        throw $e;
    }
}
}