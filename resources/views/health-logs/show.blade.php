<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Health Log #{{ $healthLog->id }}</h1>
            <p>{{ $healthLog->animal->tag_number }} · {{ $healthLog->log_date->format('F d, Y') }}</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('health-logs.edit', $healthLog) }}" class="btn btn-outline">Edit</a>
            <a href="{{ route('health-logs.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    <div style="max-width:540px;">
        <div class="card">
            <div class="card-head"><h2>Log Details</h2></div>
            <div class="detail-grid">
                <div class="detail-row"><span class="detail-label">Animal</span><span class="detail-val">{{ $healthLog->animal->tag_number }}{{ $healthLog->animal->name ? ' — '.$healthLog->animal->name : '' }}</span></div>
                <div class="detail-row"><span class="detail-label">Type</span><span class="detail-val"><span class="badge badge-blue">{{ ucfirst(str_replace('_',' ',$healthLog->type)) }}</span></span></div>
                <div class="detail-row"><span class="detail-label">Description</span><span class="detail-val">{{ $healthLog->description }}</span></div>
                <div class="detail-row"><span class="detail-label">Medicine Used</span><span class="detail-val">{{ $healthLog->medicine_used ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Dosage</span><span class="detail-val">{{ $healthLog->dosage ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Administered By</span><span class="detail-val">{{ $healthLog->administered_by ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Log Date</span><span class="detail-val">{{ $healthLog->log_date->format('M d, Y') }}</span></div>
                <div class="detail-row"><span class="detail-label">Next Schedule</span><span class="detail-val">{{ $healthLog->next_schedule_date ? $healthLog->next_schedule_date->format('M d, Y') : '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Logged By</span><span class="detail-val">{{ $healthLog->user->name }}</span></div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>