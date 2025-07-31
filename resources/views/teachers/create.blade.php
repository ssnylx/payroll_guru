@extends('layouts.app')

@section('title', 'Tambah Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Guru</h1>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body">
                <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h4 class="mb-4 border-bottom pb-2">Data Pribadi</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                                name="nip" value="{{ old('nip') }}">
                            @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                                name="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="no_telepon" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                            <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror"
                                id="pendidikan_terakhir" name="pendidikan_terakhir">
                                <option value="">Pilih Pendidikan Terakhir</option>
                                <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>
                                    SMA/SMK</option>
                                <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1
                                </option>
                                <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2
                                </option>
                                <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3
                                </option>
                            </select>
                            @error('pendidikan_terakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="education_level_id" class="form-label">Jenjang Pendidikan</label>
                            <select class="form-select @error('education_level_id') is-invalid @enderror"
                                id="education_level_id" name="education_level_id">
                                <option value="">Pilih Jenjang Pendidikan</option>
                                @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('education_level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('education_level_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <label for="peran" class="form-label">Peran</label>
                        <select class="form-control" id="peran" name="peran" required onchange="toggleFieldsByRole()">
                            <option value="guru" {{ old('peran', 'guru') == 'guru' ? 'selected' : '' }} selected>Guru
                            </option>
                            <option value="admin" {{ old('peran') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="bendahara" {{ old('peran') == 'bendahara' ? 'selected' : '' }}>Bendahara
                            </option>
                        </select>
                        @error('peran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 mt-4">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat"
                            rows="3">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <h4 class="mt-5 mb-4 border-bottom pb-2">Data Kepegawaian</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}">
                            @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="main_position_id" class="form-label">Jabatan Utama</label>
                            <select name="main_position_id" class="form-select" id="main_position_id">
                                <option value="">-- Pilih Jabatan Utama --</option>
                                @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="salary_type" class="form-label">Tipe Penggajian</label>
                            <select class="form-select @error('salary_type') is-invalid @enderror" id="salary_type"
                                name="salary_type" onchange="updateNominalLabel()">
                                <option value="">Pilih Tipe Penggajian</option>
                                <option value="per_hari" {{ old('salary_type') == 'per_hari' ? 'selected' : '' }}>Per
                                    Hari</option>
                                <option value="per_jam" {{ old('salary_type') == 'per_jam' ? 'selected' : '' }}>Per Jam
                                </option>
                                <option value="per_bulan"
                                    {{ old('salary_type', 'per_bulan') == 'per_bulan' ? 'selected' : '' }}>Per Bulan
                                </option>
                            </select>
                            @error('salary_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="nominal" class="form-label"><span id="nominal-label">Nominal Gaji</span></label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                id="nominal" name="nominal" value="{{ old('nominal') }}">
                            <div class="form-text"><span id="nominal-help">Masukkan nominal sesuai tipe
                                    penggajian</span></div>
                            @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan (Bisa Pilih Lebih dari Satu)</label>
                        <div class="row g-2">
                            @foreach($positions as $position)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="position_{{ $position->id }}"
                                        name="positions[]" value="{{ $position->id }}"
                                        {{ in_array($position->id, old('positions', [])) ? 'checked' : '' }}>
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
                        @error('positions')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran (Bisa Pilih Lebih dari Satu)</label>
                        <div class="row">
                            @foreach($subjects as $subject)
                            <div class="col-md-6 col-sm-12 mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="subject_{{ $subject->id }}"
                                        name="subjects[]" value="{{ $subject->id }}"
                                        {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="subject_{{ $subject->id }}">{{ $subject->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('subjects')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hari Kerja</label>
                            <div class="form-check-group d-flex flex-wrap gap-2">
                                @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="working_days_{{ $day }}"
                                        name="working_days[]" value="{{ $day }}"
                                        {{ in_array($day, old('working_days', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="working_days_{{ $day }}">{{ ucfirst($day) }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('working_days')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Shift Mengajar</label>
                            <div class="form-check-group d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="shift_none" name="shift_id"
                                        value="" checked>
                                    <label class="form-check-label" for="shift_none">Tidak ada shift</label>
                                </div>
                                @foreach(\App\Models\Shift::active()->get() as $shift)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="shift_{{ $shift->id }}"
                                        name="shift_id" value="{{ $shift->id }}"
                                        {{ old('shift_id') == $shift->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shift_{{ $shift->id }}">{{ $shift->name }}
                                        ({{ $shift->start_time }} - {{ $shift->end_time }})</label>
                                </div>
                                @endforeach
                            </div>
                            @error('shift_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <h4 class="mt-5 mb-4 border-bottom pb-2">Tunjangan</h4>
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <div class="form-allowance-types row g-3">
                            @foreach($allowanceTypes as $allowanceType)
                            <div class="col-md-6">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input allowance-checkbox" type="checkbox"
                                                id="allowance_types_{{ $allowanceType->id }}" name="allowance_types[]"
                                                value="{{ $allowanceType->id }}"
                                                {{ in_array($allowanceType->id, old('allowance_types', [])) ? 'checked' : '' }}
                                                onchange="toggleAllowanceOptions({{ $allowanceType->id }})">
                                            <label class="form-check-label"
                                                for="allowance_types_{{ $allowanceType->id }}">
                                                <strong>{{ $allowanceType->name }}</strong>
                                                <small class="text-muted">(Default: Rp
                                                    {{ number_format($allowanceType->default_amount, 0, ',', '.') }})</small>
                                                <br><small class="text-muted">{{ $allowanceType->description }}</small>
                                            </label>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6" id="calculation_options_{{ $allowanceType->id }}"
                                                style="display: none;">
                                                <label for="allowance_calculation_{{ $allowanceType->id }}"
                                                    class="form-label">Tipe Perhitungan</label>
                                                <select class="form-select"
                                                    name="allowance_calculation_{{ $allowanceType->id }}"
                                                    id="allowance_calculation_{{ $allowanceType->id }}">
                                                    <option value="per_hari"
                                                        {{ ($allowanceType->calculation_type ?? 'per_hari') == 'per_hari' ? 'selected' : '' }}>
                                                        Per Hari</option>
                                                    <option value="per_bulan"
                                                        {{ ($allowanceType->calculation_type ?? 'per_hari') == 'per_bulan' ? 'selected' : '' }}>
                                                        Per Bulan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="amount_options_{{ $allowanceType->id }}"
                                                style="display: none;">
                                                <label for="allowance_amount_{{ $allowanceType->id }}"
                                                    class="form-label">Nominal</label>
                                                <input type="number" class="form-control"
                                                    name="allowance_amount_{{ $allowanceType->id }}"
                                                    id="allowance_amount_{{ $allowanceType->id }}"
                                                    value="{{ old("allowance_amount_{$allowanceType->id}", $allowanceType->default_amount) }}"
                                                    min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('allowance_types')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Pilih jenis tunjangan yang akan diberikan kepada guru ini
                            dengan tipe perhitungan dan nominal yang sesuai.</small>
                    </div>
                    <div class="mb-3 mt-4">
                        <label for="photo" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo"
                            name="photo" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Script yang berjalan segera
(function() {
    console.log('Immediate script running...');

    // Tunggu sebentar untuk memastikan DOM sudah siap
    setTimeout(function() {
        console.log('Running delayed initialization...');
        if (typeof toggleFieldsByRole === 'function') {
            toggleFieldsByRole();
        } else {
            console.log('toggleFieldsByRole function not found!');
        }
    }, 100);

    // Tambahan: Jalankan lagi setelah 500ms untuk memastikan
    setTimeout(function() {
        console.log('Running second initialization...');
        if (typeof toggleFieldsByRole === 'function') {
            toggleFieldsByRole();
        }
    }, 500);
})();

// Tambahan: Jalankan saat window load
window.addEventListener('load', function() {
    console.log('Window loaded, running toggleFieldsByRole...');
    if (typeof toggleFieldsByRole === 'function') {
        toggleFieldsByRole();
    }
});

// Script langsung untuk mengatur field
(function() {
    function setupFields() {
        var role = document.getElementById('peran');
        if (role) {
            var currentRole = role.value;
            console.log('Current role:', currentRole);

            // Daftar semua field yang harus di-disable untuk admin/bendahara
            var fields = [
                'nip', 'alamat', 'no_telepon', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                'pendidikan_terakhir', 'education_level_id', 'tanggal_masuk', 'main_position_id',
                'salary_type', 'nominal'
            ];

            // Handle semua field input/select
            fields.forEach(function(fieldName) {
                var field = document.getElementById(fieldName);
                if (field) {
                    if (currentRole === 'admin' || currentRole === 'bendahara') {
                        field.removeAttribute('required');
                        field.disabled = true;
                        field.value = '';
                        if (field.type === 'select-one') {
                            field.selectedIndex = 0;
                        }
                        console.log('Disabled field:', fieldName);
                    } else {
                        field.disabled = false;
                        field.setAttribute('required', 'required');
                        console.log('Enabled field:', fieldName);
                    }
                }
            });

            // Handle textarea alamat
            var alamatField = document.getElementById('alamat');
            if (alamatField) {
                if (currentRole === 'admin' || currentRole === 'bendahara') {
                    alamatField.removeAttribute('required');
                    alamatField.disabled = true;
                    alamatField.value = '';
                    console.log('Disabled alamat field');
                } else {
                    alamatField.disabled = false;
                    alamatField.setAttribute('required', 'required');
                    console.log('Enabled alamat field');
                }
            }

            // Handle semua checkbox dan radio button
            var checkboxes = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');
            checkboxes.forEach(function(cb) {
                if (currentRole === 'admin' || currentRole === 'bendahara') {
                    cb.disabled = true;
                    cb.checked = false;
                    cb.removeAttribute('required');
                } else {
                    cb.disabled = false;
                }
            });

            // Handle photo field
            var photoField = document.getElementById('photo');
            if (photoField) {
                if (currentRole === 'admin' || currentRole === 'bendahara') {
                    photoField.disabled = true;
                    photoField.removeAttribute('required');
                } else {
                    photoField.disabled = false;
                }
            }

            // Pastikan name dan email selalu required
            var nameField = document.getElementById('name');
            var emailField = document.getElementById('email');
            if (nameField) nameField.setAttribute('required', 'required');
            if (emailField) emailField.setAttribute('required', 'required');
        }
    }

    // Jalankan segera
    setTimeout(setupFields, 50);
    setTimeout(setupFields, 200);
    setTimeout(setupFields, 500);

    // Jalankan saat peran berubah
    var peranSelect = document.getElementById('peran');
    if (peranSelect) {
        peranSelect.addEventListener('change', setupFields);
    }
})();

// Helper function to toggle allowance selection
function toggleAllAllowances() {
    const checkboxes = document.querySelectorAll('input[name="allowance_types[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

// Add a "Select All" button for allowances
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

function toggleFieldsByRole() {
    var role = document.getElementById('peran').value;
    console.log('toggleFieldsByRole called with role:', role);

    // Daftar semua field yang harus di-disable untuk admin/bendahara
    var fieldsToHandle = [
        'nip', 'alamat', 'no_telepon', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'pendidikan_terakhir', 'education_level_id', 'tanggal_masuk', 'main_position_id',
        'salary_type', 'nominal'
    ];

    // Handle semua field
    fieldsToHandle.forEach(function(fieldName) {
        var field = document.getElementById(fieldName);
        if (field) {
            console.log('Processing field:', fieldName, 'found:', !!field);
            if (role === 'admin' || role === 'bendahara') {
                field.removeAttribute('required');
                field.disabled = true;
                field.value = '';
                if (field.type === 'select-one') {
                    field.selectedIndex = 0;
                }
                console.log('Field', fieldName, 'disabled and not required');
            } else {
                field.disabled = false;
                field.setAttribute('required', 'required');
                console.log('Field', fieldName, 'enabled and required');
            }
        } else {
            console.log('Field not found:', fieldName);
        }
    });

    // Handle textarea alamat secara khusus
    var alamatField = document.getElementById('alamat');
    if (alamatField) {
        console.log('Processing alamat field');
        if (role === 'admin' || role === 'bendahara') {
            alamatField.removeAttribute('required');
            alamatField.disabled = true;
            alamatField.value = '';
            console.log('Alamat field disabled and not required');
        } else {
            alamatField.disabled = false;
            alamatField.setAttribute('required', 'required');
            console.log('Alamat field enabled and required');
        }
    }

    // Handle semua checkbox dan radio button
    var allCheckboxes = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    console.log('Found', allCheckboxes.length, 'checkboxes/radio buttons');
    allCheckboxes.forEach(function(cb) {
        if (role === 'admin' || role === 'bendahara') {
            cb.disabled = true;
            cb.checked = false;
            cb.removeAttribute('required');
        } else {
            cb.disabled = false;
        }
    });

    // Handle photo field
    var photoField = document.getElementById('photo');
    if (photoField) {
        if (role === 'admin' || role === 'bendahara') {
            photoField.disabled = true;
            photoField.removeAttribute('required');
        } else {
            photoField.disabled = false;
        }
    }
}

// Initialize saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing toggleFieldsByRole...');
    // Jalankan fungsi sekali saat halaman dimuat
    toggleFieldsByRole();

    // Tambahkan event listener untuk perubahan peran
    var peranSelect = document.getElementById('peran');
    if (peranSelect) {
        console.log('Found peran select, adding change listener');
        peranSelect.addEventListener('change', function() {
            console.log('Peran changed to:', this.value);
            toggleFieldsByRole();
        });
    } else {
        console.log('Peran select not found!');
    }
});
</script>
@endpush
@endsection