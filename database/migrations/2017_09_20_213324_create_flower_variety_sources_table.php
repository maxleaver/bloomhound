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
            $table->decimal('cost', 15, 2)->unsigned()->nullable();
            $table->integer('stems_per_bunch')->unsigned()->nullable();
            $table->timestamps();

            // $table->index('account_id');
            // $table->index('flower_variety_id');
            // $table->index('vendor_id');
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
