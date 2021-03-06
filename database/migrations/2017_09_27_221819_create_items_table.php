<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned()->index();
            $table->integer('arrangeable_type_id')->unsigned()->index();
            $table->integer('markup_id')->unsigned();
            $table->decimal('markup_value', 15, 2)->unsigned()->nullable();
            $table->boolean('use_default_markup')->default(true);
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('inventory')->unsigned()->nullable();
            $table->decimal('cost', 15, 2)->unsigned()->nullable();
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
        Schema::dropIfExists('items');
    }
}
