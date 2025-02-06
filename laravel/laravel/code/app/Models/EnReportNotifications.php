<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnReportNotifications extends Model 
{
	use HasBinaryUuid;
    public $incrementing    = false;
	protected $table        = 'en_report_notifications';	
    protected $fillable     = [
        'notification_id','report_id','report_name','export_type','user_id','read','read_at','status'
    ];
	protected $primaryKey   = 'notification_id';
	public function getKeyName()
    {
        return 'notification_id';
    }

    protected function getnotifications($notification_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_report_notifications')   
                ->select(DB::raw('BIN_TO_UUID(notification_id) AS notification_id'),DB::raw('BIN_TO_UUID(user_id) AS user_id'),DB::raw('BIN_TO_UUID(report_id) AS report_id'),'report_name','read','read_at','export_type','status','created_at',DB::raw('"report" as notification_type'))
                ->where('en_report_notifications.status', '!=', 'd')
				->where('en_report_notifications.read', '!=', 'y');
                
                $query->where(function ($query) use ($searchkeyword, $notification_id){
                    $query->where(function ($query) use ($searchkeyword, $notification_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                return $query->where('en_report_notifications.report_name', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($notification_id, function ($query) use ($notification_id)
                        {
                            return $query->where('en_report_notifications.notification_id', '=', DB::raw('UUID_TO_BIN("'.$notification_id.'")'));
                        });});
                   //user Acessiblity
                    $user_id    = isset($inputdata['loggedinuserid']) ? $inputdata['loggedinuserid'] : '';
                    $is_admin   = isset($inputdata['ENMASTERADMIN']) ? $inputdata['ENMASTERADMIN'] : '';
                    if($is_admin !="" && $is_admin !="y")
                    {   
                        if ($user_id != "") 
                        {
                           $query->where('en_report_notifications.user_id', '=', DB::raw('UUID_TO_BIN("'.$user_id.'")'));
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

}