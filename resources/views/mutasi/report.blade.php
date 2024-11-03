<!-- resources/views/mutasi/report.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Laporan Mutasi Barang</h4>
                            <div>
                                <a href="{{ request()->fullUrlWithQuery(['type' => 'pdf']) }}" class="btn btn-danger">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Periode</th>
                                        <td>{{ request('start_date', 'Semua') }} s/d {{ request('end_date', 'Semua') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Masuk</th>
                                        <td>{{ number_format($total_masuk) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Keluar</th>
                                        <td>{{ number_format($total_keluar) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Selisih</th>
                                        <td>{{ number_format($total_masuk - $total_keluar) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Petugas</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mutasis as $mutasi)
                                        <tr>
                                            <td>{{ $mutasi->tanggal->format('d/m/Y') }}</td>
                                            <td>{{ $mutasi->barang->nama_barang }}</td>
                                            <td>{{ ucfirst($mutasi->jenis_mutasi) }}</td>
                                            <td>{{ number_format($mutasi->jumlah) }}</td>
                                            <td>{{ $mutasi->user->name }}</td>
                                            <td>{{ $mutasi->keterangan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
