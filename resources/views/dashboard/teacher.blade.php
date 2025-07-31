@extends('layouts.app')

@section('title', 'Dashboard Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="text-dark mb-2 fw-bold">Dashboard Guru YAKIIN</div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm border-0 h-100 bg-info text-white">
            <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <div class="fs-2 fw-bold">{{ $myAttendanceThisMonth }} hari</div>
                <div class="small">Absensi Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card shadow-sm border-0 h-100 bg-success text-white">
            <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                <div class="fs-2 fw-bold">
                    @if($mySalaryThisMonth)
                    Rp {{ number_format($mySalaryThisMonth->total_gaji, 0, ',', '.') }}
                    @else
                    Belum tersedia
                    @endif
                </div>
                <div class="small">Gaji Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Absensi Hari Ini</h5>
                        <p class="mb-0">Lakukan absensi dengan kamera untuk mencatat kehadiran Anda</p>
                    </div>
                    <div>
                        <a href="{{ route('self-attendance.index') }}" class="btn btn-light">
                            <i class="fas fa-camera me-2"></i>
                            Buka Kamera Absensi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- KOLOM KIRI --}}
    <div class="col-md-6">
        {{-- FOTO PROFIL --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Foto Profil</h5>
            </div>
            <div class="card-body text-center">
                @if($teacher->photo_path)
                <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="Foto Profil"
                    class="img-fluid rounded-circle" style="max-width: 125px;">
                @else
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width: 125px; height: 125px;">
                    <i class="fas fa-user fa-4x text-muted"></i>
                </div>
                <p class="text-muted mt-2">Tidak ada foto</p>
                @endif
            </div>
        </div>

        {{-- DATA PRIBADI --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>NIP:</strong></td>
                        <td>{{ $teacher->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap:</strong></td>
                        <td>{{ $teacher->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $teacher->alamat }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $teacher->no_telepon }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td>{{ ucfirst($teacher->jenis_kelamin) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tpt,Tgl Lahir:</strong></td>
                        <td>{{ $teacher->tempat_lahir }}, {{ $teacher->tanggal_lahir->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendidikan Terakhir:</strong></td>
                        <td>{{ $teacher->pendidikan_terakhir }}</td>
                    </tr>
                    <tr>
                        <td><strong>Mata Pelajaran:</strong></td>
                        <td>
                            @if($teacher->subjects && $teacher->subjects->count())
                            @foreach($teacher->subjects as $subject)
                            <span class="badge bg-secondary mb-1">{{ $subject->name }}</span>
                            @endforeach
                            @else
                            <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Masuk:</strong></td>
                        <td>{{ $teacher->tanggal_masuk->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($teacher->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6">
        {{-- DETAIL TUNJANGAN --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Tunjangan</h5>
            </div>
            <div class="card-body">
                @if($activeAllowances->count() > 0)
                <div class="mb-3">
                    @foreach($activeAllowances as $allowance)
                    <div class="d-flex justify-content-between align-items-center p-2 mb-2 border rounded">
                        <div>
                            <span>{{ $allowance->allowanceType->name }}</span>
                        </div>
                        <div class="text-primary fw-bold">
                            Rp {{ number_format($allowance->amount, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total Tunjangan:</strong>
                        <strong class="text-success h5 mb-0">
                            Rp {{ number_format($totalAllowances, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
                @else
                <p class="text-muted">Belum ada tunjangan yang tersedia.</p>
                @endif
            </div>
        </div>

        {{-- ABSENSI TERBARU --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Absensi Terbaru</h5>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $attendance)
                            <tr>
                                <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    @switch($attendance->status)
                                    @case('hadir')
                                    <span class="badge bg-success">Hadir</span>
                                    @break
                                    @case('terlambat')
                                    <span class="badge bg-warning">Terlambat</span>
                                    @break
                                    @case('izin')
                                    <span class="badge bg-info">Izin</span>
                                    @break
                                    @case('sakit')
                                    <span class="badge bg-secondary">Sakit</span>
                                    @break
                                    @case('tidak_hadir')
                                    <span class="badge bg-danger">Tidak Hadir</span>
                                    @break
                                    @endswitch
                                </td>
                                <td>{{ $attendance->jam_masuk ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted">Belum ada data absensi.</p>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-times me-2"></i>
                    Pengajuan Cuti Saya
                    @if($pendingLeaveRequests > 0)
                    <span class="badge bg-warning ms-2">{{ $pendingLeaveRequests }} Pending</span>
                    @endif
                </h5>
                <div>
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-sm btn-primary me-2">
                        <i class="fas fa-plus me-1"></i>Ajukan Cuti
                    </a>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($myLeaveRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Jenis Cuti</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myLeaveRequests as $leaveRequest)
                            <tr>
                                <td>
                                    <span class="badge bg-info">
                                        {{ \App\Models\LeaveRequest::getLeaveTypes()[$leaveRequest->leave_type] }}
                                    </span>
                                </td>
                                <td>
                                    {{ $leaveRequest->start_date->format('d M Y') }}
                                    @if($leaveRequest->start_date->format('Y-m-d') !==
                                    $leaveRequest->end_date->format('Y-m-d'))
                                    <br><small class="text-muted">s/d
                                        {{ $leaveRequest->end_date->format('d M Y') }}</small>
                                    @endif
                                </td>
                                <td>{{ $leaveRequest->total_days }} hari</td>
                                <td>
                                    <span class="badge {{ $leaveRequest->getStatusBadgeClass() }}">
                                        {{ \App\Models\LeaveRequest::getStatuses()[$leaveRequest->status] }}
                                    </span>
                                </td>
                                <td>{{ $leaveRequest->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('leave-requests.show', $leaveRequest) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($leaveRequest->isPending())
                                    <a href="{{ route('leave-requests.edit', $leaveRequest) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pengajuan cuti.</p>
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Ajukan Cuti Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
