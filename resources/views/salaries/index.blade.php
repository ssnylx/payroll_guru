@extends('layouts.app')

@section('title', 'Data Gaji - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
            <h1 class="h3 mb-2 mb-sm-0">Data Gaji</h1>
            @if(auth()->user()->role == 'bendahara')
                <div class="btn-group d-none d-sm-block">
                    <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Generate Gaji
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Stats -->
@if(auth()->user()->role !== 'guru')
<div class="row mb-4">
    <div class="col-6 col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ count($salaries) }}</div>
                <div class="text-muted small">Total Gaji</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ $salaries->filter(function($salary){ return $salary->bulan == now()->format('F') && $salary->tahun == now()->year; })->count() }}</div>
                <div class="text-muted small">Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ \App\Models\Teacher::where('is_active', true)->count() }}</div>
                <div class="text-muted small">Guru Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ \App\Models\AllowanceType::where('is_active', true)->count() }}</div>
                <div class="text-muted small">Tunjangan</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('salaries.index') }}">
                    <div class="row g-3">
                        @if(auth()->user()->role !== 'guru')
                        <div class="col-12 col-md-4">
                            <label for="teacher_id" class="form-label">Guru</label>
                            <select class="form-select" id="teacher_id" name="teacher_id">
                                <option value="">Semua Guru</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-6 col-md-4">
                            <label for="year" class="form-label">Tahun</label>
                            <select class="form-select" id="year" name="year">
                                <option value="">Semua Tahun</option>
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-search me-1"></i>
                                    <span class="d-none d-sm-inline">Filter</span>
                                </button>
                                <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($salaries->count() > 0)
                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        @foreach($salaries as $salary)
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        @if(auth()->user()->role !== 'guru')
                                            <h6 class="card-title mb-1">{{ $salary->teacher->user->name }}</h6>
                                        @else
                                            <h6 class="card-title mb-1">Gaji</h6>
                                        @endif
                                        <small class="text-muted">{{ $salary->bulan }} {{ $salary->tahun }}</small>
                                        <br>
                                        <span class="badge bg-{{ ['draft'=>'secondary','approve'=>'warning','paid'=>'success'][$salary->status_gaji ?? 'draft'] }} text-uppercase">{{ strtoupper($salary->status_gaji ?? 'draft') }}</span>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge bg-success">
                                            Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="row g-2 text-sm">
                                        <div class="col-6">
                                            <strong>Gaji Pokok:</strong><br>
                                            <span class="text-primary">Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Tunjangan:</strong><br>
                                            <span class="text-info">Rp {{ number_format($salary->total_tunjangan, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Hari Kerja:</strong><br>
                                            <span class="text-success">{{ $salary->hari_kerja }} hari</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Hari Hadir:</strong><br>
                                            <span class="text-warning">{{ $salary->hari_hadir }} hari</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Jam Kerja:</strong><br>
                                            <span class="text-info">{{ $salary->jam_kerja }} Jam</span>
                                        </div>
                                        @if($salary->potongan > 0)
                                        <div class="col-12">
                                            <strong>Potongan:</strong><br>
                                            <span class="text-danger">Rp {{ number_format($salary->potongan, 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 d-flex gap-1">
                                    <a href="{{ route('salaries.show', $salary) }}" class="btn btn-outline-info btn-sm flex-grow-1">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-outline-warning btn-sm flex-grow-1">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="d-inline flex-grow-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                                    onclick="return confirm('Yakin ingin menghapus data gaji ini?')">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        @if(auth()->user()->role !== 'guru')
                                            <th>Nama Guru</th>
                                        @endif
                                        <th>Bulan/Tahun</th>
                                        <th>Gaji Pokok</th>
                                        <th>Tunjangan</th>
                                        <th>Hari Kerja</th>
                                        <th>Hari Hadir</th>
                                        <th>Jam Kerja</th>
                                        <th>Status</th>
                                        <th>Potongan</th>
                                        <th>Total Gaji</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaries as $salary)
                                    <tr>
                                        @if(auth()->user()->role !== 'guru')
                                            <td>{{ $salary->teacher->user->name }}</td>
                                        @endif
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
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('salaries.show', $salary) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger"
                                                                onclick="return confirm('Yakin ingin menghapus data gaji ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- {{ $salaries->withQueryString()->links() }} --}}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data gaji.</p>
                        @if(auth()->user()->role == 'bendahara')
                            <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Generate Gaji Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
