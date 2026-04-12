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
    Schema::table('leave_requests', function (Blueprint $table) {
        $table->boolean('student_seen')->default(false); 
    });

    if (!Schema::hasColumn('notifications', 'is_read')) {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('is_read')->default(false);
        });
    }
}

public function down()
{
    Schema::table('leave_requests', function (Blueprint $table) {
        $table->dropColumn('student_seen');
    });
    
    Schema::table('notifications', function (Blueprint $table) {
        $table->dropColumn('is_read');
    });
}
};
