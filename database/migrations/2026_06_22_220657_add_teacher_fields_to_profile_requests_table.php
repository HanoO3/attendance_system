<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherFieldsToProfileRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('profile_requests', function (Blueprint $table) {
            $table->string('type')->default('student')->after('user_id'); // student ya teacher
            $table->string('teacher_name')->nullable()->after('type');
            $table->string('contact')->nullable()->after('teacher_name');
        });
    }

    public function down()
    {
        Schema::table('profile_requests', function (Blueprint $table) {
            $table->dropColumn(['type', 'teacher_name', 'contact']);
        });
    }
}