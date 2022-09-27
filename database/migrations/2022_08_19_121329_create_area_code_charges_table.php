<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaCodeChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_code_charges', function (Blueprint $table) {
            $table->id();
            $table->string('area_code')->nullable();
            $table->string('address')->nullable();
            $table->decimal('delivery_charge')->default(0);
            $table->decimal('min_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area_code_charges');
    }
}
