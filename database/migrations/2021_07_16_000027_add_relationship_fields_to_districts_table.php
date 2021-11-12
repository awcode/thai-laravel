<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDistrictsTable extends Migration
{
    public function up()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id');
            $table->foreign('province_id', 'province_fk_3828506')->references('id')->on('provinces');
        });
    }
}
