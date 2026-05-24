<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Stock Movements</h1>
            <p>All stock-in and stock-out transactions</p>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="grid-form-table">

        <div class="card">
            <div class="card-head"><h2>Log Movement</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock-movements.store') }}">
                    @csrf
                    <div class="fg">
                        <label>Item</label>
                        <select name="item_id" required>
                            <option value=""> Select Item </option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ $item->current_stock }} {{ $item->unit->abbreviation }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Type</label>
                        <select name="type" required>
                            <option value=""> Select Type </option>
                            <option value="stock_in"  {{ old('type') == 'stock_in'  ? 'selected' : '' }}>Stock In</option>
                            <option value="stock_out" {{ old('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}"
                               min="0.01" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="fg">
                        <label>Expiry Date <span class="hint" style="display:inline;">(optional)</span></label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}">
                    </div>
                    <div class="fg">
                        <label>Reason <span class="hint" style="display:inline;">(optional)</span></label>
                        <input type="text" name="reason" value="{{ old('reason') }}"
                               placeholder="e.g. Monthly restock">
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Movement</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>Movement History</h3>
                <span class="sec-meta">{{ $movements->total() }} total</span>
            </div>

            <form method="GET" action="{{ route('stock-movements.index') }}">
                <div class="filter-bar">
                    <select name="type">
                        <option value="">All Types</option>
                        <option value="stock_in"  {{ request('type') == 'stock_in'  ? 'selected' : '' }}>Stock In</option>
                        <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                    </select>
                    <select name="item_id">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="from" value="{{ request('from') }}">
                    <input type="date" name="to" value="{{ request('to') }}">
                    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Type</th><th>Item</th><th>Qty</th><th>Lot #</th><th>Expiry</th><th>By</th><th>Date</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $mv)
                        <tr>
                            <td>
                                @if($mv->type === 'stock_in')
                                    <span class="badge badge-green">↑ In</span>
                                @else
                                    <span class="badge badge-red">↓ Out</span>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $mv->item->name }}</td>
                            <td>{{ $mv->quantity }}</td>
                            <td>{{ $mv->lot_number ?? '—' }}</td>
                            <td>{{ $mv->expiry_date ? $mv->expiry_date->format('M d, Y') : '—' }}</td>
                            <td>{{ $mv->user->name }}</td>
                            <td>{{ $mv->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('stock-movements.show', $mv) }}" class="btn btn-outline btn-sm">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="tbl-empty">No movements recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
            <div class="pag-wrap">{{ $movements->links() }}</div>
            @endif
        </div>

    </div>
</div>
</x-layouts.app-dash>