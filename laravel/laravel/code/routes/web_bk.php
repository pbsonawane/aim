<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

config(['app.host_ip' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '']); //Lumen
config(['app.site_url' => (isset($_SERVER['HTTPS']) ? "https://" : "http://") . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '')]);

Route::get('/versionmodule', function () {
    print_r(config('enconfig'));
    return config('app.version');
});
Route::get('/template', function () {
    // print_r(config('enconfig'));
    $data['pageTitle'] = "AIM - Asset Inventory Manager"; // eNlight360
    return view('template', $data);
});

$router->get('/test_session', function () {
    dd(\Session::all());
});

/*=========================== Localization ===============================*/

Route::get('language/{lang}', 'LangController@index')->name('langroute');
Route::get('/js/lang', 'LangController@lang_trans_js');
Route::get('sendbasicemail', 'MailController@basic_email');



/*=========================== session sharing ===============================*/

Route::get('/setaccess', 'Authenticate\LoginController@setaccess');
Route::get('/checkaccess', 'Authenticate\LoginController@setdomainacceess');
Route::get('/logout', 'Authenticate\LoginController@logout');
Route::get('/clear', 'Authenticate\LoginController@clearsession');

/*=========================== file checksum ===============================*/

Route::get('/filechange', 'Admin\ChecksumController@filechange'); // Checksum

//vendor registration 
Route::get('/vendors', 'Cmdb\VendorRegController@vendors');
Route::post('/vendors/save', 'Cmdb\VendorRegController@vendoraddsubmit');

Route::post('/quotationvendorcomparison/add', 'Cmdb\PoController@quotation_vendor_cmp');
Route::post('/quotationvendorcomparison/edit', 'Cmdb\PoController@quotation_vendor_cmp_edit');

