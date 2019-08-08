<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name_ar")->nullable();
            $table->string("name_en")->nullable();
            $table->string("facebook")->nullable();
            $table->string("twitter")->nullable();
            $table->string("website")->nullable();
            $table->string("contact_name")->nullable();
            $table->string("email")->nullable();
            $table->string("mobail")->nullable();
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
        Schema::dropIfExists('manufacts');
    }
}
