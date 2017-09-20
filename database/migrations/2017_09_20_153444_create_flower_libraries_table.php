<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowerLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flower_libraries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->unique();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Artisan::call( 'db:seed', [
            '--class' => 'FlowerLibrarySeeder',
            '--force' => true ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flower_libraries');
    }
}