Route::group(['middleware' => ['web', 'en.auth', 'en.permissions', 'filechecksum']], function () {
    /*=========================== regions ===============================*/

    Route::get('/regions', 'Admin\RegionController@regions');
    Route::post('/regions/list', 'Admin\RegionController@regionlist');

    /*=========================== citemplates ===============================*/

    Route::get('/citemplates', 'Cmdb\CitemplateController@citemplates');
    Route::post('/citemplates/list', 'Cmdb\CitemplateController@citemplatedata');
    Route::post('/citemplates/add', 'Cmdb\CitemplateController@compadd');
    Route::post('/citemplates/save', 'Cmdb\CitemplateController@savecomp');
    Route::post('/citemplates/edit', 'Cmdb\CitemplateController@editcomp');
    Route::post('/citemplates/update', 'Cmdb\CitemplateController@updatecomp');
    Route::post('/citemplates/delete', 'Cmdb\CitemplateController@deleteci');

    /*=========================ASSETS============================*/

    Route::post('/asset', 'Asset\AssetController@asset');
    Route::post('/asset/list', 'Asset\AssetController@assetlist');
    Route::post('/asset/add', 'Asset\AssetController@assetadd');
    Route::post('/asset/save', 'Asset\AssetController@assetsave');
    Route::post('/asset/edit', 'Asset\AssetController@assetedit');
    Route::post('/asset/update', 'Asset\AssetController@updateasset');
    Route::post('/asset/delete', 'Asset\AssetController@deleteasset');

    Route::get('/asset_import', 'Asset\AssetController@import'); //importasset

    Route::post('/assetrelationship', 'Asset\AssetController@assetrelationship');
    Route::post('/assetrelationship/delete', 'Asset\AssetController@assetrelationshipdelete');
    Route::post('/assetrelationship/add', 'Asset\AssetController@assetrelationshipadd');
    Route::post('/assetrelationship/save', 'Asset\AssetController@assetrelationshipsave');

    Route::post('/assetattach', 'Asset\AssetController@attachasset');
    Route::post('/assetattach/save', 'Asset\AssetController@attachassetsave');
    Route::post('/assetattach/delete', 'Asset\AssetController@assetfree');

    /*=========================== Puchase =============================*/

    //purchase order
   
    Route::any('/purchaseorders/{pr_type?}/{id?}', 'Cmdb\PoOrderController@purchaseorders');
    Route::post('/purchaseorder/list', 'Cmdb\PoOrderController@purchaseorderlist');
    Route::post('/purchaseorder/details', 'Cmdb\PoOrderController@purchaseorderdetail');
    Route::post('/purchaseorder/add', 'Cmdb\PoOrderController@purchaseorderadd');
    Route::post('/purchaseorder/save', 'Cmdb\PoOrderController@purchaseordersave');
    Route::post('/purchaseorder/edit', 'Cmdb\PoOrderController@purchaseorderedit'); 
    Route::post('/purchaseorder/printpreview', 'Cmdb\PoOrderController@printpreview');



    //po invoice
    Route::post('/invoice_po', 'Cmdb\PoOrderController@purchaseinvoices');
    Route::post('/invoice_po/delete', 'Cmdb\PoOrderController@poinvoicedelete');
    Route::post('/invoice_po/addsubmit', 'Cmdb\PoOrderController@prpoformActions');
    Route::post('/invoice_po/editsubmit', 'Cmdb\PoOrderController@prpoformActions');

    //purchase request
    Route::any('/purchaserequest', 'Cmdb\PoController@purchaserequests');
    Route::post('/purchaserequest/list', 'Cmdb\PoController@purchaserequestlist');
    Route::post('/purchaserequest/details', 'Cmdb\PoController@purchaserequestdetail');
    Route::post('/purchaserequest/add', 'Cmdb\PoController@purchaserequestadd');
    Route::post('/purchaserequest/edit', 'Cmdb\PoController@purchaserequestedit');
    Route::post('/purchaserequest/save', 'Cmdb\PoController@purchaserequestsave');
    
    

    Route::post('/approve_reject_po', 'Cmdb\PoController@prpoapprovereject');
    Route::post('/approve_reject_pr', 'Cmdb\PoController@prpoapprovereject');
    Route::post('/approve_reject_qc', 'Cmdb\PoController@prpoapprovereject_qc');

    Route::post('/close_pr', 'Cmdb\PoController@prpoformActions');
    Route::post('/convert_to_pr', 'Cmdb\PoController@converttopr');
    Route::post('/cancel_pr', 'Cmdb\PoController@prpoformActions');
    Route::post('/close_po', 'Cmdb\PoController@prpoformActions');
    Route::post('/cancel_po', 'Cmdb\PoController@prpoformActions');
    Route::post('/order_po', 'Cmdb\PoController@prpoformActions');
    Route::post('/receive_items_po', 'Cmdb\PoController@prpoformActions');
    Route::post('/notify_owner_email', 'Cmdb\PoController@prpoformActions');
    Route::post('/notify_vendor_email', 'Cmdb\PoController@prpoformActions');
    Route::post('/purchaseorder/delete', 'Cmdb\PoController@prpoformActions');
    Route::post('/purchaserequest/delete', 'Cmdb\PoController@prpoformActions');
    Route::post('/assignprtouser', 'Cmdb\PoController@assignprtouser');

    Route::post('/delete_attachment_pr', 'Cmdb\PoController@deleteattachment');
    Route::post('/delete_attachment_po', 'Cmdb\PoController@deleteattachment');
    Route::post('/purchaserequest/getnotifications', 'Cmdb\PoController@getnotifications');

    Route::post('/add_attachment_pr', 'Cmdb\PoController@upload');
    
    Route::post('/add_attachment_po', 'Cmdb\PoController@upload');
    Route::post('/download_attachment_pr', 'Cmdb\PoController@downloadprattachment');
    Route::post('/download_attachment_po', 'Cmdb\PoController@downloadpoattachment');

    /*==================================Relationship Type=================================*/

    Route::any('/relationshiptype', 'Cmdb\RelationshipTypeController@relationshiptypes');
    Route::post('/relationshiptype/list', 'Cmdb\RelationshipTypeController@relationshiptypeList');
    Route::post('/relationshiptype/add', 'Cmdb\RelationshipTypeController@relationshiptypeadd');
    Route::post('/relationshiptype/addsubmit', 'Cmdb\RelationshipTypeController@relationshiptypeaddsubmit');
    Route::post('/relationshiptype/edit', 'Cmdb\RelationshipTypeController@relationshiptypeedit');
    Route::post('/relationshiptype/editsubmit', 'Cmdb\RelationshipTypeController@relationshiptypeeditsubmit');
    Route::post('/relationshiptype/delete', 'Cmdb\RelationshipTypeController@relationshiptypedelete');

    /* ==================================Contract Type=================================*/

    Route::get('/contracttype', 'Cmdb\ContractTypeController@contracttypes');
    Route::post('/contracttype/list', 'Cmdb\ContractTypeController@contracttypeList');
    Route::post('/contracttype/add', 'Cmdb\ContractTypeController@contracttypeadd');
    Route::post('/contracttype/addsubmit', 'Cmdb\ContractTypeController@contracttypeaddsubmit');
    Route::post('/contracttype/edit', 'Cmdb\ContractTypeController@contracttypeedit');
    Route::post('/contracttype/editsubmit', 'Cmdb\ContractTypeController@contracttypeeditsubmit');
    Route::post('/contracttype/delete', 'Cmdb\ContractTypeController@contracttypedelete');

    /* ==================================Contracts=================================*/

    Route::get('/contract/{id?}', 'Cmdb\ContractController@contracts');
    Route::post('/contract/list', 'Cmdb\ContractController@contractList');
    Route::post('/contract/add', 'Cmdb\ContractController@contractadd');
    Route::post('/contract/addsubmit', 'Cmdb\ContractController@contractaddsubmit');
    Route::post('/contract/edit', 'Cmdb\ContractController@contractedit');
    Route::post('/contract/editsubmit', 'Cmdb\ContractController@contracteditsubmit');
    Route::post('/contract/delete', 'Cmdb\ContractController@contractdelete');
    Route::post('/contract/details', 'Cmdb\ContractController@contractdetails');

    Route::post('/remove_asset_contract', 'Cmdb\ContractController@associatedassetremove');

    Route::post('/add_attachment_contract', 'Cmdb\ContractController@attachfile');
    Route::get('/view_attachment_contract', 'Cmdb\ContractController@showattachment');
    Route::post('/delete_attachment_contract', 'Cmdb\ContractController@deletecontractattachment');
    Route::post('/download_attachment_contract', 'Cmdb\ContractController@downloadcontractattachment');

    /* ================================== Settings Template =================================*/

    Route::get('/settingstemplate', 'Cmdb\SettingsTemplateController@settingstemplate');
    Route::post('/settingstemplate/list', 'Cmdb\SettingsTemplateController@settingstemplatelist');
    Route::post('/settingstemplate/add', 'Cmdb\SettingsTemplateController@settingstemplateadd');
    Route::post('/settingstemplate/edit', 'Cmdb\SettingsTemplateController@settingstemplateedit');
    Route::post('/settingstemplate/submit', 'Cmdb\SettingsTemplateController@settingstemplatesubmit');
    Route::post('/settingstemplate/update', 'Cmdb\SettingsTemplateController@settingstemplateupdate');
    Route::post('/settingstemplate/delete', 'Cmdb\SettingsTemplateController@settingstemplatedelete');

    /* ================================== vendor =================================*/
    Route::get('/vendor', 'Cmdb\VendorController@vendors');
    Route::post('/vendor/list', 'Cmdb\VendorController@vendorList');
    Route::post('/vendor/add', 'Cmdb\VendorController@vendoradd');
    Route::post('/vendor/addsubmit', 'Cmdb\VendorController@vendoraddsubmit');
    Route::post('/vendor/edit', 'Cmdb\VendorController@vendoredit');
    Route::post('/vendor/editsubmit', 'Cmdb\VendorController@vendoreditsubmit');
    Route::post('/vendor/delete', 'Cmdb\VendorController@vendordelete');



    

    /* ================================== Payment Terms Master =================================*/
    Route::get('/paymentterm', 'Cmdb\PaymenttermController@paymentterms');
    Route::post('/paymentterm/list', 'Cmdb\PaymenttermController@paymenttermList');
    Route::post('/paymentterm/add', 'Cmdb\PaymenttermController@paymenttermadd');
    Route::post('/paymentterm/addsubmit', 'Cmdb\PaymenttermController@paymenttermaddsubmit');
    Route::post('/paymentterm/edit', 'Cmdb\PaymenttermController@paymenttermedit');
    Route::post('/paymentterm/editsubmit', 'Cmdb\PaymenttermController@paymenttermeditsubmit');
    Route::post('/paymentterm/delete', 'Cmdb\PaymenttermController@paymenttermdelete');
	
	/* ================================== Opportunity =================================*/
    Route::get('/opportunity', 'Cmdb\OppListingController@opportunities');
    Route::post('/opportunity/list', 'Cmdb\OppListingController@opportunityList');
	
    /* ================================== Bill To Master =================================*/
    Route::get('/billto', 'Cmdb\BilltoController@billtos');
    Route::post('/billto/list', 'Cmdb\BilltoController@billtoList');
    Route::post('/billto/add', 'Cmdb\BilltoController@billtoadd');
    Route::post('/billto/addsubmit', 'Cmdb\BilltoController@billtoaddsubmit');
    Route::post('/billto/edit', 'Cmdb\BilltoController@billtoedit');
    Route::post('/billto/editsubmit', 'Cmdb\BilltoController@billtoeditsubmit');
    Route::post('/billto/delete', 'Cmdb\BilltoController@billtodelete');

    /* ================================== Ship To Master =================================*/
    Route::get('/shipto', 'Cmdb\ShiptoController@shiptos');
    Route::post('/shipto/list', 'Cmdb\ShiptoController@shiptoList');
    Route::post('/shipto/add', 'Cmdb\ShiptoController@shiptoadd');
    Route::post('/shipto/addsubmit', 'Cmdb\ShiptoController@shiptoaddsubmit');
    Route::post('/shipto/edit', 'Cmdb\ShiptoController@shiptoedit');
    Route::post('/shipto/editsubmit', 'Cmdb\ShiptoController@shiptoeditsubmit');
    Route::post('/shipto/delete', 'Cmdb\ShiptoController@shiptodelete');

    /* ================================== Contact Master =================================*/
    Route::get('/contact', 'Cmdb\ContactController@contacts');
    Route::post('/contact/list', 'Cmdb\ContactController@contactList');
    Route::post('/contact/add', 'Cmdb\ContactController@contactadd');
    Route::post('/contact/addsubmit', 'Cmdb\ContactController@contactaddsubmit');
    Route::post('/contact/edit', 'Cmdb\ContactController@contactedit');
    Route::post('/contact/editsubmit', 'Cmdb\ContactController@contacteditsubmit');
    Route::post('/contact/delete', 'Cmdb\ContactController@contactdelete');


     /* ================================== Requestername Master =================================*/
    Route::get('/requestername', 'Cmdb\RequesternameController@requesternames');
    Route::post('/requestername/list', 'Cmdb\RequesternameController@requesternameList');
    Route::post('/requestername/add', 'Cmdb\RequesternameController@requesternameadd');
    Route::post('/requestername/addsubmit', 'Cmdb\RequesternameController@requesternameaddsubmit');
    Route::post('/requestername/edit', 'Cmdb\RequesternameController@requesternameedit');
    Route::post('/requestername/editsubmit', 'Cmdb\RequesternameController@requesternameeditsubmit');
    Route::post('/requestername/delete', 'Cmdb\RequesternameController@requesternamedelete');

    /* ================================== Delivery Terms Master =================================*/
    Route::get('/delivery', 'Cmdb\DeliveryController@delivery');
    Route::post('/delivery/list', 'Cmdb\DeliveryController@deliveryList');
    Route::post('/delivery/add', 'Cmdb\DeliveryController@deliveryadd');
    Route::post('/delivery/addsubmit', 'Cmdb\DeliveryController@deliveryaddsubmit');
    Route::post('/delivery/edit', 'Cmdb\DeliveryController@deliveryedit');
    Route::post('/delivery/editsubmit', 'Cmdb\DeliveryController@deliveryeditsubmit');
    Route::post('/delivery/delete', 'Cmdb\DeliveryController@deliverydelete');

    /*SEND MAIL CONTRACT*/
    //Route::post('/sendmail', 'Cmdb\ContractController@sendmail');

    /* ================================== costcenter =================================*/

    Route::get('/costcenter', 'Cmdb\CostcenterController@costcenters');
    Route::post('/costcenter/list', 'Cmdb\CostcenterController@costcenterlist');
    Route::post('/costcenter/add', 'Cmdb\CostcenterController@costcenteradd');
    Route::post('/costcenter/addsubmit', 'Cmdb\CostcenterController@costcenteraddsubmit');
    Route::post('/costcenter/edit', 'Cmdb\CostcenterController@costcenteredit');
    Route::post('/costcenter/editsubmit', 'Cmdb\CostcenterController@costcentereditsubmit');
    Route::post('/costcenter/delete', 'Cmdb\CostcenterController@costcenterdelete');

    /*================================== Email Template =================================*/

    Route::get('/emailtemplate', 'Emailtemplate\EmailTemplateController@emailtemplates');
    Route::post('/emailtemplate/add', 'Emailtemplate\EmailTemplateController@emailtemplateadd');
    Route::post('/emailtemplate/addsubmit', 'Emailtemplate\EmailTemplateController@emailtemplateaddsubmit');
    Route::post('/emailtemplate/list', 'Emailtemplate\EmailTemplateController@emailtemplatelist');
    Route::post('/emailtemplate/edit', 'Emailtemplate\EmailTemplateController@emailtemplateedit');
    Route::post('/emailtemplate/editsubmit', 'Emailtemplate\EmailTemplateController@emailtemplateeditsubmit');
    Route::post('/emailtemplate/delete', 'Emailtemplate\EmailTemplateController@emailtemplatedelete');

    /*================================== software type =================================*/

    Route::get('/softwaretype', 'Cmdb\SoftwareTypeController@softwaretypes');
    Route::post('/softwaretype/list', 'Cmdb\SoftwareTypeController@softwaretypelist');
    Route::post('/softwaretype/add', 'Cmdb\SoftwareTypeController@softwaretypeadd');
    Route::post('/softwaretype/addsubmit', 'Cmdb\SoftwareTypeController@softwaretypeaddsubmit');
    Route::post('/softwaretype/edit', 'Cmdb\SoftwareTypeController@softwaretypeedit');
    Route::post('/softwaretype/editsubmit', 'Cmdb\SoftwareTypeController@softwaretypeeditsubmit');
    Route::post('/softwaretype/delete', 'Cmdb\SoftwareTypeController@softwaretypedelete');

    /*================================== SOFTWARE Category =================================*/

    Route::get('/softwarecategory', 'Cmdb\SoftwareCategoryController@softwarecategory');
    Route::post('/softwarecategory/list', 'Cmdb\SoftwareCategoryController@softwarecategorylist');
    Route::post('/softwarecategory/add', 'Cmdb\SoftwareCategoryController@softwarecategoryadd');
    Route::post('/softwarecategory/addsubmit', 'Cmdb\SoftwareCategoryController@softwarecategoryaddsubmit');
    Route::post('/softwarecategory/edit', 'Cmdb\SoftwareCategoryController@softwarecategoryedit');
    Route::post('/softwarecategory/editsubmit', 'Cmdb\SoftwareCategoryController@softwarecategoryeditsubmit');
    Route::post('/softwarecategory/delete', 'Cmdb\SoftwareCategoryController@softwarecategorydelete');

    /*================================== Software Manufacturer =================================*/

    Route::get('/softwaremanufacturer', 'Cmdb\SoftwareManufacturerController@softwaremanufacturer');
    Route::post('/softwaremanufacturer/list', 'Cmdb\SoftwareManufacturerController@softwaremanufacturerlist');
    Route::post('/softwaremanufacturer/add', 'Cmdb\SoftwareManufacturerController@softwaremanufactureradd');
    Route::post('/softwaremanufacturer/addsubmit', 'Cmdb\SoftwareManufacturerController@softwaremanufactureraddsubmit');
    Route::post('/softwaremanufacturer/edit', 'Cmdb\SoftwareManufacturerController@softwaremanufactureredit');
    Route::post('/softwaremanufacturer/editsubmit', 'Cmdb\SoftwareManufacturerController@softwaremanufacturereditsubmit');
    Route::post('/softwaremanufacturer/delete', 'Cmdb\SoftwareManufacturerController@softwaremanufacturerdelete');

    /*================================== License Type =================================*/

    Route::get('/licensetype', 'Cmdb\LicenseTypeController@licensetype');
    Route::post('/licensetype/list', 'Cmdb\LicenseTypeController@licensetypelist');
    Route::post('/licensetype/add', 'Cmdb\LicenseTypeController@licensetypeadd');
    Route::post('/licensetype/addsubmit', 'Cmdb\LicenseTypeController@licensetypeaddsubmit');
    Route::post('/licensetype/edit', 'Cmdb\LicenseTypeController@licensetypeedit');
    Route::post('/licensetype/editsubmit', 'Cmdb\LicenseTypeController@licensetypeeditsubmit');
    Route::post('/licensetype/delete', 'Cmdb\LicenseTypeController@licensetypedelete');

    /*================================== software =================================*/

    //Route::get('/software', 'Cmdb\SoftwareController@softwares');
    Route::post('/software/list', 'Cmdb\SoftwareController@softwarelist');
    Route::post('/software/mainlist', 'Cmdb\SoftwareController@softwaremainlist');
    Route::post('/software/add', 'Cmdb\SoftwareController@softwareadd');
    Route::post('/software/addsubmit', 'Cmdb\SoftwareController@softwareaddsubmit');
    Route::post('/software/edit', 'Cmdb\SoftwareController@softwareedit');
    Route::post('/software/editsubmit', 'Cmdb\SoftwareController@softwareeditsubmit');
    Route::post('/software/delete', 'Cmdb\SoftwareController@softwaredelete');
    Route::post('/software/details', 'Cmdb\SoftwareController@softwaredetails');

    /*==================Report Category ===============*/

    Route::get('/reportcategory', 'Reports\ReportCategoryController@reportcategory');
    Route::post('/reportcategory/list', 'Reports\ReportCategoryController@reportcategorylist');
    Route::post('/reportcategory/add', 'Reports\ReportCategoryController@reportcategoryadd');
    Route::post('/reportcategory/addsubmit', 'Reports\ReportCategoryController@reportcategoryaddsubmit');
    Route::post('/reportcategory/edit', 'Reports\ReportCategoryController@reportcategoryedit');
    Route::post('/reportcategory/editsubmit', 'Reports\ReportCategoryController@reportcategoryeditsubmit');
    Route::post('/reportcategory/delete', 'Reports\ReportCategoryController@reportcategorydelete');

    /*==================== Reports ==================*/

    Route::get('/reports', 'Reports\ReportsController@reports');
    Route::post('/reports/list', 'Reports\ReportsController@reportslist');
    Route::post('/reports/add', 'Reports\ReportsController@reportsadd');
    Route::post('/reports/addsubmit', 'Reports\ReportsController@reportsaddsubmit');
    Route::post('/reports/edit', 'Reports\ReportsController@reportsedit');
    Route::post('/reports/editsubmit', 'Reports\ReportsController@reportseditsubmit');
    Route::post('/reports/delete', 'Reports\ReportsController@reportsdelete');
    Route::any('/purchaseuserdashboard', 'Cmdb\PrUserDashboard@dashboard');
    
    Route::get('/prquotationcomparison/details/{pr_id?}', 'Cmdb\PrQuotationComparison@qc_view');
    Route::post('/prquotationcomparison/update', 'Cmdb\PoController@final_quotation');
    Route::post('/prquotationcomparison_approve/update', 'Cmdb\PoController@final_quotation');
});

