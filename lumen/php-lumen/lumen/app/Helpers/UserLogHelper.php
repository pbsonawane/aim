<?php
use Illuminate\Http\Request;
//use App\Models\EnUserLog as UserLogModel;
use App\Models\EnActivityLog as UserActivityModel;
use App\Models\EnUserNotification as UserNotificationModel;
use Illuminate\Support\Facades\DB;
/**
 * Function is used to collect and add user activity log in database.
 *
 * @param array $logdata
 * @return true;
 */
function userlog($logdata = array())
{
    $request = request();
    $input = $request->all();
    $user_id = array_key_exists('loggedinuserid', $input) ? $input['loggedinuserid'] : $logdata['record_id'];
    $fullname = array_key_exists('ENFULLNAME', $input) ? $input['ENFULLNAME'] : '';
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
	
	/* Disable temparary till elastic search to complete set up
	
	// save to elastic search
	/*$UserLogs = new UserActivityModel;
	$UserLogs->json_string = json_encode($logdata);
    $UserLogs->url = $request->fullUrl();
    $UserLogs->method = $request->method();
    $UserLogs->ip = $request->ip();
    $UserLogs->agent = $request->header('user-agent');
	$UserLogs->ip = $request->ip();
	$UserLogs->action = $logdata['action'];
    $UserLogs->user_id = $user_id;
	$UserLogs->fullname = $fullname;
	$UserLogs->{'@usertime'} = date("M d H:i:s");
    $UserLogs->{'@usertimestamp'} = time();
	$UserLogs->save();*/
    return true;
}


function user_notification($notification_data = array())
{
    $type=isset($notification_data['type'])?$notification_data['type']:'';
    $message=isset($notification_data['message'])?$notification_data['message']:'';
    $store_user=isset($notification_data['store_user'])?$notification_data['store_user']:'';
    $show_user=isset($notification_data['show_user'])?$notification_data['show_user']:'';
    $notification_read=isset($notification_data['notification_read'])?$notification_data['notification_read']:'';
    $read=isset($notification_data['read'])?$notification_data['read']:'n';
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
        $UserLogs = new UserNotificationModel();
        $UserLogs->type = $type;
        $UserLogs->message = $message;
        $UserLogs->store_user = $store_user;
        $UserLogs->show_user = $show_user;
        $UserLogs->notification_read = $notification_read;
        $UserLogs->notification_read = $read;
        $UserLogs->read_at = $read_at;
      
        $UserLogs->save();
    }
    return true;
}