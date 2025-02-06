<?php
namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnAssets;

class EnReports extends Model
{
    use HasBinaryUuid;
    public $incrementing    = false;
    
    protected $table = 'en_reports';
	protected $fillable = 
					[
                        'report_id',
        				'report_name', 
                        'report_cat_id',
        				'module',
                        'filter_fields',
                        'filter_date_field',
        				'filter_date_value',
                        'filter_date_range',
                        'filters',
                        'details',
        				'user_id',
        				'share_report',
        				'schedule_type',
        				'gen_report_at',
        				'gen_report_for',
        				'report_format',
        				'email_to',
        				'email_subject',
        				'email_body',
        				'next_report_time',
        				'enableschedule',
        				'updated_at',
        				'created_at',
                        'status'
    				];

    protected $primaryKey = 'report_id';
    public function getKeyName()
    {
        return 'report_id';
    }
    
    protected function getreport($report_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_reports')   
                ->select(
                    DB::raw('BIN_TO_UUID(report_id) AS report_id'),
                    DB::raw('BIN_TO_UUID(report_cat_id) AS report_cat_id'),
                    DB::raw('BIN_TO_UUID(user_id) AS user_id'),
                        'report_name',
                        'module',
                        'filter_fields',
                        'filter_date_field',
                        'filter_date_value',
                        'filter_date_range',
                        'filters',
                        'details',
                        'share_report',
                        'schedule_type',
                        'gen_report_at',
                        'gen_report_for',
                        'report_format',
                        'email_to',
                        'email_subject',
                        'email_body',
                        'next_report_time',
                        'enableschedule',
                        'updated_at',
                        'created_at')
                ->where('en_reports.status', '!=', 'd');
                
                $query->where(function ($query) use ($searchkeyword, $report_id){
                    $query->where(function ($query) use ($searchkeyword, $report_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                return $query->where('en_reports.report_category', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_reports.report_name', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($report_id, function ($query) use ($report_id)
                        {
                            return $query->where('en_reports.report_id', '=', DB::raw('UUID_TO_BIN("'.$report_id.'")'));
                        });});
                    //user Acessiblity
                    $user_id    = isset($inputdata['loggedinuserid']) ? $inputdata['loggedinuserid'] : '';
                    $is_admin   = isset($inputdata['ENMASTERADMIN']) ? $inputdata['ENMASTERADMIN'] : '';
                    if($is_admin !="" && $is_admin !="y")
                    {   
                        if ($user_id != "") 
                        {
                            $query->where(function ($query) use ($user_id)
                            {
                                return $query->where('en_reports.user_id', '=', DB::raw('UUID_TO_BIN("'.$user_id.'")'))->orWhere('en_reports.share_report', '=','y');
                            });
                            
                        }
                    }
                $query->when(!$count, function ($query) use ($inputdata)
                        {
                            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                            {
                                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                            }
                        });
        $data = $query->get();
        if($count)
            return   count($data);
        else      
            return $data;
    }
    protected function getcontractreport($result,$inputdata=[], $count=false)
    {
        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];

        $searchkeyword = _isset($inputdata,'searchkeyword');
        
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_contract AS c')   
             
                ->leftjoin('en_ci_vendors AS v', 'v.vendor_id', '=', 'c.vendor_id') 
                ->leftjoin('en_contract_type AS ct', 'ct.contract_type_id', '=', 'c.contract_type_id')
                ->leftjoin('en_contract_details AS cd', 'cd.contract_id', '=', 'c.contract_id')
               ->where('c.status', '!=', 'd')
               ->orderBy('c.contract_id', 'DESC');

                $query->where(function ($query) use ($searchkeyword){
                    $query->where(function ($query) use ($searchkeyword) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                return $query->where('c.contract_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('v.vendor_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('ct.contract_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('cd.support', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('cd.cost', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('c.contractid', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('c.from_date', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('c.to_date', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('c.contract_status', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                    });

                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);
                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
                $data = $query->get($filter_fields);                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    protected function getsoftwarereport($result,$inputdata=[], $count=false)
    {
        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];

        $searchkeyword = _isset($inputdata,'searchkeyword');
        
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }

        $query = DB::table('en_software AS sw')   
                
                ->leftjoin('en_software_category AS sc', 'sc.software_category_id', '=', 'sw.software_category_id') 
                ->leftjoin('en_software_types AS st', 'st.software_type_id', '=', 'sw.software_type_id')
                ->leftjoin('en_software_manufacturer AS sm', 'sm.software_manufacturer_id', '=', 'sw.software_manufacturer_id')
                ->leftjoin(DB::raw("(SELECT json_length(asset_id) AS installed,software_id,status FROM `en_software_installation`) si "), function ($join) {
                    $join->on('sw.software_id', '=', 'si.software_id')
                         ->where('si.status', '=', 'y');
                })
                ->leftjoin(DB::raw("(SELECT SUM(max_installation) AS purchased,software_id,STATUS  FROM `en_software_license`) sl"), function ($join) {
                    $join->on('sw.software_id', '=', 'sl.software_id')
                         ->where('sl.status', '=', 'y');
                })
                ->leftjoin(DB::raw("(SELECT SUM(json_length(asset_id)) AS allocated, software_id,status FROM `en_software_license_allocation`) sla"), function ($join) {
                    $join->on('sw.software_id', '=', 'sla.software_id')
                         ->where('sla.status', '=', 'y')
                         ->groupBy('sla.software_id');
                })
               ->where('sw.status', '!=', 'd')
               ->groupBy('sw.software_id');

                $query->where(function ($query) use ($searchkeyword){
                    $query->where(function ($query) use ($searchkeyword) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                return $query->where('sw.software_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('sw.version', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('sw.description', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('sc.software_category', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('st.software_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('sm.software_manufacturer', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                });

                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }
                    elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);
                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
                $query->select($filter_fields);
                $data = $query->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    //
    protected function getpurchasereport($result,$inputdata=[], $count=false)
    {
        $index = array_search('billing_address',$result['filter_fields']);
        if($index !== FALSE){
            $result['filter_fields'][$index] = "b.address as billing_address";
        }

        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];
        
        $searchkeyword = _isset($inputdata,'searchkeyword');
        
        $bvdcloc        = _isset($inputdata,'bvdcloc');
        $location_id    = isset($bvdcloc['location_id']) ? $bvdcloc['location_id'] : [];
        $location       = isset($bvdcloc['location']) ? $bvdcloc['location'] : [];
        $bv_id    = isset($bvdcloc['bv_id']) ? $bvdcloc['bv_id'] : [];
        $business_vertical = isset($bvdcloc['businessvertical']) ? $bvdcloc['businessvertical'] : [];
        $dc_id      = isset($bvdcloc['dc_id']) ? $bvdcloc['dc_id'] : [];
        $datacenter = isset($bvdcloc['datacenter']) ? $bvdcloc['datacenter'] : [];

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_form_data_po AS po')
                ->leftjoin('en_ci_vendors AS v', 'v.vendor_id', '=', DB::Raw("UUID_TO_BIN(JSON_UNQUOTE(JSON_EXTRACT(po.details, '$.pr_vendor')))"))
                ->leftjoin('en_bill_to AS b', 'b.billto_id', '=', DB::Raw("UUID_TO_BIN(JSON_UNQUOTE(JSON_EXTRACT(po.details, '$.pr_billto')))"))
                /*->leftjoin(DB::raw("(SELECT CONCAT(cc_code, '-', cc_name) AS pr_cost_center,cc_id,status FROM `en_cost_centers`) cc "), function ($join) {
                    $join->on(DB::Raw("UUID_TO_BIN(JSON_UNQUOTE(JSON_EXTRACT(po.details, '$.pr_cost_center')))"), '=', 'cc.cc_id')
                         ->where('cc.status', '=', 'y');
                })*/
                ->leftjoin(DB::raw("(SELECT sum(asset_details->>'$.item_qty' * asset_details->>'$.item_estimated_cost') AS sub_total,pr_po_id,status FROM `en_pr_po_asset_details` group by pr_po_id) ad "), function ($join) {
                    $join->on('po.po_id', '=', 'ad.pr_po_id')
                         ->where('ad.status', '=', 'y')
                         ->groupBy('ad.pr_po_id');
                         
                })       
                // Rahul
                // ->join(DB::raw('(SELECT details->>"$.pr_due_date" AS `pr_due_date`,details->>"$.pr_priority" AS `pr_priority`,details->>"$.pr_req_date" AS `pr_req_date`,details->>"$.pr_description" AS `pr_description`,details->>"$.discount_per" AS `discount_per`,details->>"$.pr_billto" AS `billing_address`,details->>"$.pr_vendor" AS `vendor`,po_id,status FROM en_form_data_po) fpo'), 
                //     function ($join) {
                //     $join->on('po.po_id', '=', 'fpo.po_id');
                // });
                
                // Nikhil
                ->join(DB::raw('(SELECT details->>"$.pr_due_date" AS `pr_due_date`,details->>"$.pr_priority" AS `pr_priority`,details->>"$.pr_req_date" AS `pr_req_date`,details->>"$.pr_description" AS `pr_description`,details->>"$.discount_per" AS `discount_per`,details->>"$.pr_vendor" AS `vendor`,po_id,status FROM en_form_data_po) fpo'), 
                    function ($join) {
                    $join->on('po.po_id', '=', 'fpo.po_id');
                });

                // $query->where(function ($query) use ($searchkeyword){
                //     $query->where(function ($query) use ($searchkeyword) {
                //         $query->when($searchkeyword, function ($query) use ($searchkeyword)
                //             {
                //                 return $query->where('c.contract_name', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('v.vendor_name', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('ct.contract_type', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('cd.support', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('cd.cost', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('c.contractid', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('c.from_date', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('c.to_date', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('c.contract_status', 'like', '%' . $searchkeyword . '%');
                //                });       
                //         });
                // });

                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);
                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
                //User Acess rights BV LOC DC
                $query->when($location_id, function ($query) use ($location_id)
                {
                    $query->whereIn('location', $location_id);
                });
                $query->when($bv_id, function ($query) use ($bv_id)
                {
                    $query->whereIn('business_vertical', $bv_id);
                });
                $query->when($dc_id, function ($query) use ($dc_id)
                {
                    $query->whereIn('datacenter', $dc_id);
                });
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
                if (($total = array_search('total', $filter_fields)) !== false) 
                {
                    $filter_fields[$total] = DB::raw("(sub_total-(sub_total * nullif(discount_per,0))/100) AS 'total'");
                }
                if (($discount_amount = array_search('discount_amount', $filter_fields)) !== false) 
                {
                    $filter_fields[$discount_amount] = DB::raw("(sub_total-(sub_total-(sub_total * nullif(discount_per,0))/100))AS 'discount_amount'");
                }
                $data = $query->get($filter_fields);
                if (($dc = array_search('datacenter', $filter_fields)) !== false && !$count)
                {
                    if (is_array($datacenter) && count($datacenter)>0) 
                    {
                        $data = $data->map(function ($data) use ($datacenter){
                            foreach ($datacenter as $key => $value)
                            {
                                $data->datacenter = str_replace($key,$value,$data->datacenter);
                            }
                            return $data;
                        });
                    }
                }
                if (($dc = array_search('location', $filter_fields)) !== false && !$count)
                {
                    if (is_array($location) && count($location)>0) 
                    {
                        $data = $data->map(function ($data) use ($location){
                            foreach ($location as $key => $value)
                            {
                                $data->location = str_replace($key,$value,$data->location);
                            }
                            return $data;
                        });
                    }
                }
                if (($dc = array_search('business_vertical', $filter_fields)) !== false && !$count)
                {
                    if (is_array($business_vertical) && count($business_vertical)>0) 
                    {
                        $data = $data->map(function ($data) use ($business_vertical){
                            foreach ($business_vertical as $key => $value)
                            {
                                $data->business_vertical = str_replace($key,$value,$data->business_vertical);
                            }
                            return $data;
                        });
                    }
                }                
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    protected function getpurchasereport_pr($result,$inputdata=[], $count=false)
    {   
        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];

        $searchkeyword = _isset($inputdata,'searchkeyword');
        
        $bvdcloc        = _isset($inputdata,'bvdcloc');
        $location_id    = isset($bvdcloc['location_id']) ? $bvdcloc['location_id'] : [];
        $location       = isset($bvdcloc['location']) ? $bvdcloc['location'] : [];
        $bv_id    = isset($bvdcloc['bv_id']) ? $bvdcloc['bv_id'] : [];
        $business_vertical = isset($bvdcloc['businessvertical']) ? $bvdcloc['businessvertical'] : [];
        $dc_id      = isset($bvdcloc['dc_id']) ? $bvdcloc['dc_id'] : [];
        $datacenter = isset($bvdcloc['datacenter']) ? $bvdcloc['datacenter'] : [];

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_form_data_pr AS pr')
                ->join(DB::raw('(SELECT details->>"$.pr_due_date" AS `pr_due_date`,details->>"$.pr_priority" AS `pr_priority`,details->>"$.pr_req_date" AS `pr_req_date`,details->>"$.project_name" AS `project_name`,pr_id,status FROM en_form_data_pr) fpr'), 
                    function ($join) {
                    $join->on('pr.pr_id', '=', 'fpr.pr_id')
                         ->where('fpr.status', '!=', 'deleted');
                });

                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);
                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
               
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
              
                $data = $query->get($filter_fields);       
                                            
        if($count)
            return   count($data);
        else      
            return $data;     
    }
    //
    protected function getassetsreport($result,$inputdata=[], $count=false)
    {
        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];

        $searchkeyword     = _isset($inputdata,'searchkeyword');
        $bvdcloc           = _isset($inputdata,'bvdcloc');
        $location_id       = isset($bvdcloc['location_id']) ? $bvdcloc['location_id'] : [];
        $location          = isset($bvdcloc['location']) ? $bvdcloc['location'] : [];
        $bv_id             = isset($bvdcloc['bv_id']) ? $bvdcloc['bv_id'] : [];
        $business_vertical = isset($bvdcloc['businessvertical']) ? $bvdcloc['businessvertical'] : [];
        $dc_id             = isset($bvdcloc['dc_id']) ? $bvdcloc['dc_id'] : [];
        $datacenter        = isset($bvdcloc['datacenter']) ? $bvdcloc['datacenter'] : [];

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_assets AS a')
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id') 
                ->leftjoin('en_ci_vendors AS v', 'v.vendor_id', '=', 'ad.vendor_id')
                ->leftjoin('en_ci_templ_default AS ci', 'ci.ci_templ_id', '=', 'a.ci_templ_id')
                ->leftjoin('en_ci_types AS ci_type', 'ci_type.ci_type_id', '=','ci.ci_type_id')
                ->leftjoin('en_form_data_po AS po', 'po.po_id', '=', 'a.po_id')
                ->where('a.status', '!=', 'd')->groupBy('ci.ci_name');

                // $query->where(function ($query) use ($searchkeyword)
                // {
                //     $query->where(function ($query) use ($searchkeyword) {
                //         $query->when($searchkeyword, function ($query) use ($searchkeyword)
                //             {
                //                 return $query->where('c.contract_name', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('v.vendor_name', 'like', '%' . $searchkeyword . '%');
                //                });       
                //         });
                // });

                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);
                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
                //User Acess rights BV LOC DC
                $query->when($location_id, function ($query) use ($location_id)
                {
                    $location_arr = [];
                    if (is_array($location_id) && !empty($location_id)) 
                    {
                        foreach ($location_id as $loc) 
                        {
                            $location_arr[]  = DB::raw('UUID_TO_BIN("'.$loc.'")');
                        }
                    }
                    $query->whereIn('a.location_id', $location_arr);
                });
                $query->when($bv_id, function ($query) use ($bv_id)
                {
                    $bv_arr = [];
                    if (is_array($bv_id) && !empty($bv_id)) 
                    {
                        foreach ($bv_id as $bv) 
                        {
                            $bv_arr[]  = DB::raw('UUID_TO_BIN("'.$bv.'")');
                        }
                    }
                    $query->whereIn('a.bv_id', $bv_arr);
                });
                $rep_loc = false;
                if (($location_id = array_search('a.location_id', $filter_fields)) !== false) 
                {
                    $filter_fields[$location_id] = DB::raw("BIN_TO_UUID(a.location_id) AS 'location'");
                    $rep_loc = true;
                }
                $rep_bv = false;
                if (($bv_id = array_search('a.bv_id', $filter_fields)) !== false) 
                {
                    $filter_fields[$bv_id] = DB::raw("BIN_TO_UUID(a.bv_id) AS 'business_vertical'");
                    $rep_bv = true;
                }
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });

