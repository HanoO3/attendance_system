<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'student_name', 
        'father_name',   
        'roll_no',     
        'department_id', 
        'course',       
        'semester',      
        'session',
        'contact_number',      
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}