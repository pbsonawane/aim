<?php

use Illuminate\Database\Seeder;
use App\Models\EnCiTypes;

class EnCiTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = EnCiTypes::firstOrCreate([
            'ci_type_id'    => DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
            'citype'        =>'IT Assets',
        ],[
            'status'        =>'y'
        ]);

        $type = EnCiTypes::firstOrCreate(
        [
            'ci_type_id'    => DB::raw('UUID_TO_BIN("f72ea114-0a05-11e9-92e6-0242ac110002")'),
            'citype'        => 'Non IT Assets'
        ],[
            'status'        =>'y'
        ]);

        $type = EnCiTypes::firstOrCreate(
        [
            'ci_type_id' => DB::raw('UUID_TO_BIN("4df240ad-6819-11ea-9f85-0242ac110002")'),
            'citype' =>'Asset Components'
        ],[
            'status' =>'y'
        ]);

        $type = EnCiTypes::firstOrCreate(
        [
            'ci_type_id' => DB::raw('UUID_TO_BIN("fc9964ef-0a05-11e9-92e6-0242ac110002")'),
            'citype' =>'Softwares'
        ],[
            'status' =>'y'
        ]);
    }
}
