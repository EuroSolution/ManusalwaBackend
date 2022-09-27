<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->integer('user_id');
            $table->decimal('subtotal')->default(0);
            $table->decimal('tax')->default(0.00);
            $table->decimal('delivery_fee')->default(0.00);
            $table->decimal('discount')->default(0.00);
            $table->decimal('total_amount')->default(0.00);
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('nearest_landmark')->nullable();
            $table->text('location')->nullable();
            $table->string('order_type')->nullable();
            $table->text('notes')->nullable();
            $table->string('order_status');
            $table->bigInteger('coupon_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
