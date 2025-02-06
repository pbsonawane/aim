<?php

use Illuminate\Database\Seeder;
use App\Models\EnContractType;

class EnContractTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$status = EnContractType::firstOrCreate([
		    'contract_type' 		=>'Warranty'
		],[
			'contract_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'contract_description'  => 'Warranty contracts between vendor and organisation',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
			
		$status = EnContractType::firstOrCreate([
		    'contract_type' 		=>'Lease'
		],[
			'contract_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'contract_description'  => 'Lease contracts between vendor and organisation',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
			
		$status = EnContractType::firstOrCreate(
		[
		    'contract_type' 		=> 'Support'
		],[
			'contract_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'contract_description'  => 'Support Contracts',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);	
			
		$status = EnContractType::firstOrCreate(
		[
		    'contract_type' 		=> 'Maintenance'
		],[
			'contract_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'contract_description'  => 'Maintenance Contracts',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);	
    }
}
