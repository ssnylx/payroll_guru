@extends('layouts.app')

@section('title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Detail Gaji</h1>
            <div>
                @if(auth()->user()->role !== 'guru')
                <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @endif
                <a href="{{ route('salaries.slip', $salary) }}" class="btn btn-primary me-2">
                    <i class="fas fa-file-invoice me-2"></i>Lihat Slip Gaji
                </a>
                <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Informasi Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Nama Guru:</strong>
                            <p class="mb-0">{{ $salary->teacher->user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>NIP:</strong>
                            <p class="mb-0">{{ $salary->teacher->nip }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Mata Pelajaran:</strong>
                            <p class="mb-0">
                                @if($salary->teacher->subjects && $salary->teacher->subjects->count())
                                    @foreach($salary->teacher->subjects as $subject)
                                        {{ $subject->name }}@if(!$loop->last), @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Periode:</strong>
                            <p class="mb-0">{{ $salary->bulan }} {{ $salary->tahun }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            @if($salary->status_gaji)
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
                            @else
                                -
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Tanggal Dibuat:</strong>
                            <p class="mb-0">
                                @if($salary->created_at)
                                    {{ $salary->created_at->format('d F Y H:i') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Rincian Kehadiran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @php
                        $workingDays = $salary->teacher && $salary->teacher->working_days ?
                        $salary->teacher->working_days : [];
                        $monthMapping = [
                        'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,
                        'September'=>9,'October'=>10,'November'=>11,'December'=>12
                        ];
                        $month = is_numeric($salary->bulan) ? (int)$salary->bulan : ($monthMapping[$salary->bulan] ??
                        1);
                        $year = $salary->tahun;
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $workDaysCount = 0;
                        if (is_array($workingDays) && count($workingDays) > 0) {
                        for ($d = 1; $d <= $daysInMonth; $d++) { $date=Carbon\Carbon::create($year, $month, $d);
                            $dayName=strtolower($date->locale('id')->isoFormat('dddd'));
                            if (in_array($dayName, $workingDays)) {
                            $workDaysCount++;
                            }
                            }
                            }
                            @endphp
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-calendar fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja }}</h5>
                                <small class="text-muted">Hari Kerja</small>
                            </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h5 class="mb-1">{{ $salary->hari_hadir }}</h5>
                            <small class="text-muted">Hari Hadir</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h5 class="mb-1">
                                {{ ($workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja) - $salary->hari_hadir }}
                            </h5>
                            <small class="text-muted">Hari Tidak Hadir</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Rincian Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Gaji Pokok:</span>
                    <span>Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</span>
                </div>
                @php
                $totalTunjangan = 0;
                foreach ($salary->teacher->teacherAllowances()->active()->get() as $allowance) {
                if ($allowance->calculation_type === 'per_hari') {
                $totalTunjangan += $allowance->amount * $salary->hari_hadir;
                } else {
                $totalTunjangan += $allowance->amount;
                }
                }
                $tunjanganJabatan = $salary->teacher && $salary->teacher->positions ?
                $salary->teacher->positions->sum('base_allowance') : 0;
                $totalGaji = abs($salary->gaji_pokok)
                + $tunjanganJabatan
                + $totalTunjangan
                + abs($salary->bonus)
                - abs($salary->potongan);
                @endphp
                <div class="d-flex justify-content-between mb-2">
                    <span>Tunjangan Jabatan:</span>
                    <span>Rp {{ number_format($tunjanganJabatan, 0, ',', '.') }}</span>
                </div>
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
                @if($salary->bonus > 0)
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Bonus:</span>
                    <span>+ Rp {{ number_format($salary->bonus, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($salary->potongan > 0)
                <div class="d-flex justify-content-between mb-2 text-danger">
                    <span>Potongan:</span>
                    <span>- Rp {{ number_format($salary->potongan, 0, ',', '.') }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total Gaji:</span>
                    <span class="text-primary">
                        Rp {{ number_format($totalGaji, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        @if($salary->keterangan)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sticky-note me-2"></i>
                    Keterangan
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $salary->keterangan }}</p>
            </div>
        </div>
        @endif

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-download me-2"></i>
                    Aksi
                </h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary w-100 mb-2" onclick="printSlipGaji()">
                    <i class="fas fa-print me-2"></i>Cetak Slip Gaji
                </button>
                @if(auth()->user()->role !== 'guru')
                <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-warning w-100">
                    <i class="fas fa-edit me-2"></i>Edit Gaji
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- SLIP GAJI PRINT ONLY -->
<div style="display:none">
    <div id="slip-gaji-print"
        style="max-width:700px;margin:0 auto 0 auto;font-family:'Segoe UI',Arial,sans-serif;line-height:1.2; color:#111;">
        <div
            style="border:2px solid #222;border-radius:16px;padding:32px 40px 32px 40px;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.08); color:#111; display:flex; flex-direction:column; justify-content:space-between;">
            <div>
                <div style="text-align:center; margin-bottom:18px;">
                    <div style="font-size:2em;font-weight:700;margin:0 0 8px 0;letter-spacing:1px;">SLIP GAJI GURU</div>
                    <div style="font-size:1.1em;">Periode: <span style="font-weight:700;">{{ $salary->bulan }}
                            {{ $salary->tahun }}</span></div>
                </div>
                <div style="margin: 32px 0 18px 0;">
                    <div style="display:flex; justify-content:space-between;">
                        <div style="width:48%;">
                            <div style="display:flex; margin-bottom:6px;">
                                <div style="width:110px;">Nama Guru</div>
                                <div style="width:10px;">:</div>
                                <div style="font-weight:700; white-space:nowrap;">{{ $salary->teacher->user->name }}
                                </div>
                            </div>
                            <div style="display:flex; margin-bottom:6px;">
                                <div style="width:110px;">Hari Kerja</div>
                                <div style="width:10px;">:</div>
                                <div>{{ $workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja }} hari</div>
                            </div>
                            <div style="display:flex; margin-bottom:6px;">
                                <div style="width:110px;">Hari Tidak Hadir</div>
                                <div style="width:10px;">:</div>
                                <div>
                                    {{ ($workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja) - $salary->hari_hadir }}
                                    hari</div>
                            </div>
                        </div>
                        <div style="width:48%; margin-left:60px;">
                            <div style="display:flex; margin-bottom:6px;">
                                <div style="width:90px;">NIP</div>
                                <div style="width:10px;">:</div>
                                <div>{{ $salary->teacher->nip }}</div>
                            </div>
                            <div style="display:flex; margin-bottom:6px;">
                                <div style="width:90px;">Jam Kerja</div>
                                <div style="width:10px;">:</div>
                                <div>
                                    @php
                                    $monthMapping = [
                                    'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,
                                    'September'=>9,'October'=>10,'November'=>11,'December'=>12
                                    ];
                                    $month = is_numeric($salary->bulan) ? (int)$salary->bulan :
                                    ($monthMapping[$salary->bulan] ?? 1);
                                    $start = \Carbon\Carbon::create($salary->tahun, $month, 1)->startOfMonth();
                                    $end = (clone $start)->endOfMonth();
                                    $jamKerja = 0;
                                    $attendances = \App\Models\Attendance::where('teacher_id', $salary->teacher->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->where('status', 'hadir')
                                    ->get();
                                    foreach ($attendances as $absen) {
                                    $jamKerja += $absen->work_hours;
                                    }
                                    $jamKerja = round($jamKerja, 2);
                                    @endphp
                                    @if($salary->teacher->salary_type !== 'per_bulan')
                                    {{ $jamKerja }} Jam
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex; margin-bottom:6px;">
                                <div style="width:90px;">Hari Hadir</div>
                                <div style="width:10px;">:</div>
                                <div>{{ $salary->hari_hadir }} hari</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tambahkan rincian gaji di bawah identitas dan kehadiran -->
            <div
                style="font-weight:700; text-align:center; margin:8px 0 0 0; border-bottom:2.5px solid #222; padding-bottom:8px; letter-spacing:1px;">
                RINCIAN GAJI
            </div>
            <div style="font-size:1.1em; color:#111; margin-top:16px;">
                <div style="margin-bottom:6px; text-align:left;">
                    <span style="display:inline-block; min-width:380px;">Gaji Pokok</span>
                    <span style="display:inline-block; min-width:140px; text-align:right;">Rp
                        {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</span>
                </div>
                @php
                $tunjanganJabatan = $salary->teacher && $salary->teacher->positions ?
                $salary->teacher->positions->sum('base_allowance') : 0;
                @endphp
                @if($tunjanganJabatan > 0)
                <div style="margin-bottom:6px; text-align:left;">
                    <span style="display:inline-block; min-width:380px;">Tunjangan Jabatan</span>
                    <span style="display:inline-block; min-width:140px; text-align:right;">Rp
                        {{ number_format($tunjanganJabatan, 0, ',', '.') }}</span>
                </div>
                @endif
                @foreach($salary->teacher->teacherAllowances()->active()->with('allowanceType')->get() as $allowance)
                <div style="margin-bottom:6px; text-align:left;">
                    <span
                        style="display:inline-block; min-width:380px;">{{ $allowance->allowanceType->name ?? '-' }}</span>
                    @if($allowance->calculation_type === 'per_hari')
                    <span style="display:inline-block; min-width:140px; text-align:right;">Rp
                        {{ number_format($allowance->amount, 0, ',', '.') }}/Hari</span>
                    @else
                    <span style="display:inline-block; min-width:140px; text-align:right;">Rp
                        {{ number_format($allowance->amount, 0, ',', '.') }}</span>
                    @endif
                </div>
                @endforeach
                <div style="margin-top:2px; font-weight:700; font-size:1em; text-align:left;">
                    <span style="display:inline-block; min-width:380px;">Total Gaji:</span>
                    <span style="display:inline-block; min-width:140px; text-align:right;">Rp
                        {{ number_format($totalGaji, 0, ',', '.') }}</span>
                </div>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:40px;">
                <div style="font-size:0.98em; color:#222;">Dicetak pada: {{ now()->format('d F Y') }}</div>
                <div style="text-align:center; min-width:180px; margin-top:0px;"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function printSlipGaji() {
    // Hide everything except #slip-gaji-print
    var slip = document.getElementById('slip-gaji-print');
    var original = document.body.innerHTML;
    document.body.innerHTML = slip.outerHTML;
    window.print();
    document.body.innerHTML = original;
    window.location.reload(); // reload to restore events/styles
}
</script>
@endpush
@push('styles')
<style>
.page-title,
.h3,
h1,
h2,
h3,
h4,

h5,
h6 {
    color: #fff !important;
}
</style>
@endpush
@endsection
