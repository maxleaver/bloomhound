<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowerVarietySourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flower_variety_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned()->index();
            $table->integer('flower_variety_id')->unsigned()->index();
            $table->integer('vendor_id')->unsigned()->index();
            $table->decimal('cost', 15, 2)->unsigned();
            $table->integer('stems_per_bunch')->unsigned();
            $table->double('cost_per_stem')->unsigned();
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
        Schema::dropIfExists('flower_variety_sources');
    }
}
