<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

  
    public static function generateCourseCode($deptName)
    {
        if (!$deptName) return '';

        $name = preg_replace('/Department|of|BS|MS/i', '', $deptName);
        $name = trim($name);
        
        $parts = preg_split('/\s+/', $name);
        $prefix = "";

        if (count($parts) == 1) {
            $prefix = substr($parts[0], 0, 3);
        } else {
            foreach ($parts as $p) {
                $prefix .= substr($p, 0, 1);
            }
        }
        $prefix = strtoupper($prefix);

        $hash = 0;
        for ($i = 0; $i < strlen($deptName); $i++) {
            $hash = (($hash << 5) - $hash) + ord($deptName[$i]);
            
            $hash = $hash & 0xFFFFFFFF;
            if ($hash > 0x7FFFFFFF) {
                $hash -= 0x100000000;
            }
        }

        $suffix = abs($hash) % 900 + 100;

        return $prefix . "-" . $suffix;
    }
}