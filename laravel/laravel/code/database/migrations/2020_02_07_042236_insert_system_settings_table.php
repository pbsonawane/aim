<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('en_system_settings')) { 
            DB::table('en_system_settings')->insert([
                ['configuration' => '{"en_sysconfig_api_url":"http://en-sysconfig-lumen-nginx-server", "en_sysconfig_config":"api", "en_sysconfig_config_path":"/var/www/ensystemconfig"}', 'status' => 'y', 'type' => 'ensysconfig']
            ]);  
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('en_system_settings')
                    ->where('type', 'ensysconfig')->delete();
    }
}
