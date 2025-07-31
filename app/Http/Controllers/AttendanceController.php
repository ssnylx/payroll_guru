<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $teacher = $user->teacher;
            $attendances = Attendance::where('teacher_id', $teacher->id)
                ->when($request->month, fn($query, $month) => $query->whereMonth('tanggal', $month))
                ->when($request->year, fn($query, $year) => $query->whereYear('tanggal', $year))
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        } else {
            $attendances = Attendance::with('teacher')
                ->when($request->teacher_id, fn($query, $teacherId) => $query->where('teacher_id', $teacherId))
                ->when($request->month, fn($query, $month) => $query->whereMonth('tanggal', $month))
                ->when($request->year, fn($query, $year) => $query->whereYear('tanggal', $year))
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        }

        $teachers = Teacher::where('is_active', true)->get();

        return view('attendances.index', compact('attendances', 'teachers'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('attendances.create', compact('teachers'));
    }

    public function store(Request $request)
{
    $user = Auth::user();

    if ($user->role === 'guru') {
        abort(403, 'Unauthorized access.');
    }

    $validated = $request->validate([
        'teacher_id'     => 'required|exists:teachers,id',
        'tanggal'        => 'required|date',
        'jam_masuk'      => 'nullable|date_format:H:i',
        'jam_keluar'     => 'nullable|date_format:H:i',
        'status'         => 'required|in:hadir,tidak_hadir,terlambat,izin,sakit',
        'keterangan'     => 'nullable|string',
        'photo_masuk'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'photo_keluar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $timestamp = now()->format('Ymd_His'); // untuk buat nama file unik

    if ($request->hasFile('photo_masuk')) {
        $photoMasukName = 'photo_masuk_' . $request->teacher_id . '_' . now()->format('Ymd_His') . '.' . $request->file('photo_masuk')->getClientOriginalExtension();
        $validated['photo_masuk'] = $request->file('photo_masuk')->storeAs('attendance_photos', $photoMasukName, 'public');
    }
    

    if ($request->hasFile('photo_keluar')) {
        $photoKeluarName = 'photo_keluar_' . $request->teacher_id . '_' . now()->format('Ymd_His') . '.' . $request->file('photo_keluar')->getClientOriginalExtension();
        $validated['photo_keluar'] = $request->file('photo_keluar')->storeAs('attendance_photos', $photoKeluarName, 'public');
    }
    

    Attendance::create($validated);

    return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil ditambahkan.');
}

    public function show(Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->role === 'guru' && $attendance->teacher->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('attendances.edit', compact('attendance', 'teachers'));
    }

    public function update(Request $request, Attendance $attendance)
{
    $user = Auth::user();

    if ($user->role === 'guru') {
        abort(403, 'Unauthorized access.');
    }

    $validated = $request->validate([
        'teacher_id'     => 'required|exists:teachers,id',
        'tanggal'        => 'required|date',
        'jam_masuk'      => 'nullable|date_format:H:i',
        'jam_keluar'     => 'nullable|date_format:H:i',
        'status'         => 'required|in:hadir,tidak_hadir,terlambat,izin,sakit',
        'keterangan'     => 'nullable|string',
        'photo_masuk'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'photo_keluar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Ganti foto masuk jika ada file baru
    if ($request->hasFile('photo_masuk')) {
        if ($attendance->photo_masuk) {
            Storage::disk('public')->delete($attendance->photo_masuk);
        }

        $photoMasukName = 'photo_masuk_' . $request->teacher_id . '_' . now()->format('Ymd_His') . '.' . $request->file('photo_masuk')->getClientOriginalExtension();
        $validated['photo_masuk'] = $request->file('photo_masuk')->storeAs('attendance_photos', $photoMasukName, 'public');
    }

    // Ganti foto keluar jika ada file baru
    if ($request->hasFile('photo_keluar')) {
        if ($attendance->photo_keluar) {
            Storage::disk('public')->delete($attendance->photo_keluar);
        }

        $photoKeluarName = 'photo_keluar_' . $request->teacher_id . '_' . now()->format('Ymd_His') . '.' . $request->file('photo_keluar')->getClientOriginalExtension();
        $validated['photo_keluar'] = $request->file('photo_keluar')->storeAs('attendance_photos', $photoKeluarName, 'public');
    }

    $attendance->update($validated);

    return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil diperbarui.');
}


    public function destroy(Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        if ($attendance->photo_masuk) {
            Storage::disk('public')->delete($attendance->photo_masuk);
        }

        if ($attendance->photo_keluar) {
            Storage::disk('public')->delete($attendance->photo_keluar);
        }

        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
