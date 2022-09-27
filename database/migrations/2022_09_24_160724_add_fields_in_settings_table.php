<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('tax')->default(0)->after('currency');
            $table->text('android_app_url')->nullable()->after('tax');
            $table->string('android_app_version')->nullable()->after('android_app_url');
            $table->tinyInteger('android_force_update')->default(0)->after('android_app_version');
            $table->text('ios_app_url')->nullable()->after('android_force_update');
            $table->string('ios_app_version')->nullable()->after('ios_app_url');
            $table->tinyInteger('ios_force_update')->default(0)->after('ios_app_version');
            $table->text('qr_code_image')->nullable()->after('ios_force_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'tax',
                'android_force_update',
                'android_app_url',
                'android_app_version',
                'ios_app_url',
                'ios_app_version',
                'ios_force_update',
                'qr_code_image'
            ]);
        });
    }
}
