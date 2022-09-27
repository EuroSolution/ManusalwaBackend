<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cart_item_id');
            $table->bigInteger('attribute_item_id');
            $table->string('attribute_name')->nullable();
            $table->string('attribute_item')->nullable();
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
        Schema::dropIfExists('cart_attributes');
    }
}
