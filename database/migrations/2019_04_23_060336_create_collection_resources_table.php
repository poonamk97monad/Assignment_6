<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('collection_id')->unsigned();
            $table->foreign('collection_id')->references('id')->on('collections');
            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_resources');
    }
}
