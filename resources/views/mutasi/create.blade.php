@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Tambah Mutasi</h3>
                            <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('mutasi.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                                <select name="barang_id" id="barang_id"
                                    class="form-select @error('barang_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}"
                                            {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                            {{ $barang->kode }} - {{ $barang->nama_barang }}
                                            (Stok: {{ number_format($barang->stok) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('barang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                            class="form-control @error('tanggal') is-invalid @enderror" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Mutasi <span class="text-danger">*</span></label>
                                        <select name="jenis_mutasi" id="jenis_mutasi"
                                            class="form-select @error('jenis_mutasi') is-invalid @enderror" required>
                                            <option value="masuk" {{ old('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>
                                                Barang Masuk
                                            </option>
                                            <option value="keluar" {{ old('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>
                                                Barang Keluar
                                            </option>
                                        </select>
                                        @error('jenis_mutasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}"
                                        class="form-control @error('jumlah') is-invalid @enderror" min="1" required>
                                    <span class="input-group-text stok-info"></span>
                                </div>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted stok-warning"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                    placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Mutasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const barangSelect = document.getElementById('barang_id');
                const jenisMutasiSelect = document.getElementById('jenis_mutasi');
                const jumlahInput = document.getElementById('jumlah');
                const stokInfo = document.querySelector('.stok-info');
                const stokWarning = document.querySelector('.stok-warning');

                function updateStokInfo() {
                    const selectedOption = barangSelect.options[barangSelect.selectedIndex];
                    const stok = selectedOption.dataset.stok;

                    if (selectedOption.value) {
                        stokInfo.textContent = `Stok: ${stok}`;

                        if (jenisMutasiSelect.value === 'keluar') {
                            jumlahInput.max = stok;
                            stokWarning.textContent = `Maksimal pengambilan: ${stok}`;

                            if (parseInt(jumlahInput.value) > parseInt(stok)) {
                                jumlahInput.value = stok;
                            }
                        } else {
                            jumlahInput.removeAttribute('max');
                            stokWarning.textContent = '';
                        }
                    } else {
                        stokInfo.textContent = '';
                        stokWarning.textContent = '';
                    }
                }

                barangSelect.addEventListener('change', updateStokInfo);
                jenisMutasiSelect.addEventListener('change', updateStokInfo);

                updateStokInfo();
            });
        </script>
    @endpush
@endsection
