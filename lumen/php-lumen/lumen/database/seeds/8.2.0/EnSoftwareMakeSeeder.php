<?php
use Illuminate\Database\Seeder;
use App\Models\EnSoftwareManufacturer;

class EnSoftwareMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    		$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Microsoft Corporation'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Microsoft',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Apple Inc'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Apple Inc',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Dell'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Dell',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'       =>'Adobe Inc.'
            ],[
    			'software_manufacturer_id'	  => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                 =>'Adobe',
    			'status'	                  =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Oracle'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Oracle',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'.Net Foundation'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'.Net Foundation',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Google'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Google',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Mozilla'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Mozilla',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'NetBeans'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'NetBeans',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Notepad++ Teams'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Notepad++ Teams',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Novell'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Novell',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'NVIDIA Corporation'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'NVIDIA Corporation',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Open Office'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Open Office',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'SAP'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'SAP',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Solarwinds'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Solarwinds',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'ManageEngine'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'ManageEngine',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Quickheal'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Quickheal',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Others'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Others',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Yahoo Inc'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Yahoo Inc',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'TeamViewer'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'TeamViewer',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Avast'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Avast',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Kaspersky Lab'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Kaspersky Lab',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Cisco'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Cisco',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Fortinet'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Fortinet',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Python Software Corporation'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Python',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Postman'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Postman',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'ESDS'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'ESDS',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
			$status = EnSoftwareManufacturer::firstOrCreate([
                'software_manufacturer'      =>'Symantec'
            ],[
    			'software_manufacturer_id'   => DB::raw('UUID_TO_BIN(UUID())'),
    			'description'                =>'Symantec Corporation',
    			'status'	                 =>'y',
                'is_default'                 => 'y'
    		]);
    	
    }
}
