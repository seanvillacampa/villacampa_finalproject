<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>{{ $supplier->name }}</h1>
            <p>Supplier profile and purchase history</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline">Edit</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    <div class="grid-2" style="gap:24px;align-items:start;">

        <div style="display:flex;flex-direction:column;gap:24px;">
            <div class="card">
                <div class="card-head"><h2>Contact Information</h2></div>
                <div class="detail-grid">
                    <div class="detail-row"><span class="detail-label">Supplier Name</span><span class="detail-val">{{ $supplier->name }}</span></div>
                    <div class="detail-row"><span class="detail-label">Contact Person</span><span class="detail-val">{{ $supplier->contact_person }}</span></div>
                    <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-val">{{ $supplier->phone }}</span></div>
                    <div class="detail-row"><span class="detail-label">Email</span><span class="detail-val">{{ $supplier->email ?? '—' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Address</span><span class="detail-val">{{ $supplier->full_address }}</span></div>
                </div>
            </div>

            <div class="card">
                <div class="sec-head"><h3>Items Supplied</h3></div>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr><th>Item</th><th>Unit</th><th>Unit Price</th></tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->items as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->name }}</td>
                                <td>{{ $item->unit->abbreviation }}</td>
                                <td>{{ $item->pivot->unit_price ? '₱' . number_format($item->pivot->unit_price, 2) : '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="tbl-empty">No items linked.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>Purchase History</h3>
                <a href="{{ route('purchases.index') }}?supplier_id={{ $supplier->id }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Ref #</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->purchaseHistories()->latest('purchase_date')->take(8)->get() as $ph)
                        <tr>
                            <td><span class="badge badge-gray">{{ $ph->reference_number }}</span></td>
                            <td>{{ $ph->purchase_date->format('M d, Y') }}</td>
                            <td style="font-weight:600;">₱{{ number_format($ph->total_amount, 2) }}</td>
                            <td>
                                @if($ph->status === 'received')
                                    <span class="badge badge-green">Received</span>
                                @elseif($ph->status === 'pending')
                                    <span class="badge badge-orange">Pending</span>
                                @else
                                    <span class="badge badge-red">Cancelled</span>
                                @endif
                            </td>
                            <td><a href="{{ route('purchases.show', $ph) }}" class="btn btn-outline btn-sm">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="tbl-empty">No purchases yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</x-layouts.app-dash>