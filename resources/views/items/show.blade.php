<x-layouts.app-dash title="{{ $item->name }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>{{ $item->name }}</h1>
            <p>SKU: {{ $item->sku }}</p>
        </div>
        <div class="ph-right">
            <a href="/items/{{ $item->id }}/edit" class="btn btn-outline">Edit</a>
            <a href="/items" class="btn btn-outline">← Back</a>
        </div>
    </div>

    <div class="grid-2" style="gap:24px;align-items:start;">

        <div class="card">
            <div class="card-head"><h2>Item Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row"><span class="detail-label">Name</span><span class="detail-val">{{ $item->name }}</span></div>
                <div class="detail-row"><span class="detail-label">SKU</span><span class="detail-val">{{ $item->sku }}</span></div>
                <div class="detail-row"><span class="detail-label">Category</span><span class="detail-val">{{ $item->category->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Unit</span><span class="detail-val">{{ $item->unit->name }} ({{ $item->unit->abbreviation }})</span></div>
                <div class="detail-row"><span class="detail-label">Current Stock</span><span class="detail-val">{{ $item->current_stock }} {{ $item->unit->abbreviation }}</span></div>
                <div class="detail-row"><span class="detail-label">Reorder Level</span><span class="detail-val">{{ $item->reorder_level }}</span></div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-val">
                        @if($item->status === 'no stock')
                            <span class="badge badge-red">No Stock</span>
                        @elseif($item->status === 'low stock')
                            <span class="badge badge-orange">Low Stock</span>
                        @else
                            <span class="badge badge-green">Enough Stock</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row"><span class="detail-label">Description</span><span class="detail-val">{{ $item->description ?? '—' }}</span></div>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>Stock In History</h3>
                <a ></a>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Qty</th><th>Lot #</th><th>By</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($item->stockMovements()->with('user')->latest()->take(8)->get() as $mv)
                        <tr>
              
                            <td>{{ $mv->quantity }}</td>
                            <td>{{ $mv->lot_number ?? '—' }}</td>
                            <td>{{ $mv->user->name }}</td>
                            <td>{{ $mv->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="tbl-empty">No movements yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</x-layouts.app-dash>