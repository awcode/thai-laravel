<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('district_name_th');
            $table->string('district_name_eng')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('postcode')->nullable();
            $table->longText('latlng')->nullable();
            $table->timestamps();
        });
    }
}
