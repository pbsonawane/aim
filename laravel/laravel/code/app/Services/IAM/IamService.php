<?php
namespace App\Services\IAM;

use App\Services\RemoteApi;

class IamService
{
    public function __construct()
    {
        //$this->remoteapi = $remoteapi;
        $this->remoteapi = new RemoteApi;
        $this->url = config('enconfig.iam_service_apiurl');
    }
    /**
     * Function is used to authenticate user
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @param array $options
     */

    public function updateDeptBalBudg($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'updateDeptBalBudg', $options);
        return $data;
    }

    public function auth($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'auth/login', $options);
        return $data;
    }
    /**
     * Function is used to valiadate IP at the time of user login
     * @author Namrata Thakur
     * @access public
     * @package Authenticate
     * @param array $options
     */
    public function whitelistipvalidate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'whitelistipvalidate', $options);
        return $data;
    }
    //Datacenters

    /**
     * Function is used get datacenter List
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param array $options
     */
    public function getDatacenters($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'datacenters', $options);
        return $data;
    }
    /**
     * Function is used add new datacenter
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param array $options
     */
    public function addDatacenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'datacenteradd', $options);
        return $data;
    }
    /**
     * Function is used get datacenter data for edit datacenter
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param array $options
     */
    public function editDatacenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'datacenteredit/' . $options['form_params']['dc_id'], $options);
        return $data;
    }

    /**
     * Function is used to update datcenter data
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param array $options
     */

    public function updateDatacenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'datacenterupdate', $options);
        return $data;
    }
    /**
     * Function is used to remove datacenter data
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param array $options
     */

    public function deleteDatacenter($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'datacenterdelete/' . $options['form_params']['dc_id'], $options);
        return $data;
    }

    //Locations
    /**
     * Function is used to get all location List
     * @author Amit Khairnar
     * @access public
     * @package location
     * @param array $options
     */
    public function getLocations($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'locations', $options);
        return $data;
    }
    /**
     * Function is used to add new location in database
     * @author Amit Khairnar
     * @access public
     * @package location
     * @param array $options
     */
    public function addlocation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'locationadd', $options);
        return $data;
    }
    /**
     * Function is used to get lodation data for edit location
     * @author Amit Khairnar
     * @access public
     * @package location
     * @param array $options
     */
    public function editLocation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'locationedit/' . $options['form_params']['loc_id'], $options);
        return $data;
    }
    /**
     * Function is used to update location data in database
     * @author Amit Khairnar
     * @access public
     * @package location
     * @param array $options
     */
    public function updateLocation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'locationupdate', $options);
        return $data;
    }
    /**
     * Function is used to delete location data.
     * @author Amit Khairnar
     * @access public
     * @package location
     * @param array $options
     */
    public function deleteLocation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'locationdelete/' . $options['form_params']['loc_id'], $options);
        return $data;
    }
    // *****************************  REGION CALLS  ************************
    public function getRegions($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'regions', $options);
        return $data;
    }
    public function addRegion($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'regionadd', $options);
        return $data;
    }
    public function updateRegion($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'regionupdate', $options);
        return $data;
    }
    public function getRegionDc($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getdcbyregions', $options);
        return $data;
    }
    public function dcRegions($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'dcregions', $options);
        return $data;
    }
    public function saveRegionDc($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assigndcregions', $options);
        return $data;
    }

    // ************************************ Region Class End ***********************
    // *****************************  Module CALLS  ************************
    public function getModules($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'modules', $options);
        return $data;
    }
    public function addModule($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'moduleadd', $options);
        return $data;
    }
    public function updateModule($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'moduleupdate', $options);
        return $data;
    }
    public function deleteModule($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'moduledelete', $options);
        return $data;
    }

    public function getUserModules($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'usermodules', $options);
        return $data;
    }

    // ************************************ Module Class End ***********************

    // *****************************  Module CALLS  ************************
    public function getPods($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'pods', $options);
        return $data;
    }
    public function addPod($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'podadd', $options);
        return $data;
    }
    public function updatePod($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'podupdate', $options);
        return $data;
    }
    public function deletePod($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'poddelete', $options);
        return $data;
    }

    // ************************************ Module Class End ***********************

    // ********************************* SETTINGS TEMPLATE ********************
    /**
     * Function is used to add Settings Template Using FormBuilder from API request
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplateFormTemplateDefault
     * @param array $options
     */
    public function addSettingstemplate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefaultadd', $options);
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
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefault', $options);
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
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefaultedit/' . $options['form_params']['form_templ_id'], $options);
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
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefaultupdate', $options);
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
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefaultdelete/' . $options['form_params']['form_templ_id'], $options);
        return $data;
    }
    // ********************************* CREDENTIALS ********************
    /**
     * Function is used to get All Credential's Templates List from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateCredential
     * @param array $options
     */
    public function getcredentials($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getallformdatacredentials', $options);
        return $data;
    }
    /**
     * Function is used to get Template Credential Data based on Config Id from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateCredential
     * @param array $options
     */
    public function getformdatacredential($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getformdatacredential/' . $options['form_params']['config_id'], $options);
        return $data;
    }
    /**
     * Function is used to delete Credential data Based on config Id from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateCredential
     * @param array $options
     */
    public function deletecredential($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formdatacredentialdelete/' . $options['form_params']['config_id'], $options);
        return $data;
    }
    /**
     * Function is used to get Form Template Information based on Template Type: 'cr' for listing page from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateCredential
     * @param array $options
     */
    public function getFormTemplateDefaulteCredentialbyType($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formtemplatedefaultebytype/' . $options['form_params']['type'], $options);
        return $data;
    }
    /**
     * Function is used to add Credential's Template Form data from API request.
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateCredential
     * @param array $options
     */
    public function formdatacredentialadd($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formdatacredentialadd', $options);
        return $data;
    }
    /**
     * Function is used to Update Credential's Template Form data Based on config Id from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateCredential
     * @param array $options
     */
    public function formdatacredentialupdate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formdatacredentialupdate', $options);
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
     * Function is used to get Form Template Data based on Template Name from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateConfig
     * @param array $options
     */
    public function getFormDataConfig($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getformdataconfig/' . $options['form_params']['config_id'], $options);
        return $data;
    }
    /**
     * Function is used to Update / add Form Template Data based on Template Name from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateConfig
     * @param array $options
     */
    public function formdataconfigupdate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'formdataconfigupdate', $options);
        return $data;
    }
    /**
     * Function is used to upload file Tempalte Config(Mail server setting) from API request
     * @author Namrata Thakur
     * @access public
     * @package FormTemplateConfig
     * @param array $options
     */
    public function formdataconfigUploadfile($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'uploadproductlogo', $options);
        return $data;
    }
    //================= BUSINESS UNIT==========================================
    public function usermoduleupdate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'usermoduleupdate', $options);
        return $data;
    }
    /**
     * Function is used to get business unit from API request
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param array $options
     */
    public function getBusinessunit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessunits', $options);
        return $data;
    }
    /**
     * Function is used to get businessunit add form from API request
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param array $options
     */
    public function addBusinessunit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessunitadd', $options);
        return $data;
    }
    /**
     * Function is used to get business unit edit form from API request
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param array $options
     */
    public function editBusinessunit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessunitedit/' . $options['form_params']['bu_id'], $options);
        return $data;
    }
    /**
     * Function is used to update selected business unit details
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param array $options
     */
    public function updateBusinessunit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessunitupdate', $options);
        return $data;
    }
    /**
     * Function is used to delete selected Business Unit
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param array $options
     */
    public function deleteBusinessunit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessunitdelete/' . $options['form_params']['bu_id'], $options);
        return $data;
    }
    //========================================BUSINESS VERTICAL=====================================
    /**
     * Function is used to get business vertical from API request
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param array $options
     */
    public function getBusinessVertical($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessverticals', $options);
        return $data;
    }
    /**
     * Function is used to get businessvertical add form from API request
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param array $options
     */
    public function addBusinessvertical($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessverticaladd', $options);
        return $data;
    }
    /**
     * Function is used to get business verticals edit form from API request
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param array $options
     */
    public function editBusinessvertical($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessverticaledit/' . $options['form_params']['bv_id'], $options);
        return $data;
    }
    /**
     * Function is used to update selected business vertical details
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param array $options
     */
    public function updateBusinessvertical($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessverticalupdate', $options);
        return $data;
    }
    /**
     * Function is used to delete selected Business Vertical
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param array $options
     */
    public function deleteBusinessvertical($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'businessverticaldelete/' . $options['form_params']['bv_id'], $options);
        return $data;
    }
    //========================================DEPARTMENT============================================
    /**
     * Function is used to get departments from API request
     * @author Kavita Daware
     * @access public
     * @package department
     * @param array $options
     */
    public function getDepartment($options)
    {
        if (isset($options['form_params']['department_id'])) {
            $data = $this->remoteapi->apicall("POST", $this->url, 'departments/' . $options['form_params']['department_id'], $options);
        } else {
            $data = $this->remoteapi->apicall("POST", $this->url, 'departments', $options);
        }
        return $data;
    }
    /**
     * Function is used to get department add form from API request
     * @author Kavita Daware
     * @access public
     * @package department
     * @param array $options
     */
    public function addDepartment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'departmentadd', $options);
        return $data;
    }
    /**
     * Function is used to get department edit form from API request
     * @author Kavita Daware
     * @access public
     * @package department
     * @param array $options
     */
    public function editDepartment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'departmentsedit/' . $options['form_params']['department_id'], $options);
        return $data;
    }
    /**
     * Function is used to update selected department details
     * @author Kavita Daware
     * @access public
     * @package department
     * @param array $options
     */
    public function updateDepartment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'departmentupdate', $options);
        return $data;
    }

    /**
     * Function is used to delete selected department
     * @author Kavita Daware
     * @access public
     * @package department
     * @param array $options
     */
    public function deleteDepartment($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'departmentdelete/' . $options['form_params']['department_id'], $options);
        return $data;
    }

    //===========================================Organization==========================================
    /**
     * Function is used to get organization from API request
     * @author Kavita Daware
     * @access public
     * @package organization
     * @param array $options
     */
    public function getOrg($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getOrg', $options);
        return $data;
    }
    /**
     * Function is used to get organization add form from API request
     * @author Kavita Daware
     * @access public
     * @package organization
     * @param array $options
     */
    public function createOrg($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'createOrg', $options);
        return $data;
    }
    //===============================================================================================
    /**
     * Function is used to get roles from API request
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param array $options
     */
    public function getRoles($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'roles', $options);
        return $data;
    }
    /**
     * Function is used to get roles add form from API request
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param array $options
     */
    public function addRole($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'roleadd', $options);
        return $data;
    }
    /**
     * Function is used to get roles edit form from API request
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param array $options
     */
    public function editRole($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'roleedit', $options);
        return $data;
    }
    /**
     * Function is used to update selected role details
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param array $options
     */
    public function updateRole($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'roleupdate', $options);
        return $data;
    }

    /**
     * Function is used to delete selected role
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param array $options
     */
    public function deleterole($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'roledelete', $options);
        return $data;
    }
    /**
     * Function is used to get all Permissions by its PermissionCategories & permissions assigns to role.
     * @author Namrata Thakur
     * @access public
     * @package role
     * @param array $options
     */
    public function rolepermissions($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'rolepermissions/' . $options['form_params']['role_id'], $options);
        return $data;
    }

    /**
     * Function is used to Update all Permissions by its PermissionCategories & permissions assigns to role.
     * @author Namrata Thakur
     * @access public
     * @package role
     * @param array $options
     */
    public function assignrolepermissions($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'assignrolepermissions', $options);
        return $data;
    }
    /**
     * Function is used to get designations from API request
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function getDesignations($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'designations', $options);
        return $data;
    }
    /**
     * Function is used to get designations add form from API request
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function addDesignation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'designationadd', $options);
        return $data;
    }
    /**
     * Function is used to get designations edit form from API request
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function editDesignation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'designationedit', $options);
        return $data;
    }
    /**
     * Function is used to update selected designation details
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function updateDesignation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'designationupdate', $options);
        return $data;
    }

    /**
     * Function is used to delete selected designation
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function deleteDesignation($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'designationdelete', $options);
        return $data;
    }

    /**
     * Function is used to get permissions from API request
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function getPermissions($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'permissions', $options);
        return $data;
    }
    /**
     * Function is used to get permissions add form from API request
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function addPermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'permissionadd', $options);
        return $data;
    }
    /**
     * Function is used to get permissions edit form from API request
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function editPermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'permissionedit', $options);
        return $data;
    }
    /**
     * Function is used to update selected permission details
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function updatePermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'permissionupdate', $options);
        return $data;
    }

    /**
     * Function is used to delete selected permission
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function deletePermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'permissiondelete', $options);
        return $data;
    }

    /**
     * Function is used to get permission categories
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param array $options
     */
    public function permissionCategories()
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'permissioncategories');
        return $data;
    }

    /**
     * Function is used to check the entered username is valid
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @param array $options Param: username
     */
    public function checkvaliduser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'checkvaliduser', $options);
        return $data;
    }
    /**
     * Function is used to reset password of user
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @param array $options Param: password, confirmpassword, resettoken
     */
    public function resetpassword($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'resetforgotpwd', $options);
        return $data;
    }

    /**
     * Function is used to get users from API request
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param array $options
     */
    public function getUsers($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'users', $options);
        return $data;
    }
    /**
     * Function is used to get users from API request without any permissions
     * @author Namrata Thakur
     * @access public
     * @package user
     * @param array $options
     */
    public function getAllUsersWithoputPermission($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getallusers_withoputpermission', $options);
        return $data;
    }

    public function get_assignuserdetails($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'get_assignuserdetails', $options);
        return $data;
    }

    public function getrequesteruser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getrequesteruser', $options);
        return $data;
    }

    public function getrequesternamesall($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getrequesternamesall', $options);
        return $data;
    }
    /**
     * Function is used to get users add form from API request
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param array $options
     */
    public function addUser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'useradd', $options);
        return $data;
    }
    /**
     * Function is used to get users edit form from API request
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param array $options
     */
    public function editUser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'useredit', $options);
        return $data;
    }

    /**
     * Function is used to update selected user details
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param array $options
     */
    public function updateUser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userupdate', $options);
        return $data;
    }

    /**
     * Function is used to delete selected user
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param array $options
     */
    public function deleteuser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userdelete', $options);
        return $data;
    }

    /**
     * Function is used to get user password from API request
     * @author Amit Khairnar
     * @access public
     * @package users
     */
    public function userPassword()
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userpassword', array());
        return $data;
    }

    /**
     * Function is used to update user status from API request
     * @author Amit Khairnar
     * @access public
     * @package users
     * @param $userid User Id
     * @param $status User status
     */
    public function suspenduser($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'usersuspend', $options);
        return $data;
    }

    /**
     * This Function is used to Assign BV to user
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param array $options
     */
    public function getUserBvs($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userbvs', $options);
        return $data;
    }
    /**
     * This Function is used to Update BV to user
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param array $options
     */
    public function userBvUpdate($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userbvupdate', $options);
        return $data;
    }

    /**
     * This Function is used to fetch all region with alreadt assigned details and regions entities
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param array $options
     */
    public function userRegions($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userregions', $options);
        return $data;
    }

    /**
     * This Function is used to fetch selected region entities like locations,DCs,pods respectively
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param array $options
     */
    public function regionDcsPodsLoc($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'regiondcspods', $options);
        return $data;
    }
    /**
     * This Function is used to fetch pods of selected dcs
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param array $options
     */
    public function dcPods($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'dcpods', $options);
        return $data;
    }
    /**
     * This Function is used to fetch pods of selected dcs
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param array $options
     */
    public function userEntitiesSave($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userregionupdate', $options);
        return $data;
    }

    /**
     * This Function is used to Add IP/Subnet To whitelsit
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @param array $options
     */
    public function addUserWhitelistedIps($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'adduserwhitelistedips', $options);
        return $data;
    }
    /**
     * This Function is used to list all IPS to be whitelisted
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @param array $options
     */
    public function getTokenWhitelist($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'gettokenwhitelist', $options);
        return $data;
    }
    /**
     * This Function is used to Approve Ip to whitelist
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @param array $options
     */
    public function approveUserWhitelistedips($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'approveuserwhitelistedips', $options);
        return $data;
    }

    /**
     * This Function is used to get whitelisted IPs and Subnet
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @param array $options
     */
    public function userWhilistedIps($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userwhilistedips', $options);
        return $data;
    }

    /**
     * This Function is used to delete whitelisted IPs and Subnet
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @param array $options
     */
    public function deleteWhiteListIp($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'deleteuserwhilistedips', $options);
        return $data;
    }

    /**
     * This Function is used to get Configured Display Coloumns
     * @author Vikash Kumar
     * @access public
     * @package users
     * @param array $options
     */
    public function getFields($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getfields', $options);
        return $data;
    }
    /**
     * This Function is used save fields user wants in user list
     * @author Vikash Kumar
     * @access public
     * @package users
     * @param array $options
     */
    public function saveUserFields($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'savefields', $options);
        return $data;
    }
    public function getuserprofile($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getuserprofile', $options);
        return $data;
    }
    public function getDataFromUserId($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getDataFromUserId', $options);
        return $data;
    }

    public function checkvalidpassword($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'checkvalidpassword', $options);
        return $data;
    }
    public function updatenewpassword($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'updatenewpassword', $options);
        return $data;
    }
    public function editprofilesubmit($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'editprofilesubmit', $options);
        return $data;
    }
    public function profilephotosave($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'profilephotosave', $options);
        return $data;
    }
    /**
     * This Function is used save fields user wants in user list
     * @author Namrata Thakur
     * @access public
     * @package userLogs
     * @param array $options
     */
    public function getUserlogs($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'userlogs', $options);
        return $data;
    }
    /**
     * This Function is used save fields user wants in user list
     * @author Namrata Thakur
     * @access public
     * @package mix
     * @param array $options
     */
    public function getdclocationbv($options)
    {
        $data = $this->remoteapi->apicall("POST", $this->url, 'getdclocationbv', $options);
        return $data;
    }

}
