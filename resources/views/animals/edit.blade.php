<x-layouts.app-dash title="Edit Animal">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Animal</h1>
            <p>Tag: {{ $animal->tag_number }}</p>
        </div>
        <div class="ph-right">
            <a href="/animals" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:520px;">
        <div class="card">
            <div class="card-head"><h2>Edit — {{ $animal->tag_number }}</h2></div>
            <div class="card-body">
                <form method="POST" action="/animals/{{ $animal->id }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Tag Number</label>
                        <input type="text" value="{{ $animal->tag_number }}" disabled>
                        <span class="hint">Tag number is auto-generated.</span>
                    </div>
                    <div class="fg">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', $animal->name) }}" placeholder="Optional">
                    </div>
                    <div class="fg">
                        <label>Breed</label>
                        <select name="breed_id" required>
                            @foreach($breeds as $breed)
                                <option value="{{ $breed->id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>
                                    {{ $breed->name }} ({{ $breed->species }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Sex</label>
                            <select name="sex" required>
                                <option value="male"   {{ old('sex', $animal->sex) == 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex', $animal->sex) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>Weight (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight', $animal->weight) }}" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Birthdate</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $animal->birthdate?->format('Y-m-d')) }}">
                    </div>
                    <div class="fg">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="active"      {{ old('status', $animal->status) == 'active'      ? 'selected' : '' }}>Active</option>
                            <option value="sold"        {{ old('status', $animal->status) == 'sold'        ? 'selected' : '' }}>Sold</option>
                            <option value="deceased"    {{ old('status', $animal->status) == 'deceased'    ? 'selected' : '' }}>Deceased</option>
                            <option value="transferred" {{ old('status', $animal->status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        </select>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Animal</button>
                        <a href="/animals" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>