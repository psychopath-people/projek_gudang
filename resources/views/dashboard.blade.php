<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Barang</h5>
                        <h2>{{ number_format($total_barang) }}</h2>
                        <p class="mb-0">Jenis barang terdaftar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Stok</h5>
                        <h2>{{ number_format($total_stok) }}</h2>
                        <p class="mb-0">Unit barang tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Mutasi Hari Ini</h5>
                        <h2>{{ number_format($mutasi_hari_ini) }}</h2>
                        <p class="mb-0">Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Stok Kosong</h5>
                        <h2>{{ number_format($barang_kosong) }}</h2>
                        <p class="mb-0">Barang perlu restock</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Grafik Mutasi 7 Hari Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="mutasiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Barang Perlu Restock</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang_minimum as $barang)
                                        <tr>
                                            <td>{{ $barang->nama_barang }}</td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    {{ $barang->stok }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mutasi Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mutasi_terbaru as $mutasi)
                                        <tr>
                                            <td>{{ $mutasi->tanggal->format('d/m/Y H:i') }}</td>
                                            <td>{{ $mutasi->barang->nama_barang }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($mutasi->jenis_mutasi) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($mutasi->jumlah) }}</td>
                                            <td>{{ $mutasi->user->name }}</td>
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('mutasiChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($grafik_mutasi->pluck('date')) !!},
                        datasets: [{
                            label: 'Masuk',
                            data: {!! json_encode($grafik_mutasi->pluck('total_masuk')) !!},
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }, {
                            label: 'Keluar',
                            data: {!! json_encode($grafik_mutasi->pluck('total_keluar')) !!},
                            borderColor: 'rgb(255, 99, 132)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
