<?php

use Illuminate\Database\Seeder;
use App\Models\EnRelationshipType;

class EnRelationshipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Attached to',
			'inverse_rel_type'	=>'Attached from'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Attached asset',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);
		
    	$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Includes',
			'inverse_rel_type'	=>'Is member of'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Member relationship',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);

		$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Depends on',
			'inverse_rel_type'	=>'Used by'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Depends on - Used By',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);	
	
		$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Manages',
			'inverse_rel_type'	=>'Managed by'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Managed relationship',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);		
		
		$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Runs',
			'inverse_rel_type'	=>'Runs on'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Runs on relationship',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);			
		
		$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Uses',
			'inverse_rel_type'	=>'Use By'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Used by  relationship',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);			
		
		$status = EnRelationshipType::firstOrCreate([
		    'rel_type' 			=>'Hosted on',
			'inverse_rel_type'	=>'Hosts'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Hosting relationship for Vms',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);			
		
		$status = EnRelationshipType::firstOrCreate([
			'rel_type' 			=>'Contains',
			'inverse_rel_type'	=>'In Rack'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Rack Server Relationship',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);		
		
		$status = EnRelationshipType::firstOrCreate([
			'rel_type' 			=> 'Connected to',
			'inverse_rel_type'	=>'Connected from'
		],[
			'rel_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'description'		=> 'Connection Relationship',
			'status'			=> 'y',
			'is_default'		=> 'y'
		]);	
    }
}
