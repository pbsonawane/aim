<?php
namespace App\Http\Controllers\asset;
use App\Http\Controllers\Controller;
use App\Models\EnAssetDetails;
use App\Models\EnAssets;
use App\Models\EnCiTemplCustom;
//use App\Services\IAM\IamService;ImportAssetsService
use App\Services\ImportAssetsService;
use App\Models\EnCiTemplDefault;
use App\Models\EnAssetHistory;
use App\Models\EnRelationshipType;
use App\Models\EnImportNotifications;
use App\Models\EnContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Jobs\ImportAssets;
use App\Services\RemoteApi;
use App\Models\EnVendors;

class ImportController extends Controller
{
    var $multiassets;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        DB::connection()->enableQueryLog();
        $this->remote_api = new RemoteApi();    
        $this->multiassets = array();
        $this->ci = array('server','desktop','laptop');
        $this->ciitem = array('ethernet','ram','hdd');
        $this->status = array('in_store','in_use','in_repair','expired','disposed');
    }

    function importnotification(Request $request)
    {
        $inputdata = $request->all();
        $totalrecords = EnImportNotifications::getnotifications($inputdata, true);
        $result = EnImportNotifications::getnotifications($inputdata, false);
        $data['data']['records'] = $result->isEmpty() ? null : $result;
        $data['data']['totalrecords'] = $totalrecords;
         if ($totalrecords < 1) {
                $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
        } else {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
        }
        $data['status'] = 'success';
        return response()->json($data);
    }
    function importdata(Request $request)
    {

        $dir_exist       = true;
        $data['data']   = $request;
        $username       = $request['ENUSERNAME'];
        $prof_photo     = $request['files_content'];
        $farray         = $request['farray'];
        //print_r($farray); die();

        $saveimg        = "importfile_".time().".csv";
        $target_dir     = public_path('uploads/import/'); // add the specific path to save the file
        /*$result = $target_dir;
            $data['data']             = $result;
            $data['message']['success'] = 'success';
            $data['status'] = 'success';
            return response()->json($data);*/
        $decoded_file   = base64_decode($prof_photo); // decode the file
        //$decoded_file = base64_decode($request->input('file')); // decode the file
        $file_dir       = $target_dir."/".$saveimg;

        $validator = Validator::make($request->all(), [
            'files_content' => 'required',
            'farray' => 'required'
        ]);        
        if($validator->fails())
        {
            $error          = $validator->errors(); 
            $data['data']   = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
         try 
        {
            
            //check directory exists or not.
            if (!file_exists($target_dir)) {
                $dir_exist = mkdir($target_dir, 0766, true);
            }

            if($dir_exist && is_writable($target_dir))
            {
                if (file_exists($file_dir) && !is_writable($file_dir)){
                    $data['data']   = null;
                    $data['message']['error'] = "No write permission";
                    $data['status'] = 'error';
                    save_errlog("importdata","This controller function is implemented to encode CSV file.",$request->all(),$data['message']['error']);
                    return response()->json($data);
                }
                
                if(file_put_contents($file_dir, $decoded_file)){
                    header('Content-Type: application/json');
                    $request['content']         = $decoded_file;
                    $request['profile_photo']   = $saveimg;
                    $insertdata['import_name'] = $farray['cititle'].'_'.date('d_m_Y_h_i_s_a');
                    $insertdata['filename'] = $saveimg;
                    $insertdata['importdata'] = json_encode($farray);
                    $insertdata['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                    $notif_data = EnImportNotifications::create($insertdata);
                    if(!empty($notif_data['notification_id']))
                    {
                      $token = $request->input('token');  
                      $notification_id   = $notif_data->notification_id_text;
                      $job = (new ImportAssets($notification_id,$token))->onQueue('importassets');
                      $this->dispatch($job);
                      $data['data']['insert_id']  = $notification_id;
                      $data['message']['success'] = showmessage('msg_importsuccess', array('{name}'),array(trans('label.asset')));
                      $data['status']             = 'success';
                    }
                    else
                    {
                      $data['data']   = NULL;
                      $data['message']['error'] = showmessage('msg_importfail', array('{name}'),array(trans('label.asset')));
                      $data['status'] = 'error';
                    }

                    return response()->json($data);
                }
                else
                {

                    $data['data']   = null;
                    $data['message']['error'] = showmessage('145', array('{name}'), array(trans('label.lbl_csv'))); //144/ 145
                    $data['status'] = 'error';
                    save_errlog("importdata","This controller function is implemented to encode import file.",$request->all(),$data['message']['error']);
                    return response()->json($data);
                }
            }
            else{
                $data['data']   = null;
                $data['message']['error'] = trans('messages.msg_nowritepermissiondir');//msg_nowritepermissionfile
                $data['status'] = 'error';
                save_errlog("importdata","This controller function is implemented to encode import file.",$request->all(),$data['message']['error']);
                return response()->json($data);
            }
        }
        catch (Exception $e) 
        {
            //header('Content-Type: application/json');
            save_errlog("profilephotosave","This controller function is implemented to encode user image.",$request->all(),$e->getMessage());
            //echo json_encode($e->getMessage());
            return response()->json($data);
        }

    }
    
} // Class End
