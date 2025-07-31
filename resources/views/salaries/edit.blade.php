@extends('layouts.app')

@section('title', 'Edit Gaji - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Gaji</h1>
            <a href="{{ route('salaries.show', $salary) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Gaji
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('salaries.update', $salary) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Data gaji pokok, tunjangan, dan kehadiran tidak dapat diubah. Hanya bonus, potongan, status, dan keterangan yang dapat diedit.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bonus" class="form-label">Bonus</label>
                                <input type="number" class="form-control @error('bonus') is-invalid @enderror"
                                       id="bonus" name="bonus" value="{{ old('bonus', $salary->bonus) }}" min="0">
                                @error('bonus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan bonus dalam rupiah (tanpa titik/koma)</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="potongan" class="form-label">Potongan</label>
                                <input type="number" class="form-control @error('potongan') is-invalid @enderror"
                                       id="potongan" name="potongan" value="{{ old('potongan', $salary->potongan) }}" min="0">
                                @error('potongan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan potongan dalam rupiah (tanpa titik/koma)</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status_gaji" class="form-label">Status Gaji</label>
                        <select class="form-select @error('status_gaji') is-invalid @enderror"
                                id="status_gaji" name="status_gaji" required>
                            <option value="">Pilih Status</option>
                            <option value="draft" {{ old('status_gaji', $salary->status_gaji) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approve" {{ old('status_gaji', $salary->status_gaji) == 'approve' ? 'selected' : '' }}>Approve</option>
                            <option value="paid" {{ old('status_gaji', $salary->status_gaji) == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('status_gaji')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Draft:</strong> Gaji sedang disiapkan<br>
                            <strong>Approved:</strong> Gaji sudah disetujui<br>
                            <strong>Paid:</strong> Gaji sudah dibayarkan
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $salary->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Catatan tambahan untuk gaji ini (opsional)</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('salaries.show', $salary) }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin memperbarui data gaji ini?')">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info me-2"></i>
                    Informasi Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Guru:</strong>
                    <p class="mb-0">{{ $salary->teacher->user->name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Periode:</strong>
                    <p class="mb-0">{{ $salary->bulan }} {{ $salary->tahun }}</p>
                </div>
                <div class="mb-3">
                    <strong>Status Saat Ini:</strong>
                    @switch($salary->status_gaji)
                        @case('draft')
                            <span class="badge bg-secondary">Draft</span>
                            @break
                        @case('approve')
                            <span class="badge bg-warning">Approve</span>
                            @break
                        @case('paid')
                            <span class="badge bg-success">Paid</span>
                            @break
                        @default
                            <span class="badge bg-light">{{ ucfirst($salary->status_gaji) }}</span>
                    @endswitch
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Rincian Gaji (Read Only)
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Gaji Pokok:</span>
                    <span>Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</span>
                </div>
                @php
                    $tunjanganJabatan = $salary->teacher && $salary->teacher->positions ? $salary->teacher->positions->sum('base_allowance') : 0;
                    $totalTunjangan = 0;
                    foreach ($salary->teacher->teacherAllowances()->active()->get() as $allowance) {
                        if ($allowance->calculation_type === 'per_hari') {
                            $totalTunjangan += $allowance->amount * $salary->hari_hadir;
                        } else {
                            $totalTunjangan += $allowance->amount;
                        }
                    }
                    $bonus = old('bonus', $salary->bonus);
                    $potongan = old('potongan', $salary->potongan);
                    $totalGaji = abs($salary->gaji_pokok)
                        + $tunjanganJabatan
                        + $totalTunjangan
                        + abs($bonus)
                        - abs($potongan);
                @endphp
                @if($tunjanganJabatan > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Tunjangan Jabatan:</span>
                    <span>Rp {{ number_format($tunjanganJabatan, 0, ',', '.') }}</span>
                </div>
                @endif
                @foreach($salary->teacher->teacherAllowances()->active()->with('allowanceType')->get() as $allowance)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $allowance->allowanceType->name ?? '-' }}:</span>
                        @if($allowance->calculation_type === 'per_hari')
                            <span>Rp {{ number_format($allowance->amount, 0, ',', '.') }}/Hari</span>
                        @else
                            <span>Rp {{ number_format($allowance->amount, 0, ',', '.') }}</span>
                        @endif
                    </div>
                @endforeach
                <div class="d-flex justify-content-between mb-2">
                    <span>Hari Kerja:</span>
                    <span>
                        @php
                            $workingDays = $salary->teacher && $salary->teacher->working_days ? $salary->teacher->working_days : [];
                            $monthMapping = [
                                'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,
                                'September'=>9,'October'=>10,'November'=>11,'December'=>12
                            ];
                            $month = is_numeric($salary->bulan) ? (int)$salary->bulan : ($monthMapping[$salary->bulan] ?? 1);
                            $year = $salary->tahun;
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $workDaysCount = 0;
                            if (is_array($workingDays) && count($workingDays) > 0) {
                                for ($d = 1; $d <= $daysInMonth; $d++) {
                                    $date = Carbon\Carbon::create($year, $month, $d);
                                    $dayName = strtolower($date->locale('id')->isoFormat('dddd'));
                                    if (in_array($dayName, $workingDays)) {
                                        $workDaysCount++;
                                    }
                                }
                            }
                        @endphp
                        {{ $workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja }} hari
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Hari Hadir:</span>
                    <span>{{ $salary->hari_hadir }} hari</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2" id="current-bonus">
                    <span>Bonus Saat Ini:</span>
                    <span class="text-success">Rp {{ number_format($bonus, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2" id="current-potongan">
                    <span>Potongan Saat Ini:</span>
                    <span class="text-danger">Rp {{ number_format($potongan, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold" id="current-total">
                    <span>Total Gaji Saat Ini:</span>
                    <span class="text-primary">Rp {{ number_format($totalGaji, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Card Preview Perhitungan dihapus sesuai permintaan -->
    </div>
</div>

@push('scripts')
</script>
@endpush
@endsection
