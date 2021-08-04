<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('price');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('schedule');
            $table->string('banner')->nullable();
            $table->unsignedBigInteger('faculty_id');
            $table->json('requirement');
            $table->timestamps();


        $table->foreign('faculty_id')->references('id')
                ->on('faculties')
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
        Schema::dropIfExists('courses');
    }
}
