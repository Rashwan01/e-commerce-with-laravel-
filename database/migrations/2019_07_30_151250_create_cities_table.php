<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
           $table->bigIncrements('id');
           $table->string('cityAr');
           $table->string('cityEn');
           $table->bigInteger("country_id")->unsigned();
           $table->foreign("country_id")->references("id")->on("countries")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('cities');
    }
}