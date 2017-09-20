<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowerVarietiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flower_varieties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('flower_id')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->index('flower_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flower_varieties');
    }
}
