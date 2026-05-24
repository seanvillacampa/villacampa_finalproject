<?php
namespace App\Http\Controllers;
use App\Helpers\CodeGenerator;
use App\Models\Animal;
use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $query = Animal::with('breed');

        if ($request->filled('breed_id'))
            $query->where('breed_id', $request->breed_id);
        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('sex'))
            $query->where('sex', $request->sex);
        if ($request->filled('search'))
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('tag_number', 'like', '%' . $request->search . '%');
            });

        $animals = $query->latest()->paginate(20)->withQueryString();
        $breeds  = Breed::orderBy('name')->get();
        return view('animals.index', compact('animals', 'breeds'));
    }

    public function create()
    {
        $breeds = Breed::orderBy('name')->get();
        return view('animals.create', compact('breeds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'nullable|string|max:255',
            'breed_id'  => 'required|exists:breeds,id',
            'sex'       => 'required|in:male,female',
            'weight'    => 'required|numeric|min:0',
            'birthdate' => 'nullable|date|before_or_equal:today',
        ]);

        $animal = Animal::create([
            'name'       => $request->name,
            'tag_number' => 'TEMP-' . time(),
            'breed_id'   => $request->breed_id,
            'sex'        => $request->sex,
            'weight'     => $request->weight,
            'birthdate'  => $request->birthdate,
            'status'     => 'active',
        ]);

        // Generate tag number using real ID + species from breed
        DB::table('animals')->where('id', $animal->id)->update([
            'tag_number' => CodeGenerator::tagNumber(
                $animal->breed->species,
                $animal->id
            ),
        ]);

        return redirect()->route('animals.index')->with('success', 'Animal registered.');
    }

    public function show(Animal $animal)
    {
        $animal->load('breed', 'healthLogs.user', 'feedLogs.item', 'feedLogs.user');
        return view('animals.show', compact('animal'));
    }

    public function edit(Animal $animal)
    {
        $breeds = Breed::orderBy('name')->get();
        return view('animals.edit', compact('animal', 'breeds'));
    }

    public function update(Request $request, Animal $animal)
    {
        $request->validate([
            'name'      => 'nullable|string|max:255',
            'breed_id'  => 'required|exists:breeds,id',
            'sex'       => 'required|in:male,female',
            'weight'    => 'required|numeric|min:0',
            'birthdate' => 'nullable|date|before_or_equal:today',
            'status'    => 'required|in:active,sold,deceased,transferred',
        ]);

        $animal->update($request->only(
            'name', 'breed_id', 'sex',
            'weight', 'birthdate', 'status'
        ));

        return redirect()->route('animals.index')->with('success', 'Animal updated.');
    }

public function destroy(Animal $animal)
{
    try {
        $animal->delete();
        return redirect()->route('animals.index')
                         ->with('success', 'Animal deleted successfully.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            return redirect()->route('animals.index')
                             ->with('error', 'Cannot delete animal '.$animal->tag_number.' — it has health logs, feed logs, or stock-out records attached to it.');
        }
        throw $e;
    }
}
}