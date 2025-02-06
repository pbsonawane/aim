<?php
use Illuminate\Http\Request;
//use App\Models\EnUserLog as UserLogModel;
use App\Models\EnUserNotification as UserNotification;
use Illuminate\Support\Facades\DB;
/**
 * Function is used to collect and add user activity log in database.
 *
 * @param array $logdata
 * @return true;
 */
function user_notification($notification_data = array())
{
    $type=isset($notification_data['type'])?$notification_data['type']:'';
    $message=isset($notification_data['message'])?$notification_data['message']:'';
    $store_user=isset($notification_data['store_user'])?$notification_data['store_user']:'';
    $show_user=isset($notification_data['show_user'])?$notification_data['show_user']:'';
    $notification_read=isset($notification_data['notification_read'])?$notification_data['notification_read']:'';
    $read=isset($notification_data['read'])?$notification_data['read']:'';
    $read_at=isset($notification_data['read_at'])?$notification_data['read_at']:'';

    $action=isset($notification_data['action'])?$notification_data['action']:'';

      // 'type','message','store_user','show_user','notification_read','read','read_at','status'

    
    /*
    $log = [];
    $log['json_string'] = json_encode($logdata);
    $log['url'] = $request->fullUrl();
    $log['method'] = $request->method();
    $log['ip'] = $request->ip();
    $log['agent'] = $request->header('user-agent');    
    //$log['user_id'] = $user_id;
    $log['user_id'] = DB::raw('UUID_TO_BIN("'.$user_id.'")');
	$logid = UserLogModel::create($log);
	
	/* Disable temparary till elastic search to complete set up */
	
	// save to elastic search
    if($action=='add')
    {
           // 'type','message','store_user','show_user','notification_read','read','read_at','status'
    	$UserLogs = new UserNotification;
    	$UserLogs->type = $type;
        $UserLogs->message = $message;
        $UserLogs->store_user = $store_user;
        $UserLogs->show_user = $show_user;
        $UserLogs->notification_read = $notification_read;
    	$UserLogs->read = $read;
    	$UserLogs->read_at = $read_at;
        $UserLogs->status = $status;
    	$UserLogs->save();
    }
    return true;
}