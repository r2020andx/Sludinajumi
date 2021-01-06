<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->integer('mileage');
            $table->integer('price');
            $table->string('city');
            $table->string('street');

            $table->integer('owner');       // Kas ir sludinājuma autors
            $table->string('photos')->nullable();       // Kur atrodas bildes
            $table->integer('views');
            $table->integer('rating');
            
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
        Schema::dropIfExists('ads');
    }
}
