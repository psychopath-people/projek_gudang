<!-- resources/views/mutasi/filter.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Filter & Analisis Mutasi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('mutasi.filter') }}" method="GET">
                    <div class="row g-3">
                        <!-- Filter Waktu -->
                        <div class="col-md-4">
                            <label class="form-label">Periode</label>
                            <select name="periode" class="form-select" id="periode">
                                <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>Hari Ini
                                </option>
                                <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu
                                    Ini</option>
                                <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan
                                    Ini</option>
                                <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom
                                </option>
                            </select>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="col-md-8" id="customDateRange" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Filter Kategori -->
                        <div class="col-md-4">
                            <label class="form-label">Kategori Barang</label>
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

                        <!-- Filter Lokasi -->
                        <div class="col-md-4">
                            <label class="form-label">Lokasi</label>
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

                        <!-- Filter Jenis Mutasi -->
                        <div class="col-md-4">
                            <label class="form-label">Jenis Mutasi</label>
                            <select name="jenis_mutasi" class="form-select">
                                <option value="">Semua Jenis</option>
                                <option value="masuk" {{ request('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>Masuk
                                </option>
                                <option value="keluar" {{ request('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>Keluar
                                </option>
                            </select>
                        </div>

                        <!-- Filter User -->
                        <div class="col-md-4">
                            <label class="form-label">Petugas</label>
                            <select name="user_id" class="form-select">
                                <option value="">Semua Petugas</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            @if (request()->hasAny(['periode', 'kategori', 'lokasi', 'jenis_mutasi', 'user_id']))
                                <a href="{{ route('mutasi.filter') }}" class="btn btn-secondary">Reset Filter</a>
                            @endif
                        </div>
                    </div>
                </form>

                @if (isset($mutasis))
                    <!-- Summary Cards -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Transaksi</h6>
                                    <h3>{{ number_format($summary['total_transaksi']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Masuk</h6>
                                    <h3>{{ number_format($summary['total_masuk']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Keluar</h6>
                                    <h3>{{ number_format($summary['total_keluar']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Selisih</h6>
                                    <h3>{{ number_format($summary['selisih']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Filter Table -->
                    <div class="table-responsive mt-4">
                        <h5>Hasil Filter</h5>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mutasis as $mutasi)
                                    <tr>
                                        <td>{{ $mutasi->tanggal->format('d/m/Y H:i') }}</td>
                                        <td>{{ $mutasi->barang->kode }}</td>
                                        <td>{{ $mutasi->barang->nama_barang }}</td>
                                        <td>{{ $mutasi->barang->kategori }}</td>
                                        <td>{{ $mutasi->barang->lokasi }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : 'danger' }}">
                                                {{ ucfirst($mutasi->jenis_mutasi) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($mutasi->jumlah) }}</td>
                                        <td>{{ $mutasi->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data yang sesuai dengan filter</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $mutasis->appends(request()->query())->links() }}
                        </div>

                        <!-- Export Buttons -->
                        <div class="mt-3">
                            <a href="{{ route('mutasi.export', ['format' => 'excel'] + request()->all()) }}"
                                class="btn btn-success">
                                Export Excel
                            </a>
                            <a href="{{ route('mutasi.export', ['format' => 'pdf'] + request()->all()) }}"
                                class="btn btn-danger">
                                Export PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const periodeSelect = document.getElementById('periode');
                const customDateRange = document.getElementById('customDateRange');

                function toggleCustomDateRange() {
                    if (periodeSelect.value === 'custom') {
                        customDateRange.style.display = 'block';
                    } else {
                        customDateRange.style.display = 'none';
                    }
                }

                periodeSelect.addEventListener('change', toggleCustomDateRange);
                toggleCustomDateRange(); // Initial state
            });
        </script>
    @endpush
@endsection
