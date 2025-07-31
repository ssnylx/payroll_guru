<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Gaji {{ !is_numeric($month ?? '') ? $month : '' }} {{ $year ?? '' }} </title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            color: #fff;
        }

        .bg-secondary { background-color: #6c757d; }
        .bg-warning { background-color: #ffc107; }
        .bg-success { background-color: #28a745; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    <h1> Data Gaji {{ !is_numeric($month ?? '') ? $month : '' }} {{ $year ?? '' }} </h1>

    @if($salaries->count() > 0)

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Bulan/Tahun</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Hari Kerja</th>
                    <th>Hari Hadir</th>
                    <th>Jam Kerja</th>
                    <th>Status</th>
                    <th>Potongan</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaries as $salary)
                <tr>
                    <td>{{ $salary->teacher->user->name }}</td>
                    <td>{{ $salary->bulan }} {{ $salary->tahun }}</td>
                    <td>Rp {{ number_format(abs($salary->gaji_pokok), 0, ',', '.') }}</td>
                    <td>
                        @php
                            $totalTunjangan = 0;
                            foreach ($salary->teacher->teacherAllowances()->active()->get() as $allowance) {
                                if ($allowance->calculation_type === 'per_hari') {
                                    $totalTunjangan += $allowance->amount * $salary->hari_hadir;
                                } else {
                                    $totalTunjangan += $allowance->amount;
                                }
                            }
                        @endphp
                        Rp {{ number_format($totalTunjangan, 0, ',', '.') }}
                    </td>
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
                    <td>{{ $workDaysCount > 0 ? $workDaysCount : $salary->hari_kerja }} hari</td>
                    <td>{{ $salary->hari_hadir }} hari</td>
                    <td>
                        {{ abs($salary->jam_kerja) }} Jam
                    </td>
                    <td>
                        @php
                            $status = $salary->status_gaji ?? 'draft';
                            $badge = [
                                'draft' => 'secondary',
                                'approve' => 'warning',
                                'paid' => 'success',
                            ][$status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }} text-uppercase">{{ strtoupper($status) }}</span>
                    </td>
                    <td>
                        @if($salary->potongan > 0)
                            <span class="text-danger">Rp {{ number_format($salary->potongan, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                    @php
                        $tunjanganJabatan = $salary->teacher && $salary->teacher->positions ? $salary->teacher->positions->sum('base_allowance') : 0;
                        $totalGaji = abs($salary->gaji_pokok)
                            + $tunjanganJabatan
                            + $totalTunjangan
                            + abs($salary->bonus)
                            - abs($salary->potongan);
                    @endphp
                    <td><span class="text-success fw-bold">
                        Rp {{ number_format($totalGaji, 0, ',', '.') }}
                    </span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
    <div class="text-center py-4">
        <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
        <p class="text-muted">Belum ada data gaji.</p>
    </div>
@endif
</body>
</html>