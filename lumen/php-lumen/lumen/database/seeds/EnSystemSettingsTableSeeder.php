<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class EnSystemSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('en_system_settings')) { 
            DB::table('en_system_settings')->insert([
                ['configuration' => '{"en_sysconfig_api_url":"http://en-sysconfig-lumen-nginx-server", "en_sysconfig_config":"api", "en_sysconfig_config_path":"/var/www/ensystemconfig"}', 'status' => 'y', 'type' => 'ensysconfig']
            ]);  
        }
    }
}
