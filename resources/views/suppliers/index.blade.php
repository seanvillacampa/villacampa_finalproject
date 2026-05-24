<x-layouts.app-dash title="Suppliers">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Suppliers</h1>
            <p>Manage farm supply suppliers</p>
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
            <div class="card-head"><h2>Add Supplier</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf
                    <div class="fg">
                        <label>Supplier Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. AgriMart PH" required>
                    </div>
                    <div class="fg">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="Full name" required>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09xx-xxx-xxxx" required>
                        </div>
                        <div class="fg">
                            <label>Email <span class="hint" style="display:inline;">(optional)</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="supplier@email.com">
                        </div>
                    </div>
                    <div class="fg">
                        <label>Street Address</label>
                        <input type="text" name="street_address" value="{{ old('street_address') }}" placeholder="Street / Bldg / Unit" required>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Barangay</label>
                            <input type="text" name="barangay_address" value="{{ old('barangay_address') }}" required>
                        </div>
                        <div class="fg">
                            <label>City / Municipality</label>
                            <input type="text" name="city_address" value="{{ old('city_address') }}" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Province</label>
                        <input type="text" name="province_address" value="{{ old('province_address') }}" required>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="sec-head">
                <h3>All Suppliers</h3>
                <span class="sec-meta">{{ $suppliers->total() }} total</span>
            </div>

            <form method="GET" action="{{ route('suppliers.index') }}">
                <div class="filter-bar">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search suppliers...">
                    <button type="submit" class="btn btn-outline btn-sm">Search</button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Contact</th><th>Phone</th><th>City</th><th>Orders</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td style="font-weight:600;color:var(--g);">{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact_person }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->city_address }}</td>
                            <td><span class="badge badge-blue">{{ $supplier->purchase_histories_count }}</span></td>
                            <td>
                                <div class="btn-row">
                                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"
                                            onclick="return confirm('Delete {{ $supplier->name }}?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="tbl-empty">No suppliers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($suppliers->hasPages())
            <div class="pag-wrap">{{ $suppliers->links() }}</div>
            @endif
        </div>

    </div>
</div>
</x-layouts.app-dash>