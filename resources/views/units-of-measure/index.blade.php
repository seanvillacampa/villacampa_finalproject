<x-layouts.app-dash title="Units of Measure">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Units of Measure</h1>
            <p>Manage measurement units for items</p>
        </div>
    </div>

@if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

    <div class="grid-form-table">

        <div class="card">
            <div class="card-head"><h2>Add Unit</h2></div>
            <div class="card-body">
                <form method="POST" action="/units">
                    @csrf
                    <div class="fg">
                        <label>Unit Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Kilogram" required>
                    </div>
                    <div class="fg">
                        <label>Abbreviation</label>
                        <input type="text" name="abbreviation" value="{{ old('abbreviation') }}" placeholder="e.g. kg" required>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Unit</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Units</h3>
                <span class="sec-meta">{{ $units->total() }} total</span>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Abbreviation</th>
                            <th>Items</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td style="font-weight:600;color:var(--green-dark);">{{ $unit->name }}</td>
                            <td><span class="badge badge-gray">{{ $unit->abbreviation }}</span></td>
                            <td><span class="badge badge-green">{{ $unit->items_count }}</span></td>
                            <td>
                                <div class="btn-row">
                                    <a href="/units/{{ $unit->id }}/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="/units/{{ $unit->id }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"
                                            onclick="return confirm('Delete {{ $unit->name }}?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="tbl-empty">No units yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($units->hasPages())
            <div class="pag-wrap">{{ $units->links() }}</div>
            @endif
        </div>

    </div>
</div>
</x-layouts.app-dash>