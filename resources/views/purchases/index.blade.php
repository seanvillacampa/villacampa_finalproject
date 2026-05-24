<x-layouts.app-dash title="Purchases">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Purchase History</h1>
            <p>All purchase orders from suppliers</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('purchases.create') }}" class="btn btn-primary">+ New Purchase</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="sec-head">
            <h3>All Purchases</h3>
            <span class="sec-meta">{{ $purchases->total() }} total</span>
        </div>
        <form method="GET" action="{{ route('purchases.index') }}">
            <div class="filter-bar">
                <div class="combobox">
                    <input type="text" id="flt-sup-input" autocomplete="off" placeholder="All Suppliers"
                           value="{{ $suppliers->firstWhere('id', request('supplier_id'))->name ?? '' }}">
                    <select name="supplier_id" id="flt-sup-select" style="display:none;">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <div class="combobox-dropdown" id="flt-sup-dropdown">
                        <div class="combobox-option" data-value="" data-label="">All Suppliers</div>
                        @foreach($suppliers as $s)
                            <div class="combobox-option" data-value="{{ $s->id }}" data-label="{{ $s->name }}" data-search="{{ strtolower($s->name) }}">{{ $s->name }}</div>
                        @endforeach
                    </div>
                </div>
                <div class="combobox">
                    <input type="text" id="flt-status-input" autocomplete="off" placeholder="All Status"
                           value="{{ request('status') ? ucfirst(request('status')) : '' }}">
                    <select name="status" id="flt-status-select" style="display:none;">
                        <option value="">All Status</option>
                        <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="received"  {{ request('status') == 'received'  ? 'selected' : '' }}>Received</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <div class="combobox-dropdown" id="flt-status-dropdown">
                        <div class="combobox-option" data-value="" data-label="">All Status</div>
                        <div class="combobox-option" data-value="pending"   data-label="Pending"   data-search="pending">Pending</div>
                        <div class="combobox-option" data-value="received"  data-label="Received"  data-search="received">Received</div>
                        <div class="combobox-option" data-value="cancelled" data-label="Cancelled" data-search="cancelled">Cancelled</div>
                    </div>
                </div>
                <input type="date" name="from" value="{{ request('from') }}">
                <input type="date" name="to" value="{{ request('to') }}">
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-outline btn-sm">Reset</a>
            </div>
        </form>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr><th>Ref #</th><th>Date</th><th>Supplier</th><th>Logged By</th><th>Total</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($purchases as $ph)
                    <tr>
                        <td><span class="badge badge-gray">{{ $ph->reference_number }}</span></td>
                        <td>{{ $ph->purchase_date->format('M d, Y') }}</td>
                        <td style="font-weight:600;color:var(--g);">{{ $ph->supplier->name }}</td>
                        <td>{{ $ph->user->name }}</td>
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
                        <td>
                            <a href="{{ route('purchases.show', $ph) }}" class="btn btn-outline btn-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="tbl-empty">No purchases yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchases->hasPages())
        <div class="pag-wrap">{{ $purchases->links() }}</div>
        @endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    combobox('flt-sup-input',    'flt-sup-dropdown',    'flt-sup-select');
    combobox('flt-status-input', 'flt-status-dropdown', 'flt-status-select');
});
</script>
</x-layouts.app-dash>