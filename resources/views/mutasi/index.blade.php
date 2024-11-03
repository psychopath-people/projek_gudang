@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>Daftar Mutasi</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Tambah Mutasi
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('mutasi.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jenis</label>
                            <select name="jenis_mutasi" class="form-select">
                                <option value="">Semua</option>
                                <option value="masuk" {{ request('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>Masuk
                                </option>
                                <option value="keluar" {{ request('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>Keluar
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Barang</label>
                            <select name="barang_id" class="form-select">
                                <option value="">Semua Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}"
                                        {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>

                    @if (request()->hasAny(['start_date', 'end_date', 'jenis_mutasi', 'barang_id']))
                        <div class="row mt-2">
                            <div class="col-12">
                                <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">Reset Filter</a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <!-- Export Buttons -->
                <div class="mb-3">
                    <a href="{{ route('mutasi.print', request()->all()) }}" class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('mutasi.export', request()->all()) }}" class="btn btn-success">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mutasis as $mutasi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mutasi->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $mutasi->barang->kode }}</td>
                                    <td>{{ $mutasi->barang->nama_barang }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : 'danger' }}">
                                            {{ ucfirst($mutasi->jenis_mutasi) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($mutasi->jumlah) }}</td>
                                    <td>{{ $mutasi->user->name }}</td>
                                    <td>{{ $mutasi->keterangan ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('mutasi.historyBarang', $mutasi->barang->id) }}"
                                            class="btn btn-info btn-sm" title="History Barang">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                        <a href="{{ route('mutasi.historyUser', $mutasi->user->id) }}"
                                            class="btn btn-warning btn-sm" title="History User">
                                            <i class="bi bi-person-lines-fill"></i>
                                        </a>
                                    <td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data mutasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $mutasis->firstItem() ?? 0 }} sampai {{ $mutasis->lastItem() ?? 0 }}
                        dari {{ $mutasis->total() }} data
                    </div>
                    {{ $mutasis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto close alerts
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    </script>
@endpush
