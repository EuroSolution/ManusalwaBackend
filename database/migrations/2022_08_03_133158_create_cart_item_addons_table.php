<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_item_addons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cart_item_id');
            $table->bigInteger('addon_item_id');
            $table->string('addon_group')->nullable();
            $table->string('addon_item')->nullable();
            $table->decimal('price')->default(0);
            $table->integer('quantity')->default(1);
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
        Schema::dropIfExists('cart_item_addons');
    }
}
