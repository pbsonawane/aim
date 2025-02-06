<?php
namespace App\Services\ITAM;

use App\Services\RemoteApi;

class ItamService
{
    public function __construct()
    {
        //$this->remoteapi = $remoteapi;
        $this->remoteapi = new RemoteApi;
        $this->url       = config('enconfig.itamservice_url');
    }
    public function citypes($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'citypes', $options);
        return $data;
    }

    public function skucodes($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'skucodes', $options);
        return $data;
    }

    public function skucodename($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'skucodename', $options);
        return $data;
    }
    
    public function getcitemplates($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'citemplates', $options);
        return $data;
    }

    public function citemplateadd($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'citemplates/add', $options);
        return $data;
    }
    public function updateciname($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'updateciname', $options);
        return $data;
    }

    public function editci($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'citemplates/edit', $options);
        return $data;
    }
    public function updateci($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'citemplates/update', $options);
        return $data;
    }
    public function deleteci($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'citemplates/delete', $options);
        return $data;
    }
    /*=================Asset========================*/

    public function assets($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assets', $options);
        return $data;
    }
    public function getallassets($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'getallassets', $options);
        return $data;
    }
    public function getciitems($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getciitem', $options);
        return $data;
    }
    public function getcitemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getcitemplate', $options);
        return $data;
    }
    public function getallcitemplates($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getallcitemplates', $options);
        return $data;
    }

    public function addasset($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'asset/add', $options);
        return $data;
    }
    public function editasset($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'asset/edit', $options);
        return $data;
    }
    public function editasset_withoutpermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'asset/edit_withoutpermission', $options);
        return $data;
    }
    public function updateasset($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'asset/update', $options);
        return $data;
    }
    public function assetdelete($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'asset/delete', $options);
        return $data;
    }
    public function assethistory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assethistory', $options);
        return $data;
    }
    public function assetcontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assetcontract', $options);
        return $data;
    }
    public function assetdashboard($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'assetdashboard', $options);
        return $data;
    }

    public function attachassetsave($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'assetattach/save', $options);
        return $data;
    }

    public function statuschangesubmit($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'statuschangesubmit', $options);
        return $data;
    }

    public function assetfree($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'assetattach/delete', $options);
        return $data;
    }

    public function get_asset_relationship($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assetrelationship', $options);
        return $data;
    }
    public function deleteassetrelationship($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assetrelationship/delete', $options);
        return $data;
    }
    public function addassetrelationship($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assetrelationship/add', $options);
        return $data;
    }
    public function importsave($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'importsave', $options);
        return $data;
    }

    public function importdata($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'importdata', $options);
        return $data;
    }

    public function importnotification($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'importnotification', $options);
        return $data;
    }

    /*==================Relationship Type===============*/
    public function getrelationshiptype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'relationshiptype', $options);
        return $data;
    }
    public function addrelationshiptype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'relationshiptype/add', $options);
        return $data;
    }
    public function editrelationshiptype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'relationshiptype/edit', $options);
        return $data;
    }
    public function updaterelationshiptype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'relationshiptype/update', $options);
        return $data;
    }
    public function deleterelationshiptype($options)
    {
        //$data = $this->remoteapi->apicall("POST", $this->url, 'relationshiptypedelete', $options);
        $data = $this->remoteapi->apicall("POST", $this->url, 'relationshiptype/delete/' . $options['form_params']['rel_type_id'], $options);

        return $data;
    }

    /*==================Contract Type===============*/
    public function getcontracttype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contracttype', $options);
        return $data;
    }
    public function addcontracttype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contracttype/add', $options);
        return $data;
    }
    public function editcontracttype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contracttype/edit', $options);
        return $data;
    }
    public function updatecontracttype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contracttype/update', $options);
        return $data;
    }
    public function deletecontracttype($options)
    {
        //$data = $this->remoteapi->apicall("POST", $this->url, 'contracttypedelete', $options);
        $data = $this->remoteapi->apicall("POST", $this->url, 'contracttype/delete/' . $options['form_params']['contract_type_id'], $options);

        return $data;
    }
    /*==================Contract ===============*/
    public function getcontract($options)
    {

        if (isset($options['form_params']['contract_id'])) {
            $data = $this->remoteapi->apicall("POST", $this->url, 'contract/' . $options['form_params']['contract_id'], $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'contract', $options);
        }

        return $data;
    }
    public function addcontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contract/add', $options);
        return $data;
    }
    public function editcontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contract/edit', $options);
        return $data;
    }
    public function editcontract_withoutpermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contract/edit_withoutpermission', $options);
        return $data;
    }
    public function updatecontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contract/update', $options);
        return $data;
    }
    public function deletecontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contract/delete/' . $options['form_params']['contract_id'], $options);
        return $data;
    }
    public function getchildcontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'childcontract', $options);
        return $data;
    }
    public function getassociatechildcontract($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'associatechildcontract', $options);
        return $data;
    }
    public function contractupdateassociatechild($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contractupdateassociatechild', $options);
        return $data;
    }
    public function addcontractrenewsubmit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contractrenewsubmit', $options);
        return $data;
    }
    public function getrenewdetails($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'renewdetails', $options);
        return $data;
    }
    public function attachfile($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'add_attachment_contract', $options);
        return $data;
    }
    public function getattachfile($options = array())
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'view_attachment_contract', $options);
        return $data;
    }
    public function contractattachment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'download_attachment_contract', $options);
        return $data;
    }
    public function contractattachment_withoutpermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'download_attachment_contract_withoutpermission', $options);
        return $data;
    }

    public function contracthistorylog($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contracthistorylog', $options);
        return $data;
    }

    public function deletecontractattachment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delete_attachment_contract', $options);
        return $data;
    }

    public function updatecontractstatus($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'update_contract_status', $options);
        return $data;
    }

    /* ================ Quotation Comparison Calls Start ======================*/
    
    /* Each item Quotation Comparison add */
    public function quotation_comparison($options)
    { 
        $data = $this->remoteapi->apicall("POST", $this->url, 'quotation_comparison_save', $options);
        return $data;
    }
    
    public function quotation_comparison_edit($options)
    { 
        $data = $this->remoteapi->apicall("POST", $this->url, 'quotation_comparison_edit', $options);
        return $data;
    }
    
    public function quotation_comparison_details($options)
    { 
        $data = $this->remoteapi->apicall("POST", $this->url, 'quotation_comparison_details', $options);
        return $data;
    }
    
    /* Select items by vendors then submit Quotation Comparisons */
    public function quotation_comparison_final($options)
    { 
        //echo '<pre>'; print_r($options); echo '</pre>';exit;
        $data = $this->remoteapi->apicall("POST", $this->url, 'quotation_comparison_final', $options);
        return $data;
    }
    
    /* Submit Quotation Comparison Reject Submit */
    public function prpoapprovereject_qc($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'prpoapprovereject_qc', $options);
        return $data;
    }
    
    /* Submit Quotation Comparison Approve */
    public function quotation_comparison_approval($options)
    { 
        //echo '<pre>'; print_r($options); echo '</pre>';exit;
        $data = $this->remoteapi->apicall("POST", $this->url, 'quotation_comparison_approval', $options);
        return $data;
    }
    
    /* ================ Quotation Comparison Calls Close ======================*/

    /* ================Purchase Request ======================*/
    public function fileupload_pr_extra($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'add_attachment_extra', $options);
        return $data;
    }
    public function fileupload_pr($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'add_attachment_pr', $options);
        return $data;
    }
    public function fileupload_po($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'add_attachment_po', $options);
        return $data;
    }
    public function downloadattachment_pr($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'download_attachment_pr', $options);
        return $data;
    }
    public function purchaserequests($options)
    {
        if (isset($options['form_params']['pr_id'])) {
            $data = $this->remoteapi->apicall("POST", $this->url, 'purchaserequest/' . $options['form_params']['pr_id'], $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'purchaserequest', $options);
        }
        return $data;
    }
    public function purchaserequestsave($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'purchaserequest/add', $options);
        return $data;
    }
    public function purchaserequestconvertsave($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'purchaserequestconvert/add', $options);
        return $data;
    }
    public function prpoassetdetails($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'prpoassetdetails', $options);
        return $data;
    }

    // this Api use to get the in_stock count based on assetid
    public function prpoassetstockdetails($options)
    {
         $data = $this->remoteapi->apicall("POST", $this->url, 'prpoassetstockdetails',$options);
        return $data;
    }


    public function prconversionassetdetails($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'prconversionassetdetails', $options);
        return $data;
    }
    public function prpoapprovereject($options)
    {
        if (isset($options['form_params']['pr_po_type']) && $options['form_params']['pr_po_type'] == 'pr') {
            $data = $this->remoteapi->apicall("POST", $this->url, 'approve_reject_pr', $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'approve_reject_po', $options);
        }
        return $data;
    }
    public function prpoformActions($options)
    {
        if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'notifyowner') {
            $data = $this->remoteapi->apicall("POST", $this->url, 'notify_owner_email', $options);
        }
        if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'notifyvendor') {
            $data = $this->remoteapi->apicall("POST", $this->url, 'notify_vendor_email', $options);
        }
        if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'notifyagain') {
            $data = $this->remoteapi->apicall("POST", $this->url, 'notifyagain', $options);
        }

        if (isset($options['form_params']['pr_po_type']) && $options['form_params']['pr_po_type'] == 'pr') {
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'close') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'close_pr', $options);
            }
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'cancel') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'cancel_pr', $options);
            }
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'delete') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'purchaserequest/delete', $options);
            }
        }
        if (isset($options['form_params']['pr_po_type']) && $options['form_params']['pr_po_type'] == 'po') {
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'close') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'close_po', $options);
            }
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'cancel') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'cancel_po', $options);
            }
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'delete') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseorder/delete', $options);
            }
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'order') {
                $data = $this->remoteapi->apicall("POST", $this->url, 'order_po', $options);
            }
            // if(isset($options['form_params']['action']) && $options['form_params']['action'] == 'received'){
            //     $data = $this->remoteapi->apicall("POST", $this->url, 'receive_items_po', $options);
            // }
            if (isset($options['form_params']['action']) && $options['form_params']['action'] == 'invoice') {
                if (isset($options['form_params']['invoice_id']) && $options['form_params']['invoice_id'] != '') {
                    $data = $this->remoteapi->apicall("POST", $this->url, 'invoice_po/editsubmit', $options);
                } else {
                    $data = $this->remoteapi->apicall("POST", $this->url, 'invoice_po/addsubmit', $options);
                }

            }
        }

