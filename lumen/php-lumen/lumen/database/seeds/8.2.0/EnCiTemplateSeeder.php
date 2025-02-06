<?php

use Illuminate\Database\Seeder;
use App\Models\EnCiTemplDefault;

class EnCiTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Desktop',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'DSKTP',
			'variable_name' 		=> 'desktop'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "RAM", "input_type": "text", "validation": "", "veriable_name": "ram"}, {"unit": "", "attribute": "Hard Disk", "input_type": "text", "validation": "", "veriable_name": "hdd"}, {"unit": "", "attribute": "Processor", "input_type": "text", "validation": "", "veriable_name": "processor"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Ethernet',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'ETH',
			'variable_name' 		=> 'ethernet'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": null, "attribute": "Frequency", "input_type": "text", "validation": null, "veriable_name": "frequency"}, {"unit": "", "attribute": "Mac Address", "input_type": "text", "validation": "", "veriable_name": "mac_address"}, {"unit": "", "attribute": "Make", "input_type": "text", "validation": ["required", "alpha_num"], "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": ["required"], "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "Speed", "input_type": "text", "validation": ["required"], "veriable_name": "speed"}]',
			'status' 				=> 'y'
		]);
	
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'External Hard Disk',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("4df240ad-6819-11ea-9f85-0242ac110002")'),
			'prefix' 				=> 'EXTHDD',
			'variable_name' 		=> 'usb_hdd'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "GB", "attribute": "Capacity", "input_type": "text", "validation": "", "veriable_name": "capacity"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=>'Firewall',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'FW',
			'variable_name' 		=> 'firewall'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Make", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "MAC", "input_type": "text", "validation": "", "veriable_name": "mac"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=>'Hard Disk',
			'ci_type_id'			=>DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'HDD',
			'variable_name' 		=> 'hdd'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Type", "input_type": "text", "validation": "", "veriable_name": "hdd_type"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "hdd_model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "hdd_serial_no"}, {"unit": "GB", "attribute": "Capacity", "input_type": "text", "validation": "", "veriable_name": "hdd_capacity"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=>'Keyboard',
			'ci_type_id'			=>DB::raw('UUID_TO_BIN("4df240ad-6819-11ea-9f85-0242ac110002")'),
			'prefix' 				=> 'KYBD',
			'variable_name' 		=> 'keyboard'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Make", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Laptop',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'LTP',
			'variable_name' 		=> 'laptop'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "GB", "attribute": "RAM", "input_type": "text", "validation": "", "veriable_name": "ram"}, {"unit": "GB", "attribute": "HDD", "input_type": "text", "validation": "", "veriable_name": "hdd"}, {"unit": "", "attribute": "Processor", "input_type": "text", "validation": "", "veriable_name": "processor"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Monitor',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("4df240ad-6819-11ea-9f85-0242ac110002")'),
			'prefix' 				=> 'MON',
			'variable_name' 		=> 'monitor'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "Type", "input_type": "text", "validation": "", "veriable_name": "type"}, {"unit": "Inches", "attribute": "Size", "input_type": "text", "validation": "", "veriable_name": "size"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Mouse',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("4df240ad-6819-11ea-9f85-0242ac110002")'),
			'prefix' 				=> 'MOUSE',
			'variable_name' 		=> 'mouse'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Make", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Pen Drive',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("4df240ad-6819-11ea-9f85-0242ac110002")'),
			'prefix' 				=> 'PD',
			'variable_name' 		=> 'pendrive'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Capacity", "input_type": "text", "validation": "", "veriable_name": "capacity"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Printer',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("f72ea114-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'PRT',
			'variable_name' 		=> 'printer'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "Type", "input_type": "text", "validation": "", "veriable_name": "type"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Projector',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("f72ea114-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'PROJ',
			'variable_name' 		=> 'projector'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes'	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Rack',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("f72ea114-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'RACK',
			'variable_name' 		=> 'rack'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "U", "attribute": "Height", "input_type": "text", "validation": "", "veriable_name": "height"}, {"unit": "Amp", "attribute": "Power Limit", "input_type": "text", "validation": "", "veriable_name": "power_limit"}, {"unit": "", "attribute": "PDU", "input_type": "text", "validation": "", "veriable_name": "pdu"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'RAM',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'RAM',
			'variable_name' 		=> 'ram'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": null, "attribute": "Make", "input_type": "text", "validation": null, "veriable_name": "ram_make"}, {"unit": "GB", "attribute": "Capacity", "input_type": "text", "validation": ["numeric"], "veriable_name": "ram_capacity"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "ram_serial_no"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Router',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'RTR',
			'variable_name' 		=> 'router'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "MAC Address", "input_type": "text", "validation": "", "veriable_name": "mac"}, {"unit": "", "attribute": "Ports", "input_type": "text", "validation": "", "veriable_name": "ports"}, {"unit": "U", "attribute": "Height", "input_type": "text", "validation": "", "veriable_name": "height"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Scanner',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("f72ea114-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'SCN',
			'variable_name' 		=> 'scanner'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}]',
			'status'				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=> 'Server',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'SERV',
			'variable_name' 		=> 'server'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": null, "attribute": "Make", "input_type": "text", "validation": null, "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "sr_no"}, {"unit": "U", "attribute": "Height", "input_type": "text", "validation": ["numeric"], "veriable_name": "height"}, {"unit": "", "attribute": "RAM", "input_type": "text", "validation": "", "veriable_name": "ram"}, {"unit": "", "attribute": "Hard Disk", "input_type": "text", "validation": "", "veriable_name": "hdd"}, {"unit": "", "attribute": "Ethernet", "input_type": "text", "validation": "", "veriable_name": "eth"}, {"unit": "", "attribute": "Processor", "input_type": "text", "validation": "", "veriable_name": "processor"}]',
			'status' 				=> 'y'
		]);
		
		$status = EnCiTemplDefault::firstOrCreate([
			'ci_name' 				=>'Switch',
			'ci_type_id'			=> DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")'),
			'prefix' 				=> 'SWTH',
			'variable_name' 		=> 'switch'
		],[
			'ci_templ_id' 			=> DB::raw('UUID_TO_BIN(UUID())'),
			'default_attributes' 	=> '[{"unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make"}, {"unit": "", "attribute": "Model", "input_type": "text", "validation": "", "veriable_name": "model"}, {"unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number"}, {"unit": "", "attribute": "MAC", "input_type": "text", "validation": "", "veriable_name": "mac"}, {"unit": "", "attribute": "Ports", "input_type": "text", "validation": "", "veriable_name": "ports"}, {"unit": "U", "attribute": "Height", "input_type": "text", "validation": "", "veriable_name": "Height"}]',
			'status' 				=> 'y'
		]);
    }
}
