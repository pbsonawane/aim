<?php

use Illuminate\Database\Seeder;
use App\Models\EnLicenseType;

class EnLicenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$status = EnLicenseType::firstOrCreate([
		    'license_type' 			=> 'Enterprise-Perpetual',
			'installation_allow'	=> 'Unlimited'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'y',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
	
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'Enterprise-Subscription',
			'installation_allow' 	=> 'Unlimited'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'n',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
	
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'Free License',
			'installation_allow' 	=> 'Unlimited'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'y',
			'is_free' 				=> 'y',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
	
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'OEM',
			'installation_allow' 	=> 'OEM'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'n',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
	
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'Volume ',
			'installation_allow' 	=> 'Volume'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'n',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		
		]);
	
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'Individual',
			'installation_allow' 	=> 'Single'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'n',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		
		]);
	
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'Trial License',
			'installation_allow' 	=> 'Volume'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'n',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);	
		
		$status = EnLicenseType::firstOrCreate([
		    'license_type'  		=> 'Concurrent License',
			'installation_allow' 	=> 'Single'
		],[
			'license_type_id' 		=> DB::raw('UUID_TO_BIN(UUID())'),
			'is_perpetual' 			=> 'n',
			'is_free' 				=> 'n',
			'status'				=> 'y',
			'is_default'			=> 'y'
		]);
    }
}
