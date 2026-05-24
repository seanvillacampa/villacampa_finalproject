<x-layouts.app-dash title=" Items">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Inventory Items</h1>
            <p>Manage farm supply items</p>
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
            <div class="card-head"><h2>Add Item</h2></div>
            <div class="card-body">
                <form method="POST" action="/items">
                    @csrf
                    <div class="fg">
                        <label>Item Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Urea Fertilizer" required>
                    </div>
                    <div class="fg">
                        <label>Category</label>
                        <div class="combobox">
                            <input type="text" id="add-cat-input" autocomplete="off" placeholder="Select Category"
                                   value="{{ $categories->firstWhere('id', old('category_id'))->name ?? '' }}">
                            <select name="category_id" id="add-cat-select" style="display:none;" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="add-cat-dropdown">
                                @foreach($categories as $cat)
                                    <div class="combobox-option" data-value="{{ $cat->id }}" data-label="{{ $cat->name }}" data-search="{{ strtolower($cat->name) }}">{{ $cat->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Unit of Measure</label>
                        <div class="combobox">
                            <input type="text" id="add-unit-input" autocomplete="off" placeholder="Select Unit"
                                   value="{{ $units->firstWhere('id', old('unit_id')) ? $units->firstWhere('id', old('unit_id'))->name.' ('.$units->firstWhere('id', old('unit_id'))->abbreviation.')' : '' }}">
                            <select name="unit_id" id="add-unit-select" style="display:none;" required>
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="add-unit-dropdown">
                                @foreach($units as $unit)
                                    <div class="combobox-option" data-value="{{ $unit->id }}" data-label="{{ $unit->name }} ({{ $unit->abbreviation }})" data-search="{{ strtolower($unit->name.' '.$unit->abbreviation) }}">
                                        {{ $unit->name }} <span style="color:var(--text-muted);font-size:.85em;">({{ $unit->abbreviation }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Reorder Level</label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', 0) }}" min="0" step="0.01" required>
                        <span class="hint">Alert triggers when stock reaches this level.</span>
                    </div>
                    <div class="fg">
                        <label>Description <span class="hint" style="display:inline;">(optional)</span></label>
                        <textarea name="description" placeholder="Item description...">{{ old('description') }}</textarea>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Item</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Items</h3>
                <span class="sec-meta">{{ $items->total() }} total</span>
            </div>
            <form method="GET" action="/items">
                <div class="filter-bar">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search items...">
                    <div class="combobox">
                        <input type="text" id="flt-cat-input" autocomplete="off" placeholder="All Categories"
                               value="{{ $categories->firstWhere('id', request('category_id'))->name ?? '' }}">
                        <select name="category_id" id="flt-cat-select" style="display:none;">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="combobox-dropdown" id="flt-cat-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Categories</div>
                            @foreach($categories as $cat)
                                <div class="combobox-option" data-value="{{ $cat->id }}" data-label="{{ $cat->name }}" data-search="{{ strtolower($cat->name) }}">{{ $cat->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="no stock"     {{ request('status') == 'no stock'     ? 'selected' : '' }}>No Stock</option>
                        <option value="low stock"    {{ request('status') == 'low stock'    ? 'selected' : '' }}>Low Stock</option>
                        <option value="enough stock" {{ request('status') == 'enough stock' ? 'selected' : '' }}>Enough Stock</option>
                    </select>
                    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                    <a href="/items" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>SKU</th><th>Name</th><th>Category</th><th>Stock</th><th>Reorder</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td><span class="badge badge-gray">{{ $item->sku }}</span></td>
                            <td style="font-weight:600;color:var(--green-dark);">{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->current_stock }} {{ $item->unit->abbreviation }}</td>
                            <td>{{ $item->reorder_level }}</td>
                            <td>
                                @if($item->status === 'no stock')
                                    <span class="badge badge-red">No Stock</span>
                                @elseif($item->status === 'low stock')
                                    <span class="badge badge-orange">Low Stock</span>
                                @else
                                    <span class="badge badge-green">Enough Stock</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-row">
                                    <a href="/items/{{ $item->id }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="/items/{{ $item->id }}/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="/items/{{ $item->id }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Delete {{ $item->name }}?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="tbl-empty">No items found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
            <div class="pag-wrap">{{ $items->links() }}</div>
            @endif
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    combobox('add-cat-input',  'add-cat-dropdown',  'add-cat-select');
    combobox('add-unit-input', 'add-unit-dropdown', 'add-unit-select');
    combobox('flt-cat-input',  'flt-cat-dropdown',  'flt-cat-select');
});
</script>
</x-layouts.app-dash>