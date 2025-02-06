<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
//$router->post('/api-call', 'cmdb\ExampleController@makeApiCall');
$router->post('storesku', 'cmdb\CiskuController@storesku');
$router->get('/im', 'asset\ImportController@imcall');

$router->post('storesku', 'cmdb\CiskuController@storesku');

$router->get('/getskuitemcount/{sku}', 'asset\AssetController@getskuitemcount');

$router->post('/getusernotification','NotificationController@getusernotification');

$router->post('/getsampleexport', 'cmdb\PurchaseController@sampleprexport');

/* =========================== Opportunity Start ===============================*/

// API's
$router->get('opportunitylisting', 'cmdb\OpportunityListingController@listing');
$router->post('opportunitydetails', 'cmdb\OpportunityDetailsController@getDetails');
$router->post('getDetailsForDB', 'cmdb\OpportunityDetailsController@getDetailsForDB');

//runtime
$router->post('runtimeopportunities', 'cmdb\OpportunityListingController@listing');

//Normal Calls
$router->post('opportunity[/{id}]', 'cmdb\OpportunityController@opportunities');

/* =========================== Opportunity Close ===============================*/

$router->get('/', function () use ($router) {
    ini_set("memory_limit", "-1");
   // print_r(config('enconfig'));
    //save_errlog("enlogcheck_dummy","Added for test purpose from routes.",array('Enlogtest', 'test'), "Enlog Success");
    return $router->app->version();
});

config(['app.host_ip' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '']); //Lumen
config(['app.site_url' => (isset($_SERVER['HTTPS']) ? "https://" : "http://") . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '')]);
$router->get('/testpdf', function () {
    return view('Reports.testpdf', ['name' => 'SK']);
});

$router->post('vendors/add', 'cmdb\VendorRegController@vendorsadd');
$router->post('vendors/generatetoken', 'cmdb\VendorRegController@generatetoken');
$router->post('vendors/verifyotptoken', 'cmdb\VendorRegController@verifyotptoken');
 $router->post('getciitems', 'cmdb\CiTypesController@getciitems');

 $router->post('save_estimatecost', 'cmdb\PurchaseController@save_estimatecost');

$router->group(
    ['middleware' => ['jwt.auth']],
    function () use ($router) {

        /*==================== Common Reports ==================*/
        $router->post('reports/export', 'Reports\ReportsController@export_report');
        $router->post('reports/details', 'Reports\ReportsController@reportsdetail');
        $router->post('/reports/getreportnotifications', 'Reports\ReportsController@getreportnotifications');
        $router->post('/reports/readnotification', 'Reports\ReportsController@readnotification');

        /*==================== PO Reports ==================*/
        $router->post('poreports/details', 'Reports\ReportsController@reportsdetail');

        /*==================== PR Reports ==================*/
        $router->post('prreports/details', 'Reports\ReportsController@reportsdetail');
      

        /*==================== Contracts ==================*/
        $router->post('contract/edit_withoutpermission', 'cmdb\ContractController@contractedit');

        /*==================== Contracts ==================*/
        $router->post('vendor/view[/{vendor_id}]', 'cmdb\VendorController@vendoredit');
       
        $router->post('complaintRaisedAdd', 'cmdb\PurchaseController@complaintRaisedAdd');
        $router->post('complaintraised[/{cr_id}]', 'cmdb\PurchaseController@complaintraised');
        $router->post('complaintraisedDetail', 'cmdb\PurchaseController@complaintraisedDetail');
        $router->post('complaintitremark', 'cmdb\PurchaseController@complaintitremark');
        $router->post('complaintstoreremark', 'cmdb\PurchaseController@complaintstoreremark');
        $router->post('approve_reject_cr', 'cmdb\PurchaseController@approve_reject_cr');
    });