Route::group(['middleware' => ['web', 'en.auth', 'filechecksum']], function () {
    /*==Dashboard===*/
    Route::get('/', 'Asset\AssetController@assets');
    /*==================== Purchase ==================*/

    Route::post('/notifyagain', 'Cmdb\PoController@prpoformActions');
    Route::post('/convert/add', 'Cmdb\PoController@convertitemsinpr');
    Route::post('/convert/save', 'Cmdb\PoController@convertprsave');
    /* ================================== Assets =================================*/

    Route::get('/assets/{asset_id?}/{ci_templ_id?}/{po_id?}', 'Asset\AssetController@assets');
    Route::post('/assettree', 'Asset\AssetController@treedata');
    Route::post('/assetdashboardparent', 'Asset\AssetController@dashboard');
    

    Route::post('/assetdashboard', 'Asset\AssetController@assetdashboard');

    Route::post('/assetcontract', 'Asset\AssetController@assetcontract');
    Route::post('/assetsofcitype', 'Asset\AssetController@assetsofcitype');

    Route::post('/statuschange', 'Asset\AssetController@statuschange');
    Route::post('/statuschangesubmit', 'Asset\AssetController@statuschangesubmit');

    //old import
    //Route::post('/asset_import', 'Asset\AssetController@importasset');
    Route::post('/importfile', 'Asset\AssetController@importfile');
    Route::post('/importsave', 'Asset\AssetController@importsave');

    //new import
    //Route::get('/import', 'Asset\AssetController@import');

    Route::post('/assethistory', 'Asset\AssetController@assethistory');
    Route::post('/assetwithstatus', 'Asset\AssetController@assetwithstatus');

    /* ==================================Contracts=================================*/

    Route::post('/contract/assetlist', 'Cmdb\ContractController@contractassetlist');

    Route::post('/contractrenew', 'Cmdb\ContractController@contractrenew');
    Route::post('/contractrenewsubmit', 'Cmdb\ContractController@contractrenewsubmit');
    Route::post('/renewdetails', 'Cmdb\ContractController@renewdetails');

    Route::post('/contractupdateassociatechild', 'Cmdb\ContractController@contractupdateassociatechild');
    Route::post('/childcontract', 'Cmdb\ContractController@childcontract');
    Route::post('/associatechildcontract', 'Cmdb\ContractController@associatechildcontract');

    Route::post('/notify_owner_contract', 'Cmdb\ContractController@contractaction');
    Route::post('/notify_vendor_contract', 'Cmdb\ContractController@contractaction');

    /*==================== Software License ==================*/

    Route::post('/getswhistory', 'Cmdb\SoftwareController@getswhistory');

    /*=========================== citemplates ===============================*/

    Route::post('/addattributes', 'Cmdb\CitemplateController@addattributes');
    Route::post('/editAttribute', 'Cmdb\CitemplateController@editAttribute');
    Route::post('/updateattribute', 'Cmdb\CitemplateController@updateattribute');

    /*================================== Email Template =================================*/

    Route::post('/emailquoteaddsubmit', 'Emailtemplate\EmailTemplateController@emailquoteaddsubmit');
    Route::post('/emailtemplatechangestatus', 'Emailtemplate\EmailTemplateController@emailtemplatechangestatus');

    /* ================================== Settings Template =================================*/

    Route::post('/clone', 'Cmdb\SettingsTemplateController@formtemplatedefaultclone');

    /*================================== software =================================*/

    Route::post('/assetwithstatus', 'Cmdb\SoftwareController@assetwithstatus');

    Route::post('/swaddasset', 'Cmdb\SoftwareController@swaddasset');
    Route::post('/getcitempidsoftware', 'Cmdb\SoftwareController@getcitempidsoftware');
    Route::get('/softwarelistdetails/{id}', 'Cmdb\SoftwareController@softwarelistdetails');

    /*==================== Software License ==================*/

    Route::post('/swaddlisense', 'Cmdb\SoftwareController@swaddlisense');
    Route::post('/swaddLicensesubmit', 'Cmdb\SoftwareController@swaddLicensesubmit');
    Route::post('/softwarelicenseedit', 'Cmdb\SoftwareController@softwarelicenseedit');
    Route::post('/softwarelicenseeditsubmit', 'Cmdb\SoftwareController@softwarelicenseeditsubmit');
    //Route::post('/swLicenselist/list', 'Cmdb\SoftwareController@swLicenselist');

    Route::post('/softwarelicensellocate', 'Cmdb\SoftwareController@softwarelicensellocate');
    Route::post('/swallocateassetremove', 'Cmdb\SoftwareController@swallocateassetremove');
    Route::post('/swdeallocateuninstall', 'Cmdb\SoftwareController@swdeallocateuninstall');

    Route::post('/getsoftwarelicense', 'Cmdb\SoftwareController@getsoftwarelicense');

    Route::post('/getcitempidsw', 'Cmdb\SoftwareController@getcitempidsw');
    Route::post('/swattachassetsave', 'Cmdb\SoftwareController@swattachassetsave');
    Route::post('/swassetremove', 'Cmdb\SoftwareController@swassetremove');
    Route::post('/getswinstallation', 'Cmdb\SoftwareController@getswinstallation');

    Route::post('/swonassetdashboard', 'Asset\AssetController@swonassetdashboard');

    Route::post('/softwarelicenseallocateadd', 'Cmdb\SoftwareController@softwarelicenseallocateadd');

    /*==================== Reports ==================*/

    Route::post('/reports/export/', 'Reports\ReportsController@exportreport');
    Route::any('/reports/download/', 'Reports\ReportsController@downloadreport');
    Route::get('/reports/details/{report_id}', 'Reports\ReportsController@reportsdetail');
    Route::post('/reports/details/list', 'Reports\ReportsController@reportDetailsList');
    Route::get('/downloadPDF{id?}', 'Reports\ReportsController@downloadPDF');

    Route::post('/getreportmodules', 'Reports\ReportsController@getreportmodules');
    Route::post('/reports/getreportnotifications', 'Reports\ReportsController@getreportnotifications');
    Route::post('/reports/readnotification', 'Reports\ReportsController@readnotification');
    Route::post('getreportformdata', 'Reports\ReportsController@getReportFormData');
    Route::any('/softwaredashboard', 'Cmdb\SoftwareDashboardController@softwaredashboard');

    //Route::get('/software/{software_type_id?}', 'Cmdb\SoftwareController@softwares');
    //Route::get('/softwarem/{software_manufacturer_id?}', 'Cmdb\SoftwareController@softwaresmanufacture');
    Route::get('/software/{type?}/{id?}', 'Cmdb\SoftwareController@softwares');
    Route::post('/swlicensemaxacount', 'Cmdb\SoftwareController@swlicensemaxacount');
    Route::post('/getPurchaseRenderFormData', 'Cmdb\PoController@getPurchaseRenderFormData');
    Route::post('/getitembycategory', 'Asset\AssetController@getitembycategory');
    Route::post('/getprnumberbyvendorid', 'Cmdb\PoOrderController@getprnumberbyvendorid');

});