                $data = $query->get($filter_fields);

                if ($rep_loc === true && !$count)
                {
                    if (is_array($location) && count($location)>0) 
                    {
                        $data = $data->map(function ($data) use ($location){
                            foreach ($location as $key => $value)
                            {
                                $data->location = str_replace($key,$value,$data->location);
                            }
                            return $data;
                        });
                    }
                }
                if ($rep_bv === true && !$count)
                {
                    if (is_array($business_vertical) && count($business_vertical)>0) 
                    {
                        $data = $data->map(function ($data) use ($business_vertical){
                            foreach ($business_vertical as $key => $value)
                            {
                                $data->business_vertical = str_replace($key,$value,$data->business_vertical);
                            }
                            return $data;
                        });
                    }
                }
                //$data = $query->get($filter_fields);                        
                                 
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    //
    //
    protected function getcmdbreport($result,$inputdata=[], $count=false)
    {
        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];

        $searchkeyword     = _isset($inputdata,'searchkeyword');

        $cifields          = _isset($inputdata,'cifields');
        $cifilterfields    = _isset($inputdata,'cifilterfields');
        $details           = _isset($result,'details');
        $details           = json_decode($result['details'], true );
        if (isset($details) && is_array($details) && count($details)>0) 
        {
            $ci_templ_id = isset($details['ci_templ_id']) ? $details['ci_templ_id'] : "";
        }
        
