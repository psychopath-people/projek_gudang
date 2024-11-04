<!-- resources/views/mutasi/history_barang.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">History Mutasi Barang</h4>
                    <div>
                        <a href="{{ route('mutasi.historyBarang', ['id' => $barang->id, 'format' => 'json']) }}"
                            class="btn btn-secondary" target="_blank">
                            View JSON
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Detail Barang -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="150">Kode Barang</th>
                                <td>{{ $barang->kode }}</td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td>{{ $barang->nama_barang }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $barang->kategori }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>{{ $barang->lokasi }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5>Summary Mutasi</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-success">
                                            <h6>Total Masuk</h6>
                                            <h4>{{ number_format($summary['total_masuk']) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-danger">
                                            <h6>Total Keluar</h6>
                                            <h4>{{ number_format($summary['total_keluar']) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-primary">
                                            <h6>Stok Saat Ini</h6>
                                            <h4>{{ number_format($summary['stok_saat_ini']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel History -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Mutasi</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mutasis as $mutasi)
                                <tr>
                                    <td>{{ $mutasi->tanggal->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : 'danger' }}">
                                            {{ ucfirst($mutasi->jenis_mutasi) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($mutasi->jumlah) }}</td>
                                    <td>{{ $mutasi->user->name }}</td>
                                    <td>{{ $mutasi->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada history mutasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $mutasis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
