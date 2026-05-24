<?php
namespace App\Http\Controllers;
use App\Helpers\CodeGenerator;
use App\Models\Item;
use App\Models\PurchaseHistory;
use App\Models\PurchaseHistoryItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseHistory::with('supplier', 'user');

        if ($request->filled('supplier_id'))
            $query->where('supplier_id', $request->supplier_id);
        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('from'))
            $query->whereDate('purchase_date', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('purchase_date', '<=', $request->to);

        $purchases = $query->latest('purchase_date')->paginate(20)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $items     = Item::with('unit')->orderBy('name')->get();
        return view('purchases.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'             => 'required|exists:suppliers,id',
            'purchase_date'           => 'required|date',
            'items'                   => 'required|array|min:1',
            'items.*.item_id'         => 'required|exists:items,id',
            'items.*.quantity'        => 'required|numeric|min:0.01',
            'items.*.unit_price'      => 'required|numeric|min:0',
            'items.*.expiry_date'     => 'nullable|date',
        ]);

        $itemIds = array_column($request->items, 'item_id');
        if (count($itemIds) !== count(array_unique($itemIds))) {
            return back()->withInput()
                ->withErrors(['items' => 'Duplicate items are not allowed in one purchase.']);
        }

        DB::transaction(function () use ($request) {
            $total = collect($request->items)
                ->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            $purchase = PurchaseHistory::create([
                'supplier_id'      => $request->supplier_id,
                'user_id'          => auth()->id(),
                'reference_number' => CodeGenerator::referenceNumber(),
                'purchase_date'    => $request->purchase_date,
                'total_amount'     => $total,
                'status'           => 'pending',
            ]);

            foreach ($request->items as $line) {
                PurchaseHistoryItem::create([
                    'purchase_history_id' => $purchase->id,
                    'item_id'             => $line['item_id'],
                    'quantity'            => $line['quantity'],
                    'unit_price'          => $line['unit_price'],
                    'subtotal'            => $line['quantity'] * $line['unit_price'],
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded.');
    }

    public function show(PurchaseHistory $purchase)
    {
        $purchase->load('supplier', 'user', 'items.item.unit');
        return view('purchases.show', compact('purchase'));
    }

    // Mark as received — triggers stock-in
    public function markReceived(PurchaseHistory $purchase)
    {
        if ($purchase->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending purchases can be marked as received.']);
        }

        DB::transaction(function () use ($purchase) {
            $purchase->update(['status' => 'received']);

            foreach ($purchase->items as $line) {
                // Create stock movement
                StockMovement::create([
                    'item_id'             => $line->item_id,
                    'user_id'             => auth()->id(),
                    'purchase_history_id' => $purchase->id,
                    'type'                => 'stock_in',
                    'quantity'            => $line->quantity,
                    'lot_number'          => CodeGenerator::lotNumber(),
                    'reason'              => 'Purchase received: ' . $purchase->reference_number,
                ]);

                // Increment stock
                DB::table('items')
                    ->where('id', $line->item_id)
                    ->increment('current_stock', $line->quantity);
            }
        });

        return redirect()->route('purchases.show', $purchase)
                         ->with('success', 'Purchase marked as received. Stock updated.');
    }

    public function cancel(PurchaseHistory $purchase)
    {
        if ($purchase->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending purchases can be cancelled.']);
        }
        $purchase->update(['status' => 'cancelled']);
        return back()->with('success', 'Purchase cancelled.');
    }
}