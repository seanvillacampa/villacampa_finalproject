<x-layouts.app-dash>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Supplier</h1>
            <p>Update supplier information</p>
        </div>
        <div class="ph-right">
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:620px;">
        <div class="card">
            <div class="card-head"><h2>Edit — {{ $supplier->name }}</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Supplier Name</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required>
                    </div>
                    <div class="fg">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" required>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" required>
                        </div>
                        <div class="fg">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $supplier->email) }}">
                        </div>
                    </div>
                    <div class="fg">
                        <label>Street Address</label>
                        <input type="text" name="street_address" value="{{ old('street_address', $supplier->street_address) }}" required>
                    </div>
                    <div class="grid-2">
                        <div class="fg">
                            <label>Barangay</label>
                            <input type="text" name="barangay_address" value="{{ old('barangay_address', $supplier->barangay_address) }}" required>
                        </div>
                        <div class="fg">
                            <label>City / Municipality</label>
                            <input type="text" name="city_address" value="{{ old('city_address', $supplier->city_address) }}" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Province</label>
                        <input type="text" name="province_address" value="{{ old('province_address', $supplier->province_address) }}" required>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Supplier</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>