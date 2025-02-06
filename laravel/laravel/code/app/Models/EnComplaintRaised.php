<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class EnComplaintRaised extends Model
{
    public $incrementing = true;
    protected $table     = 'en_complaint_raised';
    //    public $timestamps = false;
    protected $fillable = [
        'cr_id', 
        'complaint_raised_no', 
        'complaint_raised_date', 
        'user_id', 
        'requester_id', 
        'asset_id',
        'priority',
        'problemdetail',         
        'attachment', 
        'hod_id', 
        'hod_remark',
        'itfile',
        'itstatus', 
        'it_remark', 
        'it_status', 
        'vendor_id', 
        'store_remark', 
        'store_status', 
        'created_at', 
        'updated_at',
    ];

    protected $primaryKey = 'cr_id';
    public function getKeyName()
    {
        return 'cr_id';
    }

    protected function getcrs($cr_id, $inputdata = array(), $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        $requester_id  = _isset($inputdata, 'requester_id');
        $user_id       = _isset($inputdata, 'user_id');
        $department_name       = _isset($inputdata, 'department_name');
        $flag          = _isset($inputdata, 'flag');
        $timerange     = _isset($inputdata, 'timerange');
        $customtime    = _isset($inputdata, 'customtime');

        $issuperadmin = _isset($inputdata, 'issuperadmin');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_complaint_raised')
            ->select('cr_id', 'complaint_raised_no', 'complaint_raised_date', DB::raw('BIN_TO_UUID(requester_id) AS requester_id')
            , DB::raw('BIN_TO_UUID(user_id) AS user_id'), DB::raw('BIN_TO_UUID(asset_id) AS asset_id')
            , 'priority', 'problemdetail', 'attachment'
            , DB::raw('BIN_TO_UUID(hod_id) AS hod_id'), 'hod_remark','itfile', 'itstatus', 'it_remark', 'it_status', 
            'vendor_id', 'store_remark', 'store_status', 'status', 'created_at', 'updated_at');
        if (!empty($customtime)) {
            $query->whereBetween(DB::raw('date(en_complaint_raised.created_at)'), [$customtime['start_date'], $customtime['end_date']]);
        }
        if (!empty($timerange)) {
            $query->whereBetween(DB::raw('date(en_complaint_raised.created_at)'), [$timerange, date('Y-m-d')]);
        }

        if($department_name != "Internal-IT" && $department_name != "Store" )
        {
            if (!empty($user_id)) {
                $query->where('en_complaint_raised.user_id', '=', DB::raw('UUID_TO_BIN("' . $inputdata['user_id'] . '")'));
            }
    
            if (!empty($requester_id)) {
                $query->orWhere('en_complaint_raised.requester_id', '=', DB::raw('UUID_TO_BIN("' . $inputdata['requester_id'] . '")'));
            }
    
            if (!empty($user_id)) {
                $query->orWhere('en_complaint_raised.hod_id', '=', DB::raw('UUID_TO_BIN("' . $inputdata['user_id'] . '")'));
            }
        }

        if($department_name == "Internal-IT" || $department_name == "Store" )
        {
            $query->Where('en_complaint_raised.hod_status', '!=', "rejected");
        }


        if($department_name == "Store" )
        {
            $query->Where('en_complaint_raised.it_status', '!=', "PENDING");
        }

        $query->where(function ($query) use ($searchkeyword, $cr_id) {
            $query->where(function ($query) use ($searchkeyword, $cr_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('en_complaint_raised.it_status', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_complaint_raised.complaint_raised_no', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($cr_id, function ($query) use ($cr_id) {
                return $query->where('en_complaint_raised.cr_id', '=', $cr_id);
            });

        });

    $query->when(!$count, function ($query) use ($inputdata) {
        if (isset($inputdata["offset"]) && isset($inputdata["limit"])) {
            return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
        }
    });
    $data = $query->orderBy('en_complaint_raised.updated_at', 'desc')->get();

    if ($count) {
        return count($data);
    } else {
        return $data;
    }
    }

    protected function get_track_cr_list($inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query  = 	DB::table('en_complaint_raised')->
        			select(
                        'cr_id',
                        'complaint_raised_no',
                        'complaint_raised_date',
	                    DB::raw('BIN_TO_UUID(user_id) AS user_id'),
                        DB::raw('BIN_TO_UUID(requester_id) AS requester_id'),
                        DB::raw('BIN_TO_UUID(asset_id) AS asset_id'),
						'priority',
                        'problemdetail',
                        'attachment',
                        DB::raw('BIN_TO_UUID(hod_id) AS hod_id'),
						'hod_remark',
						'hod_status',
                        'itfile',
                        'itstatus',
                        'it_remark',
                        'it_status',
                        'storefile',
                        'store_remark',
                        'store_status',
                        'status',
                        'created_at',
                        'updated_at'
					);
                	// whereNotIn('en_form_data_pr.status', ['closed','rejected']);
        $query->where(function ($query) use ($searchkeyword){
			$query->where(function ($query) use ($searchkeyword) {
				$query->when($searchkeyword, function ($query) use ($searchkeyword)
				{							
					return $query->where('complaint_raised_no', 'like', '%' . $searchkeyword . '%')
					->orWhere('status', 'like', '%' . strtolower($searchkeyword) . '%');
				});       
			}); 
		});     
                                 
        $query->when(!$count, function ($query) use ($inputdata) {
            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
            {
                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
            }
        });
        $query->orderBy('created_at', 'desc');
        $data = $query->get();                        
                                            
        if($count)
            return count($data);
        else      
            return $data;   
    }
}
