<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrangementIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrangement_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('arrangement_id')->unsigned()->index();
            $table->integer('arrangeable_id')->unsigned();
            $table->string('arrangeable_type');
            $table->integer('quantity')->unsigned();
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
        Schema::dropIfExists('arrangement_ingredients');
    }
}
