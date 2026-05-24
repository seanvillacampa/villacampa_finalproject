<?php
namespace App\Http\Controllers;
use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;

class UnitOfMeasureController extends Controller
{
    public function index()
    {
        $units = UnitOfMeasure::withCount('items')->orderBy('name')->paginate(20);
        return view('units-of-measure.index', compact('units'));
    }

    public function create()
    {
        return view('units-of-measure.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255|unique:units_of_measure,name',
            'abbreviation' => 'required|string|max:20|unique:units_of_measure,abbreviation',
        ]);
        UnitOfMeasure::create($request->only('name', 'abbreviation'));
       return back()->with('success', 'Unit created.');
    }

    public function edit(UnitOfMeasure $unit)
    {
        return view('units-of-measure.edit', compact('unit'));
    }

    public function update(Request $request, UnitOfMeasure $unit)
    {
        $request->validate([
            'name'         => 'required|string|max:255|unique:units_of_measure,name,' . $unit->id,
            'abbreviation' => 'required|string|max:20|unique:units_of_measure,abbreviation,' . $unit->id,
        ]);
        $unit->update($request->only('name', 'abbreviation'));
        return back()->with('success', 'Unit updated.');
    }

public function destroy(UnitOfMeasure $unit)
{
    try {
        $unit->delete();
        return back()->with('success', 'Unit deleted successfully.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            return back()->with('error', 'Cannot delete "'.$unit->name.'" — it is still assigned to one or more items.');
        }
        throw $e;
    }
}
}