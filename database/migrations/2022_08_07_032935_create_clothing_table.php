<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clothing', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('brand_type');
            $table->smallInteger('value_min')->unsigned()->nullable();
            $table->smallInteger('value_max')->unsigned()->nullable();
            $table->smallInteger('sale_price')->unsigned();
            $table->tinyText('details');
            $table->tinyText('reference');
            $table->string('image_title')->nullable();
            $table->string('image_path');            
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
        Schema::dropIfExists('clothing');
    }
};
