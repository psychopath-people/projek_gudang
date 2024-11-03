<!-- resources/views/mutasi/history_user.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">History Mutasi Petugas</h4>
                </div>
            </div>
            <div class="card-body">
                <!-- Detail User -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="150">Nama Petugas</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5>Summary Aktivitas</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-primary">
                                            <h6>Total Transaksi</h6>
                                            <h4>{{ number_format($summary['total_transaksi']) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-success">
                                            <h6>Barang Masuk</h6>
                                            <h4>{{ number_format($summary['total_barang_masuk']) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-danger">
                                            <h6>Barang Keluar</h6>
                                            <h4>{{ number_format($summary['total_barang_keluar']) }}</h4>
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
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jenis Mutasi</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mutasis as $mutasi)
                                <tr>
                                    <td>{{ $mutasi->tanggal->format('d/m/Y H:i') }}</td>
                                    <td>{{ $mutasi->barang->kode }}</td>
                                    <td>{{ $mutasi->barang->nama_barang }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : 'danger' }}">
                                            {{ ucfirst($mutasi->jenis_mutasi) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($mutasi->jumlah) }}</td>
                                    <td>{{ $mutasi->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada history mutasi</td>
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
