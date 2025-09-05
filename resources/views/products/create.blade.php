<x-default-layout>
    <h1 class="mb-5">Yeni Ürün</h1>

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
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="row g-5">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Seçiniz</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ürün Adı</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="255">
                </div>

                <div class="col-md-12">
                    <label class="form-label">PDF</label>
                    <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                    <div class="form-text">Yalnızca PDF, max 20 MB.</div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('products.index') }}" class="btn btn-light">İptal</a>
                </div>
            </form>
        </div>
    </div>
</x-default-layout>
