<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'alamat',
        'no_telepon',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'peran',
        'tanggal_masuk',
        'main_position_id',
        'nominal',
        'salary_type',
        'is_active',
        'education_level_id',
        'photo_path',
        'working_days',
        'pendidikan_terakhir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'nominal' => 'decimal:2',
        'is_active' => 'boolean',
        'working_days' => 'array',
    ];

    public function mainPosition()
{
    return $this->belongsTo(Position::class, 'main_position_id');
}


    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances for the teacher.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the salaries for the teacher.
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Get the positions that belong to this teacher (many-to-many relationship).
     */
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'teacher_positions')
                    ->withPivot([
                        'is_active',
                        'notes'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get active positions for this teacher.
     */
    public function activePositions()
    {
        return $this->positions()->wherePivot('is_active', true);
    }

    /**
     * Get the education level of the teacher.
     */
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    /**
     * Get the shifts that belong to this teacher.
     */
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'teacher_shifts')
                    ->withPivot([
                        'days',
                        'effective_date',
                        'end_date',
                        'notes',
                        'is_active'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get active shifts for this teacher.
     */
    public function activeShifts()
    {
        return $this->shifts()->wherePivot('is_active', true);
    }

    /**
     * Get current shifts for this teacher.
     */
    public function currentShifts()
    {
        return $this->shifts()
                    ->wherePivot('effective_date', '<=', now())
                    ->where(function ($query) {
                        $query->wherePivotNull('end_date')
                              ->orWherePivot('end_date', '>=', now());
                    });
    }

    /**
     * Get teacher allowances for this teacher.
     */
    public function teacherAllowances()
    {
        return $this->hasMany(TeacherAllowance::class);
    }

    /**
     * Get active teacher allowances for this teacher.
     */
    public function activeAllowances()
    {
        return $this->teacherAllowances()->active();
    }

    /**
     * Get current teacher allowances for this teacher.
     */
    public function currentAllowances()
    {
        return $this->teacherAllowances()->current();
    }

    /**
     * Get allowance types through teacher allowances.
     */
    public function allowanceTypes()
    {
        return $this->hasManyThrough(
            AllowanceType::class,
            TeacherAllowance::class,
            'teacher_id',
            'id',
            'id',
            'allowance_type_id'
        );
    }

    /**
     * Calculate total current allowances.
     */
    public function getTotalCurrentAllowancesAttribute()
    {
        return $this->currentAllowances()->sum('amount');
    }

    /**
     * Get photo URL attribute.
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    /**
     * Scope to get only active teachers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the leave requests for the teacher.
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Mata pelajaran yang diajar (many-to-many).
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }
}
