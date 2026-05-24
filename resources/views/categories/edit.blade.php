<x-layouts.app-dash title="Edit Category">
<div class="pg">

    <div class="ph">
        <div class="ph-left">
            <h1>Edit Category</h1>
            <p>Update category details</p>
        </div>
        <div class="ph-right">
            <a href="/categories" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="max-width:480px;">
        <div class="card">
            <div class="card-head"><h2>Edit — {{ $category->name }}</h2></div>
            <div class="card-body">
                <form method="POST" action="/categories/{{ $category->id }}">
                    @csrf @method('PUT')
                    <div class="fg">
                        <label>Category Name</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="fg">
                        <label>Description</label>
                        <textarea name="description">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="/categories" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app-dash>