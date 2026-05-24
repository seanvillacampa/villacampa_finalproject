<x-layouts.app-dash title="Edit Unit">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Unit</h1>
            <p>Update unit of measure</p>
        </div>
        <div class="ph-right">
            <a href="/units" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:480px;">
        <div class="card">
            <div class="card-head"><h2>Edit — {{ $unit->name }}</h2></div>
            <div class="card-body">
                <form method="POST" action="/units/{{ $unit->id }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Unit Name</label>
                        <input type="text" name="name" value="{{ old('name', $unit->name) }}" required>
                    </div>
                    <div class="fg">
                        <label>Abbreviation</label>
                        <input type="text" name="abbreviation" value="{{ old('abbreviation', $unit->abbreviation) }}" required>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Unit</button>
                        <a href="/units" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>