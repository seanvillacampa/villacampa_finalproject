<?php
namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalHealthLog;
use App\Models\Item;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalHealthLogController extends Controller
{
    private function getMedicineItems()
    {
        return Item::whereHas('category', fn($q) => $q->where('name', 'like', '%medicine%'))
                   ->orderBy('name')
                   ->get();
    }

    public function index(Request $request)
    {
        $query = AnimalHealthLog::with('animal', 'user', 'item');

        if ($request->filled('type'))
            $query->where('type', $request->type);
        if ($request->filled('animal_id'))
            $query->where('animal_id', $request->animal_id);
        if ($request->filled('upcoming'))
            $query->whereNotNull('next_schedule_date')
                  ->where('next_schedule_date', '<=', now()->addDays(7))
                  ->where('next_schedule_date', '>=', now());

        $logs      = $query->latest('log_date')->paginate(20)->withQueryString();
        $animals   = Animal::where('status', 'active')->orderBy('tag_number')->get();
        $medicines = $this->getMedicineItems();

        return view('health-logs.index', compact('logs', 'animals', 'medicines'));
    }

    public function create()
    {
        $animals   = Animal::where('status', 'active')->orderBy('tag_number')->get();
        $medicines = $this->getMedicineItems();
        return view('health-logs.create', compact('animals', 'medicines'));
    }

    public function store(Request $request)
    {
        $usesMedicine = in_array($request->type, ['vaccination', 'deworming', 'medication']);

        $request->validate([
            'animal_id'          => 'required|exists:animals,id',
            'type'               => 'required|in:vaccination,deworming,medication,vet_visit,other',
            'description'        => 'required|string|max:500',
            'item_id'            => $usesMedicine ? 'nullable|exists:items,id' : 'nullable',
            'medicine_used'      => 'nullable|string|max:255',
            'dosage_quantity'    => $usesMedicine && $request->filled('item_id') ? 'required|numeric|min:0.01' : 'nullable|numeric',
            'dosage'             => 'nullable|string|max:255',
            'administered_by'    => 'nullable|string|max:255',
            'next_schedule_date' => 'nullable|date|after:today',
            'log_date'           => 'required|date|before_or_equal:today',
        ]);

        $itemId       = $usesMedicine ? $request->item_id : null;
        $medicineUsed = $request->medicine_used;
        $dosageQty    = null;

        if ($itemId) {
            $item         = Item::findOrFail($itemId);
            $medicineUsed = $item->name;
            $dosageQty    = $request->dosage_quantity;

            if ($dosageQty > 0) {
                if ($item->current_stock < $dosageQty) {
                    return back()->withInput()->with('error', "Insufficient stock for {$item->name}. Available: {$item->current_stock} {$item->unit->abbreviation}.");
                }

                StockOut::create([
                    'item_id'   => $item->id,
                    'user_id'   => auth()->id(),
                    'animal_id' => $request->animal_id,
                    'reason'    => 'health_log',
                    'quantity'  => $dosageQty,
                    'notes'     => "Health log: {$request->type} — {$request->description}",
                    'date'      => $request->log_date,
                ]);

                DB::table('items')->where('id', $item->id)->decrement('current_stock', $dosageQty);
            }
        }

        AnimalHealthLog::create([
            'animal_id'          => $request->animal_id,
            'user_id'            => auth()->id(),
            'item_id'            => $itemId,
            'type'               => $request->type,
            'description'        => $request->description,
            'medicine_used'      => $usesMedicine ? $medicineUsed : null,
            'dosage_quantity'    => $dosageQty,
            'dosage'             => $usesMedicine ? $request->dosage : null,
            'administered_by'    => $usesMedicine ? $request->administered_by : null,
            'next_schedule_date' => $request->next_schedule_date,
            'log_date'           => $request->log_date,
        ]);

        return redirect()->route('health-logs.index')->with('success', 'Health log saved.');
    }

    public function show(AnimalHealthLog $healthLog)
    {
        $healthLog->load('animal', 'user', 'item');
        return view('health-logs.show', compact('healthLog'));
    }

    public function edit(AnimalHealthLog $healthLog)
    {
        $animals   = Animal::where('status', 'active')->orderBy('tag_number')->get();
        $medicines = $this->getMedicineItems();
        return view('health-logs.edit', compact('healthLog', 'animals', 'medicines'));
    }

    public function update(Request $request, AnimalHealthLog $healthLog)
    {
        $usesMedicine = in_array($request->type, ['vaccination', 'deworming', 'medication']);

        $request->validate([
            'type'               => 'required|in:vaccination,deworming,medication,vet_visit,other',
            'description'        => 'required|string|max:500',
            'item_id'            => 'nullable|exists:items,id',
            'medicine_used'      => 'nullable|string|max:255',
            'dosage_quantity'    => 'nullable|numeric|min:0.01',
            'dosage'             => 'nullable|string|max:255',
            'administered_by'    => 'nullable|string|max:255',
            'next_schedule_date' => 'nullable|date',
            'log_date'           => 'required|date|before_or_equal:today',
        ]);

        $itemId    = $usesMedicine ? $request->item_id : null;
        $dosageQty = $request->dosage_quantity;
        $oldItemId = $healthLog->item_id;
        $oldDosage = $healthLog->dosage_quantity;

        // Reverse old stock deduction if item or dosage changed
        if ($oldItemId && ($oldItemId != $itemId || $oldDosage != $dosageQty)) {
            $oldItem = Item::find($oldItemId);
            if ($oldItem && $oldDosage > 0) {
                DB::table('items')->where('id', $oldItem->id)->increment('current_stock', $oldDosage);
                StockOut::create([
                    'item_id'   => $oldItem->id,
                    'user_id'   => auth()->id(),
                    'animal_id' => $healthLog->animal_id,
                    'reason'    => 'health_log_reversal',
                    'quantity'  => -$oldDosage,
                    'notes'     => "Reversal: edited health log #{$healthLog->id}",
                    'date'      => $healthLog->log_date,
                ]);
            }
        }

        // Apply new stock deduction
        if ($itemId && $dosageQty > 0) {
            $item = Item::findOrFail($itemId);

            // Re-fetch fresh stock after possible reversal above
            $freshStock = DB::table('items')->where('id', $item->id)->value('current_stock');

            if ($freshStock < $dosageQty) {
                // Undo the reversal we just did before returning error
                if ($oldItemId && ($oldItemId != $itemId || $oldDosage != $dosageQty)) {
                    $oldItem = Item::find($oldItemId);
                    if ($oldItem && $oldDosage > 0) {
                        DB::table('items')->where('id', $oldItem->id)->decrement('current_stock', $oldDosage);
                    }
                }
                return back()->withInput()->with('error', "Insufficient stock for {$item->name}. Available: {$freshStock} {$item->unit->abbreviation}.");
            }

            StockOut::create([
                'item_id'   => $item->id,
                'user_id'   => auth()->id(),
                'animal_id' => $request->animal_id,
                'reason'    => 'health_log',
                'quantity'  => $dosageQty,
                'notes'     => "Health log update: {$request->type} — {$request->description}",
                'date'      => $request->log_date,
            ]);

            DB::table('items')->where('id', $item->id)->decrement('current_stock', $dosageQty);
        }

        $healthLog->update([
            'item_id'            => $itemId,
            'type'               => $request->type,
            'description'        => $request->description,
            'medicine_used'      => $usesMedicine ? ($itemId ? Item::find($itemId)?->name : $request->medicine_used) : null,
            'dosage_quantity'    => $usesMedicine ? $dosageQty : null,
            'dosage'             => $usesMedicine ? $request->dosage : null,
            'administered_by'    => $usesMedicine ? $request->administered_by : null,
            'next_schedule_date' => $request->next_schedule_date,
            'log_date'           => $request->log_date,
        ]);

        return redirect()->route('health-logs.index')->with('success', 'Health log updated.');
    }

    public function destroy(AnimalHealthLog $healthLog)
    {
        if ($healthLog->item_id && $healthLog->dosage_quantity > 0) {
            DB::table('items')->where('id', $healthLog->item_id)->increment('current_stock', $healthLog->dosage_quantity);

            StockOut::create([
                'item_id'   => $healthLog->item_id,
                'user_id'   => auth()->id(),
                'animal_id' => $healthLog->animal_id,
                'reason'    => 'health_log_reversal',
                'quantity'  => -$healthLog->dosage_quantity,
                'notes'     => "Reversal: deleted health log #{$healthLog->id}",
                'date'      => now()->toDateString(),
            ]);
        }

        $healthLog->delete();
        return redirect()->route('health-logs.index')->with('success', 'Health log deleted.');
    }
}