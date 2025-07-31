@extends('layouts.app')
@section('title', 'Slip Gaji Guru')
@section('content')
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
            $date = \Carbon\Carbon::create($year, $month, $d);
            $dayName = strtolower($date->locale('id')->isoFormat('dddd'));
            if (in_array($dayName, $workingDays)) {
                $workDaysCount++;
            }
        }
    }
    $totalTunjangan = 0;
    foreach ($salary->teacher->teacherAllowances()->active()->get() as $allowance) {
        if ($allowance->calculation_type === 'per_hari') {
            $totalTunjangan += $allowance->amount * $salary->hari_hadir;
        } else {
            $totalTunjangan += $allowance->amount;
        }
    }
    $tunjanganJabatan = $salary->teacher && $salary->teacher->positions ? $salary->teacher->positions->sum('base_allowance') : 0;
    $totalGaji = abs($salary->gaji_pokok)
        + $tunjanganJabatan
        + $totalTunjangan
        + abs($salary->bonus)
        - abs($salary->potongan);
@endphp
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card mt-4 shadow-lg border-0">
            <div class="card-header text-center bg-white border-0 pb-0">
                <h2 class="mb-1 fw-bold" style="color:#111;">SLIP GAJI GURU</h2>
                <div class="small" style="color:#111;">Periode: <b>{{ $salary->bulan }} {{ $salary->tahun }}</b></div>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <table class="table table-borderless mb-0 w-100" style="font-size:1.1em;">
                            <tr>
                                <td style="width:22%;padding:4px 0;"><strong style="color:#111;">Nama Guru</strong></td>
                                <td style="width:3%">:</td>
                                <td style="width:35%">{{ $salary->teacher->user->name }}</td>
                                <td style="width:20%"><strong style="color:#111;">NIP</strong></td>
                                <td style="width:3%">:</td>
                                <td style="width:17%">{{ $salary->teacher->nip }}</td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0;"><strong style="color:#111;">Hari Kerja</strong></td>
                                <td>:</td>
                                <td>{{ $workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja }} hari</td>
                                <td style="padding:4px 0;"><strong style="color:#111;">Hari Hadir</strong></td>
                                <td>:</td>
                                <td>{{ $salary->hari_hadir }} hari</td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0;"><strong style="color:#111;">Jam Kerja</strong></td>
                                <td>:</td>
                                <td colspan="4">
                                    @php
                                        $jamKerja = 0;
                                        $start = \Carbon\Carbon::create($salary->tahun, $month, 1)->startOfMonth();
                                        $end = (clone $start)->endOfMonth();
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
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0;"><strong style="color:#111;">Hari Tidak Hadir</strong></td>
                                <td>:</td>
                                <td colspan="4">{{ ($workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja) - $salary->hari_hadir }} hari</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mb-4">
                    <table class="table table-bordered align-middle mb-0" style="color:#111;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" colspan="3" style="color:#111;">Rincian Gaji</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="40%" style="color:#111;">Gaji Pokok</th>
                                <td width="40%" style="color:#111;">Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</td>
                                <td width="20%"></td>
                            </tr>
                            @php
                                $tunjanganJabatan = $salary->teacher->positions ? $salary->teacher->positions->sum('base_allowance') : 0;
                            @endphp
                            @if($tunjanganJabatan > 0)
                            <tr>
                                <th style="color:#111;">Tunjangan Jabatan</th>
                                <td style="color:#111;">Rp {{ number_format($tunjanganJabatan, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                            @endif
                            @foreach($salary->teacher->teacherAllowances()->active()->with('allowanceType')->get() as $allowance)
                                <tr>
                                    <th style="color:#111;">{{ $allowance->allowanceType->name ?? '-' }}</th>
                                    @if($allowance->calculation_type === 'per_hari')
                                        <td style="color:#111;">Rp {{ number_format($allowance->amount, 0, ',', '.') }}/Hari</td>
                                    @else
                                        <td style="color:#111;">Rp {{ number_format($allowance->amount, 0, ',', '.') }}</td>
                                    @endif
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="fw-bold" style="color:#111;">Total Gaji</th>
                                <td class="fw-bold" style="color:#111;">
                                    Rp {{ number_format($totalGaji, 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-6 text-start">
                        <div class="small" style="color:#111;">Dicetak pada: {{ now()->format('d F Y') }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="small" style="color:#111;">Tanda tangan:</div>
                        <br><br>
                        <div class="fw-bold">_________________________</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
@media print {
    nav, footer, .btn, .card-header, .card-footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
    body {
        background: #fff !important;
    }
}
.table th, .table td {
    vertical-align: middle !important;
}
</style>
@endpush
@endsection
