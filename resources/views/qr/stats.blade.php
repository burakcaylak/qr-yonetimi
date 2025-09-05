<x-default-layout>
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-5">
        <h1 class="mb-2">QR İstatistik — {{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="btn btn-light">Geri</a>
    </div>

    <div class="row g-5">
        <div class="col-md-4">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="fs-6 text-muted">Toplam Taranma</div>
                    <div class="fs-1 fw-bold">{{ $total }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="fs-6 text-muted">Son 7 Gün</div>
                    <div class="fs-1 fw-bold">{{ $last7 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="fs-6 text-muted">Durum</div>
                    <div class="fs-1 fw-bold">
                        @if($product->qr_code)
                            <span class="badge {{ $product->qr_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                {{ $product->qr_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        @else
                            <span class="badge badge-light-warning">QR Yok</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            <div class="card-title">Son 20 Tarama</div>
        </div>
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed mb-0">
                    <thead>
                        <tr class="text-gray-600 fw-bold">
                            <th>Tarih</th>
                            <th>IP</th>
                            <th>User-Agent</th>
                            <th>Referer</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold">
                        @forelse($recent as $r)
                            <tr>
                                <td>{{ $r->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $r->ip }}</td>
                                <td class="text-break">{{ $r->user_agent }}</td>
                                <td class="text-break">{{ $r->referer }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-10">Kayıt yok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-default-layout>
