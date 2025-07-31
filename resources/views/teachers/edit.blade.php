@extends('layouts.app')

@section('title', 'Edit Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Guru</h1>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $teacher->user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $teacher->user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                                    name="nip" value="{{ old('nip', $teacher->nip) }}" required>
                                @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                    name="alamat" rows="2" required>{{ old('alamat', $teacher->alamat) }}</textarea>
                                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                    id="no_telepon" name="no_telepon"
                                    value="{{ old('no_telepon', $teacher->no_telepon) }}" required>
                                @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="laki-laki"
                                        {{ old('jenis_kelamin', $teacher->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="perempuan"
                                        {{ old('jenis_kelamin', $teacher->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $teacher->tanggal_lahir ? $teacher->tanggal_lahir->format('Y-m-d') : '') }}"
                                    required>
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $teacher->tempat_lahir) }}" required>
                                @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    id="tanggal_masuk" name="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', $teacher->tanggal_masuk ? $teacher->tanggal_masuk->format('Y-m-d') : '') }}"
                                    required>
                                @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal Gaji</label>
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                    id="nominal" name="nominal" value="{{ old('nominal', $teacher->nominal) }}"
                                    required>
                                @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="salary_type" class="form-label">Tipe Penggajian</label>
                                <select class="form-select @error('salary_type') is-invalid @enderror" id="salary_type"
                                    name="salary_type" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="per_hari"
                                        {{ old('salary_type', $teacher->salary_type) == 'per_hari' ? 'selected' : '' }}>
                                        Per Hari</option>
                                    <option value="per_jam"
                                        {{ old('salary_type', $teacher->salary_type) == 'per_jam' ? 'selected' : '' }}>
                                        Per Jam</option>
                                    <option value="per_bulan"
                                        {{ old('salary_type', $teacher->salary_type) == 'per_bulan' ? 'selected' : '' }}>
                                        Per Bulan</option>
                                </select>
                                @error('salary_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active"
                                    name="is_active" required>
                                    <option value="1"
                                        {{ old('is_active', $teacher->is_active) == 1 ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0"
                                        {{ old('is_active', $teacher->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                                </select>
                                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror"
                                    id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                    <option value="">Pilih Pendidikan Terakhir</option>
                                    <option value="SMA/SMK"
                                        {{ old('pendidikan_terakhir', $teacher->pendidikan_terakhir) == 'SMA/SMK' ? 'selected' : '' }}>
                                        SMA/SMK</option>
                                    <option value="S1"
                                        {{ old('pendidikan_terakhir', $teacher->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>
                                        S1</option>
                                    <option value="S2"
                                        {{ old('pendidikan_terakhir', $teacher->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>
                                        S2</option>
                                    <option value="S3"
                                        {{ old('pendidikan_terakhir', $teacher->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>
                                        S3</option>
                                </select>
                                @error('pendidikan_terakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="education_level_id" class="form-label">Jenjang Pendidikan</label>
                                <select class="form-select @error('education_level_id') is-invalid @enderror"
                                    id="education_level_id" name="education_level_id">
                                    <option value="">Pilih Jenjang Pendidikan</option>
                                    @foreach($educationLevels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ old('education_level_id', $teacher->education_level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}</option>
                                    @endforeach
                                </select>
                                @error('education_level_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="main_position_id" class="form-label">Jabatan Utama</label>
                                <select name="main_position_id" class="form-select" id="main_position_id">
                                    <option value="">-- Pilih Jabatan Utama --</option>
                                    @foreach($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{ old('main_position_id', $teacher->main_position_id) == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan (Bisa Pilih Lebih dari Satu)</label>
                        <div class="row">
                            @php $teacherPositionIds = $teacher->positions->pluck('id')->toArray(); @endphp
                            @foreach($positions as $position)
                            <div class="col-md-2 col-sm-4 col-5 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="position_{{ $position->id }}"
                                        name="positions[]" value="{{ $position->id }}"
                                        {{ in_array($position->id, old('positions', $teacherPositionIds)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="position_{{ $position->id }}">
                                        {{ $position->name }}
                                        @if($position->base_allowance > 0)
                                        <br><small class="text-muted">(Rp
                                            {{ number_format($position->base_allowance) }})</small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <div class="row">
                                    @php $teacherSubjectIds = $teacher->subjects->pluck('id')->toArray(); @endphp
                                    @foreach($subjects as $subject)
                                    <div class="col-md-6 col-sm-12 mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="subject_{{ $subject->id }}" name="subjects[]"
                                                value="{{ $subject->id }}"
                                                {{ in_array($subject->id, old('subjects', $teacherSubjectIds)) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="subject_{{ $subject->id }}">{{ $subject->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo"
                                    name="photo" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                                @if($teacher->photo_path)
                                <div class="mt-2"><img src="{{ asset('storage/'.$teacher->photo_path) }}"
                                        alt="Foto Guru" width="80"></div>
                                @endif
                                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hari Kerja</label>
                                <div class="form-check-group">
                                    @php $teacherWorkingDays = $teacher->working_days ?
                                    (is_array($teacher->working_days) ? $teacher->working_days : explode(',',
                                    $teacher->working_days)) : []; @endphp
                                    @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="working_days_{{ $day }}"
                                            name="working_days[]" value="{{ $day }}"
                                            {{ in_array($day, old('working_days', $teacherWorkingDays)) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="working_days_{{ $day }}">{{ ucfirst($day) }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Shift Mengajar</label>
                                <div class="form-check-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="shift_none" name="shift_id"
                                            value="" {{ !$teacher->shifts->count() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shift_none">Tidak ada shift</label>
                                    </div>
                                    @foreach($shifts as $shift)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="shift_{{ $shift->id }}"
                                            name="shift_id" value="{{ $shift->id }}"
                                            {{ $teacher->shifts->contains('id', $shift->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shift_{{ $shift->id }}">{{ $shift->name }}
                                            ({{ $shift->start_time }} - {{ $shift->end_time }})</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Jenis Tunjangan</label>
                                <div class="form-allowance-types">
                                    @php
                                    $teacherAllowanceIds =
                                    $teacher->teacherAllowances->pluck('allowance_type_id')->toArray();
                                    $teacherAllowanceData = $teacher->teacherAllowances->keyBy('allowance_type_id');
                                    @endphp
                                    @foreach($allowanceTypes as $allowanceType)
                                    <div class="card mb-3" id="allowance_card_{{ $allowanceType->id }}">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input allowance-checkbox"
                                                            type="checkbox"
                                                            id="allowance_types_{{ $allowanceType->id }}"
                                                            name="allowance_types[]" value="{{ $allowanceType->id }}"
                                                            {{ in_array($allowanceType->id, old('allowance_types', $teacherAllowanceIds)) ? 'checked' : '' }}
                                                            onchange="toggleAllowanceOptions({{ $allowanceType->id }})">
                                                        <label class="form-check-label"
                                                            for="allowance_types_{{ $allowanceType->id }}">
                                                            <strong>{{ $allowanceType->name }}</strong>
                                                            <small class="text-muted">(Default: Rp
                                                                {{ number_format($allowanceType->default_amount, 0, ',', '.') }})</small>
                                                            <br><small
                                                                class="text-muted">{{ $allowanceType->description }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="calculation_options_{{ $allowanceType->id }}"
                                                    style="display: none;">
                                                    <label for="allowance_calculation_{{ $allowanceType->id }}"
                                                        class="form-label">Tipe Perhitungan</label>
                                                    <select class="form-select"
                                                        name="allowance_calculation_types[{{ $allowanceType->id }}]"
                                                        id="allowance_calculation_{{ $allowanceType->id }}">
                                                        <option value="per_hari"
                                                            {{ (old('allowance_calculation_types.'.$allowanceType->id, $teacherAllowanceData[$allowanceType->id]->calculation_type ?? $allowanceType->calculation_type ?? 'per_hari') == 'per_hari') ? 'selected' : '' }}>
                                                            Per Hari</option>
                                                        <option value="per_bulan"
                                                            {{ (old('allowance_calculation_types.'.$allowanceType->id, $teacherAllowanceData[$allowanceType->id]->calculation_type ?? $allowanceType->calculation_type ?? 'per_hari') == 'per_bulan') ? 'selected' : '' }}>
                                                            Per Bulan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4" id="amount_options_{{ $allowanceType->id }}"
                                                    style="display: none;">
                                                    <label for="allowance_amount_{{ $allowanceType->id }}"
                                                        class="form-label">Nominal</label>
                                                    <input type="number" class="form-control"
                                                        name="allowance_amounts[{{ $allowanceType->id }}]"
                                                        id="allowance_amount_{{ $allowanceType->id }}"
                                                        value="{{ old('allowance_amounts.'.$allowanceType->id, $teacherAllowanceData[$allowanceType->id]->amount ?? $allowanceType->default_amount) }}"
                                                        min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <small class="form-text text-muted">Pilih jenis tunjangan yang akan diberikan kepada
                                    guru ini dengan tipe perhitungan dan nominal yang sesuai.</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAllAllowances() {
    const checkboxes = document.querySelectorAll('input[name="allowance_types[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}
document.addEventListener('DOMContentLoaded', function() {
    const allowanceContainer = document.querySelector('.form-allowance-types');
    if (allowanceContainer) {
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'btn btn-sm btn-outline-secondary mb-2';
        selectAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i>Pilih Semua / Batal Pilih';
        selectAllBtn.onclick = toggleAllAllowances;
        allowanceContainer.parentNode.insertBefore(selectAllBtn, allowanceContainer);
    }
});

function toggleAllowanceOptions(allowanceTypeId) {
    const calculationOptions = document.getElementById(`calculation_options_${allowanceTypeId}`);
    const amountOptions = document.getElementById(`amount_options_${allowanceTypeId}`);

    if (calculationOptions && amountOptions) {
        if (document.getElementById(`allowance_types_${allowanceTypeId}`).checked) {
            calculationOptions.style.display = 'block';
            amountOptions.style.display = 'block';
        } else {
            calculationOptions.style.display = 'none';
            amountOptions.style.display = 'none';
        }
    }
}
</script>
@endpush