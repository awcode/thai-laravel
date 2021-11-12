<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_th')->unique();
            $table->string('name_eng')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('postcode_prefix')->nullable();
            $table->longText('latlng')->nullable();
            $table->timestamps();
        });
    }
}
