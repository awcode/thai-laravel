<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProvincesTable extends Migration
{
    public function up()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id', 'region_fk_3837953')->references('id')->on('regions');
        });
    }
}
