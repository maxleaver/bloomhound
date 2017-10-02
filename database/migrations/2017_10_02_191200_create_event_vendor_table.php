<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_vendor', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->index();
            $table->integer('vendor_id')->unsigned();

            $table->primary(['event_id', 'vendor_id']);

            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('vendor_id')
                ->references('id')->on('vendors')
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
        Schema::dropIfExists('event_vendor');
    }
}
