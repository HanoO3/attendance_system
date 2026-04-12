<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'department_id',     
        'subject_id',      
        'department_name',  
        'subject_name',     
        'session',           
        'attendance_date', 
        'status', 
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}