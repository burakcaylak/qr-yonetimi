<x-default-layout>
    <h1 class="mb-5">Kategori Düzenle</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-5">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="row g-5">
                @csrf @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required maxlength="255">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Güncelle</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-light">Geri</a>
                </div>
            </form>
        </div>
    </div>
</x-default-layout>
