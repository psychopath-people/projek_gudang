<!-- resources/views/laporan/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Generate Laporan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan.generate') }}" method="GET" target="_blank">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="end_date" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Laporan</label>
                                <select name="jenis_laporan" class="form-select" required>
                                    <option value="mutasi">Laporan Mutasi</option>
                                    <option value="stok">Laporan Stok</option>
                                </select>
                            </div>

                            <div class="mb-3 barang-filter">
                                <label class="form-label">Filter Barang (Opsional)</label>
                                <select name="barang_id" class="form-select">
                                    <option value="">Semua Barang</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Format</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" value="excel"
                                        id="formatExcel" checked>
                                    <label class="form-check-label" for="formatExcel">
                                        Excel
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" value="pdf"
                                        id="formatPdf">
                                    <label class="form-check-label" for="formatPdf">
                                        PDF
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Generate Laporan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelector('select[name="jenis_laporan"]').addEventListener('change', function() {
                const barangFilter = document.querySelector('.barang-filter');
                if (this.value === 'stok') {
                    barangFilter.style.display = 'none';
                } else {
                    barangFilter.style.display = 'block';
                }
            });
        </script>
    @endpush
@endsection

<!-- resources/views/laporan/mutasi_pdf.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Laporan Mutasi Barang</title>
    <style>
        /* Add your PDF styling here */
    </style>
</head>

<body>
    <h2>Laporan Mutasi Barang</h2>
    <p>Periode: {{ date('d/m/Y', strtotime($summary['periode']['start'])) }} -
        {{ date('d/m/Y', strtotime($summary['periode']['end'])) }}</p>

    <table border="1" cellpadding="4" cellspacing="0" width="100%">
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
            @foreach ($mutasis as $mutasi)
                <tr>
                    <td>{{ $mutasi->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $mutasi->barang->nama_barang }}</td>
                    <td>{{ strtoupper($mutasi->jenis_mutasi) }}</td>
                    <td>{{ number_format($mutasi->jumlah) }}</td>
                    <td>{{ $mutasi->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px">
        <p><strong>Total Masuk:</strong> {{ number_format($summary['total_masuk']) }}</p>
        <p><strong>Total Keluar:</strong> {{ number_format($summary['total_keluar']) }}</p>
        <p><strong>Selisih:</strong> {{ number_format($summary['total_masuk'] - $summary['total_keluar']) }}</p>
    </div>
</body>

</html>
