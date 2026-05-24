<?php
namespace App\Http\Controllers;
use App\Models\Breed;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function index()
    {
        $breeds = Breed::withCount('animals')->orderBy('name')->paginate(20);
        return view('breeds.index', compact('breeds'));
    }

    public function create()
    {
        return view('breeds.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:breeds,name',
            'species'     => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);
        Breed::create($request->only('name', 'species', 'description'));
        return redirect()->route('breeds.index')->with('success', 'Breed added.');
    }

    public function edit(Breed $breed)
    {
        return view('breeds.edit', compact('breed'));
    }

    public function update(Request $request, Breed $breed)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:breeds,name,' . $breed->id,
            'species'     => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);
        $breed->update($request->only('name', 'species', 'description'));
        return redirect()->route('breeds.index')->with('success', 'Breed updated.');
    }

public function destroy(Breed $breed)
{
    try {
        $breed->delete();
        return redirect()->route('breeds.index')
                         ->with('success', 'Breed deleted successfully.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            return redirect()->route('breeds.index')
                             ->with('error', 'Cannot delete "'.$breed->name.'" — one or more animals are registered under this breed.');
        }
        throw $e;
    }
}
}