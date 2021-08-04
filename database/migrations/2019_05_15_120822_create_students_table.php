<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('profile_picture_url');
            $table->string('date_of_birth');
            $table->string('title');
            $table->string('place_of_birth');
            $table->string('residential_address');
            $table->string('mailing_address');
            $table->string('work_phone');
            $table->string('mobile_phone');
            $table->string('emergency_name');
            $table->string('emergency_mobile_phone');
            $table->string('emergency_relationship');
            $table->string('emergency_email');
            $table->string('sex')->nullable();

            $table->string('ref_name1');
            $table->string('ref_relationship1');
            $table->string('ref_address1');
            $table->string('ref_number1');
            $table->string('ref_email1');

            $table->string('ref_name2');
            $table->string('ref_relationship2');
            $table->string('ref_address2');
            $table->string('ref_number2');
            $table->string('ref_email2');

            $table->json('alumni_course');
            $table->json('alumni_date');

            $table->timestamps();


        $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
