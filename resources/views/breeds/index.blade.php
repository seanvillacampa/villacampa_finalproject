<x-layouts.app-dash title="Breeds">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Breeds</h1>
            <p>Manage animal breeds and species</p>
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
            <div class="card-head"><h2>Add Breed</h2></div>
            <div class="card-body">
                <form method="POST" action="/breeds">
                    @csrf
                    <div class="fg">
                        <label>Breed Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Landrace" required>
                    </div>
                    <div class="fg">
                        <label>Species</label>
                        <input type="text" name="species" value="{{ old('species') }}" placeholder="e.g. Swine, Cattle, Poultry" required>
                        <span class="hint">The animal type this breed belongs to.</span>
                    </div>
                    <div class="fg">
                        <label>Description <span class="hint" style="display:inline;">(optional)</span></label>
                        <textarea name="description" placeholder="Short description...">{{ old('description') }}</textarea>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Breed</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Breeds</h3>
                <span class="sec-meta">{{ $breeds->total() }} total</span>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>#</th><th>Breed Name</th><th>Species</th><th>Description</th><th>Animals</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($breeds as $breed)
                        <tr>
                            <td>{{ $breed->id }}</td>
                            <td style="font-weight:600;color:var(--green-dark);">{{ $breed->name }}</td>
                            <td><span class="badge badge-blue">{{ $breed->species }}</span></td>
                            <td>{{ $breed->description ?? '—' }}</td>
                            <td><span class="badge badge-green">{{ $breed->animals_count }}</span></td>
                            <td>
                                <div class="btn-row">
                                    <a href="/breeds/{{ $breed->id }}/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="/breeds/{{ $breed->id }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"
                                            onclick="return confirm('Delete {{ $breed->name }}?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="tbl-empty">No breeds yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($breeds->hasPages())
            <div class="pag-wrap">{{ $breeds->links() }}</div>
            @endif
        </div>

    </div>
</div>
</x-layouts.app-dash>