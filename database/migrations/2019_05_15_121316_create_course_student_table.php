<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('session_id')->index();
            $table->integer('status');
            $table->timestamps();

        $table->foreign('course_id')->references('id')
            ->on('courses')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        $table->foreign('session_id')->references('id')
            ->on('sessions')
            ->onUpdate('cascade')
            ->onDelete('cascade');


        $table->foreign('student_id')->references('id')
        ->on('students')
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
        Schema::dropIfExists('courses_students');
    }
}
