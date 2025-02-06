<?php
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Access-Control-Max-Age: 86400'); // cache for 1 day
header("Access-Control-Allow-Headers:  Origin, Content-Type, Accept, Authorization, X-Requested-With, content-type");
header('Access-Control-Allow-Credentials: true');
// error reporting
error_reporting(E_ALL);
define('WS_USERNAME', 'crmiapiclient');
define('WS_PASSWORD', '6AG?xR$s4;P9$??!K');
require "db.php";
$response = array("status" => 0, "message" => "");

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $message = "Invalid request type. Must use request type as POST.";
    $out_put = array(
        'status'  => false,
        'message' => $message,
        'code'    => '201');
    return send_response($out_put);
} else {
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] == WS_USERNAME && $_SERVER['PHP_AUTH_PW'] == WS_PASSWORD) {
            $post_data = file_get_contents('php://input');
            $out_put   = array();

            if ($post_data == "") {
                $message = "Request body can not be blank.";
                $out_put = array(
                    'status'  => false,
                    'message' => $message,
                    'code'    => '201');
                return send_response($out_put);
            } else {
                $out_put   = array();
                $post_data = json_decode($post_data, true);

                if (empty($post_data)) {
                    $message = "Invalid/Blank request data";
                    $out_put = array(
                        'status'  => false,
                        'message' => $message,
                        'code'    => '201');
                    return send_response($out_put);
                } else {
                    if (true) {
                        $opp_id = isset($post_data['opp_id']) ? $post_data['opp_id'] : '';

                        if ($opp_id == '' && is_numeric($opp_id) && $opp_id == '0') {
                            $message = "Invalid Opportunity Id";
                            $out_put = array(
                                'status'  => false,
                                'message' => $message,
                                'code'    => '201');
                            return send_response($out_put);
                        }
                        /* Opportunity Details */
                        $opp_data = get_opp_details($opp_id);

                        /* Check Opportunity Id */
                        if ($opp_data == false) {
                            $message = "Opportunity Id Not Found.";
                            $out_put = array(
                                'status'  => false,
                                'message' => $message,
                                'code'    => '202');
                            return send_response($out_put);
                        }

                        /* Check Quotation */
                        $is_poc        = $days_poc        = $date_poc        = '';
                        $quotation_sql = pg_query(DB_OBJ, "SELECT * FROM quotations
                        INNER JOIN LOI ON quotations.quotation_id = LOI.quotation_id
                        WHERE opportunity_id = '" . $opp_id . "' LIMIT 1");
                        $quotation_row = pg_fetch_array($quotation_sql);

                        $quotation_id = $quotation_row['quotation_id'];
                        $opp_pro_id   = $quotation_row['opp_pro_id'];
                        $is_poc       = $quotation_row['is_poc'];
                        $days_poc     = $quotation_row['months_poc'];
                        $date_poc     = $quotation_row['date_poc'];

                        if ($quotation_id == '') {
                            $message = "Quotation Details not found for this Opportunity Id : " . $opp_id;
                            $out_put = array(
                                'status'  => false,
                                'message' => $message,
                                'code'    => '202');
                            return send_response($out_put);
                        }

                        /* Opp Loi Stage Check */
                        $loi_sql    = pg_query(DB_OBJ, "SELECT is_complete FROM opportunity_process_stage WHERE opp_pro_id = '" . $opp_pro_id . "'");
                        $loi_row    = pg_fetch_array($loi_sql);
                        $loi_status = $loi_row['is_complete'];
                        if ($loi_status == 'f') {
                            $message = "LOI Stage not completed";
                            $out_put = array(
                                'status'  => false,
                                'message' => $message,
                                'code'    => '203');
                            return send_response($out_put);
                        }

                        // Get PO Details
                        $po_sql = pg_query(DB_OBJ, "SELECT po_amount,po_number,po_receved_date,tentative_start_date FROM opportunity_po WHERE opportunity_id = '" . $opp_id . "'");
                        $po_row = pg_fetch_array($po_sql);

                        /* Lead Details */
                        $lead_data = get_lead_details($opp_data["leadid"]);

                        $lead_type   = $lead_subtype   = $lead_businesstype   = '';
                        $lead_source = $product_name = $account_subbusinesstype = '';

                        // Get Lead Type
                        if (!empty(trim($lead_data["lead_type_id"]))) {
                            $lead_type = get_name_by_id(
                                'lead_types',
                                'lead_type_name',
                                'lead_type_id',
                                $lead_data["lead_type_id"]
                            );
                        }
                        // Get Lead Sub Type
                        if (!empty(trim($lead_data["lead_subtype_id"]))) {
                            $lead_subtype = get_name_by_id(
                                'lead_subtypes',
                                'lead_subtype_name',
                                'lead_subtype_id',
                                $lead_data["lead_subtype_id"]
                            );
                        }
                        // Get Business Type
                        if (!empty(trim($lead_data["lead_businesstype_id"]))) {
                            $lead_businesstype = get_name_by_id(
                                'lead_businesstypes',
                                'lead_businesstype_name',
                                'lead_businesstype_id',
                                $lead_data["lead_businesstype_id"]
                            );
                        }
                        // Get Lead Source
                        if (!empty(trim($lead_data["lead_source_id"]))) {
                            $lead_source = get_name_by_id(
                                'lead_source',
                                'lead_source_name',
                                'lead_source_id',
                                $lead_data["lead_source_id"]
                            );
                        }
                        if (!empty(trim($lead_data["account_subbusinesstype_id"]))) {
                            $account_subbusinesstype = get_name_by_id(
                                'account_subbusinesstypes',
                                'account_subbusinesstype_name',
                                'account_subbusinesstype_id',
                                $lead_data["account_subbusinesstype_id"]
                            );
                        }
                        $product_name = '';
                        if (!empty(trim($lead_data["product"]))) {
                            $product_name = get_name_by_id(
                                'products',
                                'product_name',
                                'product_id',
                                $lead_data["product"]
                            );
                        }

                        if (!empty(trim($lead_data["owner_userid"]))) {
                            $owner_userid = get_name_by_id(
                                'users',
                                'name',
                                'userid',
                                $lead_data["owner_userid"]
                            );
                        }
                        $owner_useridlist = '';
                        $owner_useridlist = $lead_data['owner_useridlist'];
                        $userssql         = pg_query(DB_OBJ, "SELECT name FROM users WHERE userid IN (" . $owner_useridlist . ")");
                        $ownr_names       = array();
                        while ($usersrow = pg_fetch_array($userssql)) {
                            $ownr_names[] = $usersrow['name'];
                        }
                        $order_receved_by = implode(",", $ownr_names);

                        $ba_useridlist = $ba_username_list = '';
                        $ba_useridlist = $lead_data['ba_useridlist'];
                        $users_sql_1   = pg_query(DB_OBJ,
                            "SELECT users.name, designations.designationid, designations.designationname
                        FROM users
                        LEFT JOIN designations ON users.designationid = designations.designationid
                        WHERE designations.designationname LIKE '%Solution%'
                        AND userid IN (" . $ba_useridlist . ") ");
                        $ba_userid_list_arr = array();
                        while ($users_row_1 = pg_fetch_array($users_sql_1)) {
                            //$ba_userid_list_arr[] = $users_row_1['name'] .' ('.$users_row_1['designationname'].')';
                            $ba_userid_list_arr[] = $users_row_1['name'];
                        }
                        $ba_username_list = implode(",", $ba_userid_list_arr);

                        $biddesk_useridlist = $biddesk_username_list = '';
                        $biddesk_useridlist = $lead_data['biddesk_userlist'];
                        $users_sql_2        = pg_query(DB_OBJ,
                            "SELECT users.name, designations.designationid, designations.designationname
                        FROM users
                        LEFT JOIN designations ON users.designationid = designations.designationid
                        WHERE designations.designationname LIKE '%Solution%'
                        AND userid IN (" . $biddesk_useridlist . ") ");

                        $biddesk_userid_list_arr = array();
                        while ($users_row_2 = pg_fetch_array($users_sql_2)) {
                            //$biddesk_userid_list_arr[] = $users_row_2['name'] .' ('.$users_row_2['designationname'].')';
                            $biddesk_userid_list_arr[] = $users_row_2['name'];
                        }
                        $biddesk_username_list = implode(",", $biddesk_userid_list_arr);

                        $solution_team = "";
                        $solution_team = $ba_username_list . "," . $biddesk_username_list;

                        $ba_useridlist1 = $ba_username_list1 = '';
                        $ba_useridlist1 = $lead_data['ba_useridlist'];
                        $users_sql_11   = pg_query(DB_OBJ,
                            "SELECT users.name, designations.designationid, designations.designationname
                        FROM users
                        LEFT JOIN designations ON users.designationid = designations.designationid
                        WHERE userid IN (" . $ba_useridlist1 . ") ");
                        $ba_userid_list_arr1 = array();
                        while ($users_row_11 = pg_fetch_array($users_sql_11)) {
                            $ba_userid_list_arr1[] = $users_row_11['name'] . ' (' . $users_row_11['designationname'] . ')';

                        }
                        $ba_username_list1 = implode(",", $ba_userid_list_arr1);

                        $biddesk_useridlist1 = $biddesk_username_list1 = '';
                        $biddesk_useridlist1 = $lead_data['biddesk_userlist'];
                        $users_sql_21        = pg_query(DB_OBJ,
                            "SELECT users.name, designations.designationid, designations.designationname
                        FROM users
                        LEFT JOIN designations ON users.designationid = designations.designationid
                        WHERE userid IN (" . $biddesk_useridlist1 . ") ");

                        $biddesk_userid_list_arr1 = array();
                        while ($users_row_21 = pg_fetch_array($users_sql_21)) {
                            $biddesk_userid_list_arr1[] = $users_row_21['name'] . ' (' . $users_row_21['designationname'] . ')';
                        }
                        $biddesk_username_list1 = implode(",", $biddesk_userid_list_arr1);

                        /* Company Details */
                        $region = $account_type = $sub_account_category = $account_businesstype = '';

                        $industry_segment = $parent_company = '';

                        $company_data = get_company_details($lead_data["company_id"]);

                        $owner_user_name = $inside_sales_owner_name = $spochub_owner_name = '';
                        //return send_response($lead_data);
                        // Account Owner
                        if ($lead_data['business_category_id'] == 2) {
                            if (!empty(trim($company_data["inside_sales_owner"]))) {
                                $inside_sales_owner_name = $owner_user_name = get_name_by_id(
                                    'users',
                                    'name',
                                    'userid',
                                    $company_data["inside_sales_owner"]);
                            }
                        } else if ($lead_data['business_category_id'] == 3) {
                            if (!empty(trim($company_data["spochub_owner"]))) {
                                $spochub_owner_name = $owner_user_name = get_name_by_id(
                                    'users',
                                    'name',
                                    'userid',
                                    $company_data["spochub_owner"]);
                            }
                        } else if ($lead_data['business_category_id'] == 1) {
                            if (!empty(trim($company_data["owner_userid"]))) {
                                $owner_user_name = get_name_by_id(
                                    'users',
                                    'name',
                                    'userid',
                                    $company_data["owner_userid"]);
                            }
                        }

                        // Industry Segment
                        if (!empty(trim($company_data["industry_segment_id"]))) {
                            $industry_segment = get_name_by_id(
                                'industry_segments',
                                'segment_name',
                                'industry_segment_id',
                                $company_data["industry_segment_id"]);
                        }
                        // Parent Company Name
                        if (!empty(trim($company_data["parent_company_id"]))) {
                            $parent_company = get_name_by_id(
                                'company',
                                'company_name',
                                'company_id',
                                $company_data["parent_company_id"]);
                        }
                        // Account Region Details
                        if (!empty(trim($company_data["account_region"]))) {
                            $region = get_name_by_id(
                                'regions',
                                'region_name',
                                'region_id',
                                $company_data["account_region"]
                            );
                        }
                        // Account Type Details
                        if (!empty(trim($company_data["account_type_id"]))) {
                            $account_type = get_name_by_id(
                                'account_types',
                                'account_type_name',
                                'account_type_id',
                                $company_data["account_type_id"]
                            );
                        }
                        // Sub Account Category
                        if (!empty(trim($company_data["sub_account_category_id"]))) {
                            $sub_account_category = get_name_by_id(
                                'sub_account_category',
                                'sub_account_category_name',
                                'sub_account_category_id',
                                $company_data["sub_account_category_id"]);
                        }
                        // Account Business Type
                        if (!empty(trim($company_data["account_businesstype_id"]))) {
                            $account_businesstype = get_name_by_id(
                                'account_businesstypes',
                                'account_businesstype_name',
                                'account_businesstype_id',
                                $company_data["account_businesstype_id"]);
                        }
                        $opp_data['billing_address'];

                        /* Quotation Details */
                        $quotation = get_quotation_details($opp_data["opportunity_id"]);

                        /* Company Details */
                        $company_details = array(
                            'opportunity_id'          => $opp_data['opportunity_id'],
                            'opportunity_code'        => $opp_data['opportunity_code'],                            
                            'customer_name'           => html_decode(ucwords(trim($company_data["company_name"]))),
                            'industry_segment'        => html_decode($industry_segment),
                            'project_section'         => html_decode(ucwords(trim($account_type))),
                            'zone'                    => html_decode($sub_account_category),
                            'account_region'          => html_decode($region),
                            'account_businesstype'    => html_decode($account_businesstype),
                            'account_subbusinesstype' => html_decode($account_subbusinesstype),
                            'leadidname'              => html_decode(trim($lead_data["leadidname"])),
                            'project_name'            => html_decode(trim(ucwords($lead_data["lead_title"]))),
                            'lead_type'               => html_decode(trim($lead_type)),
                            'lead_subtyp'             => html_decode(trim($lead_subtype)),
                            'lead_businesstype'       => html_decode(trim($lead_businesstype)),
                            'lead_source'             => html_decode(trim($lead_source)),
                            'product'                 => html_decode($product_name),
                            'isleadapproved'          => $lead_data["isleadapproved"],
                            'loi_status'              => $loi_status,
                            'opportunity_win'         => $opp_data['is_oppwin'],
                            'opportunity_lost'        => $opp_data['is_opplost'],
                            'order_receved_by'        => html_decode($owner_user_name),
                            'solution_team'           => html_decode($solution_team),
                            'ba_username_list'        => html_decode($ba_username_list1),
                            'bid_desk_username_list'  => html_decode($biddesk_username_list1),
                            'lead_opp_description'    => html_decode($lead_data['description']),
                            'billing_address'         => $opp_data['billing_address'],
                            'shipping_address'        => $opp_data['shipping_address'],
                            'owner_user_name'         => html_decode($owner_user_name),
                            'inside_sales_owner_name' => html_decode($inside_sales_owner_name),
                            'spochub_owner_name'      => html_decode($spochub_owner_name),
                        );

                        $out_put = array(
                            'status'           => true,
                            'message'          => 'success',
                            'code'             => '200',
                            'customer_details' => $company_details,
                            'quotation'        => $quotation);
                        return send_response($out_put);
                    } else {
                        $message = "Error";
                        $out_put = array(
                            'status'  => false,
                            'message' => $message,
                            'code'    => '201');
                        return send_response($out_put);
                    }
                }
            }
        } else {
            $message = "Invalid Authorization for API. Please check your Credentials.";
            $out_put = array(
                'status'  => false,
                'message' => $message,
                'code'    => '201');
            return send_response($out_put);
        }
    } else {
        $message = "API Username or Password can not be blank.";
        $out_put = array(
            'status'  => false,
            'message' => $message,
            'code'    => '201');
        return send_response($out_put);
    }
}
function send_response($out_put)
{
    header('Content-type: application/json');
    $arr_res           = array();
    $arr_res['result'] = $out_put;
    echo json_encode($arr_res);
}
/* EOF Select Data from PG */
function get_quotation_details($opportunity_id)
{
    $quotation_id = $quotation_phase_id = $quotation_group_id = $quotation_item_id = 0;
    /* GET Quotation Data */
    $quotation_sql = pg_query(DB_OBJ, "SELECT * FROM quotations
    INNER JOIN LOI ON quotations.quotation_id = LOI.quotation_id
    WHERE opportunity_id = '" . $opportunity_id . "'
    LIMIT 1");

    $quotation_row = pg_fetch_array($quotation_sql);
    $quotation_id  = $quotation_row['quotation_id'];

    /* GET Phase Data */
    $total_phase_data = array();
    $phase_sql        = pg_query(DB_OBJ, "SELECT * FROM quotation_phase WHERE quotation_id = '" . $quotation_id . "' AND is_deleted = 'f' AND is_active = 't' ORDER BY quotation_phase_id ASC");
    while ($phase_row = pg_fetch_array($phase_sql)) {
        $quotation_phase_id             = $phase_row['quotation_phase_id'];
        $phase_data                     = array();
        $phase_data["phase_id"]         = $phase_row['quotation_phase_id'];
        $phase_data["phase_name"]       = html_decode($phase_row["quotation_phase_name"]);

        /* GET Group Data */
        $total_group_data = array();
        $group_sql        = pg_query(DB_OBJ, "SELECT * FROM quotation_group WHERE quotation_phase_id = '" . $quotation_phase_id . "' AND is_deleted = 'f' AND is_active = 't' ORDER BY quotation_group_id ASC");
        while ($group_row = pg_fetch_array($group_sql)) {
            $quotation_group_id           = $group_row['quotation_group_id'];
            $group_data                   = array();
            $group_data["group_name"]     = html_decode(trim($group_row["quotation_group_name"]));
            $group_data["group_quantity"] = $group_row["group_quantity"];

            /* GET Item Data */
            $total_item_data = array();
            $item_sql        = pg_query(DB_OBJ, "SELECT * FROM quotation_item WHERE quotation_group_id = '" . $quotation_group_id . "' AND is_deleted = 'f' AND is_active = 't' ORDER BY quotation_item_id ASC");
            while ($item_row = pg_fetch_array($item_sql)) {
                $quotation_item_id  = $item_row['quotation_item_id'];
                $item_location_name = '';
                if (!empty(trim($item_row["item_location_id"]))) {
                    $item_location_name = get_name_by_id(
                        'locations',
                        'location_name',
                        'location_id',
                        $item_row["item_location_id"]);
                }
                $item_location_description = '';
                if (!empty(trim($item_row["item_location_id"]))) {
                    $item_location_description = get_name_by_id(
                        'locations',
                        'location_description',
                        'location_id',
                        $item_row["item_location_id"]);
                }
                $stock_status_name = '';
                if (!empty(trim($item_row["stock_status_id"]))) {
                    $stock_status_name = get_name_by_id(
                        'stock_status',
                        'stock_status_name',
                        'stock_status_id',
                        $item_row["stock_status_id"]);
                }
                $stock_status_description = '';
                if (!empty(trim($item_row["stock_status_id"]))) {
                    $stock_status_description = get_name_by_id(
                        'stock_status',
                        'stock_status_description',
                        'stock_status_id',
                        $item_row["stock_status_id"]);
                }

                /* Get Core Product Details */
                $core_product_row = get_core_product_by_id($item_row["core_product_id"]);

                $item_data                              = array();
                $item_data["core_product_name"]         = html_decode(trim($item_row["core_product_name"]));
                $item_data["coreproduct_description"]   = html_decode(trim($core_product_row["coreproduct_description"]));
                $item_data["sku_code"]                  = html_decode(trim($core_product_row["skucode"]));
                $item_data["skucode_id"]                = trim($core_product_row["skucodeid"]);
                $item_data["item_quantity"]             = $item_row["item_quantity"];
                $item_data["item_location_name"]        = html_decode(trim($item_location_name));
                $item_data["item_location_description"] = html_decode(trim($item_location_description));
                $item_data["stock_status_name"]         = html_decode(trim($stock_status_name));
                $item_data["stock_status_description"]  = html_decode(trim($stock_status_description));
                $item_data["unit_name"]                 = html_decode(trim($item_row["unit_name"]));
                $item_data["item_note"]                 = $item_row["item_note"];
                $total_item_data[]                      = $item_data;
            }
            $group_data['items'] = $total_item_data;
            $total_group_data[]  = $group_data;
        }
        $phase_data['group'] = $total_group_data;
        $total_phase_data[]  = $phase_data;
    }

    $quotation = array(
        'quotation_code' => trim($quotation_row['quotation_code']),
        'phases'         => $total_phase_data,
    );
    return $quotation;
}
function html_decode($string)
{
    return strip_tags(stripslashes(htmlspecialchars_decode($string)));
}
function get_core_product_by_id($core_product_id)
{
    $sql = pg_query(DB_OBJ, "SELECT * FROM core_product_model WHERE core_product_id = '" . $core_product_id . "'");
    return pg_fetch_array($sql);
}
function get_opp_details($opp_id)
{
    $sql = pg_query(DB_OBJ,
        "SELECT * FROM opportunity WHERE opportunity_id = '" . $opp_id . "' LIMIT 1"
    );
    return pg_fetch_array($sql);
}
function get_lead_details($leadid)
{
    $sql = pg_query(DB_OBJ,
        "SELECT * FROM leads WHERE leadid = '" . $leadid . "' LIMIT 1"
    );
    return pg_fetch_array($sql);
}
function get_company_details($company_id)
{
    $sql = pg_query(DB_OBJ,
        "SELECT * FROM company WHERE company_id = '" . $company_id . "' LIMIT 1"
    );
    return pg_fetch_array($sql);
}
function get_name_by_id($table_name, $select, $where_name, $where_val)
{
    $sql = pg_query(DB_OBJ,
        "SELECT " . $select . " FROM " . $table_name . " WHERE " . $where_name . " = '" . $where_val . "' LIMIT 1"
    );
    $row = pg_fetch_array($sql);
    return $row[$select];
}
header('Content-type: application/json');