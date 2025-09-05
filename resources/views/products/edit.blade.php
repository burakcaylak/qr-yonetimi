<x-default-layout>
    <h1 class="mb-5">Ürün Düzenle (PDF)</h1>

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
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="row g-5">
                @csrf @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" value="{{ $product->category->name ?? '-' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ürün Adı</label>
                    <input type="text" class="form-control" value="{{ $product->name }}" disabled>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Yeni PDF</label>
                    <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                    <div class="form-text">Mevcut PDF: <a target="_blank" href="{{ route('pdf.serve', ['slug' => $product->slug]) }}">Görüntüle</a>
                    <button type="button" class="btn btn-sm btn-light ms-2" data-bs-toggle="modal" data-bs-target="#pdfPreviewModal">
                        {!! getIcon('eye', 'fs-3 me-1') !!} Önizleme
                    </button>
</div>

                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('products.index') }}" class="btn btn-light">Geri</a>
                </div>
            </form>
        </div>
    </div>

<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">PDF Önizleme — {{ $product->name }}</h3>
        <button type="button" class="btn btn-icon btn-sm btn-light" data-bs-dismiss="modal">
          {!! getIcon('cross', 'fs-2') !!}
        </button>
      </div>
      <div class="modal-body p-0">
        <iframe
          src="{{ route('pdf.serve', ['slug' => $product->slug]) }}#view=FitH&toolbar=0&navpanes=0"
          style="width:100%; height:75vh;" frameborder="0">
        </iframe>
      </div>
    </div>
  </div>
</div>

</x-default-layout>
