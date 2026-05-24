<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Stock Movement #{{ $stockMovement->id }}</h1>
            <p>{{ $stockMovement->type === 'stock_in' ? 'Stock In' : 'Stock Out' }} — {{ $stockMovement->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    <div style="max-width:540px;">
        <div class="card">
            <div class="card-head"><h2>Movement Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row"><span class="detail-label">Type</span>
                    <span class="detail-val">
                        @if($stockMovement->type === 'stock_in')
                            <span class="badge badge-green">↑ Stock In</span>
                        @else
                            <span class="badge badge-red">↓ Stock Out</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row"><span class="detail-label">Item</span><span class="detail-val">{{ $stockMovement->item->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Quantity</span><span class="detail-val">{{ $stockMovement->quantity }} {{ $stockMovement->item->unit->abbreviation }}</span></div>
                <div class="detail-row"><span class="detail-label">Lot Number</span><span class="detail-val">{{ $stockMovement->lot_number ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Expiry Date</span><span class="detail-val">{{ $stockMovement->expiry_date ? $stockMovement->expiry_date->format('M d, Y') : '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Reason</span><span class="detail-val">{{ $stockMovement->reason ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Logged By</span><span class="detail-val">{{ $stockMovement->user->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Date</span><span class="detail-val">{{ $stockMovement->created_at->format('M d, Y h:i A') }}</span></div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>