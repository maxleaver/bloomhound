<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->string('name');
            $table->string('type');
            $table->decimal('amount', 15, 2)->unsigned();
            $table->unsignedInteger('discountable_id');
            $table->string('discountable_type');
            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')->on('accounts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
