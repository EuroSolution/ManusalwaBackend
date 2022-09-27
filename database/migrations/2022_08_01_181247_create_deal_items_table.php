<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_items', function (Blueprint $table) {
            $table->id();
            $table->integer('deal_id');
            $table->integer('product_id');
            $table->integer('category_id');
            $table->string('product_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('size')->nullable();
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
        Schema::dropIfExists('deal_items');
    }
}
