<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_vendor', function (Blueprint $table) {
            $table->integer('proposal_id')->unsigned()->index();
            $table->integer('vendor_id')->unsigned();

            $table->primary(['proposal_id', 'vendor_id']);

            $table->foreign('proposal_id')
                ->references('id')->on('proposals')
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
        Schema::dropIfExists('proposal_vendor');
    }
}
