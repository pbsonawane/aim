<?php

use Illuminate\Database\Seeder;
use App\Models\EnSoftwareType;

class EnSoftwareTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$status = EnSoftwareType::firstOrCreate([
            'software_type'      =>'Freeware'
        ],[
			'software_type_id'   => DB::raw('UUID_TO_BIN(UUID())'),
			'description'        =>'Freeware software is a software that is available free of cost. Freeware software will come under this type',
			'status'             =>'y',
			'is_default'		 => 'y'
		]);
		$status = EnSoftwareType::firstOrCreate([
            'software_type'      =>'Prohibited'
        ],[
			'software_type_id'   => DB::raw('UUID_TO_BIN(UUID())'),
			'description'        =>'All Prohibited software in the organization will come under this type',
			'status'             =>'y',
			'is_default'		 => 'y'
		]);
		$status = EnSoftwareType::firstOrCreate([
            'software_type'      =>'Shareware'
        ],[
			'software_type_id'   => DB::raw('UUID_TO_BIN(UUID())'),
			'description'        =>'Shareware software is a software that are freely distributed to users on trial basis.All software of this kind will come under this type',
			'status'             =>'y',
			'is_default'		 => 'y'
		]);
		$status = EnSoftwareType::firstOrCreate([
            'software_type'      =>'Managed'
        ],[
			'software_type_id'   => DB::raw('UUID_TO_BIN(UUID())'),
			'description'        =>'All managed software come under this type. For managed software license can be created.',
			'status'             =>'y',
			'is_default'		 => 'y'
		]);
		$status = EnSoftwareType::firstOrCreate([
            'software_type'      =>'Excluded'
        ],[
			'software_type_id'   => DB::raw('UUID_TO_BIN(UUID())'),
			'description'        =>'Handles the softwares that need not be managed',
			'status'             =>'y',
			'is_default'		 => 'y'
		]);
		$status = EnSoftwareType::firstOrCreate([
            'software_type'      =>'Unidentified'
        ],[
			'software_type_id'   => DB::raw('UUID_TO_BIN(UUID())'),
			'description'        =>'All unidentified software will be handled by this type',
			'status'	         =>'y',
			'is_default'		 => 'y'
		]);
    }
}
