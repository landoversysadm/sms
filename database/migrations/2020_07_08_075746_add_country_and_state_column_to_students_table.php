<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryAndStateColumnToStudentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('students', function (Blueprint $table) {
      $table->string('residential_country')->nullable();
      $table->string('residential_state')->nullable();
      $table->string('residential_lg')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('student', function (Blueprint $table) {
      //
    });
  }
}
