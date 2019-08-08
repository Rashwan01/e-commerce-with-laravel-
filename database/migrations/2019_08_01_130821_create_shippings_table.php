<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->string("name_ar")->nullable();
         $table->string("name_en")->nullable();
         $table->bigInteger("user_id")->unsigned();
         $table->foreign("user_id")->references("id")->on("users")->onDElete("cascade")->onUpdate("cascade");
         $table->string("lat")->nullable();
         $table->string("lng")->nullable();
         $table->string("icon")->nullable();
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
        Schema::dropIfExists('shippings');
    }
}
