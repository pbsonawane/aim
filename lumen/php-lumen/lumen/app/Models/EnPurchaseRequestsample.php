<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPurchaseRequestsample extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_form_data_pr_sample';
   //	public $timestamps = false;	
    protected $fillable = [
        'pr_id', 'form_templ_id', 'form_templ_type', 'details','status', 'pr_no','pr_requester_name','requester_id'
    ];
	
	protected $primaryKey = 'pr_id';
	public function getKeyName()
    {
        return 'pr_id'; 
    }
	
	protected function getprs($pr_id, $inputdata=array(), $count=false)
    {	
		//$user_bv_ids = array('13883632-2b95-11e9-9038-0242ac110004','68cf0180-35ca-11ea-bd9c-0242ac110003');
        $searchkeyword = _isset($inputdata,'searchkeyword');
        $requester_id = _isset($inputdata,'requester_id');
        $dept_type = _isset($inputdata,'dept_type');
        $user_id = _isset($inputdata,'user_id');
        $flag = _isset($inputdata,'flag');
        $assignpr_user_id = _isset($inputdata,'user_id');
        $vendor_id = _isset($inputdata,'vendor_id');
        $timerange = _isset($inputdata,'timerange');
        $customtime = _isset($inputdata,'customtime');
        $vendor_id = _isset($inputdata,'vendor_id');
        $issuperadmin = _isset($inputdata,'issuperadmin');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_form_data_pr')   
                ->select(DB::raw('BIN_TO_UUID(pr_id) AS pr_id'),DB::raw('BIN_TO_UUID(form_templ_id) AS form_templ_id'), 'details','approval_req', 'pr_no','en_form_data_pr.status', 'en_form_data_pr.created_at', 'asset_details', 'approval_details','approved_status',DB::raw('BIN_TO_UUID(assignpr_user_id) AS assignpr_user_id'), DB::raw('BIN_TO_UUID(requester_id) AS requester_id'));           
                 
                if(!empty($vendor_id)){	 	           
                    $query->join('en_ci_quotation_comparison', 'en_ci_quotation_comparison.pr_po_id', '=', 'en_form_data_pr.pr_id');
	               	$query->where(DB::raw('JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.vendor_id")'), '=', $vendor_id);
	            }
	            if(!empty($customtime)){	 	           
	               	$query->whereBetween(DB::raw('date(en_form_data_pr.created_at)'),[$customtime['start_date'],$customtime['end_date']]);
	            }
	            if(!empty($timerange)){	 	           
	               	$query->whereBetween(DB::raw('date(en_form_data_pr.created_at)'),[$timerange,date('Y-m-d')]);
	            }
	            $query->where('en_form_data_pr.status', '!=', 'deleted');
	            if(!empty($requester_id)){
	                $query->where('en_form_data_pr.requester_id', '=', DB::raw('UUID_TO_BIN("'.$inputdata['requester_id'].'")'));
	            }
	            if(!empty($dept_type) && $dept_type == 'store'){
	                $query->where('en_form_data_pr.status', '!=', 'pending approval');
	            }

	            if(!empty($dept_type) && $dept_type == 'purchase'){
	               
	                	 $query->where(DB::raw("JSON_EXTRACT(en_form_data_pr.approved_status, '$.convert_to_pr')"), '!=', '');
	               
	                if(!empty($dept_type) && $flag == true){
	                	$query->where('assignpr_user_id', '=', DB::raw('UUID_TO_BIN("'.$user_id.'")'));
	                }else{

	                }
	            }
	            if(!empty($assignpr_user_id)){	 	                
	                $query->where('assignpr_user_id', '=', DB::raw('UUID_TO_BIN("'.$assignpr_user_id.'")'));	              
	            }
	            if(!empty($vendor_id)){	 	           
                     $query->groupBy('en_ci_quotation_comparison.pr_po_id');
	            }

				 //->whereIn('en_form_data_pr.details->bv_id',$user_bv_ids);
				$query->where(function ($query) use ($searchkeyword, $pr_id){
                  $query->where(function ($query) use ($searchkeyword, $pr_id) {
					$query->when($searchkeyword, function ($query) use ($searchkeyword)
						{
							
							return $query->where('en_form_data_pr.status', 'like', '%' . $searchkeyword . '%')
							->orWhere('en_form_data_pr.pr_no', 'like', '%' . $searchkeyword . '%')
							->orWhereRaw('LOWER(JSON_EXTRACT(en_form_data_pr.details, "$.pr_title")) like ?', ['"%' . strtolower($searchkeyword) . '%"']);
							//->orWhere('en_ci_vendors.vendor_ref_id', 'like', '%' . $searchkeyword . '%')
						});       
					}); 
					$query->when($pr_id, function ($query) use ($pr_id)
					{
						return $query->where('en_form_data_pr.pr_id', '=', DB::raw('UUID_TO_BIN("'.$pr_id.'")'));
					});
					
				});
				
                $query->when(!$count, function ($query) use ($inputdata)
				{
					if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
					{
						return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
					}
				});
                $data = $query->orderBy('en_form_data_pr.updated_at', 'desc')->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    }
    protected function getAddresses($pr_ids)
    {

    	$ids =  array_map(function($id){
                          return DB::raw('UUID_TO_BIN("'.$id.'")');
                        },$pr_ids);
    	return  DB::table('en_form_data_pr')
                ->join('en_ship_to', DB::raw('bin_to_uuid(en_ship_to.shipto_id)'), '=', DB::raw('JSON_UNQUOTE(en_form_data_pr.`details`->"$.pr_shipto")'))
                ->whereIn('pr_id',$ids)
                ->select(DB::raw("BIN_TO_UUID(pr_id) as pr_id"), DB::raw("JSON_UNQUOTE(`details`->'$.pr_shipto') as pr_shiptoid"), DB::raw('if(JSON_UNQUOTE(`details`->"$.pr_shipto")="9ff21ebb-46d2-11ec-9512-764a8a13ae2c",JSON_UNQUOTE(`details`->"$.ship_to_other"),address) as address'))
                ->get();
    }


}