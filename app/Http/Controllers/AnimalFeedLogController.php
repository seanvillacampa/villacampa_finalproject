<?php
namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Animal;
use App\Models\AnimalFeedLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalFeedLogController extends Controller
{
    private function getFeedItems()
    {
        return Item::whereHas('category', fn($q) => $q->where('name', 'like', '%feed%'))
                   ->with('unit')
                   ->orderBy('name')
                   ->get();
    }

    public function index(Request $request)
    {
        $query = AnimalFeedLog::with('animal', 'item.unit', 'user');

        if ($request->filled('animal_id'))
            $query->where('animal_id', $request->animal_id);
        if ($request->filled('from'))
            $query->whereDate('feed_date', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('feed_date', '<=', $request->to);

        $logs    = $query->latest('feed_date')->paginate(20)->withQueryString();
        $animals = Animal::where('status', 'active')->orderBy('tag_number')->get();
        $items   = $this->getFeedItems();

        return view('feed-logs.index', compact('logs', 'animals', 'items'));
    }

    public function create()
    {
        $animals = Animal::where('status', 'active')->orderBy('tag_number')->get();
        $items   = $this->getFeedItems();
        return view('feed-logs.create', compact('animals', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'item_id'   => 'required|exists:items,id',
            'quantity'  => 'required|numeric|min:0.01',
            'feed_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $item = Item::findOrFail($request->item_id);

                if ($item->current_stock < $request->quantity) {
                    throw new \Exception(
                        "Insufficient stock for {$item->name}. Available: {$item->current_stock} {$item->unit->abbreviation}."
                    );
                }

                AnimalFeedLog::create([
                    'animal_id' => $request->animal_id,
                    'item_id'   => $request->item_id,
                    'user_id'   => auth()->id(),
                    'quantity'  => $request->quantity,
                    'feed_date' => $request->feed_date,
                ]);

                StockOut::create([
                    'item_id'   => $request->item_id,
                    'user_id'   => auth()->id(),
                    'animal_id' => $request->animal_id,
                    'reason'    => 'feed_log',
                    'quantity'  => $request->quantity,
                    'notes'     => "Feed log for animal #{$request->animal_id} on {$request->feed_date}",
                    'date'      => $request->feed_date,
                ]);

                DB::table('items')
                    ->where('id', $request->item_id)
                    ->decrement('current_stock', $request->quantity);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('feed-logs.index')->with('success', 'Feed log saved and stock deducted.');
    }

    public function show(AnimalFeedLog $feedLog)
    {
        $feedLog->load('animal', 'item.unit', 'user');
        return view('feed-logs.show', compact('feedLog'));
    }

    public function destroy(AnimalFeedLog $feedLog)
    {
        try {
            DB::transaction(function () use ($feedLog) {
                DB::table('items')
                    ->where('id', $feedLog->item_id)
                    ->increment('current_stock', $feedLog->quantity);

                StockOut::where('item_id', $feedLog->item_id)
                        ->where('animal_id', $feedLog->animal_id)
                        ->where('reason', 'feed_log')
                        ->where('quantity', $feedLog->quantity)
                        ->where('date', $feedLog->feed_date)
                        ->latest()
                        ->first()
                        ?->delete();

                $feedLog->delete();
            });
            return redirect()->route('feed-logs.index')
                             ->with('success', 'Feed log deleted and stock restored.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('feed-logs.index')
                                 ->with('error', 'Cannot delete this feed log — it has dependent records attached to it.');
            }
            throw $e;
        }
    }
}