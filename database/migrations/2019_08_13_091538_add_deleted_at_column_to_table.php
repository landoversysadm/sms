<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtColumnToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('enrollments', function (Blueprint $table) {
          $table->softDeletes();
        });

        Schema::table('faculties', function (Blueprint $table) {
          $table->softDeletes();
        });

        Schema::table('students', function (Blueprint $table) {
          $table->softDeletes();
        });

        Schema::table('payments', function (Blueprint $table) {
          $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
