<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteColumnToPaymentsEnrollments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('admin_note')->default(NULL)->nullable();
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('admin_note')->default(NULL)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('admin_note');
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('admin_note');
        });
    }
}