//      $data = $this->remoteapi->apicall("POST", $this->url, 'prpoformActions', $options);

        return $data;
    }
    public function poreceiveditem($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'receive_items_po', $options);
        return $data;
    }
    public function deleteattachment($options)
    {
        if (isset($options['form_params']['attachment_type']) && $options['form_params']['attachment_type'] == 'pr') {
            $data = $this->remoteapi->apicall("POST", $this->url, 'delete_attachment_pr', $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'delete_attachment_po', $options);
        }
        return $data;
    }

    /*===================== Purchase Order  ===========================*/
    public function purchaseorder($options)
    {
        if (isset($options['form_params']['po_id'])) {
            $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseorders/' . $options['form_params']['po_id'], $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseorders', $options);
        }
        return $data;
    }
    public function purchaseordersave($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseorder/add', $options);
        return $data;
    }
    public function purchaseinvoices($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'invoice_po', $options);
        return $data;
    }
    public function poinvoicedelete($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'invoice_po/delete', $options);
        return $data;
    }

    /*===================== Purchase History===========================*/
    public function prpohistorylog($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'prpohistorylog', $options);
        return $data;
    }
    public function getnotifications($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getnotifications', $options);
        return $data;
    }
    /*===================== Purchase Attachment===========================*/
    public function prpoattachment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'prpoattachment', $options);
        return $data;
    }
    /*==================costcenters ===============*/
    public function getcostcenters($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'costcenter', $options);
        return $data;
    }
    // ********************************* SETTINGS TEMPLATE ********************
    /**
     * Function is used to add Settings Template Using FormBuilder from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    //changed in urls  formtemplatedefault with settingstemplates for permissions
    public function addSettingstemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'settingstemplate/add', $options);
        return $data;
    }
    /**
     * Function is used to List Settings Template generated by FormBuilder from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    public function getSettingstemplates($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'settingstemplate', $options);
        return $data;
    }
    /**
     * Function is used to Edit Settings Template Using FormBuilder from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    public function editSettingstemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'settingstemplate/edit/' . $options['form_params']['form_templ_id'], $options);
        return $data;
    }
    /**
     * Function is used to Update Settings Template Using FormBuilder from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    public function updateSettingstemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'settingstemplate/update', $options);
        return $data;
    }
    /**
     * Function is used to Delete Settings Template Using FormBuilder from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    public function deleteSettingstemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'settingstemplate/delete/' . $options['form_params']['form_templ_id'], $options);
        return $data;
    }

    /**
     * This is controller funtion used to clone the Form Template Default from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    public function formtemplatedefaultclone($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'clone/formtemplatedefaultclone/' . $options['form_params']['form_templ_id'], $options);
        return $data;
    }
    //===================== Config SETTING (Mail Server, AdConfig etc)========================
    /**
     * Function is used to get Template JSON based on Template Name from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateConfig
     * @param array $options
     */
    public function getFormTemplateDefaulteConfigbyTemplateName($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefaultebyname/' . $options['form_params']['template_name'], $options);
        return $data;
    }

    /**
     * This Function is used to delete associated asset in contract details page
     * @author Kavita Daware
     * @access public
     * @package mix
     * @param array $options
     */
    public function assetremove($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'remove_asset_contract', $options);
        return $data;
    }
    /*==================vendors ===============*/

    public function getvendors($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendor', $options);
        return $data;
    }
    public function getvendors_withoutpermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendor_withoutpermission', $options);
        return $data;
    }

    public function addvendor($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendor/add', $options);
        return $data;
    }

    public function editvendor($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendor/edit', $options);
        return $data;
    }
    public function updatevendor($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendor/update', $options);
        return $data;
    }
    public function deletevendor($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendor/delete/' . $options['form_params']['vendor_id'], $options);
        return $data;
    }
    // public function contractaction($options)
    // {
    //     $data = $this->remoteapi->apicall("POST", $this->url, 'contractaction', $options);
    //     return $data;
    // }
    public function contractaction_notifyowner($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'notify_owner_contract', $options);
        return $data;
    }
    public function contractaction_notifyvendor($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'notify_vendor_contract', $options);
        return $data;
    }
    /*==================costcenters ===============*/
    public function addcostcenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'costcenter/add', $options);
        return $data;
    }
    public function editcostcenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'costcenter/edit', $options);
        return $data;
    }
    public function updatecostcenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'costcenter/update', $options);
        return $data;
    }
    public function deletecostcenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'costcenter/delete/' . $options['form_params']['cc_id'], $options);
        return $data;
    }

    /*================== Bill To ===============*/
    public function getopportunities($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'opportunity', $options);
        return $data;
    }

    /*================== Bill To ===============*/
    public function getbilltos($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'billto', $options);
        return $data;
    }
    /*public function getbilltos_withoutpermission($options)
    {
    $data = $this->remoteapi->apicall("POST", $this->url, 'billto_withoutpermission', $options);
    return $data;
    }*/

    public function addbillto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'billto/add', $options);
        return $data;
    }

    public function editbillto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'billto/edit', $options);
        return $data;
    }
    public function updatebillto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'billto/update', $options);
        return $data;
    }
    public function deletebillto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'billto/delete/' . $options['form_params']['billto_id'], $options);
        return $data;
    }
    /*================== Ship To ===============*/
    public function getshiptos($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'shipto', $options);
        return $data;
    }
    /*public function getshiptos_withoutpermission($options)
    {
    $data = $this->remoteapi->apicall("POST", $this->url, 'shipto_withoutpermission', $options);
    return $data;
    }*/

    public function addshipto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'shipto/add', $options);
        return $data;
    }

    public function editshipto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'shipto/edit', $options);
        return $data;
    }
    public function updateshipto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'shipto/update', $options);
        return $data;
    }
    public function deleteshipto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'shipto/delete/' . $options['form_params']['shipto_id'], $options);
        return $data;
    }

    /*================== Contacts ===============*/

    public function getcontacts($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact', $options);
        return $data;
    }
   /* public function getcontacts_shipto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact_shipto', $options);
        return $data;
    }
    public function getcontacts_billto($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact_billto', $options);
        return $data;
    }*/
    /*public function getcontacts_withoutpermission($options)
    {
    $data = $this->remoteapi->apicall("POST", $this->url, 'contact_withoutpermission', $options);
    return $data;
    }*/
    public function addcontact($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact/add', $options);
        return $data;
    }
    public function editcontact($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact/edit', $options);
        return $data;
    }
    public function updatecontact($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact/update', $options);
        return $data;
    }
    public function deletecontact($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'contact/delete/' . $options['form_params']['contact_id'], $options);
        return $data;
    }

    /*================== requesternames ===============*/

    public function getrequesternames($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'requestername', $options);
        return $data;
    }
    /*public function getrequesternames_withoutpermission($options)
    {
    $data = $this->remoteapi->apicall("POST", $this->url, 'requestername_withoutpermission', $options);
    return $data;
    }*/
    public function addrequestername($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'requestername/add', $options);
        return $data;
    }
    public function editrequestername($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'requestername/edit', $options);
        return $data;
    }
    public function updaterequestername($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'requestername/update', $options);
        return $data;
    }
    public function deleterequestername($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'requestername/delete/' . $options['form_params']['requestername_id'], $options);
        return $data;
    }

    /********************* Email template*************/
    public function addemailtemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplate/add', $options);
        return $data;
    }
    public function getemailtemplates($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplate', $options);
        return $data;
    }
    public function getetemplatecategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplatecategory', $options);
        return $data;
    }

    public function addemailquote($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailquoteadd', $options);
        return $data;
    }

    public function getemailquotes($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailquotes', $options);
        return $data;
    }
    public function editemailtemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplate/edit', $options);
        return $data;
    }

    public function updateemailtemplate($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplate/update', $options);
        return $data;
    }
    public function deleteemailtemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplate/delete/' . $options['form_params']['template_id'], $options);
        return $data;
    }

    public function updateemailtemplatestatus($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'emailtemplatestatusupdate', $options);
        return $data;
    }

    /*==================Software Types ===============*/
    public function getsoftwaretype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaretype', $options);
        return $data;
    }
    public function addsoftwaretype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaretype/add', $options);
        return $data;
    }
    public function editsoftwaretype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaretype/edit', $options);
        return $data;
    }
    public function updatesoftwaretype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaretype/update', $options);
        return $data;
    }
    public function deletesoftwaretype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaretype/delete/' . $options['form_params']['software_type_id'], $options);
        return $data;
    }

    /*==================Software Category ===============*/
    public function getsoftwarecategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarecategory', $options);
        return $data;
    }
    public function addsoftwarecategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarecategory/add', $options);
        return $data;
    }
    public function editsoftwarecategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarecategory/edit', $options);
        return $data;
    }
    public function updatesoftwarecategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarecategory/update', $options);
        return $data;
    }
    public function deletesoftwarecategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarecategory/delete/' . $options['form_params']['software_category_id'], $options);
        return $data;
    }

    /*==================Software Manufacturer ===============*/
    public function getsoftwaremanufacturer($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaremanufacturer', $options);
        return $data;
    }
    public function addsoftwaremanufacturer($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaremanufacturer/add', $options);
        return $data;
    }
    public function editsoftwaremanufacturer($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaremanufacturer/edit', $options);
        return $data;
    }
    public function updatesoftwaremanufacturer($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaremanufacturer/update', $options);
        return $data;
    }
    public function deletesoftwaremanufacturer($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwaremanufacturer/delete/' . $options['form_params']['software_manufacturer_id'], $options);
        return $data;
    }

    /*==================License Type ===============*/
    public function getlicensetype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'licensetype', $options);
        return $data;
    }
    public function addlicensetype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'licensetype/add', $options);
        return $data;
    }
    public function editlicensetype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'licensetype/edit', $options);
        return $data;
    }
    public function updatelicensetype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'licensetype/update', $options);
        return $data;
    }
    public function deletelicensetype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'licensetype/delete/' . $options['form_params']['license_type_id'], $options);
        return $data;
    }

    /*==================Software ===============*/
    public function getsoftware($options)
    {
        //$data = $this->remoteapi->apicall("POST", $this->url, 'softwares', $options);
        $data = $this->remoteapi->apicall("POST", $this->url, 'software/' . $options['form_params']['software_id'], $options);
        return $data;
    }
    public function getsoftwaremainlist($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'software/mainlist', $options);
        return $data;
    }

    public function addsoftware($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'software/add', $options);
        return $data;
    }
    public function editsoftware($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'software/edit', $options);
        return $data;
    }
    public function updatesoftware($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'software/update', $options);
        return $data;
    }
    public function deletesoftware($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'software/delete/' . $options['form_params']['software_id'], $options);
        return $data;
    }

    /*==================Report Category ===============*/
    public function getreportcategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reportcategory', $options);
        return $data;
    }
    public function addreportcategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reportcategory/add', $options);
        return $data;
    }
    public function editreportcategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reportcategory/edit', $options);
        return $data;
    }
    public function updatereportcategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reportcategory/update', $options);
        return $data;
    }
    public function deletereportcategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reportcategory/delete', $options);
        return $data;
    }
    /*==================Reports ===============*/
    public function getreports($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports', $options);
        return $data;
    }
    public function addreports($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/add', $options);
        return $data;
    }
    public function editreports($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/edit', $options);
        return $data;
    }
    public function updatereports($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/update', $options);
        return $data;
    }
    public function deletereports($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/delete', $options);
        return $data;
    }
    public function getreportdetails($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/details', $options);
        return $data;
    }
    public function exportreport($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/export', $options);
        return $data;
    }
    public function downloadreport($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/download', $options);
        return $data;
    }
    public function getreportnotifications($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/getreportnotifications', $options);
        return $data;
    }
    public function readnotification($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reports/readnotification', $options);
        return $data;
    }
    /*==================Report Modules ===============*/
    public function getreportmodules($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'reportmodules', $options);
        return $data;
    }

    public function getcitempidsoftware($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'getcitempid/' . $options['form_params']['variable_name'], $options);
        return $data;
    }

    public function swattachassetsave($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'swattachassetsave', $options);
        return $data;
    }

    public function getsoftwareinstallation($options)
    {

        $data = $this->remoteapi->apicall("POST", $this->url, 'softwareinstallation', $options);

        return $data;
    }

    /**
     * This Function is used to delete asset in software details page
     * @author Kavita Daware
     * @access public
     * @package mix
     * @param array $options
     */
    public function swassetremove($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swassetremove', $options);
        return $data;
    }
    public function getswhistory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getswhistory', $options);
        return $data;
    }
    /*==================Software License===============*/
    public function getsoftwarelicense($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarelicense', $options);
        //print_r($data);
        return $data;
    }
    public function addsoftwarelicense($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarelicenseadd', $options);
        return $data;
    }
    public function editsoftwarelicense($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarelicenseedit', $options);
        return $data;
    }
    public function updatesoftwarelicense($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarelicenseupdate', $options);
        return $data;

    }
    public function deletesoftwarelicense($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarelicensedelete/' . $options['form_params']['software_license_id'], $options);
        return $data;
    }
    public function softwarelicensellocate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'softwarelicensellocate', $options);
        return $data;
    }
    public function getswallocation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getswallocation', $options);
        return $data;
    }
    public function swallocateassetremove($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swallocateassetremove', $options);
        return $data;
    }
    public function swdeallocateuninstall($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swdeallocateuninstall', $options);
        return $data;
    }
    public function swonassetdashboard($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swonassetdashboard', $options);

        return $data;
    }

    public function getswpurchasecount($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swpurchasecount', $options);

        return $data;
    }

    public function getswallocationlist($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getswallocationlist', $options);
        return $data;
    }

    public function getswdashboard($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swdashboard', $options);
        return $data;
    }

    public function getswdashboardlicense($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swdashboardlicense', $options);
        return $data;
    }

    public function getswdashboardswtype($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swdashboardswtype', $options);
        return $data;
    }

    public function getswallocationallsw($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getswallocationallsw', $options);
        return $data;
    }
    public function getswpurchasecountallsw($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swpurchasecountallsw', $options);

        return $data;
    }
    public function getswdashboardmanufacturer($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swdashboardmanufacturer', $options);
        return $data;
    }

    public function getswlicensemaxacount($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'swlicensemaxacount', $options);
        return $data;
    }

    /*================== paymentterms ===============*/
    public function getpaymentterms($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'paymentterm', $options);
        return $data;
    }
    public function getpaymentterms_withoutpermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'paymentterm_withoutpermission', $options);
        return $data;
    }

    public function addpaymentterm($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'paymentterm/add', $options);
        return $data;
    }

    public function editpaymentterm($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'paymentterm/edit', $options);
        return $data;
    }
    public function updatepaymentterm($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'paymentterm/update', $options);
        return $data;
    }
    public function deletepaymentterm($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'paymentterm/delete/' . $options['form_params']['paymentterm_id'], $options);
        return $data;
    }

    /*================== Delivery ===============*/
    public function getdelivery($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delivery', $options);
        return $data;
    }
    public function getdelivery_withoutpermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delivery_withoutpermission', $options);
        return $data;
    }
    public function adddelivery($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delivery/add', $options);
        return $data;
    }

    public function editdelivery($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delivery/edit', $options);
        return $data;
    }
    public function updatedelivery($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delivery/update', $options);
        return $data;
    }
    public function deletedelivery($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'delivery/delete/' . $options['form_params']['delivery_id'], $options);
        return $data;
    }
    //--------------------------------------Vendor Registration --------------------------------
    /*
    devp: Rahul Badhe
     */
    public function addvendors($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'vendors/add', $options);
        return $data;
    }
    /*
    devp: Rahul Badhe
     */
    public function downloadPo($options)
    {

        if (isset($options['po_id'])) {
            $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseorders/' . $options['po_id'], $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseorders', $options);
        }
        return $data;
    }
    public function generateponumber($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'generateponumber', $options);
        return $data;
    }
    public function generateprnumber($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'generateprnumber', $options);
        return $data;
    }
    public function getvendorbyid($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getvendorbyid', $options);
        return $data;
    }
    public function converttopr($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'converttopr', $options);
        return $data;
    }
    public function assignprtouser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assignprtouser', $options);
        return $data;
    }
    public function purchaseuserdashboard($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'purchaseuserdashboard', $options);
        return $data;
    }
    public function getitembycategory($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getitembycategory', $options);
        return $data;
    }
    public function getprnumberbyvendorid($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getprnumberbyvendorid', $options);
        return $data;
    }

}
