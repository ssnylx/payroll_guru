@extends('layouts.app')

@section('title', 'Tambah Jabatan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Jabatan Baru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('positions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('positions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Jabatan <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Contoh: Kepala Sekolah, Guru Senior"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">Level Jabatan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('level') is-invalid @enderror"
                                            id="level"
                                            name="level"
                                            required>
                                        <option value="">Pilih Level</option>
                                        <option value="1" {{ old('level') == '1' ? 'selected' : '' }}>Level 1 (Terendah)</option>
                                        <option value="2" {{ old('level') == '2' ? 'selected' : '' }}>Level 2</option>
                                        <option value="3" {{ old('level') == '3' ? 'selected' : '' }}>Level 3</option>
                                        <option value="4" {{ old('level') == '4' ? 'selected' : '' }}>Level 4</option>
                                        <option value="5" {{ old('level') == '5' ? 'selected' : '' }}>Level 5 (Tertinggi)</option>
                                    </select>
                                    @error('level')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="base_allowance">Tunjangan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('base_allowance') is-invalid @enderror"
                                               id="base_allowance"
                                               name="base_allowance"
                                               value="{{ old('base_allowance') }}"
                                               placeholder="0"
                                               min="0"
                                               required>
                                        @error('base_allowance')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <input type="text"
                                           class="form-control @error('description') is-invalid @enderror"
                                           id="description"
                                           name="description"
                                           value="{{ old('description') }}"
                                           placeholder="Deskripsi jabatan (opsional)">
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Informasi:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Level 1 = Jabatan terendah, Level 5 = Jabatan tertinggi</li>
                                        <li>Tunjangan akan digunakan sebagai dasar perhitungan gaji guru</li>
                                        <li>Nama jabatan harus unik dan tidak boleh sama</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Jabatan
                                </button>
                                <a href="{{ route('positions.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Format currency input
    $('#base_allowance').on('input', function() {
        var value = this.value.replace(/[^\d]/g, '');
        this.value = value;
    });

    // Format display with thousand separator
    $('#base_allowance').on('blur', function() {
        var value = parseInt(this.value);
        if (!isNaN(value)) {
            $(this).next('.input-group-append').find('.formatted-value').text(
                'Rp ' + value.toLocaleString('id-ID')
            );
        }
    });
});
</script>
@endsection
