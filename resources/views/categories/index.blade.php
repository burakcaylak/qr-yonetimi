<x-default-layout>
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-5">
        <h1 class="mb-2">Kategoriler</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            {!! getIcon('plus', 'fs-2 me-2', 'duotone') !!} Yeni Kategori
        </a>
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

    <div class="card">
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed mb-0">
                    <thead>
                        <tr class="text-gray-600 fw-bold">
                            <th>#</th>
                            <th>Ad</th>
                            <th>Ürün Sayısı</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold">
                        @forelse($categories as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->products_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('categories.edit', $c) }}" class="btn btn-sm btn-light-warning">Düzenle</a>
                                    <form action="{{ route('categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Silinsin mi?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light-danger">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-10">Kategori yok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($categories->hasPages())
            <div class="card-footer">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-default-layout>
