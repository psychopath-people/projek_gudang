<!-- resources/views/barang/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Tambah Barang Baru</h4>
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                        <input type="text" name="kode"
                                            class="form-control @error('kode') is-invalid @enderror"
                                            value="{{ old('kode') }}" required>
                                        @error('kode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_barang"
                                            class="form-control @error('nama_barang') is-invalid @enderror"
                                            value="{{ old('nama_barang') }}" required>
                                        @error('nama_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="kategori"
                                            class="form-control @error('kategori') is-invalid @enderror"
                                            value="{{ old('kategori') }}" required>
                                        @error('kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                                        <input type="text" name="lokasi"
                                            class="form-control @error('lokasi') is-invalid @enderror"
                                            value="{{ old('lokasi') }}" required>
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                                        <input type="number" name="stok"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            value="{{ old('stok', 0) }}" required min="0">
                                        @error('stok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Harga <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="harga"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                value="{{ old('harga', 0) }}" required min="0">
                                        </div>
                                        @error('harga')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <select name="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                            <option value="">Pilih Satuan</option>
                                            <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>PCS
                                            </option>
                                            <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>Unit
                                            </option>
                                            <option value="box" {{ old('satuan') == 'box' ? 'selected' : '' }}>Box
                                            </option>
                                            <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kg
                                            </option>
                                            <option value="lusin" {{ old('satuan') == 'lusin' ? 'selected' : '' }}>Lusin
                                            </option>
                                        </select>
                                        @error('satuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Supplier</label>
                                        <input type="text" name="supplier"
                                            class="form-control @error('supplier') is-invalid @enderror"
                                            value="{{ old('supplier') }}">
                                        @error('supplier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                                            required>
                                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="tidak_aktif"
                                                {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" name="foto"
                                    class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                <small class="form-text text-muted">Format: JPG, JPEG, PNG (Max. 2MB)</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Barang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Preview foto sebelum upload
            document.querySelector('input[type="file"]').addEventListener('change', function(e) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.classList.add('img-thumbnail', 'mt-2');
                    img.style.maxHeight = '200px';

                    const previewContainer = document.querySelector('input[type="file"]').parentNode;
                    const oldPreview = previewContainer.querySelector('img');
                    if (oldPreview) {
                        oldPreview.remove();
                    }
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        </script>
    @endpush
@endsection
