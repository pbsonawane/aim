<?php

use Illuminate\Database\Seeder;

use App\Models\EnSystemSettings;

class EnSystemSettingsTableSeederRevert extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = EnSystemSettings::where('type', 'filechecksum')->delete();
    }
}