$router->group(
    ['middleware' => ['jwt.auth', 'en.permissions']],
    function () use ($router) {
        /* =========================== citeplates ===============================*/
        $router->post('converttopr', 'cmdb\PurchaseController@converttopr');
        $router->post('citemplates', 'cmdb\CiTypesController@citemplates');
        $router->post('citemplates/add[/{ci_type_id}]', 'cmdb\CiTypesController@citemplateadd');
        $router->post('citemplates/edit[/{ci_templ_id}]', 'cmdb\CiTypesController@editci');
        $router->post('citemplates/update', 'cmdb\CiTypesController@updateci');
        $router->post('citemplates/delete', 'cmdb\CiTypesController@deleteci');

        $router->post('skucodes','cmdb\CiTypesController@skucode');
       

        /* =========================== vendors ===============================*/

        $router->post('vendor/add', 'cmdb\VendorController@vendoradd');
        $router->post('vendor/update', 'cmdb\VendorController@vendorupdate');
        $router->post('vendor/edit[/{vendor_id}]', 'cmdb\VendorController@vendoredit');
        $router->post('vendor/delete[/{vendor_id}]', 'cmdb\VendorController@vendordelete');
        $router->post('vendor[/{vendor_id}]', 'cmdb\VendorController@vendors');
               

        /* =========================== paymentterms ===============================*/

        $router->post('paymentterm/add', 'cmdb\PaymenttermController@paymenttermadd');
        $router->post('paymentterm/update', 'cmdb\PaymenttermController@paymenttermupdate');
        $router->post('paymentterm/edit[/{paymentterm_id}]', 'cmdb\PaymenttermController@paymenttermedit');
        $router->post('paymentterm/delete[/{paymentterm_id}]', 'cmdb\PaymenttermController@paymenttermdelete');
        $router->post('paymentterm[/{paymentterm_id}]', 'cmdb\PaymenttermController@paymentterms');

        /* =========================== Bill To ===============================*/
        $router->post('billto/add', 'cmdb\BilltoController@billtoadd');
        $router->post('billto/update', 'cmdb\BilltoController@billtoupdate');
        $router->post('billto/delete[/{billto_id}]', 'cmdb\BilltoController@billtodelete');
        $router->post('billto/edit[/{billto_id}]', 'cmdb\BilltoController@billtoedit');
        $router->post('billto[/{billto_id}]', 'cmdb\BilltoController@billtos');

        /* =========================== Ship To ===============================*/
        $router->post('shipto/add', 'cmdb\ShiptoController@shiptoadd');
        $router->post('shipto/update', 'cmdb\ShiptoController@shiptoupdate');
        $router->post('shipto/delete[/{shipto_id}]', 'cmdb\ShiptoController@shiptodelete');
        $router->post('shipto/edit[/{shipto_id}]', 'cmdb\ShiptoController@shiptoedit');
        $router->post('shipto[/{shipto_id}]', 'cmdb\ShiptoController@shiptos');

        /* ================================== Contact Master =================================*/
        $router->post('contact/add', 'cmdb\ContactController@contactadd');
        $router->post('contact/update', 'cmdb\ContactController@contactupdate');
        $router->post('contact/delete[/{contact_id}]', 'cmdb\ContactController@contactdelete');
        $router->post('contact/edit[/{contact_id}]', 'cmdb\ContactController@contactedit');
        $router->post('contact[/{contact_id}]', 'cmdb\ContactController@contacts');
        $router->post('contact_shipto[/{contact_id}]', 'cmdb\ContactController@contacts_shipto');
        $router->post('contact_billto[/{contact_id}]', 'cmdb\ContactController@contacts_billto');


        /* ================================== Requestername Master =================================*/
        
        
        
        $router->post('requestername/add', 'cmdb\RequesternameController@requesternameadd');
        $router->post('requestername/update', 'cmdb\RequesternameController@requesternameupdate');
        $router->post('requestername/delete[/{requestername_id}]', 'cmdb\RequesternameController@requesternamedelete');
        $router->post('requestername/edit[/{requestername_id}]', 'cmdb\RequesternameController@requesternameedit');
        $router->post('requestername[/{requestername_id}]', 'cmdb\RequesternameController@requesternames');
        // $router->post('getrequesternames[/{requestername_id}]', 'cmdb\RequesternameController@getrequesternames');
        //$router->post('requestername_shipto[/{requestername_id}]', 'cmdb\requesternameController@requesternames_shipto');
        //$router->post('requestername_billto[/{requestername_id}]', 'cmdb\requesternameController@requesternames_billto');

        /* =========================== Delivery Details ===============================*/
        $router->post('delivery/add', 'cmdb\DeliveryController@deliveryadd');
        $router->post('delivery/update', 'cmdb\DeliveryController@deliveryupdate');
        $router->post('delivery/edit[/{delivery_id}]', 'cmdb\DeliveryController@deliveryedit');
        $router->post('delivery/delete[/{delivery_id}]', 'cmdb\DeliveryController@deliverydelete');
        $router->post('delivery[/{delivery_id}]', 'cmdb\DeliveryController@delivery');

        /* =========================== Cost Centers ===============================*/

        $router->post('costcenter/add', 'cmdb\CostCenterController@costcenteradd');
        $router->post('costcenter/update', 'cmdb\CostCenterController@costcenterupdate');
        $router->post('costcenter/delete[/{cc_id}]', 'cmdb\CostCenterController@costcenterdelete');
        $router->post('costcenter/edit[/{cc_id}]', 'cmdb\CostCenterController@costcenteredit');
        $router->post('costcenter[/{cc_id}]', 'cmdb\CostCenterController@costcenters');

        /*===================================Relationship Type=======================================*/

        $router->post('relationshiptype/add', 'cmdb\RelationshipTypeController@relationshiptypeadd');
        $router->post('relationshiptype/edit', 'cmdb\RelationshipTypeController@relationshiptypeedit');
        $router->post('relationshiptype/update', 'cmdb\RelationshipTypeController@relationshiptypeupdate');
        $router->post('relationshiptype/delete[/{rel_type_id}]', 'cmdb\RelationshipTypeController@relationshiptypedelete');
        $router->post('relationshiptype[/{rel_type_id}]', 'cmdb\RelationshipTypeController@relationshiptypes');

        $router->post('assetrelationship', 'cmdb\RelationshipTypeController@get_asset_relationship');
        $router->post('assetrelationship/delete', 'cmdb\RelationshipTypeController@deleteassetrelationship');
        $router->post('assetrelationship/add', 'cmdb\RelationshipTypeController@addassetrelationship');


        // $router->post('complaintraised[/{cr_id}]', 'asset\CrController@complaintraised');
        
        /*============== Purchase =======================*/
        //Purchase Request
        $router->post('purchaserequest/add', 'cmdb\PurchaseController@purchaserequestadd');
        
        $router->post('approve_reject_pr', 'cmdb\PurchaseController@prpoapprovereject');
        $router->post('approve_reject_po', 'cmdb\PurchaseController@prpoapprovereject');



        $router->post('close_pr', 'cmdb\PurchaseController@prpoformActions');
        
        $router->post('cancel_pr', 'cmdb\PurchaseController@prpoformActions');
        $router->post('close_po', 'cmdb\PurchaseController@prpoformActions');
        $router->post('cancel_po', 'cmdb\PurchaseController@prpoformActions');
        $router->post('order_po', 'cmdb\PurchaseController@prpoformActions');
//        $router->post('receive_items_po', 'cmdb\PurchaseController@prpoformActions');
        $router->post('notify_owner_email', 'cmdb\PurchaseController@prpoformActions');
        $router->post('notify_vendor_email', 'cmdb\PurchaseController@prpoformActions');

        $router->post('purchaserequest/delete', 'cmdb\PurchaseController@prpoformActions');
        $router->post('purchaserequest[/{pr_id}]', 'cmdb\PurchaseController@purchaserequests');

        $router->post('partially_close_pr', 'cmdb\PurchaseController@prpoformActions');
        
        $router->post('add_attachment_pr', 'cmdb\PurchaseController@fileupload');
        $router->post('add_attachment_po', 'cmdb\PurchaseController@fileupload');
        $router->post('download_attachment_pr', 'cmdb\PurchaseController@downloadattachment_pr');
        $router->post('delete_attachment_pr', 'cmdb\PurchaseController@deleteattachment');
        $router->post('delete_attachment_po', 'cmdb\PurchaseController@deleteattachment');

        // Sample PR
        $router->post('purchaserequestsample/add', 'cmdb\PurchaseController@purchaserequestaddsample');

        // Purchase Order
        
        $router->post('purchaseorder/add', 'cmdb\PurchaseOrderController@purchaseorderadd');
        $router->post('purchaseorder/delete', 'cmdb\PurchaseOrderController@prpoformActions');
        $router->post('receive_items_po', 'cmdb\PurchaseOrderController@poreceiveditem');
        $router->post('purchaseorders[/{po_id}]', 'cmdb\PurchaseOrderController@purchaseorders');
        $router->post('assignprtouser', 'cmdb\PurchaseOrderController@assignprtouser');

        $router->post('invoice_po/addsubmit', 'cmdb\PurchaseOrderController@prpoformActions');
        $router->post('invoice_po/editsubmit', 'cmdb\PurchaseOrderController@prpoformActions');
        $router->post('invoice_po/delete', 'cmdb\PurchaseOrderController@poinvoicedelete');
        $router->post('invoice_po', 'cmdb\PurchaseOrderController@purchaseinvoices');

        /*=================================== Assets =======================================*/

        $router->post('asset/add', 'asset\AssetController@addasset');
        $router->post('asset/edit', 'asset\AssetController@editasset');
        $router->post('asset/update', 'asset\AssetController@updateasset');
        $router->post('asset/delete', 'asset\AssetController@assetdelete');

        $router->post('assetattach/save', 'asset\AssetController@attachassetsave');
        $router->post('assetattach/delete', 'asset\AssetController@assetfree');
        

        /*=================================== Contract Type =======================================*/

        $router->post('contracttype/add', 'cmdb\ContractTypeController@contracttypeadd');
        $router->post('contracttype/edit', 'cmdb\ContractTypeController@contracttypeedit');
        $router->post('contracttype/update', 'cmdb\ContractTypeController@contracttypeupdate');
        $router->post('contracttype/delete[/{contract_type_id}]', 'cmdb\ContractTypeController@contracttypedelete');
        $router->post('contracttype[/{contract_type_id}]', 'cmdb\ContractTypeController@contractstype');

        /*===================================Contracts=======================================*/

        $router->post('contract/add', 'cmdb\ContractController@contractadd');
        $router->post('contract/edit', 'cmdb\ContractController@contractedit');
        $router->post('contract/update', 'cmdb\ContractController@contractupdate');

        $router->post('add_attachment_contract', 'cmdb\ContractController@attachfile');
        $router->post('view_attachment_contract', 'cmdb\ContractController@getattachfile');
        $router->post('download_attachment_contract', 'cmdb\ContractController@contractattachment');
        $router->post('delete_attachment_contract', 'cmdb\ContractController@deletecontractattachment');

        $router->post('remove_asset_contract', 'cmdb\ContractController@assetremove');

        $router->post('contract/delete[/{contract_id}]', 'cmdb\ContractController@contractdelete');
        $router->post('contract[/{contract_id}]', 'cmdb\ContractController@contracts');

        /* =========================== FormTemplateDefaultController ================================*/

        $router->post('settingstemplate/add', 'cmdb\FormTemplateDefaultController@formtemplatedefaultadd');

        $router->post('settingstemplate/delete[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultdelete');

        $router->post('settingstemplate/edit[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultedit');

        $router->post('settingstemplate/update', 'cmdb\FormTemplateDefaultController@formtemplatedefaultupdate');

        $router->post('settingstemplate[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefault');

        /*=========================== Software Type ================================*/

        $router->post('softwaretype/add', 'cmdb\SoftwareTypeController@softwaretypeadd');
        $router->post('softwaretype/edit', 'cmdb\SoftwareTypeController@softwaretypeedit');
        $router->post('softwaretype/update', 'cmdb\SoftwareTypeController@softwaretypeupdate');
        $router->post('softwaretype/delete[/{software_type_id}]', 'cmdb\SoftwareTypeController@softwaretypedelete');
        $router->post('softwaretype[/{software_type_id}]', 'cmdb\SoftwareTypeController@softwaretype');

        /*=========================== Software Catgory ================================*/

        $router->post('softwarecategory/add', 'cmdb\SoftwareCategoryController@softwarecatgoryadd');
        $router->post('softwarecategory/edit', 'cmdb\SoftwareCategoryController@softwarecatgoryedit');
        $router->post('softwarecategory/update', 'cmdb\SoftwareCategoryController@softwarecatgoryupdate');
        $router->post('softwarecategory/delete[/{software_category_id}]', 'cmdb\SoftwareCategoryController@softwarecatgorydelete');
        $router->post('softwarecategory[/{software_category_id}]', 'cmdb\SoftwareCategoryController@softwarecatgory');

        /*=========================== email template ================================*/

        $router->post('emailtemplate/add', 'emailtemplate\EmailTemplateController@emailtemplateadd');
        $router->post('emailtemplate/update', 'emailtemplate\EmailTemplateController@emailtemplateupdate');
        $router->post('emailtemplate/edit[/{template_id}]', 'emailtemplate\EmailTemplateController@emailtemplateedit');
        $router->post('emailtemplate/delete[/{template_id}]', 'emailtemplate\EmailTemplateController@emailtemplatedelete');
        $router->post('emailtemplate[/{template_id}]', 'emailtemplate\EmailTemplateController@emailtemplates');

        /*=========================== Software Manufacturer ================================*/

        $router->post('softwaremanufacturer/add', 'cmdb\SoftwareManufacturerController@softwaremanufactureradd');
        $router->post('softwaremanufacturer/edit', 'cmdb\SoftwareManufacturerController@softwaremanufactureredit');
        $router->post('softwaremanufacturer/update', 'cmdb\SoftwareManufacturerController@softwaremanufacturerupdate');
        $router->post('softwaremanufacturer/delete[/{software_manufacturer_id}]', 'cmdb\SoftwareManufacturerController@softwaremanufacturerdelete');
        $router->post('softwaremanufacturer[/{software_manufacturer_id}]', 'cmdb\SoftwareManufacturerController@softwaremanufacturer');

        /*=========================== License Types ================================*/

        $router->post('licensetype/add', 'cmdb\LicenseTypeController@licensetypeadd');
        $router->post('licensetype/edit', 'cmdb\LicenseTypeController@licensetypeedit');
        $router->post('licensetype/update', 'cmdb\LicenseTypeController@licensetypeupdate');
        $router->post('licensetype/delete[/{license_type_id}]', 'cmdb\LicenseTypeController@licensetypedelete');
        $router->post('licensetype[/{license_type_id}]', 'cmdb\LicenseTypeController@licensetype');

        /*=========================== Softwares ================================*/

        $router->post('software/mainlist', 'cmdb\SoftwareController@softwares');
        $router->post('software/add', 'cmdb\SoftwareController@softwareadd');
        $router->post('software/edit', 'cmdb\SoftwareController@softwareedit');
        $router->post('software/update', 'cmdb\SoftwareController@softwareupdate');
        $router->post('software/delete[/{software_id}]', 'cmdb\SoftwareController@softwaredelete');
        $router->post('software[/{software_id}]', 'cmdb\SoftwareController@softwares');

        /*========================= Report Category========================*/
        $router->post('reportcategory/add', 'Reports\ReportCategoryController@reportcategoryadd');
        $router->post('reportcategory/edit', 'Reports\ReportCategoryController@reportcategoryedit');
        $router->post('reportcategory/update', 'Reports\ReportCategoryController@reportcategoryupdate');
        $router->post('reportcategory/delete[/{report_cat_id}]', 'Reports\ReportCategoryController@reportcategorydelete');
        $router->post('reportcategory[/{report_cat_id}]', 'Reports\ReportCategoryController@reportcategory');

        /*========================= Comman Reports========================*/
        $router->post('reports/add', 'Reports\ReportsController@reportsadd');
        $router->post('reports/edit', 'Reports\ReportsController@reportsedit');
        $router->post('reports/update', 'Reports\ReportsController@reportsupdate');
        $router->post('reports/download', 'Reports\ReportsController@download');
        $router->post('reports/delete[/{report_id}]', 'Reports\ReportsController@reportsdelete');
        $router->post('reports[/{report_id}]', 'Reports\ReportsController@reports');
        
        // Dashboard
        $router->post('purchaseuserdashboard', 'cmdb\PurchaseController@purchaseuserdashboard');
        
    }
);

