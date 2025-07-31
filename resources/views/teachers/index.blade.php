@extends('layouts.app')

@section('title', 'Data Pengguna - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
            <h1 class="h3 mb-2 mb-sm-0">Data Guru</h1>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Guru
            </a>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($teachers->count() > 0)
                <!-- Mobile Card View -->
                <div class="d-md-none">
                    @foreach($teachers as $teacher)
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h6 class="card-title mb-1">{{ $teacher->user->name }}</h6>
                                    <small class="text-muted">NIP: {{ $teacher->nip }}</small>
                                </div>
                                <div class="col-4 text-end">
                                    @if($teacher->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="row g-2 text-sm">
                                    <div class="col-6">
                                        <strong>Peran:</strong><br>
                                        <span class="badge bg-info">{{ $teacher->peran }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>No. Telepon:</strong><br>
                                        <a href="tel:{{ $teacher->no_telepon }}" class="text-decoration-none">
                                            {{ $teacher->no_telepon }}
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <strong>Jenjang Pendidikan:</strong><br>
                                        <span class="badge bg-warning text-dark">{{ $teacher->educationLevel->name ?? '-' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Gaji:</strong><br>
                                        <span class="text-success fw-bold">
                                            Rp {{ number_format($teacher->nominal, 0, ',', '.') }}
                                            @if($teacher->salary_type)
                                            <small
                                                class="text-muted">({{ str_replace('_', ' ', $teacher->salary_type) }})</small>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-1">
                                <a href="{{ route('teachers.show', $teacher) }}"
                                    class="btn btn-outline-info btn-sm flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('teachers.edit', $teacher) }}"
                                    class="btn btn-outline-warning btn-sm flex-grow-1">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST"
                                    class="d-inline flex-grow-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                        onclick="return confirm('Yakin ingin menonaktifkan guru ini?')">
                                        <i class="fas fa-ban me-1"></i>Nonaktif
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
                                    <th>NIP</th>
                                    <th>Nama Lengkap</th>
                                    <th>Peran</th>
                                    <th>No. Telepon</th>
                                    <th>Jenjang Pendidikan</th>
                                    <th>Gaji</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->nip }}</td>
                                    <td>{{ $teacher->user->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $teacher->peran }}</span>
                                    </td>
                                    <td>{{ $teacher->no_telepon }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $teacher->educationLevel->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        Rp {{ number_format($teacher->nominal, 0, ',', '.') }}
                                        @if($teacher->salary_type)
                                        <br><small
                                            class="text-muted">({{ str_replace('_', ' ', $teacher->salary_type) }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($teacher->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teachers.show', $teacher) }}"
                                                class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('teachers.edit', $teacher) }}"
                                                class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $teachers->links() }}
                @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data guru.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
