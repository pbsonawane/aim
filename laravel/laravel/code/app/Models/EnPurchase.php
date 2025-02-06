<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPurchase extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_form_data_pr';
   //	public $timestamps = false;	
    protected $fillable = [
        'pr_id', 'form_templ_id', 'form_templ_type', 'details','asset_details','approval_details','approved_status', 'requester_id','approval_req', 'bv_id', 'dc_id', 'location_id', 'status'
    ];
	
	protected $primaryKey = 'pr_id';
	public function getKeyName()
    {
        return 'pr_id';
    }
	
	protected function getprs($pr_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_form_data_pr')   
                ->select(DB::raw('BIN_TO_UUID(pr_id) AS pr_id'),DB::raw('BIN_TO_UUID(form_templ_id) AS form_templ_id'), 'details','approval_req', 'status', 'created_at', 'asset_details', 'approval_details','approved_status', DB::raw('BIN_TO_UUID(requester_id) AS requester_id'))            
                ->where('en_form_data_pr.status', '!=', 'deleted');
				$query->where(function ($query) use ($searchkeyword, $pr_id){
                  $query->where(function ($query) use ($searchkeyword, $pr_id) {
					$query->when($searchkeyword, function ($query) use ($searchkeyword)
						{
							
							 return $query->where('en_form_data_pr.status', 'like', '%' . $searchkeyword . '%');
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
                $data = $query->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    	}


}