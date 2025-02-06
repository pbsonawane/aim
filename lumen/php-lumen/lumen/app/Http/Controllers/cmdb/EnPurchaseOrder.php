<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPurchaseOrder extends Model 
{
	use HasBinaryUuid;
	public $incrementing = false;
	protected $table = 'en_form_data_po';
   //	public $timestamps = false;	
	protected $fillable = [
		'po_id', 'pr_id', 'po_name','po_no', 'form_templ_id', 'form_templ_type', 'details','other_details','po_amt','approval_details', 'approved_status', 'requester_id','approval_req', 'bv_id', 'dc_id', 'location_id', 'status'
	];
	
	protected $primaryKey = 'po_id';
	public function getKeyName()
	{
		return 'po_id';
	}
	
	protected function getpos($po_id, $inputdata=array(), $count=false)
	{
		$searchkeyword = _isset($inputdata,'searchkeyword');
		$vendor_id = _isset($inputdata,'vendor_id');
		$po_amt_status = _isset($inputdata,'po_amt_status');
		$user_id = _isset($inputdata,'user_id');
		$dept_type = _isset($inputdata,'dept_type');
		$pr_type = _isset($inputdata,'pr_type');
		$timerange = _isset($inputdata,'timerange');
		$customtime = _isset($inputdata,'customtime');
		if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
		{
			unset($inputdata["offset"]);
			unset($inputdata["limit"]);
		}
		$query = DB::table('en_form_data_po')   
		->select(DB::raw('BIN_TO_UUID(po_id) AS po_id'), 
			DB::raw('BIN_TO_UUID(pr_id) AS pr_id'),
			DB::raw('BIN_TO_UUID(form_templ_id) AS form_templ_id'), 'details','approval_req', 'en_form_data_po.status', 'en_form_data_po.created_at', 'other_details', 'approval_details','approved_status','po_amt',
			DB::raw('BIN_TO_UUID(requester_id) AS requester_id'), 'po_name', 'po_no')->where('en_form_data_po.status', '!=', 'deleted');
		if(!empty($vendor_id)){	 	           
			$query->join('en_pr_po_asset_details', 'en_pr_po_asset_details.pr_po_id', '=', 'en_form_data_po.po_id');
			$query->where(DB::raw('JSON_EXTRACT(en_pr_po_asset_details.vendor_approval, "$.vendor_id")'), '=', $vendor_id);
		}
		if(!empty($user_id)){                   
			$query->where('en_form_data_po.requester_id', '=', DB::raw('UUID_TO_BIN("'.$user_id.'")'));
		}
		if(!empty($dept_type) && $dept_type == 'payment_committee'){                   
			$query->whereNotNull(DB::raw('JSON_EXTRACT(en_form_data_po.approved_status, "$.confirmed")'));
	               	// $query->where('en_form_data_po.status', '=', 'approved');
			// $query->where('en_form_data_po.po_amt', '>', '25000');
		}
		if(!empty($dept_type) && $dept_type == 'internal_auditor'){                   
			$query->whereNotNull(DB::raw('JSON_EXTRACT(en_form_data_po.approved_status, "$.confirmed")'));
			/*if($po_amt_status == 'gt25'){
				$query->where('en_form_data_po.po_amt', '>', '25000');
			}elseif($po_amt_status == 'lte25'){
				$query->where('en_form_data_po.po_amt', '<=', '25000');

			}*/
		}
		if(!empty($customtime)){	 	           
			$query->whereBetween(DB::raw('date(en_form_data_po.created_at)'),[$customtime['start_date'],$customtime['end_date']]);
		}
		if(!empty($timerange)){	 	           
			$query->whereBetween(DB::raw('date(en_form_data_po.created_at)'),[$timerange,date('Y-m-d')]);
		}

		$query->where(function ($query) use ($searchkeyword, $po_id,$pr_type)
		{
			$query->where(function ($query) use ($searchkeyword, $po_id) 
			{
				$query->when($searchkeyword, function ($query) use ($searchkeyword)
				{
					return $query->where('en_form_data_po.status', 'like', '%' . $searchkeyword . '%')
					->orWhere('en_form_data_po.po_name', 'like', '%' . $searchkeyword . '%')
					->orWhere('en_form_data_po.po_no', 'like', '%' . $searchkeyword . '%'); 
							//->orWhere('en_ci_vendors.vendor_ref_id', 'like', '%' . $searchkeyword . '%')

				});       
			}); 
			if($pr_type == '0'){
				$query->when($po_id, function ($query) use ($po_id)
				{
							// return $query->where('en_form_data_po.pr_id', '=', DB::raw('UUID_TO_BIN("'.$po_id.'")'));

							// This change need for multiple PR clubs in 1 PO 
					return $query->where(DB::raw('json_extract(`details`,"$.pr_id")'),'LIKE',"%{$po_id}%");
				});	
			}else{
				$query->when($po_id, function ($query) use ($po_id)
				{
					return $query->where('en_form_data_po.po_id', '=', DB::raw('UUID_TO_BIN("'.$po_id.'")'));
				});	
			}


		});

		if(!empty($vendor_id)){	 	           
			$query->groupBy('en_pr_po_asset_details.pr_po_id');
		}
		$query->orderBy('en_form_data_po.created_at', 'desc');
		// $query->orderBy('en_form_data_po.po_no', 'desc');
		$query->when(!$count, function ($query) use ($inputdata)
		{
			if (isset($inputdata["offset"]) && isset($inputdata["limit"]))
			{
				return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
			}
		});
		$data = $query->get();                        

		if($count === true)
			return   count($data);
		else      
			return $data;    
	}


	 protected function get_track_po_list($inputdata=array(), $count=false)
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