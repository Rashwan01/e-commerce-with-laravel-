<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('statsAr');
            $table->string('statsEn');
            $table->bigInteger("country_id")->unsigned();
            $table->bigInteger("city_id")->unsigned();
            $table->foreign("country_id")->references("id")->on("countries")->onDElete("cascade")->onUpdate("cascade");
            $table->foreign("city_id")->references("id")->on("cities")->onDElete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('stats');
    }
}
