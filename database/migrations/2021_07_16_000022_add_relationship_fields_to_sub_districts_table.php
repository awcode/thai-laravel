<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSubDistrictsTable extends Migration
{
    public function up()
    {
        Schema::table('sub_districts', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id', 'district_fk_3828566')->references('id')->on('districts');
        });
    }
}
