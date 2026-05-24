<x-layouts.app-dash title="Feed Logs">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Feed Logs</h1>
            <p>Animal feeding records — stock is auto-deducted on save</p>
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
            <div class="card-head"><h2>Log Feeding</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('feed-logs.store') }}">
                    @csrf
                    <div class="fg">
                        <label>Animal</label>
                        <div class="combobox">
                            <input type="text" id="add-animal-input" autocomplete="off" placeholder="Select Animal"
                                   value="{{ $animals->firstWhere('id', old('animal_id') ?? request('animal_id')) ? $animals->firstWhere('id', old('animal_id') ?? request('animal_id'))->tag_number : '' }}">
                            <select name="animal_id" id="add-animal-select" style="display:none;" required>
                                <option value="">Select Animal</option>
                                @foreach($animals as $animal)
                                    <option value="{{ $animal->id }}" {{ (old('animal_id') ?? request('animal_id')) == $animal->id ? 'selected' : '' }}>
                                        {{ $animal->tag_number }} {{ $animal->name ? '— '.$animal->name : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="add-animal-dropdown">
                                @foreach($animals as $animal)
                                    <div class="combobox-option"
                                         data-value="{{ $animal->id }}"
                                         data-label="{{ $animal->tag_number }}{{ $animal->name ? ' — '.$animal->name : '' }}"
                                         data-search="{{ strtolower($animal->tag_number.' '.($animal->name ?? '')) }}">
                                        <strong>{{ $animal->tag_number }}</strong>
                                        @if($animal->name) · {{ $animal->name }} @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Feed Item</label>
                        <div class="combobox">
                            <input type="text" id="add-item-input" autocomplete="off" placeholder="Select Item"
                                   value="{{ $items->firstWhere('id', old('item_id'))->name ?? '' }}">
                            <select name="item_id" id="add-item-select" style="display:none;" required>
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->current_stock }} {{ $item->unit->abbreviation }} available)
                                    </option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="add-item-dropdown">
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
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" min="0.01" step="0.01" placeholder="0.00" required>
                        <span class="hint">Stock will be automatically deducted from inventory.</span>
                    </div>
                    <div class="fg">
                        <label>Feed Date</label>
                        <input type="date" name="feed_date" value="{{ old('feed_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Feed Log</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Feed Logs</h3>
                <span class="sec-meta">{{ $logs->total() }} total</span>
            </div>
            <form method="GET" action="{{ route('feed-logs.index') }}">
                <div class="filter-bar">
                    <div class="combobox">
                        <input type="text" id="flt-animal-input" autocomplete="off" placeholder="All Animals"
                               value="{{ $animals->firstWhere('id', request('animal_id'))->tag_number ?? '' }}">
                        <select name="animal_id" id="flt-animal-select" style="display:none;">
                            <option value="">All Animals</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}" {{ request('animal_id') == $animal->id ? 'selected' : '' }}>{{ $animal->tag_number }}</option>
                            @endforeach
                        </select>
                        <div class="combobox-dropdown" id="flt-animal-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Animals</div>
                            @foreach($animals as $animal)
                                <div class="combobox-option"
                                     data-value="{{ $animal->id }}"
                                     data-label="{{ $animal->tag_number }}{{ $animal->name ? ' — '.$animal->name : '' }}"
                                     data-search="{{ strtolower($animal->tag_number.' '.($animal->name ?? '')) }}">
                                    <strong>{{ $animal->tag_number }}</strong>
                                    @if($animal->name) · {{ $animal->name }} @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="date" name="from" value="{{ request('from') }}">
                    <input type="date" name="to" value="{{ request('to') }}">
                    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                    <a href="{{ route('feed-logs.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Animal</th><th>Feed Item</th><th>Quantity</th><th>Feed Date</th><th>Logged By</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td><span class="badge badge-gray">{{ $log->animal->tag_number }}</span></td>
                            <td style="font-weight:600;">{{ $log->item->name }}</td>
                            <td>{{ $log->quantity }} {{ $log->item->unit->abbreviation }}</td>
                            <td>{{ $log->feed_date->format('M d, Y') }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>
                                <div class="btn-row">
                                    <a href="{{ route('feed-logs.show', $log) }}" class="btn btn-outline btn-sm">View</a>
                                    <form method="POST" action="{{ route('feed-logs.destroy', $log) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"
                                            onclick="return confirm('Delete this feed log? Stock will be restored.')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="tbl-empty">No feed logs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="pag-wrap">{{ $logs->links() }}</div>
            @endif
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    combobox('add-animal-input', 'add-animal-dropdown', 'add-animal-select');
    combobox('add-item-input',   'add-item-dropdown',   'add-item-select');
    combobox('flt-animal-input', 'flt-animal-dropdown', 'flt-animal-select');
});
</script>
</x-layouts.app-dash>