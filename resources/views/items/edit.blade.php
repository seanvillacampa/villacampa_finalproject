<x-layouts.app-dash title="Edit Item">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Item</h1>
            <p>Update item details</p>
        </div>
        <div class="ph-right">
            <a href="/items" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:540px;">
        <div class="card">
            <div class="card-head"><h2>Edit — {{ $item->name }}</h2></div>
            <div class="card-body">
                <form method="POST" action="/items/{{ $item->id }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>SKU</label>
                        <input type="text" value="{{ $item->sku }}" disabled>
                        <span class="hint">SKU is auto-generated and cannot be changed.</span>
                    </div>
                    <div class="fg">
                        <label>Item Name</label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
                    </div>
                    <div class="fg">
                        <label>Category</label>
                        <select name="category_id" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Unit of Measure</label>
                        <select name="unit_id" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $item->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->abbreviation }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Reorder Level</label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', $item->reorder_level) }}" min="0" step="0.01" required>
                    </div>
                    <div class="fg">
                        <label>Description</label>
                        <textarea name="description">{{ old('description', $item->description) }}</textarea>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Item</button>
                        <a href="/items" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>