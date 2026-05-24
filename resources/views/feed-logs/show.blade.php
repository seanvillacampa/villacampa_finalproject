<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Feed Log #{{ $feedLog->id }}</h1>
            <p>{{ $feedLog->animal->tag_number }} · {{ $feedLog->feed_date->format('F d, Y') }}</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('feed-logs.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    <div style="max-width:480px;">
        <div class="card">
            <div class="card-head"><h2>Feed Log Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row"><span class="detail-label">Animal</span><span class="detail-val">{{ $feedLog->animal->tag_number }}{{ $feedLog->animal->name ? ' — '.$feedLog->animal->name : '' }}</span></div>
                <div class="detail-row"><span class="detail-label">Feed Item</span><span class="detail-val">{{ $feedLog->item->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Quantity</span><span class="detail-val">{{ $feedLog->quantity }} {{ $feedLog->item->unit->abbreviation }}</span></div>
                <div class="detail-row"><span class="detail-label">Feed Date</span><span class="detail-val">{{ $feedLog->feed_date->format('M d, Y') }}</span></div>
                <div class="detail-row"><span class="detail-label">Logged By</span><span class="detail-val">{{ $feedLog->user->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Logged At</span><span class="detail-val">{{ $feedLog->created_at->format('M d, Y h:i A') }}</span></div>
            </div>
            <div class="card-footer">
                <form method="POST" action="{{ route('feed-logs.destroy', $feedLog) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit"
                        onclick="return confirm('Delete this feed log? Stock will be restored to inventory.')">
                        Delete & Restore Stock
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>