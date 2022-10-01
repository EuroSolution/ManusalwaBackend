<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnInAddonGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addon_groups', function (Blueprint $table) {
            $table->integer('category_id')->nullable()->after('id');
            $table->string('type')->after('category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addon_groups', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'type']);
        });
    }
}
