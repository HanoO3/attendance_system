<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'leave_date',
        'reason',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}