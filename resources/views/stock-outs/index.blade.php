<x-layouts.app-dash title="Stock Out">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Stock Out</h1>
            <p>Record supply consumption and usage</p>
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
            <div class="card-head"><h2>Log Stock Out</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock-outs.store') }}">
                    @csrf
                    <div class="fg">
                        <label>Item</label>
                        <div class="combobox">
                            <input type="text" id="so-add-item-input" autocomplete="off" placeholder="Select Item"
                                   value="{{ $items->firstWhere('id', old('item_id')) ? $items->firstWhere('id', old('item_id'))->name : '' }}">
                            <select name="item_id" id="so-add-item-select" style="display:none;" required>
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->current_stock }} {{ $item->unit->abbreviation }} available)
                                    </option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="so-add-item-dropdown">
                                @foreach($items as $item)
                                    <div class="combobox-option"
                                         data-value="{{ $item->id }}"
                                         data-label="{{ $item->name }}"
                                         data-search="{{ strtolower($item->name) }}">
                                        {{ $item->name }}
                                        <span style="color:var(--text-muted);font-size:.85em;">{{ $item->current_stock }} {{ $item->unit->abbreviation }} available</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Reason</label>
                        <input type="text" name="reason" value="{{ old('reason') }}"
                               placeholder="e.g. Livestock Medication, Pest Control…" required maxlength="255">
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Quantity</label>
                            <input type="number" name="quantity" value="{{ old('quantity') }}"
                                   min="0.01" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="fg">
                            <label>Date</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Notes <span class="hint" style="display:inline;">(optional)</span></label>
                        <input type="text" name="notes" value="{{ old('notes') }}" placeholder="e.g. Applied to north field">
                    </div>
                    <div class="fg">
                        <label>Logged By</label>
                        <input type="text" value="{{ auth()->user()->name }}" disabled>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Stock Out</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>Stock Out Records</h3>
                <span class="sec-meta">{{ $stockOuts->total() }} total</span>
            </div>
            <form method="GET" action="{{ route('stock-outs.index') }}">
                <div class="filter-bar">
                    <input type="text" name="reason" value="{{ request('reason') }}" placeholder="Filter by reason…">
                    <div class="combobox">
                        <input type="text" id="so-flt-item-input" autocomplete="off" placeholder="All Items"
                               value="{{ $items->firstWhere('id', request('item_id'))->name ?? '' }}">
                        <select name="item_id" id="so-flt-item-select" style="display:none;">
                            <option value="">All Items</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="combobox-dropdown" id="so-flt-item-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Items</div>
                            @foreach($items as $item)
                                <div class="combobox-option" data-value="{{ $item->id }}" data-label="{{ $item->name }}" data-search="{{ strtolower($item->name) }}">{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <input type="date" name="from" value="{{ request('from') }}">
                    <input type="date" name="to" value="{{ request('to') }}">
                    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                    <a href="{{ route('stock-outs.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Item</th><th>Reason</th><th>Animal</th><th>Qty</th><th>Date</th><th>Logged By</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($stockOuts as $so)
                        <tr>
                            <td style="font-weight:600;color:var(--g);">{{ $so->item->name }}</td>
                            <td><span class="badge badge-blue">{{ $so->reason }}</span></td>
                            <td>
                                @if($so->animal)
                                    <a href="{{ route('animals.show', $so->animal) }}" style="color:var(--gm);font-weight:500;text-decoration:none;">{{ $so->animal->tag_number }}</a>
                                @else —
                                @endif
                            </td>
                            <td>{{ $so->quantity }} {{ $so->item->unit->abbreviation }}</td>
                            <td>{{ $so->date->format('M d, Y') }}</td>
                            <td>{{ $so->user->name }}</td>
                            <td>
                                <div class="btn-row">
                                    <a href="{{ route('stock-outs.show', $so) }}" class="btn btn-outline btn-sm">View</a>
                                    
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="tbl-empty">No stock-out records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($stockOuts->hasPages())
            <div class="pag-wrap">{{ $stockOuts->links() }}</div>
            @endif
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    combobox('so-add-item-input',  'so-add-item-dropdown',  'so-add-item-select');
    combobox('so-flt-item-input',  'so-flt-item-dropdown',  'so-flt-item-select');
});
</script>
</x-layouts.app-dash>