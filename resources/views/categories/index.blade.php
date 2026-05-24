<x-layouts.app-dash title="Categories">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Categories</h1>
            <p>Manage item categories</p>
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
            <div class="card-head"><h2>Add Category</h2></div>
            <div class="card-body">
                <form method="POST" action="/categories">
                    @csrf
                    <div class="fg">
                        <label>Category Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Fertilizers" required>
                    </div>
                    <div class="fg">
                        <label>Description <span class="hint" style="display:inline;">(optional)</span></label>
                        <textarea name="description" placeholder="Short description...">{{ old('description') }}</textarea>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Categories</h3>
                <span class="sec-meta">{{ $categories->total() }} total</span>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Description</th><th>Items</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td style="font-weight:600;color:var(--green-dark);">{{ $category->name }}</td>
                            <td>{{ $category->description ?? '—' }}</td>
                            <td><span class="badge badge-green">{{ $category->items_count }}</span></td>
                            <td>
                                <div class="btn-row">
                                    <a href="/categories/{{ $category->id }}/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="/categories/{{ $category->id }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"
                                            onclick="return confirm('Delete {{ $category->name }}?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="tbl-empty">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
            <div class="pag-wrap">{{ $categories->links() }}</div>
            @endif
        </div>

    </div>
</div>
</x-layouts.app-dash>