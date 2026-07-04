<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id',
    'name',
    'contact',
    'department_id',
    'semester',
    'session'
];

public function department()
{
    return $this->belongsTo(Department::class);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}