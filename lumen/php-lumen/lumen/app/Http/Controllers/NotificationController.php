<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\EnUserNotification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @author       Vikas Kumar
     * @access       public
     * @package purchaseorder
     * @return void
     */
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }
  
    public function getusernotification(Request $request)
    {
        try
        {
            // pr requested
            $prrequest=EnUserNotification::where('type','=','pr')
            ->where('notification_read','n')
            ->get()->count();
            $data['data']['prrequestcount']      =$prrequest; 

            // convert to pr
            $coverttopr=EnUserNotification::where('type','=','cpr')
            ->where('notification_read','n')
            ->get()->count();
            $data['data']['coverttopr']      =$coverttopr;

            //assign pr
            $assigntopr=EnUserNotification::where('type','=','apr')
            ->where('notification_read','n')
            ->where('show_user',DB::raw('UUID_TO_BIN("' . $request['showuserid'] . '")'))
            ->get()->count();
            $data['data']['assigntopr']      =$assigntopr;

            // quotation generated
            $quotationgenerated=EnUserNotification::where('type','=','qg')
            ->where('notification_read','n')
            ->get()->count();
            $data['data']['quotationgenerated']      =$quotationgenerated;

            // quotation approved
            $quotationapproved=EnUserNotification::where('type','=','qa')
            ->where('notification_read','n')
            ->get()->count();
            $data['data']['quotationapproved']      =$quotationapproved;

            // quotation rejected
            $quotationrejected=EnUserNotification::where('type','=','qr')
            ->where('notification_read','n')
            ->get()->count();
            $data['data']['quotationrejected']      =$quotationrejected;

            // convert to po
            $converttopo=EnUserNotification::where('type','=','cpo')
            ->where('notification_read','n')
            ->get()->count();
            $data['data']['converttopo']      =$converttopo;

           // EnUserNotification::where('notification_read','n')
           //  ->update(['notification_read'=>'y']);

            $data['message']['success'] = 'success';
            $data['status']           = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            return response()->json($data);
        
        }
    }

} // Class End
