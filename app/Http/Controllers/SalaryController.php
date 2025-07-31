<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Teacher;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $teacher = $user->teacher;
            $salaries = Salary::where('teacher_id', $teacher->id)
                            ->when($request->year, function($query, $year) {
                                return $query->where('tahun', $year);
                            })
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->get();
        } else {
            $salaries = Salary::with('teacher')
                            ->when($request->teacher_id, function($query, $teacherId) {
                                return $query->where('teacher_id', $teacherId);
                            })
                            ->when($request->year, function($query, $year) {
                                return $query->where('tahun', $year);
                            })
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->get();
        }

        // Hitung ulang jam kerja untuk setiap salary
        foreach ($salaries as $salary) {
            $teacher = $salary->teacher;
            if (!$teacher) continue;
            $monthMapping = [
                'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,
                'September'=>9,'October'=>10,'November'=>11,'December'=>12
            ];
            $month = is_numeric($salary->bulan) ? (int)$salary->bulan : ($monthMapping[$salary->bulan] ?? 1);
            $start = \Carbon\Carbon::create($salary->tahun, $month, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $jamKerja = 0;
            $attendances = \App\Models\Attendance::where('teacher_id', $teacher->id)
                ->whereBetween('tanggal', [$start, $end])
                ->where('status', 'hadir')
                ->get();
            foreach ($attendances as $absen) {
                $jamKerja += $absen->work_hours;
            }
            $salary->jam_kerja = round($jamKerja, 2);
        }

        $teachers = Teacher::where('is_active', true)->get();

        return view('salaries.index', compact('salaries', 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('salaries.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2020|max:2030',
            'bonus' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $teacher = Teacher::findOrFail($validated['teacher_id']);

        // Parse bulan dari string ke number
        $monthMapping = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $monthNumber = is_numeric($validated['bulan'])
            ? (int) $validated['bulan']
            : ($monthMapping[$validated['bulan']] ?? 1);

        $startDate = Carbon::create($validated['tahun'], $monthNumber, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $hariKerja = $this->calculateWorkingDays($startDate, $endDate);
        $hariHadir = Attendance::where('teacher_id', $teacher->id)
                             ->whereBetween('tanggal', [$startDate, $endDate])
                             ->where('status', 'hadir')
                             ->count();
        $hariTidakHadir = $hariKerja - $hariHadir;

        // Hitung total jam kerja dari absensi
        $jamKerja = 0;
        $attendances = Attendance::where('teacher_id', $teacher->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status', 'hadir')
            ->get();
        foreach ($attendances as $absen) {
            $jamKerja += $absen->work_hours;
        }
        $jamKerja = round($jamKerja, 2);

        // Hitung total tunjangan (per_hari dikali hari hadir, lainnya dijumlahkan biasa)
        $allAllowances = $teacher->teacherAllowances()->where('is_active', true)->get();
        $totalTunjangan = 0;
        foreach ($allAllowances as $allowance) {
            if ($allowance->calculation_type === 'per_hari') {
                $totalTunjangan += $allowance->amount * $hariHadir;
            } else {
                $totalTunjangan += $allowance->amount;
            }
        }

        // Gaji pokok dan total gaji sesuai tipe penggajian
        $nominalGaji = $teacher->nominal ?? 0;
        $salaryType = $teacher->salary_type ?? 'per_bulan';
        if ($salaryType === 'per_bulan') {
            $gajiPokok = $nominalGaji;
        } elseif ($salaryType === 'per_jam') {
            $gajiPokok = $nominalGaji * $jamKerja;
        } elseif ($salaryType === 'per_hari') {
            $gajiPokok = $nominalGaji * $hariHadir;
        } else {
            $gajiPokok = $nominalGaji;
        }
        $totalGaji = $gajiPokok + $totalTunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

        Salary::create([
            'teacher_id' => $validated['teacher_id'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'gaji_pokok' => $gajiPokok,
            'tunjangan' => $totalTunjangan,
            'tunjangan_transport' => 0,
            'tunjangan_jabatan' => 0,
            'tunjangan_kerajinan' => 0,
            'jam_kerja' => $jamKerja,
            'bonus' => $validated['bonus'] ?? 0,
            'potongan' => $validated['potongan'] ?? 0,
            'hari_kerja' => $hariKerja,
            'hari_hadir' => $hariHadir,
            'hari_tidak_hadir' => $hariTidakHadir,
            'total_gaji' => $totalGaji,
            'status_gaji' => 'draft',
        ]);

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        $user = Auth::user();

        // Guru hanya bisa melihat gaji dirinya sendiri
        if ($user->role === 'guru' && $salary->teacher->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('salaries.show', compact('salary'));
    }

    /**
     * Show the salary slip for printing.
     */
    public function slip(Salary $salary)
    {
        $user = Auth::user();
        // Guru hanya bisa melihat gaji dirinya sendiri
        if ($user->role === 'guru' && $salary->teacher->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        return view('salaries.slip', compact('salary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('salaries.edit', compact('salary', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'bonus' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'status_gaji' => 'required|in:draft,approve,paid',
            'keterangan' => 'nullable|string',
        ]);

        // Recalculate total gaji based on the teacher's salary system
        $teacher = $salary->teacher;
        $salaryType = $teacher->salary_type ?? 'per_bulan';

        // The gaji_pokok in the salary record should already be calculated correctly
        // based on the salary type when it was created, so we just use it as is
        $totalGaji = $salary->gaji_pokok + $salary->tunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

        $salary->update([
            'bonus' => $validated['bonus'] ?? 0,
            'potongan' => $validated['potongan'] ?? 0,
            'total_gaji' => $totalGaji,
            'status_gaji' => $validated['status_gaji'], // kembali ke status
            'keterangan' => $validated['keterangan'],
        ]);

        // Tambahkan baris ini
        $salary->refresh(); // <= pastikan model mengambil data terbaru dari database

        return redirect()->route('salaries.show', $salary)->with('success', 'Data gaji berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil dihapus.');
    }

    /**
     * Show form for bulk salary generation
     */
    public function bulkCreate()
    {
        $user = Auth::user();

        if ($user && $user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        return view('salaries.bulk-create');
    }

    /**
     * Generate salaries for all active teachers
     */
    public function bulkStore(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        try {
            $validated = $request->validate([
                'bulan' => 'required|string',
                'tahun' => 'required|integer|min:2020|max:2030',
                'bonus' => 'nullable|numeric|min:0',
                'potongan' => 'nullable|numeric|min:0',
                'keterangan' => 'nullable|string',
            ]);

            $monthMapping = [
                'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
                'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
                'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
            ];

            $monthNumber = is_numeric($validated['bulan'])
                ? (int) $validated['bulan']
                : ($monthMapping[$validated['bulan']] ?? 1);

            $startDate = Carbon::create($validated['tahun'], $monthNumber, 1);
            $endDate = $startDate->copy()->endOfMonth();

            $teachers = Teacher::where('is_active', true)->get();
            $successCount = 0;
            $skippedCount = 0;

            foreach ($teachers as $teacher) {
                // Check if salary already exists for this month/year
                $existingSalary = Salary::where('teacher_id', $teacher->id)
                                       ->where('bulan', $validated['bulan'])
                                   ->where('tahun', $validated['tahun'])
                                   ->first();

            if ($existingSalary) {
                $skippedCount++;
                continue;
            }

            // Calculate working days and attendance
            $hariKerja = $this->calculateWorkingDays($startDate, $endDate);
            $hariHadir = Attendance::where('teacher_id', $teacher->id)
                                 ->whereBetween('tanggal', [$startDate, $endDate])
                                 ->where('status', 'hadir')
                                 ->count();
            $hariTidakHadir = $hariKerja - $hariHadir;

            // Hitung total jam kerja dari absensi
            $jamKerja = 0;
            $attendances = Attendance::where('teacher_id', $teacher->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'hadir')
                ->get();
            foreach ($attendances as $absen) {
                $jamKerja += $absen->work_hours;
            }
            $jamKerja = round($jamKerja, 2);

            // Hitung total tunjangan (per_hari dikali hari hadir, lainnya dijumlahkan biasa)
            $allAllowances = $teacher->teacherAllowances()->where('is_active', true)->get();
            $totalTunjangan = 0;
            foreach ($allAllowances as $allowance) {
                if ($allowance->calculation_type === 'per_hari') {
                    $totalTunjangan += $allowance->amount * $hariHadir;
                } else {
                    $totalTunjangan += $allowance->amount;
                }
            }

            // Gaji pokok dan total gaji sesuai tipe penggajian
            $nominalGaji = $teacher->nominal ?? 0;
            $salaryType = $teacher->salary_type ?? 'per_bulan';
            if ($salaryType === 'per_bulan') {
                $gajiPokok = $nominalGaji;
            } elseif ($salaryType === 'per_jam') {
                $gajiPokok = $nominalGaji * $jamKerja;
            } elseif ($salaryType === 'per_hari') {
                    $gajiPokok = $nominalGaji * $hariHadir;
            } else {
                    $gajiPokok = $nominalGaji;
            }
            $totalGaji = $gajiPokok + $totalTunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

            // Prepare keterangan with allowance details including calculation types
            $keteranganWithDetails = $validated['keterangan'] ?? '';
            $tunjanganDetails = [];

            if (0 > 0) { // This line was removed as per the edit hint
                $tunjanganDetails[] = ['type' => 'Tunjangan Transport (Per Hari Hadir)', 'amount' => 0, 'calculation' => 'per_hari'];
            }
            if (0 > 0) { // This line was removed as per the edit hint
                $tunjanganDetails[] = ['type' => 'Tunjangan Jabatan', 'amount' => 0, 'calculation' => 'fixed'];
            }
            if (0 > 0) { // This line was removed as per the edit hint
                $tunjanganDetails[] = ['type' => 'Tunjangan Kerajinan', 'amount' => 0, 'calculation' => 'fixed'];
            }

            if (!empty($tunjanganDetails)) {
                $keteranganWithDetails .= (empty($keteranganWithDetails) ? '' : ' | ') . 'Tunjangan: ';
                $allowanceList = [];
                foreach ($tunjanganDetails as $detail) {
                    $calcText = $detail['calculation'] == 'fixed' ? '' : ' (' . str_replace('_', ' ', $detail['calculation']) . ')';
                    $allowanceList[] = $detail['type'] . $calcText . ' (Rp ' . number_format($detail['amount'], 0, ',', '.') . ')';
                }
                $keteranganWithDetails .= implode(', ', $allowanceList);
            }

            // Create salary record
            Salary::create([
                'teacher_id' => $teacher->id,
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'gaji_pokok' => $gajiPokok,
                'tunjangan' => $totalTunjangan,
                'tunjangan_transport' => 0,
                'tunjangan_jabatan' => 0,
                'tunjangan_kerajinan' => 0,
                'jam_kerja' => $jamKerja,
                'bonus' => $validated['bonus'] ?? 0,
                'potongan' => $validated['potongan'] ?? 0,
                'hari_kerja' => $hariKerja,
                'hari_hadir' => $hariHadir,
                'hari_tidak_hadir' => $hariTidakHadir,
                'total_gaji' => $totalGaji,
                'status_gaji' => 'draft',
                'keterangan' => $keteranganWithDetails,
            ]);

            $successCount++;
        }        $message = "Berhasil generate gaji untuk {$successCount} guru.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} guru dilewati karena sudah ada data gaji untuk periode tersebut.";
        }

        return redirect()->route('salaries.index')->with('success', $message);

        } catch (\Exception $e) {
            // Handle any exceptions
            $errorMessage = 'Terjadi kesalahan saat memproses generate gaji: ' . $e->getMessage();

            return redirect()->route('salaries.bulk-create')->with('error', $errorMessage);
        }
    }

    /**
     * Calculate working days between two dates (excluding weekends)
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $workingDays = 0;
        $current = $startDate->copy();

        while ($current <= $endDate) {
            // Skip Sunday (0) and Saturday (6)
            if ($current->dayOfWeek !== 0 && $current->dayOfWeek !== 6) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

        public function cetak(Request $request)
    {
        $user = Auth::user();

        $month = $request->month ?? '';
        $year = $request->year ?? '';

        $salaries = Salary::when($request->year, function($query, $year) {
                    return $query->where('tahun', $year);
                })
                ->when($request->month, function($query, $month) {
                    return $query->where('bulan', $month);
                })
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->get();

        // Hitung ulang jam kerja untuk setiap salary
        foreach ($salaries as $salary) {
            $teacher = $salary->teacher;
            if (!$teacher) continue;
            $monthMapping = [
                'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,
                'September'=>9,'October'=>10,'November'=>11,'December'=>12
            ];
            $month = is_numeric($salary->bulan) ? (int)$salary->bulan : ($monthMapping[$salary->bulan] ?? 1);
            $start = \Carbon\Carbon::create($salary->tahun, $month, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $jamKerja = 0;
            $attendances = \App\Models\Attendance::where('teacher_id', $teacher->id)
                ->whereBetween('tanggal', [$start, $end])
                ->where('status', 'hadir')
                ->get();
            foreach ($attendances as $absen) {
                $jamKerja += $absen->work_hours;
            }
            $salary->jam_kerja = round($jamKerja, 2);
        }

        $pdf = PDF::loadView('salaries.cetak', compact('salaries', 'month', 'year'));
        return $pdf->stream('Data Gaji.pdf');
    }
}




