<?php

use Illuminate\Database\Seeder;
use App\Models\EnReportCategory;

class EnReportCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Contract = EnReportCategory::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("005e944c-9b51-11ea-8871-66849b151752")'),
                'report_category' =>'Contract'
                ],
                [
                'description'=>'Contract Reports',
                'status'    =>'y'
                ]
            );
        $Software = EnReportCategory::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("4d686384-9b51-11ea-8871-66849b151752")'),
                'report_category' =>'Software'
                ],
                [
                'description'=>'Software Reports',
                'status'    =>'y'
                ]
            );
        $Purchase = EnReportCategory::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("77e42747-9b51-11ea-8871-66849b151752")'),
                'report_category' =>'Purchase'
                ],
                [
                'description'=>'Purchase Reports',
                'status'    =>'y'
                ]
            );
        $Assets   = EnReportCategory::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("a8b35dae-9b51-11ea-8871-66849b151752")'),
                'report_category' =>'Assets'
                ],
                [
                'description'=>'Assets Reports',
                'status'    =>'y'
                ]
            );
        $Allcomp   = EnReportCategory::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("4a7906c7-cce0-11ea-8a23-da4ce191425e")'),
                'report_category' =>'All Computers - Workstations and Servers'
                ],
                [
                'description'=>'All Computers -Workstations and Servers',
                'status'    =>'y'
                ]
            );
        
    }
}
