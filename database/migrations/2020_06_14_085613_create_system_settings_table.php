<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_banner');
            $table->string('app_login_banner')->nullable();
            $table->string('app_title');
            $table->string('app_name');
            $table->string('app_main_logo')->nullable();
            $table->string('app_dasboard_logo')->nullable();
            $table->string('app_footer_text')->nullable();
            $table->string('app_primary_color')->nullable();
            $table->string('app_secondary_color')->nullable();
            $table->boolean('app_menu_autocollapse');
            $table->text('app_social_links')->nullable();
            $table->text('app_contact')->nullable();
            $table->text('others')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}
