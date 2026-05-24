<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Purchase — {{ $purchase->reference_number }}</h1>
            <p>{{ $purchase->purchase_date->format('F d, Y') }} · {{ $purchase->supplier->name }}</p>
        </div>
        <div class="ph-right">
            @if($purchase->status === 'pending')
                <form method="POST" action="{{ route('purchases.received', $purchase) }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <button class="btn btn-primary" onclick="return confirm('Mark as received? This will update inventory stock.')">Mark Received</button>
                </form>
                <form method="POST" action="{{ route('purchases.cancel', $purchase) }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <button class="btn btn-danger" onclick="return confirm('Cancel this purchase?')">Cancel</button>
                </form>
            @endif
            <a href="{{ route('purchases.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="grid-2" style="gap:24px;align-items:start;">

        <div class="card">
            <div class="card-head"><h2>Purchase Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row"><span class="detail-label">Reference #</span><span class="detail-val">{{ $purchase->reference_number }}</span></div>
                <div class="detail-row"><span class="detail-label">Supplier</span><span class="detail-val">{{ $purchase->supplier->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Purchase Date</span><span class="detail-val">{{ $purchase->purchase_date->format('M d, Y') }}</span></div>
                <div class="detail-row"><span class="detail-label">Logged By</span><span class="detail-val">{{ $purchase->user->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Total Amount</span><span class="detail-val" style="font-weight:700;font-size:1rem;">₱{{ number_format($purchase->total_amount, 2) }}</span></div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-val">
                        @if($purchase->status === 'received')
                            <span class="badge badge-green">Received</span>
                        @elseif($purchase->status === 'pending')
                            <span class="badge badge-orange">Pending</span>
                        @else
                            <span class="badge badge-red">Cancelled</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="sec-head"><h3>Line Items</h3></div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @forelse($purchase->items as $line)
                        <tr>
                            <td style="font-weight:600;">{{ $line->item->name }}</td>
                            <td>{{ $line->quantity }} {{ $line->item->unit->abbreviation }}</td>
                            <td>₱{{ number_format($line->unit_price, 2) }}</td>
                            <td style="font-weight:600;">₱{{ number_format($line->subtotal, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="tbl-empty">No items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:14px 20px;text-align:right;border-top:1px solid var(--bd);">
                <span style="font-weight:700;font-size:1rem;color:var(--g);">Total: ₱{{ number_format($purchase->total_amount, 2) }}</span>
            </div>
        </div>

    </div>
</div>
</x-layouts.app-dash>