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
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('delivery_id')->nullable()->index();
            $table->unsignedInteger('proposal_id')->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('quantity');
            $table->boolean('override_price')->default(false);
            $table->decimal('price', 15, 2)->unsigned()->nullable();
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
        Schema::dropIfExists('arrangements');
    }
}
