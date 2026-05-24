<x-layouts.app-dash title="Animals">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Animals</h1>
            <p>Manage livestock records</p>
        </div>
    </div>

@if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

    <div class="grid-form-table">

        <div class="card">
            <div class="card-head"><h2>Register Animal</h2></div>
            <div class="card-body">
                <form method="POST" action="/animals">
                    @csrf
                    <div class="fg">
                        <label>Name <span class="hint" style="display:inline;">(optional)</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Brownie">
                    </div>
                    <div class="fg">
                        <label>Breed</label>
                        <div class="combobox">
                            <input type="text" id="add-breed-input" autocomplete="off" placeholder="Select Breed"
                                   value="{{ $breeds->firstWhere('id', old('breed_id')) ? $breeds->firstWhere('id', old('breed_id'))->name.' ('.$breeds->firstWhere('id', old('breed_id'))->species.')' : '' }}">
                            <select name="breed_id" id="add-breed-select" style="display:none;" required>
                                <option value="">Select Breed</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ old('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }} ({{ $breed->species }})</option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="add-breed-dropdown">
                                @foreach($breeds as $breed)
                                    <div class="combobox-option"
                                         data-value="{{ $breed->id }}"
                                         data-label="{{ $breed->name }} ({{ $breed->species }})"
                                         data-search="{{ strtolower($breed->name.' '.$breed->species) }}">
                                        {{ $breed->name }} <span style="color:var(--text-muted);font-size:.85em;">({{ $breed->species }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Sex</label>
                            <div class="combobox">
                                <input type="text" id="add-sex-input" autocomplete="off" placeholder="Select Sex"
                                       value="{{ old('sex') ? ucfirst(old('sex')) : '' }}">
                                <select name="sex" id="add-sex-select" style="display:none;" required>
                                    <option value="">Select</option>
                                    <option value="male"   {{ old('sex') == 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <div class="combobox-dropdown" id="add-sex-dropdown">
                                    <div class="combobox-option" data-value="male"   data-label="Male"   data-search="male">Male</div>
                                    <div class="combobox-option" data-value="female" data-label="Female" data-search="female">Female</div>
                                </div>
                            </div>
                        </div>
                        <div class="fg">
                            <label>Weight (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight') }}" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Birthdate <span class="hint" style="display:inline;">(optional)</span></label>
                        <input type="date" name="birthdate" value="{{ old('birthdate') }}">
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Register Animal</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Animals</h3>
                <span class="sec-meta">{{ $animals->total() }} total</span>
            </div>
            <form method="GET" action="/animals">
                <div class="filter-bar">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name or tag...">
                    <div class="combobox">
                        <input type="text" id="flt-breed-input" autocomplete="off" placeholder="All Breeds"
                               value="{{ $breeds->firstWhere('id', request('breed_id'))->name ?? '' }}">
                        <select name="breed_id" id="flt-breed-select" style="display:none;">
                            <option value="">All Breeds</option>
                            @foreach($breeds as $breed)
                                <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                            @endforeach
                        </select>
                        <div class="combobox-dropdown" id="flt-breed-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Breeds</div>
                            @foreach($breeds as $breed)
                                <div class="combobox-option"
                                     data-value="{{ $breed->id }}"
                                     data-label="{{ $breed->name }}"
                                     data-search="{{ strtolower($breed->name.' '.$breed->species) }}">
                                    {{ $breed->name }} <span style="color:var(--text-muted);font-size:.85em;">· {{ $breed->species }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="combobox">
                        <input type="text" id="flt-status-input" autocomplete="off" placeholder="All Status"
                               value="{{ request('status') ? ucfirst(request('status')) : '' }}">
                        <select name="status" id="flt-status-select" style="display:none;">
                            <option value="">All Status</option>
                            <option value="active"      {{ request('status') == 'active'      ? 'selected' : '' }}>Active</option>
                            <option value="sold"        {{ request('status') == 'sold'        ? 'selected' : '' }}>Sold</option>
                            <option value="deceased"    {{ request('status') == 'deceased'    ? 'selected' : '' }}>Deceased</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        </select>
                        <div class="combobox-dropdown" id="flt-status-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Status</div>
                            <div class="combobox-option" data-value="active"      data-label="Active"      data-search="active">Active</div>
                            <div class="combobox-option" data-value="sold"        data-label="Sold"        data-search="sold">Sold</div>
                            <div class="combobox-option" data-value="deceased"    data-label="Deceased"    data-search="deceased">Deceased</div>
                            <div class="combobox-option" data-value="transferred" data-label="Transferred" data-search="transferred">Transferred</div>
                        </div>
                    </div>
                    <div class="combobox">
                        <input type="text" id="flt-sex-input" autocomplete="off" placeholder="All Sex"
                               value="{{ request('sex') ? ucfirst(request('sex')) : '' }}">
                        <select name="sex" id="flt-sex-select" style="display:none;">
                            <option value="">All Sex</option>
                            <option value="male"   {{ request('sex') == 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('sex') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="combobox-dropdown" id="flt-sex-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Sex</div>
                            <div class="combobox-option" data-value="male"   data-label="Male"   data-search="male">Male</div>
                            <div class="combobox-option" data-value="female" data-label="Female" data-search="female">Female</div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                    <a href="/animals" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Tag #</th><th>Name</th><th>Breed</th><th>Species</th><th>Sex</th><th>Weight</th><th>Age</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($animals as $animal)
                        <tr>
                            <td><span class="badge badge-gray">{{ $animal->tag_number }}</span></td>
                            <td style="font-weight:600;color:var(--green-dark);">{{ $animal->name ?? '—' }}</td>
                            <td>{{ $animal->breed->name }}</td>
                            <td><span class="badge badge-blue">{{ $animal->breed->species }}</span></td>
                            <td>{{ ucfirst($animal->sex) }}</td>
                            <td>{{ $animal->weight }} kg</td>
                            <td>{{ $animal->age }}</td>
                            <td>
                                @if($animal->status === 'active')
                                    <span class="badge badge-green">Active</span>
                                @elseif($animal->status === 'sold')
                                    <span class="badge badge-gold">Sold</span>
                                @elseif($animal->status === 'deceased')
                                    <span class="badge badge-red">Deceased</span>
                                @else
                                    <span class="badge badge-gray">Transferred</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-row">
                                    <a href="/animals/{{ $animal->id }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="/animals/{{ $animal->id }}/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="/animals/{{ $animal->id }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Delete this animal?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="tbl-empty">No animals registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($animals->hasPages())
            <div class="pag-wrap">{{ $animals->links() }}</div>
            @endif
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    combobox('add-breed-input',  'add-breed-dropdown',  'add-breed-select');
    combobox('add-sex-input',    'add-sex-dropdown',    'add-sex-select');
    combobox('flt-breed-input',  'flt-breed-dropdown',  'flt-breed-select');
    combobox('flt-status-input', 'flt-status-dropdown', 'flt-status-select');
    combobox('flt-sex-input',    'flt-sex-dropdown',    'flt-sex-select');
});
</script>
</x-layouts.app-dash>