<?php

use Illuminate\Database\Seeder;
use App\Models\EnReports;

class EnReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("005e944c-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Contarct By Vendors',
                'module'        =>'CONTRACT',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["contractid","contract_name","contract_type","contract_status","cost","vendor_name"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );

        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("005e944c-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Active Contracts',
                'module'        =>'CONTRACT',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["contractid","contract_name","contract_type","vendor_name","from_date","to_date"]',
                'filters'       => '[{"criteria":"equal","filter_column":"contract_status","criteria_match":"AND","criteria_value":"active"}]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );
        
        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("005e944c-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Expired Contracts',
                'module'        =>'CONTRACT',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["contractid","contract_name","contract_type","from_date","to_date"]',
                'filters'       => '[{"criteria":"equal","filter_column":"contract_status","criteria_match":"AND","criteria_value":"expired"}]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );

        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("4d686384-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Software License Usage',
                'module'        =>'SOFTWARE',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["software_name","version","software_type","installed","purchased","allocated"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );

        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("4d686384-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Softwares By Category',
                'module'        =>'SOFTWARE',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["software_category","software_name","software_manufacturer","version"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );

        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("4d686384-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Software by manufecturer',
                'module'        =>'SOFTWARE',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["software_name","version","software_manufacturer","software_type"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );

        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("77e42747-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Purchase Order By Status',
                'module'        =>'PURCHASE',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["po_name","po_no","pr_req_date","pr_due_date","po_status"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );
        
        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("77e42747-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'Purchase Order By Vendor',
                'module'        =>'PURCHASE',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["po_name","po_no","pr_title","pr_req_date","vendor_name","pr_due_date","po_status"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );

        $report = EnReports::firstOrCreate(
                ['report_cat_id'   => DB::raw('UUID_TO_BIN("a8b35dae-9b51-11ea-8871-66849b151752")'),
                'user_id'       => DB::raw('UUID_TO_BIN("7117a498-41c3-11ea-9e9a-0242ac110003")'),
                'report_name'   =>'All Assets',
                'module'        =>'ASSETS',
                ],
                [
                'report_id'     => DB::raw('UUID_TO_BIN(UUID())'),
                'filter_fields' => '["asset_status","po_name","asset_tag","vendor_name","acquisitiondate","warrantyexpirydate"]',
                'share_report'  =>'y',
                'enableschedule'=>'n',
                'status'        =>'y'
                ]
            );
    }
}