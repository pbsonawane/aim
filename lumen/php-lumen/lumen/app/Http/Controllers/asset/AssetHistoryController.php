<?php
namespace App\Http\Controllers\asset;

use App\Http\Controllers\Controller;
use App\Models\EnAssetDetails;
use App\Models\EnAssets;
use App\Models\EnCiTemplCustom;
use App\Models\EnCiTemplDefault;
use App\Models\EnAssetHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class AssetHistoryController extends Controller
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
    }
    /*
    *This is controller funtion used to List Asset history.
    * @author      Amit Khairnar
    * @access       public
    * @param        URL : asset_id [Optional]
    * @param_type   Integer
    * @return       JSON
    * @tables       en_assets_history
    */

    public function assethistory(Request $request)
    {
      //  try
       // {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {

            $inputdata = $request->all();
            $inputdata['asset_id'] = trim(_isset($inputdata, 'asset_id'));
            $asset_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('asset_id').'")');
            $result = DB::table('en_asset_history')
            ->select(DB::raw('BIN_TO_UUID(asset_id) AS asset_id'), DB::raw('BIN_TO_UUID(user_id) AS user_id'), 'action', 'message', 'updated_at','comment')            
            ->where('asset_id', '=', $asset_id_bin)
            ->orderBy('updated_at','DESC')
            ->get();
            $data['data']['records'] = $result->isEmpty() ? NULL : $result;   
            if (count($result) > 1) {
                $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
            } else {
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
            }
            $data['status'] = 'success';

            return response()->json($data);
        }
        /*}
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("assets","This controller function is implemented to get  Asset.",$request->all(),$e->getMessage());
           return response()->json($data); 
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assets","This controller function is implemented to get Assey.",$request->all(),$e->getMessage());
            return response()->json($data); 
        }  */
    //}
    }
    public function assignassethistory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'asset_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {

            $inputdata = $request->all();
            $inputdata['asset_id'] = trim(_isset($inputdata, 'asset_id'));
            $asset_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('asset_id').'")');
            $result = DB::table('en_assets_assign')
            ->select(DB::raw('BIN_TO_UUID(asset_id) AS asset_id'),DB::raw('BIN_TO_UUID(department_id) AS department_id'), DB::raw('BIN_TO_UUID(en_ci_requesternames.requestername_id) AS requestername_id'),DB::raw('concat(en_ci_requesternames.fname," ",en_ci_requesternames.lname) AS requester_name'),'en_ci_requesternames.employee_id', 'assign_date', 'en_assets_assign.status', 'return_date','en_assets_assign.created_at','en_assets_assign.updated_at')    
            ->join('en_ci_requesternames', 'en_ci_requesternames.requestername_id', '=', 'en_assets_assign.requestername_id')        
            ->where('asset_id', '=', $asset_id_bin)
            ->orderBy('created_at', 'DESC')
            ->get();
            $data['data']['records'] = $result->isEmpty() ? NULL : $result;   
            if (count($result) > 1) {
                $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
            } else {
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
            }
            $data['status'] = 'success';

            return response()->json($data);
        }
        
    }

} // Class End
