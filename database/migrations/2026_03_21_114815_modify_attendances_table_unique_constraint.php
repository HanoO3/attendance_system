<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_student_id_attendance_date_unique');

            $table->unique(['student_id', 'attendance_date', 'subject_id'], 'attendance_unique_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendance_unique_record');
            $table->unique(['student_id', 'attendance_date'], 'attendances_student_id_attendance_date_unique');
        });
    }
}; 

