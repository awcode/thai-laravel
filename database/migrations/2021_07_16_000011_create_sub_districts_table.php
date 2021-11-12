<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDistrictsTable extends Migration
{
    public function up()
    {
        Schema::create('sub_districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subdistrict_name_th');
            $table->string('subdistrict_name_eng')->nullable();
            $table->integer('sort_order')->nullable();
            $table->decimal('lat',11,8)->nullable();
            $table->decimal('lng',11,8)->nullable();
            $table->timestamps();
        });
    }
}
