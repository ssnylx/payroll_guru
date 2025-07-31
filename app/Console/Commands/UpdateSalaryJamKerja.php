<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Salary;
use App\Models\Attendance;
use Carbon\Carbon;

class UpdateSalaryJamKerja extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:update-jam-kerja';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ulang kolom jam_kerja pada semua data salary berdasarkan absensi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update jam_kerja pada semua data salary...');
        $salaries = Salary::with('teacher')->get();
        $monthMapping = [
            'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,
            'September'=>9,'October'=>10,'November'=>11,'December'=>12
        ];
        $updated = 0;
        foreach ($salaries as $salary) {
            $teacher = $salary->teacher;
            if (!$teacher) continue;
            $month = is_numeric($salary->bulan) ? (int)$salary->bulan : ($monthMapping[$salary->bulan] ?? 1);
            $start = Carbon::create($salary->tahun, $month, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $jamKerja = 0;
            $attendances = Attendance::where('teacher_id', $teacher->id)
                ->whereBetween('tanggal', [$start, $end])
                ->where('status', 'hadir')
                ->get();
            foreach ($attendances as $absen) {
                if ($absen->jam_masuk && $absen->jam_keluar) {
                    $masuk = Carbon::parse($absen->jam_masuk);
                    $keluar = Carbon::parse($absen->jam_keluar);
                    $jamKerja += $keluar->diffInMinutes($masuk);
                }
            }
            $jamKerja = round($jamKerja / 60, 1);
            $salary->jam_kerja = $jamKerja;
            $salary->save();
            $updated++;
        }
        $this->info("Update selesai. Total salary diupdate: $updated");
    }
}
