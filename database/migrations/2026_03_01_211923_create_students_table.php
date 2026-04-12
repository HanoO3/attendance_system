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
        Schema::create('students', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->string('student_name'); 
        $table->string('roll_number')->unique();
        $table->string('father_name');
        $table->string('contact_number');
        $table->string('semester');
        
        
        $table->foreignId('department_id')->constrained()->onDelete('cascade');        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
