<x-layouts.app-dash title="{{ $animal->name ?? $animal->tag_number }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>{{ $animal->name ?? $animal->tag_number }}</h1>
            <p>Tag: {{ $animal->tag_number }} · {{ $animal->breed->species }} · {{ $animal->breed->name }}</p>
        </div>
        <div class="ph-right">
            
            <a href="/animals/{{ $animal->id }}/edit" class="btn btn-outline">Edit</a>
            <a href="/animals" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="grid-2" style="gap:24px;align-items:start;margin-bottom:24px;">

        <div class="card">
            <div class="card-head"><h2>Animal Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row"><span class="detail-label">Tag Number</span><span class="detail-val">{{ $animal->tag_number }}</span></div>
                <div class="detail-row"><span class="detail-label">Name</span><span class="detail-val">{{ $animal->name ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Species</span><span class="detail-val">{{ $animal->breed->species }}</span></div>
                <div class="detail-row"><span class="detail-label">Breed</span><span class="detail-val">{{ $animal->breed->name }}</span></div>
                <div class="detail-row"><span class="detail-label">Sex</span><span class="detail-val">{{ ucfirst($animal->sex) }}</span></div>
                <div class="detail-row"><span class="detail-label">Weight</span><span class="detail-val">{{ $animal->weight }} kg</span></div>
                <div class="detail-row"><span class="detail-label">Age</span><span class="detail-val">{{ $animal->age }}</span></div>
                <div class="detail-row"><span class="detail-label">Birthdate</span><span class="detail-val">{{ $animal->birthdate ? $animal->birthdate->format('M d, Y') : '—' }}</span></div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-val">
                        @if($animal->status === 'active')      <span class="badge badge-green">Active</span>
                        @elseif($animal->status === 'sold')    <span class="badge badge-gold">Sold</span>
                        @elseif($animal->status === 'deceased')<span class="badge badge-red">Deceased</span>
                        @else                                  <span class="badge badge-gray">Transferred</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>Recent Health Logs</h3>
                <a href="/health-logs?animal_id={{ $animal->id }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Type</th><th>Description</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($animal->healthLogs()->latest('log_date')->take(5)->get() as $log)
                        <tr>
                            <td><span class="badge badge-blue">{{ ucfirst(str_replace('_',' ',$log->type)) }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->log_date->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="tbl-empty">No health logs.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="sec-head">
            <h3>Recent Feed Logs</h3>
            <a href="/feed-logs?animal_id={{ $animal->id }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr><th>Feed Item</th><th>Quantity</th><th>Date</th><th>Logged By</th></tr>
                </thead>
                <tbody>
                    @forelse($animal->feedLogs()->with('item.unit','user')->latest('feed_date')->take(6)->get() as $fl)
                    <tr>
                        <td style="font-weight:600;">{{ $fl->item->name }}</td>
                        <td>{{ $fl->quantity }} {{ $fl->item->unit->abbreviation }}</td>
                        <td>{{ $fl->feed_date->format('M d, Y') }}</td>
                        <td>{{ $fl->user->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="tbl-empty">No feed logs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.app-dash>