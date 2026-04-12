<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('profile_requests', function (Blueprint $table) {
        $table->string('student_name')->nullable();
        $table->string('roll_no')->nullable();
        $table->unsignedBigInteger('department_id')->nullable();
        $table->string('session')->nullable();
    });
}

public function down()
{
    Schema::table('profile_requests', function (Blueprint $table) {
        $table->dropColumn(['student_name', 'roll_no', 'department_id', 'session']);
    });
}
};
