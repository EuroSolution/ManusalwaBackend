<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'title' => 'Manusalwa',
            'email' => 'admin@manusalwa.com',
            'phone' => '(123) 456-7891',
            'address' => 'Lorem Street, Abc road',
            'currency' => 'Euro',
        ]);
    }
}
