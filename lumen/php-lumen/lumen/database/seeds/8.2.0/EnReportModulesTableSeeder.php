<?php

use Illuminate\Database\Seeder;
use App\Models\EnReportModules;

class EnReportModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Contract = EnReportModules::firstOrCreate(
                [
                    'module_id'   => DB::raw('UUID_TO_BIN("0f0c0ec4-9a86-11ea-a21e-0242ac110003")'),
                    'module_name' =>'Contract',
                    'module_key'  =>'CONTRACT'
                ],
                [
                    'module_fields' =>'{"cost":"Total Price","renewed":"Pending Status","to_date":"To Date","support":"Support","from_date":"From Date","contractid":"ContractID","created_at":"Created Time","updated_at": "Updated Time","vendor_name":"Vendor Name","contract_name":"Contract Name","contract_type":"Contract Type","contract_status":"Contract Status"}',

                    'filter_fields' =>'{"cost": "Total Price", "renewed": "Pending Status", "support": "Support", "contractid": "ContractID", "vendor": "Vendor", "contract_name": "Contract Name", "contracttype": "Contract Type", "contract_status": "Contract Status"}',

                    'date_filter_fields' =>'{"created_at": "Created Time", "updated_at": "Updated Time","to_date": "To Date", "from_date": "From Date"}',

                    'orignal_fields' =>'{"vendor_name":"v.vendor_name","contract_type":"ct.contract_type","support":"cd.support","cost":"cd.cost","contractid":"c.contractid","contract_name":"c.contract_name","renewed":"c.renewed","from_date":"c.from_date","to_date":"c.to_date","contract_status":"c.contract_status","status":"c.status","created_at":"c.created_at","updated_at":"c.updated_at","vendor":"c.vendor_id","contracttype":"c.contract_type_id"}',

                    'module_description'=>'Contract Report Module',
                    'status'    =>'y'
                ]
            );
        $Software = EnReportModules::firstOrCreate(
                [
                    'module_id'   => DB::raw('UUID_TO_BIN("180882e5-9a8e-11ea-a3a1-0242ac110003")'),
                    'module_name' =>'Software',
                    'module_key'  =>'SOFTWARE'
                ],
                [
                    'module_fields'  =>'{"software_name":"Software Name","software_category":"Software Category", "software_type":"Software Type", "software_manufacturer":"Manufacturer", "version":"Version", "installed":"Installed", "purchased":"Purchased", "allocated":"Allocated","created_at":"Created Time", "updated_at":"Updated Time"}',

                    'filter_fields' =>'{"software_name":"Software Name","sw_category":"Software Category", "sw_type":"Software Type","sw_manufacturer":"Manufacturer","version":"Version"}',

                    'date_filter_fields' =>'{"created_at":"Created Time", "updated_at":"Updated Time"}',

                    'orignal_fields' =>'{"software_name":"sw.software_name","software_category":"sc.software_category","sw_category":"sw.software_category_id","software_type":"st.software_type","sw_type":"sw.software_type_id","software_manufacturer":"sm.software_manufacturer","sw_manufacturer":"sw.software_manufacturer_id","version":"sw.version","installed":"installed","purchased":"purchased","allocated":"allocated","created_at":"sw.created_at","updated_at":"sw.updated_at"}',

                    'module_description'=>'Software Report Module',
                    'status'    =>'y'
                ]
            );
        $Purchase = EnReportModules::firstOrCreate(
                [
                    'module_id'   => DB::raw('UUID_TO_BIN("ab7559cd-9a91-11ea-a3a1-0242ac110003")'),
                    'module_name' =>'Purchase',
                    'module_key'  =>'PURCHASE'
                ],
                [
                    'module_fields'  =>'{"business_vertical":"Business Vertical","datacenter":"Datacenter","pr_title":"Purchase Title","vendor_name":"Vendor","location":"Location","pr_due_date":"Due Date","pr_priority":"Priority","pr_req_date":"Request Date","pr_cost_center":"Cost Center","billing_address":"Billing Address","shipping_address":"Shipping Address","po_name":"PO Name","po_no":"PO NO","po_status":"PO Status","discount_amount":"Discount Amount","discount_per":"Discount percentage","sub_total":"Sub Total","total":"Total","created_at":"Created Time", "updated_at":"Updated Time"}',

                    'filter_fields'  =>'{"bv":"Business Vertical","dc":"Datacenter","pr_title":"Purchase Title","vendor":"Vendor","loc":"Location","pr_priority":"Priority","cost_center":"Cost Center","po_name":"PO Name","po_no":"PO NO","po_status":"PO Status"}',

                    'date_filter_fields' =>'{"created_at":"Created Time", "updated_at":"Updated Time","pr_due_date":"Due Date","pr_req_date":"Request Date"}',

                    'orignal_fields' =>'{"business_vertical":"business_vertical","datacenter":"datacenter","location":"location","pr_title":"pr_title","vendor_name":"v.vendor_name","vendor":"vendor","pr_priority":"pr_priority","pr_cost_center":"pr_cost_center","billing_address":"billing_address","shipping_address":"shipping_address","po_name":"po.po_name","po_no":"po.po_no","po_status":"po.status","pr_due_date":"pr_due_date","pr_req_date":"pr_req_date","discount_amount":"discount_amount","discount_per":"discount_per","sub_total":"sub_total","total":"total","cost_center":"cost_center","created_at":"po.created_at","updated_at":"po.updated_at","bv":"po.bv_id","dc":"po.dc_id","loc":"po.location_id"}',

                    'module_description'=>'Purchase Report Module',
                    'status'    =>'y'
                ]
            );
        $Assets   = EnReportModules::firstOrCreate(
                [
                    'module_id'   => DB::raw('UUID_TO_BIN("c71c7ab1-9a93-11ea-a3a1-0242ac110003")'),
                    'module_name' =>'Assets',
                    'module_key'  =>'ASSETS'
                ],
                [
                    'module_fields'        =>'{"business_vertical":"Business Vertical","location":"Location","po_name":"PO Name","ci_name":" CI Name","ci_type":"CI Type","vendor_name":"Vendor","asset_tag":"Tag","display_name":"Display Name","asset_status":"Asset Status","purchasecost":"Purchase Cost","acquisitiondate":"Acquisition Date","expirydate":"Expiry Date","warrantyexpirydate":"Warranty Expiry Date","created_at":"Created Time","updated_at":"Updated Time"}',

                    'filter_fields'  =>'{"business_vertical":"Business Vertical","location":"Location","po_name":"PO Name","vendor":"Vendor","asset_tag":"Tag","display_name":"Display Name","asset_status":"Asset Status","purchasecost":"Purchase Cost","ciname":"CI Name","citype":"CI Type"}',

                    'date_filter_fields' =>'{"created_at":"Created Time", "updated_at":"Updated Time","acquisitiondate":"Acquisition Date","expirydate":"Expiry Date","warrantyexpirydate":"Warranty Expiry Date"}',

                    'orignal_fields' =>'{"business_vertical":"a.bv_id","location":"a.location_id","po_name":"po.po_name","vendor_name":"v.vendor_name","vendor":"ad.vendor_id","asset_tag":"a.asset_tag","display_name":"a.display_name","asset_status":"a.asset_status","purchasecost":"ad.purchasecost","acquisitiondate":"ad.acquisitiondate","expirydate":"ad.expirydate","warrantyexpirydate":"ad.warrantyexpirydate","created_at":"a.created_at","updated_at":"a.updated_at","citype":"ci_type.ci_type_id","ci_type":"ci_type.citype","ci_name":"ci.ci_name","ciname":"a.ci_templ_id"}',
                
                    'module_description'=>'Assets Report Module',
                    'status'    =>'y'
                ]
            );
        $CMDB     = EnReportModules::firstOrCreate(
                [
                    'module_id'   => DB::raw('UUID_TO_BIN("4c26133a-ccdf-11ea-8a23-da4ce191425e")'),
                    'module_name' =>'CMDB',
                    'module_key'  =>'CMDB'
                ],
                [
                    'module_fields'        =>'{"business_vertical":"Business Vertical","location":"Location","po_name":"PO Name","vendor_name":"Vendor","asset_tag":"Tag","display_name":"Display Name","asset_status":"Asset Status","purchasecost":"Purchase Cost","acquisitiondate":"Acquisition Date","expirydate":"Expiry Date","warrantyexpirydate":"Warranty Expiry Date","created_at":"Created Time","updated_at":"Updated Time"}',

                    'filter_fields'  =>'{"business_vertical":"Business Vertical","location":"Location","po_name":"PO Name","vendor":"Vendor","asset_tag":"Tag","display_name":"Display Name","asset_status":"Asset Status","purchasecost":"Purchase Cost"}',

                    'date_filter_fields' =>'{"created_at":"Created Time", "updated_at":"Updated Time","acquisitiondate":"Acquisition Date","expirydate":"Expiry Date","warrantyexpirydate":"Warranty Expiry Date"}',

                    'orignal_fields' =>'{"business_vertical":"a.bv_id","location":"a.location_id","po_name":"po.po_name","vendor_name":"v.vendor_name","vendor":"ad.vendor_id","asset_tag":"a.asset_tag","display_name":"a.display_name","asset_status":"a.asset_status","purchasecost":"ad.purchasecost","acquisitiondate":"ad.acquisitiondate","expirydate":"ad.expirydate","warrantyexpirydate":"ad.warrantyexpirydate","created_at":"a.created_at","updated_at":"a.updated_at"}',
                
                    'module_description'=>'CMDB Report Module',
                    'status'    =>'y'
                ]
            );
        $ALLCOMP  = EnReportModules::firstOrCreate(
                [
                    'module_id'   => DB::raw('UUID_TO_BIN("881a2497-cce0-11ea-8a23-da4ce191425e")'),
                    'module_name' =>'All Computers (Desktops,Servers,Laptops)',
                    'module_key'  =>'ALLCOMP'
                ],
                [
                    'module_fields'        =>'{"attach": "Attached Asset", "no_hdd": "Number of Hard Disks", "no_rams": "Number of RAMs", "po_name": "PO Name", "location": "Location", "asset_tag": "Tag", "total_mem": "Total Memory", "total_ram": "Total RAM", "created_at": "Created Time", "expirydate": "Expiry Date", "total_cost": "Total Cost", "updated_at": "Updated Time", "vendor_name": "Vendor", "asset_status": "Asset Status", "display_name": "Display Name", "purchasecost": "Purchase Cost", "acquisitiondate": "Acquisition Date", "business_vertical": "Business Vertical", "warrantyexpirydate": "Warranty Expiry Date"}',

                    'filter_fields'  =>'{"vendor": "Vendor", "po_name": "PO Name", "location": "Location", "asset_tag": "Tag", "asset_status": "Asset Status", "display_name": "Display Name", "purchasecost": "Purchase Cost", "business_vertical": "Business Vertical"}',

                    'date_filter_fields' =>'{"created_at":"Created Time", "updated_at":"Updated Time","acquisitiondate":"Acquisition Date","expirydate":"Expiry Date","warrantyexpirydate":"Warranty Expiry Date"}',

                    'orignal_fields' =>'{"attach": "attach", "no_hdd": "no_hdd", "vendor": "ad.vendor_id", "no_rams": "no_rams", "po_name": "po.po_name", "location": "a.location_id", "asset_tag": "a.asset_tag", "total_mem": "total_mem", "total_ram": "total_ram", "created_at": "a.created_at", "expirydate": "ad.expirydate", "total_cost": "total_cost", "updated_at": "a.updated_at", "vendor_name": "v.vendor_name", "asset_status": "a.asset_status", "display_name": "a.display_name", "purchasecost": "ad.purchasecost", "acquisitiondate": "ad.acquisitiondate", "business_vertical": "a.bv_id", "warrantyexpirydate": "ad.warrantyexpirydate"}',
                
                    'module_description'=>'All Computers (Desktops,Servers,Laptops)',
                    'status'    =>'y'
                ]
            );
    }
}
