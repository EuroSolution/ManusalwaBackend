<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_items', function (Blueprint $table) {
            $table->id();
            $table->integer('addon_group_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price')->default(0);
            $table->decimal('discounted_price')->default(0);
            $table->text('image')->nullable();
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
        Schema::dropIfExists('addon_items');
    }
}
