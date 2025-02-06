<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPurchaseRequest extends Model 
{
	use HasBinaryUuid;
	public $incrementing = false;
	protected $table = 'en_form_data_pr';
   //	public $timestamps = false;	
	protected $fillable = [
		'pr_id', 'form_templ_id', 'form_templ_type', 'details','asset_details','approval_details','approved_status', 'requester_id','approval_req', 'bv_id', 'dc_id', 'location_id', 'status', 'pr_no', 'estimate_cost','estimate_cost_comment','estimate_status','remark'
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
		$requester_id1 = _isset($inputdata,'requester_id1');
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
		->select(DB::raw('BIN_TO_UUID(pr_id) AS pr_id'),'estimate_cost','estimate_cost_comment','estimate_status',DB::raw('BIN_TO_UUID(form_templ_id) AS form_templ_id'), 'details','approval_req', 'pr_no','en_form_data_pr.status', 'en_form_data_pr.created_at', 'asset_details', 'approval_details','approved_status',DB::raw('BIN_TO_UUID(assignpr_user_id) AS assignpr_user_id'), DB::raw('BIN_TO_UUID(requester_id) AS requester_id'));           

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
			if(is_array($requester_id)){
				$ids =  array_map(function($requester_id){
					return DB::raw('UUID_TO_BIN("'.$requester_id.'")');
				},$requester_id);
				$query->whereIn('en_form_data_pr.requester_id', $ids);
			}else{
				$query->where('en_form_data_pr.requester_id', '=', DB::raw('UUID_TO_BIN("'.$inputdata['requester_id'].'")'));	
			}

		}

		if(!empty($dept_type) && $dept_type == 'store'){
			$query->where('en_form_data_pr.status', '!=', 'pending approval');
			$query->where('en_form_data_pr.status', '!=', 'rejected');
			if($searchkeyword == "" || $searchkeyword == "pending approval" || $searchkeyword == "rejected")
			{
				$query->orWhereIn('en_form_data_pr.pr_id',function($query) use ($requester_id1)
				{
					if(!empty($requester_id1)){
						if(is_array($requester_id1)){
							$requester_id1 =  array_map(function($requester_id1){
								return DB::raw('UUID_TO_BIN("'.$requester_id1.'")');
							},$requester_id1);
						
						}else{
							$requester_id1 = $requester_id1;
						}
	
					}
	
					$query->select('pr_id')
					->from('en_form_data_pr')
					->where('status', '=', 'pending approval')
					->whereIn('requester_id', $requester_id1);
				});
			}			
		}

		if(!empty($dept_type) && $dept_type == 'purchase'){

			$query->where(DB::raw("JSON_EXTRACT(en_form_data_pr.approved_status, '$.convert_to_pr')"), '!=', '');

			if(!empty($dept_type) && $flag == true){
				$query->where('assignpr_user_id', '=', DB::raw('UUID_TO_BIN("'.$user_id.'")'));
			}else{

			}
		}
		if(!empty($dept_type) && ($dept_type == 'payment_committee' || $dept_type == 'internal_auditor')){
			$query->where(DB::raw("JSON_EXTRACT(en_form_data_pr.approved_status, '$.convert_to_pr')"), '!=', '');
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
		// $data = $query->orderBy('en_form_data_pr.created_at', 'desc')->get();
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

	protected function get_pr_list () {
		return $data 	= 	DB::table('en_form_data_pr')->
							join('en_pr_po_asset_details', 'en_pr_po_asset_details.pr_po_id', '=', 'en_form_data_pr.pr_id')->
							where('en_pr_po_asset_details.asset_type','=','pr')->
							join('en_assets', DB::raw("BIN_TO_UUID(en_assets.asset_id)") , '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`en_pr_po_asset_details`.`asset_details`,'$.item_product'))"))->

							select(
								DB::raw('BIN_TO_UUID(en_form_data_pr.pr_id) AS pr_id'),
								'en_form_data_pr.pr_no',
								'en_form_data_pr.details',
								DB::raw('GROUP_CONCAT(en_pr_po_asset_details.asset_details SEPARATOR "#") as asset_details'),
								DB::raw('GROUP_CONCAT(JSON_OBJECT(BIN_TO_UUID(`en_assets`.`asset_id`),`en_assets`.`display_name`)) as asset_name'),
								'en_form_data_pr.created_at',
								'en_form_data_pr.status',
								'en_form_data_pr.approval_details',
								'en_form_data_pr.approved_status',
								DB::raw('BIN_TO_UUID(`en_form_data_pr`.`assignpr_user_id`) AS assignpr_user_id'),
								DB::raw('BIN_TO_UUID(`en_form_data_pr`.`requester_id`) AS requester_id'),
								'en_form_data_pr.remark')->
							whereNotIn('en_form_data_pr.status', ['closed','rejected'])->
							groupBy('en_form_data_pr.pr_id')->
							get();
	}	

	protected function addremark($inputdata) {
		$data 	= 	DB::table('en_form_data_pr')->
					select(
						DB::raw('BIN_TO_UUID(en_form_data_pr.pr_id) AS pr_id'),
						'remark'
					)->
					where('pr_id', '=', DB::raw('UUID_TO_BIN("'.$inputdata['pr_id'].'")'))->
					get();
		
		$res 	= 	json_decode(json_encode($data[0]), true);

		if($res['remark'] != 'null') {
			$tmp_remark 	  	= 	", " . date('Y-m-d h:i:s') . " " . trim($inputdata['add_remark'])." ( "  . trim($inputdata['requestername']) . " )";
			$remark 			= 	json_decode($res['remark']);
			array_push($remark, $tmp_remark);
		} else {
			$remark 			= 	array("" . date('Y-m-d h:i:s') . " " . trim($inputdata['add_remark'])." ( " . trim($inputdata['requestername']) . " )" );
		}
		return $res 			= 	DB::table('en_form_data_pr')->
									where('pr_id', '=', DB::raw('UUID_TO_BIN("'.$inputdata['pr_id'].'")'))->
									update(array('remark' => json_encode($remark)));
	}


	protected function add_poremark($inputdata) {
		$data 	= 	DB::table('en_form_data_po')->
					select(
						DB::raw('BIN_TO_UUID(en_form_data_po.po_id) AS po_id'),
						'remark'
					)->
					where('po_id', '=', DB::raw('UUID_TO_BIN("'.$inputdata['po_id'].'")'))->
					get();
		
		$res 	= 	json_decode(json_encode($data[0]), true);

		if($res['remark'] != 'null') {
			$tmp_remark 	  	= 	"" . date('Y-m-d h:i:s') . " " . trim($inputdata['add_remark']);

			$remark 			= 	json_decode($res['remark']);
			if($remark == "")
			{
				$remark = array();
			}
			array_push($remark, $tmp_remark);
		} else {
			$remark 			= 	array("" . date('Y-m-d h:i:s') . " " . trim($inputdata['add_remark']) );
			
		}
	
		return $res 			= 	DB::table('en_form_data_po')->
									where('po_id', '=', DB::raw('UUID_TO_BIN("'.$inputdata['po_id'].'")'))->
									update(array('remark' => json_encode($remark)));
	}

	protected function get_track_pr_list($inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }

        $query  = 	DB::table('en_form_data_pr')->
        			join('en_pr_po_asset_details', 'en_pr_po_asset_details.pr_po_id', '=', 'en_form_data_pr.pr_id')->
						where('en_pr_po_asset_details.asset_type','=','pr')->
					join('en_assets', DB::raw("BIN_TO_UUID(en_assets.asset_id)") , '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`en_pr_po_asset_details`.`asset_details`,'$.item_product'))"))->
					join('en_ci_templ_default', DB::raw("BIN_TO_UUID(en_assets.ci_templ_id)") , '=' , DB::raw("BIN_To_UUID(en_ci_templ_default.ci_templ_id)"))->
	                select(
	                    DB::raw('BIN_TO_UUID(en_form_data_pr.pr_id) AS pr_id'),
						'en_form_data_pr.pr_no',
						'en_ci_templ_default.ci_name',
						'en_form_data_pr.details',
						DB::raw('GROUP_CONCAT(en_pr_po_asset_details.asset_details SEPARATOR "#") as asset_details'),
						DB::raw('GROUP_CONCAT(JSON_OBJECT(BIN_TO_UUID(`en_assets`.`asset_id`),`en_assets`.`display_name`)) as asset_name'),
						'en_form_data_pr.created_at',
						'en_form_data_pr.status',
						'en_form_data_pr.approval_details',
						'en_form_data_pr.approved_status',
						DB::raw('BIN_TO_UUID(`en_form_data_pr`.`assignpr_user_id`) AS assignpr_user_id'),
						DB::raw('BIN_TO_UUID(`en_form_data_pr`.`requester_id`) AS requester_id'),
						'en_form_data_pr.remark'
					);
                	// whereNotIn('en_form_data_pr.status', ['closed','rejected']);
        $query->where(function ($query) use ($searchkeyword){
			$query->where(function ($query) use ($searchkeyword) {
				$query->when($searchkeyword, function ($query) use ($searchkeyword)
				{							
					return $query->where('en_form_data_pr.pr_no', 'like', '%' . $searchkeyword . '%')
					->orWhere('en_form_data_pr.status', 'like', '%' . strtolower($searchkeyword) . '%');
				});       
			}); 
		});     
                                 
        $query->when(!$count, function ($query) use ($inputdata) {
            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
            {
                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
            }
        });
        $query->groupBy('en_form_data_pr.pr_id');
        $query->orderBy('en_form_data_pr.updated_at', 'desc');
        $data = $query->get();                        
                                            
        if($count)
            return count($data);
        else      
            return $data;    
    }

    protected function get_track_pr_list_for_export($inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        
         if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }

        $query  = 	DB::table('en_form_data_pr')->
        			join('en_pr_po_asset_details', 'en_pr_po_asset_details.pr_po_id', '=', 'en_form_data_pr.pr_id')->
						where('en_pr_po_asset_details.asset_type','=','pr')->
					join('en_assets', DB::raw("BIN_TO_UUID(en_assets.asset_id)") , '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`en_pr_po_asset_details`.`asset_details`,'$.item_product'))"))->
					join('en_ci_templ_default', DB::raw("BIN_TO_UUID(en_assets.ci_templ_id)") , '=' , DB::raw("BIN_To_UUID(en_ci_templ_default.ci_templ_id)"))->
	                select(
	                    DB::raw('BIN_TO_UUID(en_form_data_pr.pr_id) AS pr_id'),
						'en_form_data_pr.pr_no',
						'en_ci_templ_default.ci_name',
						'en_form_data_pr.details',
						DB::raw('GROUP_CONCAT(en_pr_po_asset_details.asset_details SEPARATOR "#") as asset_details'),
						DB::raw('GROUP_CONCAT(JSON_OBJECT(BIN_TO_UUID(`en_assets`.`asset_id`),`en_assets`.`display_name`)) as asset_name'),
						'en_form_data_pr.created_at',
						'en_form_data_pr.status',
						'en_form_data_pr.approval_details',
						'en_form_data_pr.approved_status',
						DB::raw('BIN_TO_UUID(`en_form_data_pr`.`assignpr_user_id`) AS assignpr_user_id'),
						DB::raw('BIN_TO_UUID(`en_form_data_pr`.`requester_id`) AS requester_id'),
						'en_form_data_pr.remark'
					);
                	// whereNotIn('en_form_data_pr.status', ['closed','rejected']);
        $query->where(function ($query) use ($searchkeyword){
			$query->where(function ($query) use ($searchkeyword) {
				$query->when($searchkeyword, function ($query) use ($searchkeyword)
				{							
					return $query->where('en_form_data_pr.pr_no', 'like', '%' . $searchkeyword . '%')
					->orWhere('en_form_data_pr.status', 'like', '%' . strtolower($searchkeyword) . '%');
				});       
			}); 
		});     
                                 
        $query->when(!$count, function ($query) use ($inputdata) {
            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
            {
                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);


            }
        });
        $query->groupBy('en_form_data_pr.pr_id');
        $query->orderBy('en_form_data_pr.created_at', 'desc');
        $data = $query->get();                        
                                            
        if($count)
            return count($data);
        else      
            return $data;    
    }


}



          