<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>New Purchase</h1>
            <p>Record a purchase order from a supplier</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('purchases.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('purchases.store') }}"
          x-data="{
              rows: [{ item_id:'', quantity:'', unit_price:'' }],
              addRow() { this.rows.push({ item_id:'', quantity:'', unit_price:'' }); },
              removeRow(i) { if(this.rows.length > 1) this.rows.splice(i,1); },
              get grandTotal() {
                  return this.rows.reduce((s,r) =>
                      s + ((parseFloat(r.quantity)||0)*(parseFloat(r.unit_price)||0)),0
                  ).toFixed(2);
              }
          }">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-head"><h2>Purchase Details</h2></div>
            <div class="card-body">
                <div class="fg">
                    <label>Logged By</label>
                    <input type="text" value="{{ auth()->user()->name }}" disabled>
                </div>
                <div class="grid-2">
                    <div class="fg">
                        <label>Supplier</label>
                        <div class="combobox">
                            <input type="text" id="sup-input" autocomplete="off" placeholder="Select Supplier"
                                   value="{{ $suppliers->firstWhere('id', old('supplier_id'))->name ?? '' }}">
                            <select name="supplier_id" id="sup-select" style="display:none;" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            <div class="combobox-dropdown" id="sup-dropdown">
                                @foreach($suppliers as $s)
                                    <div class="combobox-option" data-value="{{ $s->id }}" data-label="{{ $s->name }}" data-search="{{ strtolower($s->name) }}">{{ $s->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h2>Line Items</h2></div>
            <div class="card-body">
                <div class="line-grid line-grid-5" style="margin-bottom:8px;">
                    <span style="font-size:.72rem;font-weight:700;color:var(--ts);text-transform:uppercase;letter-spacing:.06em;">Item</span>
                    <span style="font-size:.72rem;font-weight:700;color:var(--ts);text-transform:uppercase;letter-spacing:.06em;">Quantity</span>
                    <span style="font-size:.72rem;font-weight:700;color:var(--ts);text-transform:uppercase;letter-spacing:.06em;">Unit Price (₱)</span>
                    <span style="font-size:.72rem;font-weight:700;color:var(--ts);text-transform:uppercase;letter-spacing:.06em;">Subtotal</span>
                    <span></span>
                </div>
                <template x-for="(row, i) in rows" :key="i">
                    <div class="line-grid line-grid-5">
                        <select :name="'items['+i+'][item_id]'" x-model="row.item_id" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit->abbreviation }})</option>
                            @endforeach
                        </select>
                        <input type="number" :name="'items['+i+'][quantity]'" x-model="row.quantity" placeholder="0" min="0.01" step="0.01" required>
                        <input type="number" :name="'items['+i+'][unit_price]'" x-model="row.unit_price" placeholder="0.00" min="0" step="0.01" required>
                        <div class="subtotal-cell">
                            ₱ <span x-text="((parseFloat(row.quantity)||0)*(parseFloat(row.unit_price)||0)).toFixed(2)"></span>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" x-show="rows.length > 1" @click="removeRow(i)">Remove</button>
                    </div>
                </template>
                <button type="button" class="btn btn-outline btn-sm" style="margin-top:8px;" @click="addRow()">+ Add Item</button>
                <div class="grand-total">
                    <span style="font-weight:600;color:var(--ts);">Grand Total:</span>
                    <span style="font-weight:700;font-size:1.1rem;color:var(--g);">₱ <span x-text="grandTotal"></span></span>
                </div>
            </div>
        </div>

        <div class="btn-row" style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save Purchase</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    combobox('sup-input', 'sup-dropdown', 'sup-select');
});
</script>
</x-layouts.app-dash>