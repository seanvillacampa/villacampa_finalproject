<x-layouts.app-dash title="Edit Breed">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Breed</h1>
            <p>Update breed details</p>
        </div>
        <div class="ph-right">
            <a href="/breeds" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:480px;">
        <div class="card">
            <div class="card-head"><h2>Edit — {{ $breed->name }}</h2></div>
            <div class="card-body">
                <form method="POST" action="/breeds/{{ $breed->id }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Breed Name</label>
                        <input type="text" name="name" value="{{ old('name', $breed->name) }}" required>
                    </div>
                    <div class="fg">
                        <label>Species</label>
                        <input type="text" name="species" value="{{ old('species', $breed->species) }}" required>
                    </div>
                    <div class="fg">
                        <label>Description</label>
                        <textarea name="description">{{ old('description', $breed->description) }}</textarea>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Breed</button>
                        <a href="/breeds" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>