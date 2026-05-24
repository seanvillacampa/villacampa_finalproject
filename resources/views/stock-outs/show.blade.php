<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Stock Out #{{ $stockOut->id }}</h1>
            <p>{{ $stockOut->reason_label }} — {{ $stockOut->date->format('F d, Y') }}</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('stock-outs.index') }}" class="btn btn-outline">Back</a>
        </div>
    </div>

    <div style="max-width:520px;">
        <div class="card">
            <div class="card-head"><h2>Record Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row">
                    <span class="detail-label">Item</span>
                    <span class="detail-val">{{ $stockOut->item->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Quantity Used</span>
                    <span class="detail-val">
                        {{ $stockOut->quantity }} {{ $stockOut->item->unit->abbreviation }}
                    </span>
                </div>
 <div class="detail-row">
    <span class="detail-label">Reason</span>
    <span class="detail-val">
        <span class="badge badge-blue">{{ $stockOut->reason }}</span>
    </span>
</div>
                <div class="detail-row">
                    <span class="detail-label">Animal</span>
                    <span class="detail-val">
                        @if($stockOut->animal)
                            <a href="{{ route('animals.show', $stockOut->animal) }}"
                               style="color:var(--gm);font-weight:500;text-decoration:none;">
                                {{ $stockOut->animal->tag_number }}
                                {{ $stockOut->animal->name ? '— '.$stockOut->animal->name : '' }}
                            </a>
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-val">{{ $stockOut->date->format('M d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Notes</span>
                    <span class="detail-val">{{ $stockOut->notes ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Logged By</span>
                    <span class="detail-val">{{ $stockOut->user->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Logged At</span>
                    <span class="detail-val">{{ $stockOut->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>