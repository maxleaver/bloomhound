<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrangementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrangements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned()->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('arrangement_event', function (Blueprint $table) {
            $table->integer('arrangement_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->timestamps();

            $table->primary(['arrangement_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arrangements');
        Schema::dropIfExists('arrangement_event');
    }
}
