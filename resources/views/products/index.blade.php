<x-default-layout>
    @php
        $pageTitle = 'Ürünler';
    @endphp

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-5">
        <h1 class="mb-2">{{ $pageTitle }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                {!! getIcon('qr-code', 'fs-2 me-2') !!} Yeni QR Oluştur
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-5">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-5">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- QR küçük önizleme için stil (36 -> hover 240) --}}
    <style>
        .qr-td { position: relative; overflow: visible; }
        .qr-wrap { position: relative; width:36px; height:36px; }
        .qr-wrap img {
            position: absolute; top:0; left:0;
            width:240px; height:240px;
            transform: scale(0.15);
            transform-origin: top left;
            transition: transform .15s ease, box-shadow .15s ease;
            border-radius: .5rem; box-shadow: none; z-index: 20;
        }
        .qr-wrap:hover img {
            transform: scale(1);
            box-shadow: 0 10px 20px rgba(0,0,0,.2);
        }
    </style>

    <div class="card">
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed mb-0">
                    <thead>
                        <tr class="text-gray-600 fw-bold">
                            <th>#</th>
                            <th>Ürün Adı</th>
                            <th>Kategori</th>
                            <th>QR Durum</th>
                            <th>QR Link &amp; Önizleme</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold">
                        @forelse($products as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td>
                                    @if ($p->qr_code)
                                        <span class="badge {{ $p->qr_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                            {{ $p->qr_active ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    @else
                                        <span class="badge badge-light-warning">Henüz oluşturulmadı</span>
                                    @endif
                                </td>

                                {{-- QR Link + Küçük Önizleme --}}
                                <td class="qr-td" style="max-width:420px">
                                    @if ($p->qr_code)
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-grow-1">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" readonly
                                                           value="{{ $p->qrRedirectUrl() }}">
                                                    <button class="btn btn-light"
                                                            onclick="navigator.clipboard.writeText('{{ $p->qrRedirectUrl() }}')">
                                                        {!! getIcon('copy', 'fs-3') !!}
                                                    </button>
                                                </div>
                                                <div class="form-text">
                                                    PDF linki: {{ route('pdf.serve', ['slug' => $p->slug]) }}
                                                </div>
                                            </div>
                                            <div class="qr-wrap">
                                                <img src="{{ route('qr.preview', $p) }}" alt="QR">
                                            </div>
                                        </div>
                                    @else
                                        <em class="text-muted">QR oluşturulmadı</em>
                                    @endif
                                </td>

                                <td class="text-end">
                                    {{-- QR oluştur (sadece yoksa) --}}
                                    @if(!$p->qr_code)
                                        <form action="{{ route('qr.generate', $p) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-light-primary">
                                                {!! getIcon('qr-code', 'fs-3 me-1') !!} QR Oluştur
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Aktif/Pasif --}}
                                    @if($p->qr_code)
                                        <form action="{{ route('qr.toggle', $p) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm {{ $p->qr_active ? 'btn-light-danger' : 'btn-light-success' }}">
                                                {!! getIcon($p->qr_active ? 'eye-slash' : 'eye', 'fs-3 me-1') !!}
                                                {{ $p->qr_active ? 'Pasife Al' : 'Aktife Al' }}
                                            </button>
                                        </form>

                                        {{-- İndir --}}
                                        <a href="{{ route('qr.png', $p) }}" class="btn btn-sm btn-light">
                                            {!! getIcon('image', 'fs-3 me-1') !!} PNG
                                        </a>
                                        <a href="{{ route('qr.svg', $p) }}" class="btn btn-sm btn-light">
                                            {!! getIcon('file-down', 'fs-3 me-1') !!} SVG
                                        </a>

                                        {{-- İstatistik --}}
                                        <a href="{{ route('qr.stats', $p) }}" class="btn btn-sm btn-light-info">
                                            {!! getIcon('chart', 'fs-3 me-1') !!} İstatistik
                                        </a>
                                    @endif

                                    {{-- PDF Güncelle --}}
                                    <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-light-warning">
                                        {!! getIcon('file-edit', 'fs-3 me-1') !!} PDF Güncelle
                                    </a>

                                    {{-- Sil (sadece admin) --}}
                                    @can('product-delete')
                                        <form action="{{ route('products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Silinsin mi?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-light-danger">
                                                {!! getIcon('trash', 'fs-3 me-1') !!} Sil
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-10">Henüz ürün eklenmedi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-default-layout>
