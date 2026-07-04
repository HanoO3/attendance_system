<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'department_id', 'semester'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'teacher_id')
                    ->withPivot('session')
                    ->withTimestamps();
    }

 
    public function scopeMatchSemester($query, $semester)
    {
        return $query->where(function($q) use ($semester) {
            $q->where('semester', $semester)
              ->orWhere('semester', $semester . 'st')
              ->orWhere('semester', $semester . 'nd')
              ->orWhere('semester', $semester . 'rd')
              ->orWhere('semester', $semester . 'th');
        });
    }
}