<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_addons', function (Blueprint $table) {
            $table->id();
            $table->integer('deal_id');
            $table->integer('addon_group_id')->nullable();
            $table->integer('addon_item_id');
            $table->string('addon_group_name')->nullable();
            $table->string('addon_item_name')->nullable();
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
        Schema::dropIfExists('deal_addons');
    }
}
