<!-- resources/views/barang/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Daftar Barang</h2>
                    <a href="{{ route('barang.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('barang.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Cari barang..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori }}"
                                        {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="lokasi" class="form-select">
                                <option value="">Semua Lokasi</option>
                                @foreach ($lokasis as $lokasi)
                                    <option value="{{ $lokasi }}"
                                        {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                                        {{ $lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                @if (request('search') || request('kategori') || request('lokasi'))
                                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Clear</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangs as $barang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $barang->kode }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->kategori }}</td>
                                    <td>{{ $barang->lokasi }}</td>
                                    <td>{{ $barang->stok }} {{ $barang->satuan }}</td>
                                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $barang->status == 'aktif' ? 'success' : 'danger' }}">
                                            {{ $barang->status == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('barang.edit', $barang->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data barang</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $barangs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
