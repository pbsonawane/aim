<?php

use Illuminate\Database\Seeder;
use App\Models\EnSoftwareCategory;
class EnSoftwareCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$status = EnSoftwareCategory::firstOrCreate([
		    'software_category' 	=> 'Operating System'
		],[
			'software_category_id' 	=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'  			=> 'Operating Systems',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
		
		$status = EnSoftwareCategory::firstOrCreate([
		    'software_category' 	=> 'Others'
		],[
			'software_category_id' 	=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'  			=> 'Softwares not coming in any other category',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);	
		
		$status = EnSoftwareCategory::firstOrCreate([
		    'software_category' 	=> 'Internet'
		],[
			'software_category_id' 	=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'  			=> 'Internet',
			'status'				=> 'y',
			'is_default'			=> 'y'
		
		]);		
		
		$status = EnSoftwareCategory::firstOrCreate([
		    'software_category' 	=> 'Multimedia'
		],[
			'software_category_id' 	=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'  			=> 'Multimedia',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);		
		
		$status = EnSoftwareCategory::firstOrCreate([
		    'software_category' 	=> 'Control Panel'
		],[
			'software_category_id' 	=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'  			=> 'Control Panel softwares',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);		
		
		$status = EnSoftwareCategory::firstOrCreate([
		    'software_category' 	=> 'Databases'
		],[
			'software_category_id' 	=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'  			=> 'Database software like Mysql, MSSQL, Oracle etc',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);	
			
    }
}
