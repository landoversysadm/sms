<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->json('requirement_uploads');
            $table->timestamps();

        $table->foreign('student_id')->references('id')
                ->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        $table->foreign('course_id')->references('id')
                ->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
