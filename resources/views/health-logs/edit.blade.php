<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Health Log</h1>
            <p>Animal: {{ $healthLog->animal->tag_number }}</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('health-logs.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:580px;">
        <div class="card">
            <div class="card-head"><h2>Edit Log #{{ $healthLog->id }}</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('health-logs.update', $healthLog) }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Animal</label>
                        <input type="text" value="{{ $healthLog->animal->tag_number }}{{ $healthLog->animal->name ? ' — '.$healthLog->animal->name : '' }}" disabled>
                    </div>
                    <div class="fg">
                        <label>Type</label>
                        <select name="type" required>
                            <option value="vaccination"  {{ old('type', $healthLog->type) == 'vaccination'  ? 'selected' : '' }}>Vaccination</option>
                            <option value="deworming"    {{ old('type', $healthLog->type) == 'deworming'    ? 'selected' : '' }}>Deworming</option>
                            <option value="medication"   {{ old('type', $healthLog->type) == 'medication'   ? 'selected' : '' }}>Medication</option>
                            <option value="vet_visit"    {{ old('type', $healthLog->type) == 'vet_visit'    ? 'selected' : '' }}>Vet Visit</option>
                            <option value="other"        {{ old('type', $healthLog->type) == 'other'        ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Description</label>
                        <input type="text" name="description" value="{{ old('description', $healthLog->description) }}" required>
                    </div>
                    <div class="fg">
                        <label>Medicine Used</label>
                        <input type="text" name="medicine_used" value="{{ old('medicine_used', $healthLog->medicine_used) }}">
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Dosage</label>
                            <input type="text" name="dosage" value="{{ old('dosage', $healthLog->dosage) }}">
                        </div>
                        <div class="fg">
                            <label>Administered By</label>
                            <input type="text" name="administered_by" value="{{ old('administered_by', $healthLog->administered_by) }}">
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Log Date</label>
                            <input type="date" name="log_date" value="{{ old('log_date', $healthLog->log_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="fg">
                            <label>Next Schedule</label>
                            <input type="date" name="next_schedule_date" value="{{ old('next_schedule_date', $healthLog->next_schedule_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Log</button>
                        <a href="{{ route('health-logs.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>