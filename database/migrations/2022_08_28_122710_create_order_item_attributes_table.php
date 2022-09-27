<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_item_id');
            $table->bigInteger('attribute_item_id');
            $table->string('attribute_name')->nullable();
            $table->string('attribute_item_name')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_attributes');
    }
}
