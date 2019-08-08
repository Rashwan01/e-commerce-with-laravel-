<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("webNameAr");
            $table->string("webNameEn");
            $table->string("website_email");
            $table->string("logo")->nullable(true);
            $table->string("logo1")->nullable(true);
            $table->string("description");
            $table->string("lang");
            $table->string("status");
            $table->string("msg_maintanience");
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
        Schema::dropIfExists('settings');
    }
}
