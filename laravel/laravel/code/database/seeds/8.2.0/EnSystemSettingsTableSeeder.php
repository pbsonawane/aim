<?php

use Illuminate\Database\Seeder;

use App\Models\EnSystemSettings;

class EnSystemSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EnSystemSettings::firstOrCreate([
            'type'          => 'filechecksum'
        ],[
            'configuration'   => '{"en_work_dir": "/var/www/html"}',
            'status'  =>'y',            
        ]);
    }
}