$router->group(
    ['middleware' => ['jwt.auth']],
    function () use ($router) {
        $router->post('getRequester', 'cmdb\RequesternameController@getRequester');
        $router->post('getAssetDetail', 'cmdb\RequesternameController@getAssetDetail');
        $router->post('getvendorbyservices', 'cmdb\VendorController@getvendorbyservices');
        $router->post('getvendorservices', 'cmdb\VendorController@getvendorservices');
        $router->post('getrequesternames[/{requestername_id}]', 'cmdb\RequesternameController@getrequesternames');
        /*==========================Power BI Reports==============================*/
        $router->post('downloadpbireport', 'Reports\ReportsController@downloadpbireport');
        
        $router->post('inStoreAssetCount', 'cmdb\PurchaseOrderController@inStoreAssetCount');
        $router->post('assetskuunit', 'cmdb\PurchaseOrderController@assetskuunit');
        $router->post('getProject', 'cmdb\PurchaseController@getProject');
        $router->post('checkPOisGeneratedOrNot', 'cmdb\PurchaseOrderController@checkPOisGeneratedOrNot');
        $router->post('getvendorsinquotation', 'cmdb\VendorController@getvendorsinquotation');
        
        $router->post('purchaserequestconvert/add', 'cmdb\PurchaseController@purchaserequestconvertadd');
        /* =========================== FormTemplateDefaultController ================================*/
        $router->post('contact[/{contact_id}]', 'cmdb\ContactController@contacts');
        $router->post('prconversionassetdetails', 'cmdb\PurchaseController@prconversionassetdetails');
        $router->post('converttopr', 'cmdb\PurchaseController@converttopr');
        $router->post('formtemplatedefault[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefault');
        $router->post('formtemplatedefaultadd', 'cmdb\FormTemplateDefaultController@formtemplatedefaultadd');
        $router->post('formtemplatedefaultdelete[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultdelete');
        $router->post('formtemplatedefaultedit[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultedit');
        $router->post('formtemplatedefaultupdate', 'cmdb\FormTemplateDefaultController@formtemplatedefaultupdate');
        $router->post('formtemplatedefaultebyname[/{template_name}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultebyname');
        $router->post('formtemplatedefaultebytype[/{type}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultebytype');
        $router->post('formtemplatedefaultclone[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultclone');

        /* =========================== FormTemplateCustfiledsController ===============================*/

        $router->post('formtemplatecustomfields[/{form_templ_id}]', 'cmdb\FormTemplateCustfiledsController@formtemplatecustomfields');
        $router->post('formtemplatecustomfieldsadd', 'cmdb\FormTemplateCustfiledsController@formtemplatecustomfieldsadd');
        $router->post('formtemplatecustomfieldsdelete[/{form_templ_id}]', 'cmdb\FormTemplateCustfiledsController@formtemplatecustomfieldsdelete');
        $router->post('formtemplatecustomfieldsedit[/{form_templ_id}]', 'cmdb\FormTemplateCustfiledsController@formtemplatecustomfieldsedit');
        $router->post('formtemplatecustomfieldsupdate', 'cmdb\FormTemplateCustfiledsController@formtemplatecustomfieldsupdate');

        /*=================================== Assets =======================================*/



        $router->post('assets[/{asset_id}]', 'asset\AssetController@assets');
        $router->post('assetdashboard', 'asset\AssetController@assetdashboard');

        /*License Dashboard*/
          $router->post('licensedashboard', 'asset\AssetController@licensedashboard');
        /*------------------*/
        $router->post('assetcontract', 'asset\AssetController@assetcontract');
        $router->post('statuschangesubmit', 'asset\AssetController@statuschangesubmit');
        $router->post('importsave', 'asset\AssetController@importsave');
        $router->post('importdata', 'asset\ImportController@importdata');
        $router->post('assethistory', 'asset\AssetHistoryController@assethistory');
        $router->post('importprocess', 'asset\AssetController@importprocess');
        $router->post('importnotification', 'asset\ImportController@importnotification');
        $router->post('assignassethistory', 'asset\AssetHistoryController@assignassethistory');

        $router->post('asset/edit_withoutpermission', 'asset\AssetController@editasset');

        /*=================================== Vendor =======================================*/

        $router->post('vendor_withoutpermission[/{vendor_id}]', 'cmdb\VendorController@vendors');

        $router->post('getpaymentterms_withoutpermission[/{paymentterm_id}]', 'cmdb\PaymenttermController@paymentterms');

        /*===================================Contracts=======================================*/

        $router->post('associatechildcontract', 'cmdb\ContractController@associatechildcontract');
        $router->post('contractupdateassociatechild', 'cmdb\ContractController@contractupdateassociatechild');
        $router->post('contractrenewsubmit', 'cmdb\ContractController@contractrenewsubmit');
        $router->post('renewdetails', 'cmdb\ContractController@renewdetails');
        $router->post('childcontract[/{contract_id}]', 'cmdb\ContractController@childcontract');
        $router->post('contracthistorylog', 'cmdb\ContractController@contracthistorylog');

        $router->post('notify_owner_contract', 'cmdb\ContractController@contractaction');
        $router->post('notify_vendor_contract', 'cmdb\ContractController@contractaction');

        $router->post('getallassets', 'cmdb\ContractController@getallassets');
        $router->post('update_contract_status', 'cmdb\ContractController@updatecontractstatus');

        $router->post('download_attachment_contract_withoutpermission', 'cmdb\ContractController@contractattachment');

        /* =========================== FormTemplateDefaultController ================================*/

        $router->post('clone/formtemplatedefaultclone[/{form_templ_id}]', 'cmdb\FormTemplateDefaultController@formtemplatedefaultclone');

        /* =========================== citeplates ===============================*/

        $router->post('getcitemplate[/{cidata}]', 'cmdb\CiTypesController@getcitemplate');
        $router->post('getciitem', 'cmdb\CiTypesController@getciitems');

        $router->post('citypes[/{ci_type_id}]', 'cmdb\CiTypesController@citypes');
        $router->post('getallcitemplates', 'cmdb\CiTypesController@getallcitemplates');
        $router->post('updateciname[/{cidata}]', 'cmdb\CiTypesController@updateciname');

        /*=========================== email template ================================*/

        $router->post('emailquoteadd', 'emailtemplate\EmailTemplateController@emailquoteadd');
        $router->post('emailtemplatestatusupdate', 'emailtemplate\EmailTemplateController@emailtemplatestatusupdate');
        $router->post('emailquotes[/{quote_id}]', 'emailtemplate\EmailTemplateController@emailquotes');
        $router->post('emailtemplatecategory[/{template_id}]', 'emailtemplate\EmailTemplateController@emailtemplatecategory');

        /*========================= Purchase ========================*/
        
        $router->post('notifyagain', 'cmdb\PurchaseController@prpoformActions');
        $router->post('prpoassetdetails', 'cmdb\PurchaseController@prpoassetdetails');
        $router->post('prdetails[/{pr_id}]', 'cmdb\PurchaseController@prdetails');
        $router->post('getnotifications', 'cmdb\PurchaseController@getnotifications');

        $router->post('prpoassetstockdetails', 'cmdb\PurchaseController@prpoassetstockdetails');

        //prpohistory
        $router->post('prpohistoryadd', 'cmdb\PurchaseController@prpohistoryadd');
        $router->post('prpohistorylog', 'cmdb\PurchaseController@prpohistorylog');
        $router->post('prpoattachment', 'cmdb\PurchaseController@prpoattachment');

        /*========================= Report Modules========================*/

        $router->post('reportmodules', 'Reports\ReportsController@getreportmodules');

        /*=========================== Softwares ================================*/

        $router->post('getswhistory[/{software_id}]', 'cmdb\SoftwareController@getswhistory');

        $router->post('getcitempid[/{variable_name}]', 'cmdb\SoftwareController@getcitempid');
        $router->post('swattachassetsave', 'cmdb\SoftwareController@swattachassetsave');
        $router->post('softwareinstallation', 'cmdb\SoftwareController@softwareinstallation');
        $router->post('swassetremove', 'cmdb\SoftwareController@swassetremove');

        $router->post('softwarelicensellocate', 'cmdb\SoftwareLicenseController@softwarelicensellocate');
        $router->post('getswallocation', 'cmdb\SoftwareLicenseController@getswallocation');
        $router->post('swallocateassetremove', 'cmdb\SoftwareLicenseController@swallocateassetremove');
        $router->post('swdeallocateuninstall', 'cmdb\SoftwareLicenseController@swdeallocateuninstall');
        $router->post('swonassetdashboard', 'cmdb\SoftwareLicenseController@swonassetdashboard');
        $router->post('swpurchasecount', 'cmdb\SoftwareLicenseController@swpurchasecount');

        /*========================= Softwares License ========================*/

        $router->post('softwarelicense[/{software_id}]', 'cmdb\SoftwareLicenseController@softwarelicense');
        $router->post('softwarelicenseadd', 'cmdb\SoftwareLicenseController@softwarelicenseadd');
        $router->post('softwarelicenseedit', 'cmdb\SoftwareLicenseController@softwarelicenseedit');
        $router->post('softwarelicenseupdate', 'cmdb\SoftwareLicenseController@softwarelicenseupdate');
        $router->post('softwarelicensedelete[/{software_license_id}]', 'cmdb\SoftwareLicenseController@softwarelicensedelete');

        $router->post('swdashboardlicense', 'cmdb\SoftwareController@swdashboardlicense');
        //$router->post('swdashboard', 'cmdb\SoftwareController@swdashboard');
        $router->post('swdashboardswtype', 'cmdb\SoftwareController@swdashboardswtype');
        $router->post('getswallocationallsw', 'cmdb\SoftwareLicenseController@getswallocationallsw');
        $router->post('swpurchasecountallsw', 'cmdb\SoftwareLicenseController@swpurchasecountallsw');
        $router->post('swdashboardmanufacturer', 'cmdb\SoftwareController@swdashboardmanufacturer');
        $router->post('swdashboard', 'cmdb\SoftwareController@swdashboard');
        $router->post('swlicensemaxacount', 'cmdb\SoftwareLicenseController@swlicensemaxacount');
        $router->post('generateponumber', 'cmdb\PurchaseController@generateponumber');
        $router->post('generateprnumber', 'cmdb\PurchaseController@generateprnumber');
        $router->post('getvendorbyid', 'cmdb\PurchaseController@getvendorbyid');
        $router->post('assetTracking', 'asset\AssetController@assetTracking');
        $router->post('assetTrackingByEmpId', 'asset\AssetController@assetTrackingByEmpId');
        $router->post('getprnumberbyvendorid', 'cmdb\PurchaseOrderController@getprnumberbyvendorid');
        $router->post('getassetsbyskus', 'asset\AssetController@getassetsbyskus');
        $router->post('resendtoapproval', 'cmdb\PurchaseOrderController@resendtoapproval');

        $router->post('syncrequesteruser', 'cmdb\RequesternameController@syncrequesteruser');
    });
        
        $router->post('getIssueAsset', 'cmdb\PurchaseController@getIssueAsset');
        $router->post('generatecrnumber', 'cmdb\PurchaseController@generatecrnumber');

        $router->post('swreport', 'cmdb\SoftwareController@swreport');
        $router->post('getswallocation', 'cmdb\SoftwareLicenseController@getswallocation');

        // PR File Upload
        $router->post('add_attachment_extra', 'cmdb\PurchaseController@fileupload_pr_extra');

        // Quotation Comparison
        $router->post('quotation_comparison_save', 'cmdb\PurchaseController@quotation_comparison_save');
        $router->post('quotation_comparison_edit', 'cmdb\PurchaseController@quotation_comparison_edit');
        $router->post('quotation_comparison_details', 'cmdb\PurchaseController@quotation_comparison_details');
        $router->post('quotation_comparison_final', 'cmdb\PurchaseController@quotation_comparison_final');
        $router->post('prpoapprovereject_qc', 'cmdb\PurchaseController@prpoapprovereject_qc');
        $router->post('quotation_comparison_approval', 'cmdb\PurchaseController@quotation_comparison_approval');

        $router->post('getitembycategory', 'asset\AssetController@getitembycategory');
        $router->post('skucodename','cmdb\CiTypesController@skucodename');

        $router->post('crm_purchaserequest', 'cmdb\PurchaseController@crm_purchaserequestadd');

        $router->post('sd_purchaserequest', 'cmdb\PurchaseController@sd_purchaserequestadd');

        $router->post('approvereject_vendor', 'cmdb\VendorController@approvereject_vendor');

        $router->post('trackpurchaserequest', 'cmdb\PurchaseController@trackpurchaserequest');

        $router->post('addremark', 'cmdb\PurchaseController@addremark');

        $router->post('add_poremark', 'cmdb\PurchaseController@add_poremark');

    $router->post('track_pr_list', 'cmdb\PurchaseController@track_pr_list');

        $router->post('track_po_list', 'cmdb\PurchaseOrderController@track_po_list');

        $router->post('track_pr_list_for_export', 'cmdb\PurchaseController@track_pr_list_for_export');

	  /** New Route For Project Costing API **/
  $router->post('track_api_list', 'cmdb\PurchaseOrderController@track_api_list');




        $router->post('track_cr_list', 'cmdb\PurchaseController@track_cr_list');

        $router->post('reports/download', 'Reports\ReportsController@download');

        $router->post('download_vendorattachment', 'cmdb\VendorController@download_vendorattachment');

        $router->post('download_complaintattachment', 'cmdb\PurchaseController@download_complaintattachment');
        
        $router->post('licensedashboard', 'cmdb\SoftwareController@licensedashboard');
        $router->post('databasedashboard', 'cmdb\SoftwareController@databasedashboard');
        $router->post('cpaneldashboard', 'cmdb\SoftwareController@cpaneldashboard');

         $router->post('storedashboard', 'cmdb\SoftwareController@getstoredashboard');

        /*********Start of PurchasePR Request*********/

        $router->post('purchaseprreport','cmdb\PurchaseController@purchaseprreport');

        /***********End of Purchase PR Reqest********/
        
       // $router->post('licenseParticular','cmdb\SoftwareController@licenseParticular');


      