        $bvdcloc           = _isset($inputdata,'bvdcloc');
        $location_id       = isset($bvdcloc['location_id']) ? $bvdcloc['location_id'] : [];
        $location          = isset($bvdcloc['location']) ? $bvdcloc['location'] : [];
        $bv_id             = isset($bvdcloc['bv_id']) ? $bvdcloc['bv_id'] : [];
        $business_vertical = isset($bvdcloc['businessvertical']) ? $bvdcloc['businessvertical'] : [];
        $dc_id             = isset($bvdcloc['dc_id']) ? $bvdcloc['dc_id'] : [];
        $datacenter        = isset($bvdcloc['datacenter']) ? $bvdcloc['datacenter'] : [];

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_assets AS a')
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id') 
                ->leftjoin('en_ci_vendors AS v', 'v.vendor_id', '=', 'ad.vendor_id')
                ->leftjoin('en_ci_templ_default AS ci', 'ci.ci_templ_id', '=', 'a.ci_templ_id')
                ->leftjoin('en_ci_types AS ci_type', 'ci_type.ci_type_id', '=','ci.ci_type_id')
                ->leftjoin('en_form_data_po AS po', 'po.po_id', '=', 'a.po_id')
                ->where('a.status', '!=', 'd');

                // $query->where(function ($query) use ($searchkeyword)
                // {
                //     $query->where(function ($query) use ($searchkeyword) {
                //         $query->when($searchkeyword, function ($query) use ($searchkeyword)
                //             {
                //                 return $query->where('c.contract_name', 'like', '%' . $searchkeyword . '%')
                //                 ->orWhere('v.vendor_name', 'like', '%' . $searchkeyword . '%');
                //                });       
                //         });
                // });
                $query->when($ci_templ_id, function ($query) use ($ci_templ_id)
                { 
                    if ($ci_templ_id !="") 
                    {
                        return $query->where('a.ci_templ_id','=',DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")')); 
                    }
                });
                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);

                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
                //User Acess rights BV LOC DC
                $query->when($location_id, function ($query) use ($location_id)
                {
                    $location_arr = [];
                    if (is_array($location_id) && !empty($location_id)) 
                    {
                        foreach ($location_id as $loc) 
                        {
                            $location_arr[]  = DB::raw('UUID_TO_BIN("'.$loc.'")');
                        }
                    }
                    $query->whereIn('a.location_id', $location_arr);
                });
                $query->when($bv_id, function ($query) use ($bv_id)
                {
                    $bv_arr = [];
                    if (is_array($bv_id) && !empty($bv_id)) 
                    {
                        foreach ($bv_id as $bv) 
                        {
                            $bv_arr[]  = DB::raw('UUID_TO_BIN("'.$bv.'")');
                        }
                    }
                    $query->whereIn('a.bv_id', $bv_arr);
                });
                $rep_loc = false;
                if (($location_id = array_search('a.location_id', $filter_fields)) !== false) 
                {
                    $filter_fields[$location_id] = DB::raw("BIN_TO_UUID(a.location_id) AS 'location'");
                    $rep_loc = true;
                }
                $rep_bv = false;
                if (($bv_id = array_search('a.bv_id', $filter_fields)) !== false) 
                {
                    $filter_fields[$bv_id] = DB::raw("BIN_TO_UUID(a.bv_id) AS 'business_vertical'");
                    $rep_bv = true;
                } 
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query,$cifilterfields);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
                if (isset($cifields) && is_array($cifields) && count($cifields)>0)
                {
                    foreach ($cifields as $cifield) 
                    {
                        if (($asset = array_search($cifield, $filter_fields)) !== false) 
                        {
                            $filter_fields[$asset] = DB::raw('ad.asset_details->>"$.'.$cifield.'" AS `'.$cifield.'`');    
                        }
                    }
                }    
                $data = $query->get($filter_fields);

                if ($rep_loc === true && !$count)
                {
                    if (is_array($location) && count($location)>0) 
                    {
                        $data = $data->map(function ($data) use ($location){
                            foreach ($location as $key => $value)
                            {
                                $data->location = str_replace($key,$value,$data->location);
                            }
                            return $data;
                        });
                    }
                }
                if ($rep_bv === true && !$count)
                {
                    if (is_array($business_vertical) && count($business_vertical)>0) 
                    {
                        $data = $data->map(function ($data) use ($business_vertical){
                            foreach ($business_vertical as $key => $value)
                            {
                                $data->business_vertical = str_replace($key,$value,$data->business_vertical);
                            }
                            return $data;
                        });
                    }
                }
                //$data = $query->get($filter_fields);                        
                                 
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    //
    //
    protected function getallcompreport($result,$inputdata=[], $count=false)
    {
        $filter_fields      = $result['filter_fields'];
        $filters            = $result['filters'];
        $filter_date_field  = $result['filter_date_field'];
        $filter_date_value  = $result['filter_date_value'];
        $filter_date_range  = $result['filter_date_range'];
        $ramArr = $hddArr   = $attachArr = $noramsArr = $nohddArr = $totalramArr = $totalmemArr     = $totalcostArr =  [];

        $searchkeyword      = _isset($inputdata,'searchkeyword');
        $cifields           = _isset($inputdata,'cifields');
        $cifilterfields     = _isset($inputdata,'cifilterfields');
        $details            = _isset($result,'details');
        $details            = json_decode($result['details'], true );

        if (isset($details) && is_array($details) && count($details)>0) 
        {
            $ci_templ_id = isset($details['ci_templ_id']) ? $details['ci_templ_id'] : "";
        }
        if ($ci_templ_id !="" && !$count) 
        {
            $obj_asset = EnAssets::select(DB::raw('BIN_TO_UUID(en_assets.asset_id) AS asset_id'),'en_asset_details.purchasecost')
                        ->leftJoin('en_asset_details', 'en_assets.asset_id', '=', 'en_asset_details.asset_id')
                        ->where('en_assets.ci_templ_id', '=', DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")'))
                        ->where('en_assets.status',"!=",'d')->get();
            $arr_assets  = $obj_asset->toArray();
            foreach ($arr_assets as $arr_asset) 
            {
                $inputdata['limit']           = 'all';
                $inputdata['parent_asset_id'] = $arr_asset['asset_id'];
                $child_assets      = EnAssets::getassets($inputdata);
                $arr_child_assets  = $child_assets->toArray();    
                $ram        = $hdd     = $attach = "";
                $totalcost  = $no_rams = $no_hdd = $totalram = $totalmem = 0; 
                $totalcost  =  isset($asset_details['purchasecost']) ? $asset_details['purchasecost'] : '';

                foreach ($arr_child_assets as $child_asset)
                {   
                    $asset_details  = json_decode($child_asset->asset_details,true); 
                    $str            = $child_asset->asset_tag;
                    $asset_tag      = explode("#",$str);
                    if (isset($asset_tag[0]) && $asset_tag[0]=="RAM") 
                    {
                        $asset_details['ram_make'] = isset($asset_details['ram_make']) ? $asset_details['ram_make'] : '';
                        
                        $asset_details['ram_capacity'] = isset($asset_details['ram_capacity']) ? $asset_details['ram_capacity'] : 0;

                        $ram .= $str.' ( '.$asset_details['ram_make'].'-'.$asset_details['ram_capacity'].'GB )</br>';
                        if (is_numeric($totalram) && is_numeric($asset_details['ram_capacity'])) 
                        {
                            $totalram = $totalram+$asset_details['ram_capacity'];
                        }
                        $no_rams++;

                    }
                    elseif(isset($asset_tag[0]) && $asset_tag[0]=="HDD") 
                    {
                        $asset_details['hdd_model'] = isset($asset_details['hdd_model']) ? $asset_details['hdd_model'] : '';

                        $asset_details['hdd_capacity'] = isset($asset_details['hdd_capacity']) ? $asset_details['hdd_capacity'] : 0;

                        $hdd .= $str.' ( '.$asset_details['hdd_model'].'-'.$asset_details['hdd_capacity'].'GB )</br>';
                        $no_hdd++;
                        if (is_numeric($totalmem) && is_numeric($asset_details['hdd_capacity'])) 
                        {
                            $totalmem = $totalmem+$asset_details['hdd_capacity'];
                        }
                    }
                    else
                    {
                        $attach.= $str.' ( '.$child_asset->display_name.' )</br>';
                    }
                    if (isset($child_asset->purchasecost) && is_numeric($child_asset->purchasecost)) 
                    {
                        $totalcost = $totalcost + $child_asset->purchasecost;
                    }

                    $ramArr[$child_asset->parent_asset_id]          = $ram;
                    $hddArr[$child_asset->parent_asset_id]          = $hdd;
                    $attachArr[$child_asset->parent_asset_id]       = $attach;
                    $noramsArr[$child_asset->parent_asset_id]        = $no_rams;
                    $nohddArr[$child_asset->parent_asset_id]        = $no_hdd;
                    $totalramArr[$child_asset->parent_asset_id]     = $totalram;
                    $totalmemArr[$child_asset->parent_asset_id]     = $totalmem;
                    $totalcostArr[$child_asset->parent_asset_id]    = $totalcost;
                }
            }
        }
        $bvdcloc           = _isset($inputdata,'bvdcloc');
        $location_id       = isset($bvdcloc['location_id']) ? $bvdcloc['location_id'] : [];
        $location          = isset($bvdcloc['location']) ? $bvdcloc['location'] : [];
        $bv_id             = isset($bvdcloc['bv_id']) ? $bvdcloc['bv_id'] : [];
        $business_vertical = isset($bvdcloc['businessvertical']) ? $bvdcloc['businessvertical'] : [];
        $dc_id             = isset($bvdcloc['dc_id']) ? $bvdcloc['dc_id'] : [];
        $datacenter        = isset($bvdcloc['datacenter']) ? $bvdcloc['datacenter'] : [];

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_assets AS a')
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id') 
                ->leftjoin('en_ci_vendors AS v', 'v.vendor_id', '=', 'ad.vendor_id')
                ->leftjoin('en_ci_templ_default AS ci', 'ci.ci_templ_id', '=', 'a.ci_templ_id')
                ->leftjoin('en_ci_types AS ci_type', 'ci_type.ci_type_id', '=','ci.ci_type_id')
                ->leftjoin('en_form_data_po AS po', 'po.po_id', '=', 'a.po_id')
                
                ->where('a.status', '!=', 'd');
                
                $query->when($ci_templ_id, function ($query) use ($ci_templ_id)
                { 
                    if ($ci_templ_id !="") 
                    {
                        return $query->where('a.ci_templ_id','=',DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")')); 
                    }
                });
                $query->when($filter_date_field, function ($query) use ($filter_date_field,$filter_date_value,$filter_date_range)
                {
                    
                    if ($filter_date_value !="") 
                    {
                        $filter_date = $this->getDate($filter_date_value);
                        if (is_array($filter_date) && count($filter_date)>0) 
                        {
                            $from   = $filter_date['start'];
                            $to     = $filter_date['end'];
                            return $query->whereBetween($filter_date_field, [$from, $to]);
                        }
                        else
                        {
                           return $query->whereDate($filter_date_field,'>=', $filter_date); 
                        }
                    }elseif ($filter_date_range !="") 
                    {
                        $date_range = explode(" - ", $filter_date_range);

                        $from = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";

                        $to   = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";

                        return $query->whereBetween($filter_date_field, [$from, $to]);
                    }
                });
                //User Acess rights BV LOC DC
                $query->when($location_id, function ($query) use ($location_id)
                {
                    $location_arr = [];
                    if (is_array($location_id) && !empty($location_id)) 
                    {
                        foreach ($location_id as $loc) 
                        {
                            $location_arr[]  = DB::raw('UUID_TO_BIN("'.$loc.'")');
                        }
                    }
                    $query->whereIn('a.location_id', $location_arr);
                });
                $query->when($bv_id, function ($query) use ($bv_id)
                {
                    $bv_arr = [];
                    if (is_array($bv_id) && !empty($bv_id)) 
                    {
                        foreach ($bv_id as $bv) 
                        {
                            $bv_arr[]  = DB::raw('UUID_TO_BIN("'.$bv.'")');
                        }
                    }
                    $query->whereIn('a.bv_id', $bv_arr);
                });
                $rep_loc = false;
                if (($location_id = array_search('a.location_id', $filter_fields)) !== false) 
                {
                    $filter_fields[$location_id] = DB::raw("BIN_TO_UUID(a.location_id) AS 'location'");
                    $rep_loc = true;
                }
                $rep_bv = false;
                if (($bv_id = array_search('a.bv_id', $filter_fields)) !== false) 
                {
                    $filter_fields[$bv_id] = DB::raw("BIN_TO_UUID(a.bv_id) AS 'business_vertical'");
                    $rep_bv = true;
                } 
                if ($filters && $filters !="") 
                {
                    $query = $this->getfilters($filters,$query,$cifilterfields);    
                }
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
                $rep_hdd = $rep_ram = false;
                if (isset($cifields) && is_array($cifields) && count($cifields)>0)
                {
                    foreach ($cifields as $cifield) 
                    {
                        if (($asset = array_search($cifield, $filter_fields)) !== false) 
                        {
                            if ($cifield == "ram") 
                            {
                                $filter_fields[$asset] = DB::raw('BIN_TO_UUID(a.asset_id) AS ram');
                                $rep_ram = true;
                            }
                            elseif ($cifield == "hdd") 
                            {
                                $filter_fields[$asset] = DB::raw('BIN_TO_UUID(a.asset_id) AS hdd');
                                $rep_hdd = true;
                            }
                            else
                            {
                              $filter_fields[$asset] = DB::raw('ad.asset_details->>"$.'.$cifield.'" AS `'.$cifield.'`');  
                            }   
                        }
                    }
                } 
                if (($attach = array_search('attach', $filter_fields)) !== false) 
                {
                    $filter_fields[$attach] = DB::raw('BIN_TO_UUID(a.asset_id) AS attach');
                    $rep_attach = true;
                }
                if (($no_rams = array_search('no_rams', $filter_fields)) !== false) 
                {
                    $filter_fields[$no_rams] = DB::raw('BIN_TO_UUID(a.asset_id) AS no_rams');
                    $rep_no_rams = true;
                }
                if (($no_hdd = array_search('no_hdd', $filter_fields)) !== false) 
                {
                    $filter_fields[$no_hdd] = DB::raw('BIN_TO_UUID(a.asset_id) AS no_hdd');
                    $rep_no_hdd = true;
                }
                if (($total_ram = array_search('total_ram', $filter_fields)) !== false) 
                {
                    $filter_fields[$total_ram] = DB::raw('BIN_TO_UUID(a.asset_id) AS total_ram');
                    $rep_totalram = true;
                }
                if (($total_mem = array_search('total_mem', $filter_fields)) !== false) 
                {
                    $filter_fields[$total_mem] = DB::raw('BIN_TO_UUID(a.asset_id) AS total_mem');
                    $rep_totalmem = true;
                }
                if (($total_cost = array_search('total_cost', $filter_fields)) !== false) 
                {
                    $filter_fields[$total_cost] = DB::raw('BIN_TO_UUID(a.asset_id) AS total_cost');
                    $rep_totalcost = true;
                }

                $data = $query->get($filter_fields);

                if (@$rep_loc === true && !$count)
                {
                    if (is_array($location) && count($location)>0) 
                    {
                        $data = $data->map(function ($data) use ($location){
                            foreach ($location as $key => $value)
                            {
                                $data->location = str_replace($key,$value,$data->location);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_bv === true && !$count)
                {
                    if (is_array($business_vertical) && count($business_vertical)>0) 
                    {
                        $data = $data->map(function ($data) use ($business_vertical){
                            foreach ($business_vertical as $key => $value)
                            {
                                $data->business_vertical = str_replace($key,$value,$data->business_vertical);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_ram === true && !$count)
                {
                    if (is_array($ramArr) && count($ramArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($ramArr){
                            foreach ($ramArr as $key => $value)
                            {
                                $data->ram = str_replace($key,$value,$data->ram);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_hdd === true && !$count)
                {
                    if (is_array($hddArr) && count($hddArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($hddArr){
                            foreach ($hddArr as $key => $value)
                            {
                                $data->hdd = str_replace($key,$value,$data->hdd);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_attach === true && !$count)
                {
                    if (is_array($attachArr) && count($attachArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($attachArr){
                            foreach ($attachArr as $key => $value)
                            {
                                $data->attach = str_replace($key,$value,$data->attach);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_no_rams === true && !$count)
                {
                    if (is_array($noramsArr) && count($noramsArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($noramsArr){
                            foreach ($noramsArr as $key => $value)
                            {
                                $data->no_rams = str_replace($key,$value,$data->no_rams);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_no_hdd === true && !$count)
                {
                    if (is_array($nohddArr) && count($nohddArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($nohddArr){
                            foreach ($nohddArr as $key => $value)
                            {
                                $data->no_hdd = str_replace($key,$value,$data->no_hdd);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_totalram === true && !$count)
                {
                    if (is_array($totalramArr) && count($totalramArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($totalramArr){
                            foreach ($totalramArr as $key => $value)
                            {
                                $data->total_ram = str_replace($key,$value.' GB',$data->total_ram);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_totalmem === true && !$count)
                {
                    if (is_array($totalmemArr) && count($totalmemArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($totalmemArr){
                            foreach ($totalmemArr as $key => $value)
                            {
                                $data->total_mem = str_replace($key,$value.' GB',$data->total_mem);
                            }
                            return $data;
                        });
                    }
                }
                if (@$rep_totalcost === true && !$count)
                {
                    if (is_array($totalcostArr) && count($totalcostArr)>0) 
                    {
                        $data = $data->map(function ($data) use ($totalcostArr){
                            foreach ($totalcostArr as $key => $value)
                            {
                                $data->total_cost = str_replace($key,$value,$data->total_cost);
                            }
                            return $data;
                        });
                    }
                }
                                 
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    //
    protected function getfilters($filters=[],$query=null,$cifilterfields=[])
    {
      foreach($filters as $filter)
      {
        if(isset($filter['filter_column']))
        {
            if($filter['filter_column'] == 'vendor')
            {
                $filter['filter_column'] = 'vendor_id';
            }
        }
        if (isset($filter['filter_column']) && isset($filter['criteria_match']) && isset($filter['criteria_value']) && isset($filter['criteria']))
        {
            if ($filter['criteria'] !="" && $filter['criteria'] == "contains" || $filter['criteria'] =="notcontains") 
            {
                $filter['criteria_value'] = '%' . $filter['criteria_value'] . '%';
            }
            elseif ($filter['criteria'] !="" && $filter['criteria'] == "start_with") 
            {
                $filter['criteria_value'] = $filter['criteria_value'] . '%';
            }
            elseif ($filter['criteria'] !="" && $filter['criteria'] == "end_with") 
            {
                $filter['criteria_value'] = '%' . $filter['criteria_value'];
            }
            else
            {
                $filter['criteria_value'] = $filter['criteria_value'];
            }

            $criteria_value_arr = [];
            if (is_array($filter['criteria_value']) && !empty($filter['criteria_value'])) 
            {
                foreach ($filter['criteria_value'] as $criteria_value) 
                {
                    $criteria = (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $criteria_value);
                    if($criteria === true)
                    {
                        $criteria_value_arr[]  = DB::raw('UUID_TO_BIN("'.$criteria_value.'")');
                    }
                    else
                    {
                        $criteria_value_arr[]  = $criteria_value;
                    }
                }
            }
            if (isset($cifilterfields) && is_array($cifilterfields) && count($cifilterfields)>0)
            {

                foreach ($cifilterfields as $cifilterfield) 
                {
                    if ($cifilterfield == $filter['filter_column']) 
                    {
                        $filter['filter_column'] = DB::raw('ad.asset_details->>"$.'.$cifilterfield.'"');    
                    }
                }
            }
            //$filter['criteria'] = $this->getcriteria($filter['criteria']);
            if ($filter['criteria_match'] !="" && $filter['criteria_match'] == "AND") 
            {
                //$query->where($filter['filter_column'], $filter['criteria'], $filter['criteria_value']);
                if ($filter['criteria']!="" && $filter['criteria']=="notequal") 
                {
                    $query->whereNotIn($filter['filter_column'], $criteria_value_arr);    
                }
                else
                {
                    $query->whereIn($filter['filter_column'], $criteria_value_arr);
                }
            }
            if ($filter['criteria_match'] !="" && $filter['criteria_match'] == "OR") 
            {
                if ($filter['criteria']!="" && $filter['criteria']=="notequal") 
                {
                    $query->orWhereNotIn($filter['filter_column'], $criteria_value_arr);    
                }
                else
                {
                    $query->orWhereIn($filter['filter_column'], $criteria_value_arr);
                }
                //$query->orWhere($filter['filter_column'], $filter['criteria'], $filter['criteria_value']);
            }
        }
      }
      return $query; 
    }
    protected function getcriteria($criteria="")
    {
        switch ($criteria)
        {
            case "equal":
                return "=";
                break;
            case "notequal":
                return "!=";
                break;
            case "contains":
            case "start_with":
            case "end_with":
                return "LIKE";
                break;
            case "notcontains":
                return "NOT LIKE";
                break;
            default:
                return "=";
        }
    }
    protected function getDate($filter_date_value="")
    {
        $hours = isset($filter_date_value['hours']) ? $filter_date_value['hours'] : 1;
        $date = date("Y-m-d H:i");
        $week =  date('W', strtotime($date));
        $year =  date('Y', strtotime($date));
        $Current = Date('N');
        $DaysToSunday = 7 - $Current;
        $DaysFromMonday = $Current - 1;
        $Sunday = Date('Y-m-d 00:00', StrToTime("+ {$DaysToSunday} Days"));
        $Monday = Date('Y-m-d 00:00', StrToTime("- {$DaysFromMonday} Days"));
        switch ($filter_date_value) 
        {
            case "last_15_min":
                return date("Y-m-d H:i",strtotime("$date -15 min"));
                break;               
            case "last_30_min":
                return date("Y-m-d H:i",strtotime("$date -30 min"));
                break;                                  
            case "last_1_hour":
                return date("Y-m-d H:i",strtotime("$date -1 hours"));
                break;                                  
            case "last_6_hour":
                return date("Y-m-d H:i",strtotime("$date -6 hours"));
                break;                                  
            case "last_12_hour":
                return date("Y-m-d H:i",strtotime("$date -12 hours"));
                break;                                  
            case "last_24_hour":
                return date("Y-m-d H:i",strtotime("$date -24 hours"));
                break;                                  
            case "last_3_days":
                return date("Y-m-d H:i",strtotime("$date -3 day"));
                break;                                 
            case "last_7_days":
                return date("Y-m-d H:i",strtotime("$date -7 day"));
                break;                                 
            case "last_15_days":
                return date("Y-m-d H:i",strtotime("$date -15 day"));
                break;                                  
            case "last_30_days":
                return date("Y-m-d H:i",strtotime("$date -30 day"));
                break;                                  
            case "last_60_days":
                return date("Y-m-d H:i",strtotime("$date -60 day"));
                break;                               
            case "last_90_days":
                return date("Y-m-d H:i",strtotime("$date -90 day"));
                break;                                  
            case "last_6_month":
                return date("Y-m-d H:i",strtotime("$date -6 month"));
                break;                                  
            case "last_1_year":
                return date("Y-m-d H:i",strtotime("$date -1 year"));
                break;                                  
            case "last_2_year":
                return date("Y-m-d H:i",strtotime("$date -2 year"));
                break;                                  
            case "today":
                return date("Y-m-d 00:00");
                break;                                                                   
            case "yesterday":
                return date("Y-m-d H:i",strtotime("$date -1 day"));
                break;                                  
            case "day_b4_yest":
                return date("Y-m-d H:i",strtotime("$date -2 day"));
                break;
            case "week_to_date":
                return date("Y-m-d 00:00", strtotime("{$year}-W{$week}-0")); 
                break; 
            case "month_to_date":
                return date('Y-m-01 00:00'); // hard-coded '01' for first day 
                break;
            case "year_to_date":
                return date('Y-01-01 00:00'); 
                break;
            case "this_week":
                return ["start" => $Monday, "end" => $Sunday];
                break;
            case "this_month":
                return ["start" => date('Y-m-01 00:00:00',strtotime('this month')), "end" => date('Y-m-t 12:59:59',strtotime('this month'))];
                break;
            case "this_year":
                return ["start" => date('Y-01-01 00:00:00'), "end" => date('Y-12-31 12:59:59')];
                break;                               
            default:
                return date("Y-m-d 00:00");
        }
    } 

    protected function get_pbireports($from_date, $to_date, $report_type) 
    {
        $from           =  date("Y-m-d",strtotime($from_date));
        $monthly_date   = date("Y-m-d",strtotime($to_date));
        $to             = date('Y-m-d', strtotime($to_date . ' +1 day'));

        if($report_type == 'open_po') {
            $query =    DB::table('en_form_data_po')->  
                        select(
                            DB::raw("ROW_NUMBER() OVER(ORDER BY en_form_data_po.created_at) AS 'Sr. No'"),
                            'en_form_data_po.po_no AS PO NO',
                            'en_form_data_po.created_at AS PO DATE',
                            DB::raw("DATE_FORMAT('".$monthly_date."','%d/%m/%Y') AS 'monthly Date'"),
                            DB::raw("json_extract(en_form_data_pr.details, '$.project_name') as 'Project Name'"),
                            DB::raw("(select vendor_name from en_ci_vendors WHERE vendor_id = uuid_to_bin(JSON_UNQUOTE(json_extract(en_form_data_po.details, '$.pr_vendor')))) as 'Vendor Name'"),
                            // DB::raw("(select count(*) from en_pr_po_asset_details WHERE pr_po_id = en_form_data_po.po_id) as 'Qty'"),
                            DB::raw("(select SUM(json_extract(en_pr_po_asset_details.asset_details, '$.item_qty')) from en_pr_po_asset_details WHERE pr_po_id = en_form_data_po.po_id) AS QTY"),
                            DB::raw("('') AS OTC"),
                            'en_form_data_po.po_amt AS Amount',
                            DB::raw("('') as Description"),
                            'en_form_data_po.status AS Remark'
                        )->
                        leftjoin('en_form_data_pr', 'en_form_data_po.pr_id', '=', 'en_form_data_pr.pr_id')->
                        whereNotIn('en_form_data_po.status', ['closed','cancelled','deleted','rejected','ordered','hold'])->
                        whereDate('en_form_data_po.created_at','<', $to)->
                        orderBy('en_form_data_po.created_at');
        } else if($report_type == 'supporting') {
            $query =    DB::table('en_ci_vendors')->  
                        select(
                            DB::raw("ROW_NUMBER() OVER(ORDER BY en_ci_vendors.created_at) AS 'Sr. NO'"),
                            'en_ci_vendors.created_at AS Vendor Joing Date',
                            'en_ci_vendors.vendor_name AS New Vendor Empanelment',
                            'en_ci_vendors.products_services_offered AS Description of Material and Service Provider',
                            'en_ci_vendors.city AS Location')->
                        whereBetween('en_ci_vendors.created_at', [$from, $to])->
                        where('en_ci_vendors.status', '!=', 'd')->
                        orderBy('en_ci_vendors.created_at');
        } else {
              $query =    DB::table('en_form_data_po')->  
                        select(
                            DB::raw("ROW_NUMBER() OVER(ORDER BY en_form_data_po.created_at) AS 'Sr No'"),
                            'en_form_data_po.po_no AS PO Number',
                            'en_form_data_po.created_at AS PO Date',
                            DB::raw("json_extract(en_form_data_pr.details, '$.project_name') AS Project"),
                            DB::raw("(select vendor_name from en_ci_vendors WHERE vendor_id = uuid_to_bin(JSON_UNQUOTE(json_extract(en_form_data_po.details, '$.pr_vendor')))) AS Vendor"),
                            DB::raw("('') AS description"),
                            // DB::raw("(select count(*) from en_pr_po_asset_details WHERE pr_po_id = en_form_data_po.po_id) AS QTY"),
                            DB::raw("(select SUM(json_extract(en_pr_po_asset_details.asset_details, '$.item_qty')) from en_pr_po_asset_details WHERE pr_po_id = en_form_data_po.po_id) AS QTY"),
                            DB::raw("('') AS OTC"),
                            DB::raw("('') AS 'ARC Charges'"),
                            'en_form_data_po.po_amt AS AMOUNT',
                            DB::raw("('') AS 'Saving/Profit'"),
                            'en_form_data_po.status AS Remark'
                        )->
                        leftjoin('en_form_data_pr', 'en_form_data_po.pr_id', '=', 'en_form_data_pr.pr_id')->
                        whereBetween('en_form_data_pr.created_at', [$from, $to])->
                        orderBy('en_form_data_po.created_at');
        }
        $data = $query->get();
        return $data;       
    }
//End of class    
}