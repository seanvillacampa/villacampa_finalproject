<x-layouts.app-dash title="Health Logs">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Animal Health Logs</h1>
            <p>Vaccination, medication, and vet visit records</p>
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
            <div class="card-head"><h2>Add Health Log</h2></div>
            <div class="card-body">
    <form method="POST" action="{{ route('health-logs.store') }}">
        @csrf
        <div class="fg">
            <label>Animal</label>
            <div class="combobox">
                <input type="text" id="add-animal-input" autocomplete="off" placeholder="Select Animal"
                       value="{{ $animals->firstWhere('id', old('animal_id') ?? request('animal_id')) ? $animals->firstWhere('id', old('animal_id') ?? request('animal_id'))->tag_number : '' }}">
                <select name="animal_id" id="add-animal-select" style="display:none;" required>
                    <option value="">Select Animal</option>
                    @foreach($animals as $animal)
                        <option value="{{ $animal->id }}" {{ (old('animal_id') ?? request('animal_id')) == $animal->id ? 'selected' : '' }}>
                            {{ $animal->tag_number }} {{ $animal->name ? '— '.$animal->name : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="combobox-dropdown" id="add-animal-dropdown">
                    @foreach($animals as $animal)
                        <div class="combobox-option"
                             data-value="{{ $animal->id }}"
                             data-label="{{ $animal->tag_number }}{{ $animal->name ? ' — '.$animal->name : '' }}"
                             data-search="{{ strtolower($animal->tag_number.' '.($animal->name ?? '')) }}">
                            <strong>{{ $animal->tag_number }}</strong>
                            @if($animal->name) · {{ $animal->name }} @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="fg">
            <label>Type</label>
            <div class="combobox">
                <input type="text" id="add-type-input" autocomplete="off" placeholder="Select Type"
                       value="{{ old('type') ? ucfirst(str_replace('_',' ',old('type'))) : '' }}">
                <select name="type" id="add-type-select" style="display:none;" required>
                    <option value="">Select Type</option>
                    <option value="vaccination"  {{ old('type') == 'vaccination'  ? 'selected' : '' }}>Vaccination</option>
                    <option value="deworming"    {{ old('type') == 'deworming'    ? 'selected' : '' }}>Deworming</option>
                    <option value="medication"   {{ old('type') == 'medication'   ? 'selected' : '' }}>Medication</option>
                    <option value="vet_visit"    {{ old('type') == 'vet_visit'    ? 'selected' : '' }}>Vet Visit</option>
                    <option value="other"        {{ old('type') == 'other'        ? 'selected' : '' }}>Other</option>
                </select>
                <div class="combobox-dropdown" id="add-type-dropdown">
                    <div class="combobox-option" data-value="vaccination"  data-label="Vaccination"  data-search="vaccination">Vaccination</div>
                    <div class="combobox-option" data-value="deworming"    data-label="Deworming"    data-search="deworming">Deworming</div>
                    <div class="combobox-option" data-value="medication"   data-label="Medication"   data-search="medication">Medication</div>
                    <div class="combobox-option" data-value="vet_visit"    data-label="Vet Visit"    data-search="vet visit">Vet Visit</div>
                    <div class="combobox-option" data-value="other"        data-label="Other"        data-search="other">Other</div>
                </div>
            </div>
        </div>

        <div class="fg">
            <label>Description</label>
            <input type="text" name="description" value="{{ old('description') }}" placeholder="e.g. FMD Vaccine administered" required>
        </div>

        {{-- Medicine fields: shown only for vaccination / deworming / medication --}}
        <div id="medicine-fields" style="display:none;">

            <div class="fg">
                <label>Medicine Used <span class="hint" style="display:inline;">(optional)</span></label>
                <div class="combobox">
                    <input type="text" id="add-medicine-input" autocomplete="off" placeholder="Select or type medicine">
                    <select name="item_id" id="add-medicine-select" style="display:none;">
                        <option value="">— none —</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}"
                                    data-stock="{{ $medicine->current_stock }}"
                                    data-unit="{{ $medicine->unit->abbreviation ?? $medicine->unit->name ?? '' }}"
                                    {{ old('item_id') == $medicine->id ? 'selected' : '' }}>
                                {{ $medicine->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="combobox-dropdown" id="add-medicine-dropdown">
                        <div class="combobox-option" data-value="" data-label="">— none —</div>
                        @foreach($medicines as $medicine)
                            <div class="combobox-option"
                                 data-value="{{ $medicine->id }}"
                                 data-label="{{ $medicine->name }}"
                                 data-search="{{ strtolower($medicine->name) }}"
                                 data-stock="{{ $medicine->current_stock }}"
                                 data-unit="{{ $medicine->unit->abbreviation ?? $medicine->unit->name ?? '' }}">
                                {{ $medicine->name }}
                                <span class="hint" style="display:inline;">
                                    — {{ number_format($medicine->current_stock, 2) }} {{ $medicine->unit->abbreviation ?? $medicine->unit->name ?? '' }} in stock
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Stock hint shown after medicine is selected --}}
            <div id="stock-hint" style="display:none; margin-bottom:.5rem;">
                <span class="hint">Available stock: <strong id="stock-available"></strong></span>
            </div>

            <div class="grid-2">
                <div class="fg">
                    <label>Dosage Quantity <span class="hint" style="display:inline;" id="dosage-unit-hint"></span></label>
                    <input type="number" name="dosage_quantity" id="add-dosage-qty"
                           value="{{ old('dosage_quantity') }}" placeholder="e.g. 5" min="0.01" step="0.01">
                </div>
                <div class="fg">
                    <label>Dosage Notes <span class="hint" style="display:inline;">(optional)</span></label>
                    <input type="text" name="dosage" value="{{ old('dosage') }}" placeholder="e.g. once daily">
                </div>
            </div>

            <div class="fg">
                <label>Administered By <span class="hint" style="display:inline;">(optional)</span></label>
                <input type="text" name="administered_by" value="{{ old('administered_by') }}" placeholder="e.g. Dr. Santos">
            </div>
        </div>

        <div class="grid-2">
            <div class="fg">
                <label>Log Date</label>
                <input type="date" name="log_date" value="{{ old('log_date', date('Y-m-d')) }}" required>
            </div>
            <div class="fg">
                <label>Next Schedule <span class="hint" style="display:inline;">(optional)</span></label>
                <input type="date" name="next_schedule_date" value="{{ old('next_schedule_date') }}">
            </div>
        </div>

        <div class="btn-row">
            <button type="submit" class="btn btn-primary">Save Log</button>
        
    </form>
</div>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Health Logs</h3>
                <span class="sec-meta">{{ $logs->total() }} total</span>
            </div>
            <form method="GET" action="{{ route('health-logs.index') }}">
                <div class="filter-bar">
                    <div class="combobox">
                        <input type="text" id="flt-animal-input" autocomplete="off" placeholder="All Animals"
                               value="{{ $animals->firstWhere('id', request('animal_id'))->tag_number ?? '' }}">
                        <select name="animal_id" id="flt-animal-select" style="display:none;">
                            <option value="">All Animals</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}" {{ request('animal_id') == $animal->id ? 'selected' : '' }}>{{ $animal->tag_number }}</option>
                            @endforeach
                        </select>
                        <div class="combobox-dropdown" id="flt-animal-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Animals</div>
                            @foreach($animals as $animal)
                                <div class="combobox-option"
                                     data-value="{{ $animal->id }}"
                                     data-label="{{ $animal->tag_number }}{{ $animal->name ? ' — '.$animal->name : '' }}"
                                     data-search="{{ strtolower($animal->tag_number.' '.($animal->name ?? '')) }}">
                                    <strong>{{ $animal->tag_number }}</strong>
                                    @if($animal->name) · {{ $animal->name }} @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="combobox">
                        <input type="text" id="flt-type-input" autocomplete="off" placeholder="All Types"
                               value="{{ request('type') ? ucfirst(str_replace('_',' ',request('type'))) : '' }}">
                        <select name="type" id="flt-type-select" style="display:none;">
                            <option value="">All Types</option>
                            <option value="vaccination"  {{ request('type') == 'vaccination'  ? 'selected' : '' }}>Vaccination</option>
                            <option value="deworming"    {{ request('type') == 'deworming'    ? 'selected' : '' }}>Deworming</option>
                            <option value="medication"   {{ request('type') == 'medication'   ? 'selected' : '' }}>Medication</option>
                            <option value="vet_visit"    {{ request('type') == 'vet_visit'    ? 'selected' : '' }}>Vet Visit</option>
                            <option value="other"        {{ request('type') == 'other'        ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="combobox-dropdown" id="flt-type-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All Types</div>
                            <div class="combobox-option" data-value="vaccination"  data-label="Vaccination"  data-search="vaccination">Vaccination</div>
                            <div class="combobox-option" data-value="deworming"    data-label="Deworming"    data-search="deworming">Deworming</div>
                            <div class="combobox-option" data-value="medication"   data-label="Medication"   data-search="medication">Medication</div>
                            <div class="combobox-option" data-value="vet_visit"    data-label="Vet Visit"    data-search="vet visit">Vet Visit</div>
                            <div class="combobox-option" data-value="other"        data-label="Other"        data-search="other">Other</div>
                        </div>
                    </div>
                    <div class="combobox">
                        <input type="text" id="flt-upcoming-input" autocomplete="off" placeholder="All"
                               value="{{ request('upcoming') ? 'Due within 7 days' : '' }}">
                        <select name="upcoming" id="flt-upcoming-select" style="display:none;">
                            <option value="">All</option>
                            <option value="1" {{ request('upcoming') ? 'selected' : '' }}>Due within 7 days</option>
                        </select>
                        <div class="combobox-dropdown" id="flt-upcoming-dropdown">
                            <div class="combobox-option" data-value="" data-label="">All</div>
                            <div class="combobox-option" data-value="1" data-label="Due within 7 days" data-search="due 7 days upcoming">Due within 7 days</div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                    <a href="{{ route('health-logs.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Animal</th><th>Type</th><th>Description</th><th>Medicine</th><th>Log Date</th><th>Next Schedule</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td><span class="badge badge-gray">{{ $log->animal->tag_number }}</span></td>
                            <td><span class="badge badge-blue">{{ ucfirst(str_replace('_',' ',$log->type)) }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->medicine_used ?? '—' }}</td>
                            <td>{{ $log->log_date->format('M d, Y') }}</td>
                            <td>
                                @if($log->next_schedule_date)
                                    @if($log->next_schedule_date->isPast())
                                        <span class="badge badge-red">{{ $log->next_schedule_date->format('M d, Y') }}</span>
                                    @elseif($log->next_schedule_date->diffInDays(now()) <= 7)
                                        <span class="badge badge-orange">{{ $log->next_schedule_date->format('M d, Y') }}</span>
                                    @else
                                        {{ $log->next_schedule_date->format('M d, Y') }}
                                    @endif
                                @else —
                                @endif
                            </td>
                            <td>
                                <div class="btn-row">
                                    <a href="{{ route('health-logs.show', $log) }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('health-logs.edit', $log) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('health-logs.destroy', $log) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Delete this log?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="tbl-empty">No health logs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="pag-wrap">{{ $logs->links() }}</div>
            @endif
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const MEDICINE_TYPES = ['vaccination', 'deworming', 'medication'];
    const typeSelect     = document.getElementById('add-type-select');
    const medicineFields = document.getElementById('medicine-fields');
    const medicineSelect = document.getElementById('add-medicine-select');
    const stockHint      = document.getElementById('stock-hint');
    const stockAvail     = document.getElementById('stock-available');
    const dosageUnitHint = document.getElementById('dosage-unit-hint');

    function toggleMedicineFields() {
        const val = typeSelect.value;
        if (MEDICINE_TYPES.includes(val)) {
            medicineFields.style.display = '';
        } else {
            medicineFields.style.display = 'none';
            medicineSelect.value = '';
            document.getElementById('add-medicine-input').value = '';
            stockHint.style.display = 'none';
            document.getElementById('add-dosage-qty').value = '';
        }
    }

    function updateStockHint() {
        const selected = medicineSelect.options[medicineSelect.selectedIndex];
        if (selected && selected.value) {
            const stock = parseFloat(selected.dataset.stock ?? 0);
            const unit  = selected.dataset.unit ?? '';
            stockAvail.textContent     = `${stock.toFixed(2)} ${unit}`;
            dosageUnitHint.textContent = unit ? `(${unit})` : '';
            stockHint.style.display    = '';
        } else {
            stockHint.style.display    = 'none';
            dosageUnitHint.textContent = '';
        }
    }

    // Init comboboxes — pass onChange callbacks directly
    combobox('add-animal-input',   'add-animal-dropdown',   'add-animal-select');
    combobox('add-type-input',     'add-type-dropdown',     'add-type-select',     toggleMedicineFields);
    combobox('add-medicine-input', 'add-medicine-dropdown', 'add-medicine-select', updateStockHint);
    combobox('flt-animal-input',   'flt-animal-dropdown',   'flt-animal-select');
    combobox('flt-type-input',     'flt-type-dropdown',     'flt-type-select');
    combobox('flt-upcoming-input', 'flt-upcoming-dropdown', 'flt-upcoming-select');

    // Run on load for old() repopulation
    toggleMedicineFields();
    updateStockHint();

    // Fallback: also listen to native change in case combobox does fire it
    typeSelect.addEventListener('change', toggleMedicineFields);
    medicineSelect.addEventListener('change', updateStockHint);
});
</script>
</x-layouts.app-dash>