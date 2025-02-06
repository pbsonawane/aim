<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnSoftware;
use App\Models\EnSoftwareInstall;
use App\Models\EnSoftwareHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ap\Models\EnAssets;
use App\Models\EnCiTemplCustom;
use App\Models\EnCiTypes;

use Validator;


class SoftwareController extends Controller
{
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
     *This is controller funtion used for Software.

     * @author       Kavita Daware
     * @access       public
     * @param        URL : software_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_software
     */
    public function softwares(Request $request,$software_id = null)
    {
        try
        {
            $requset['software_id'] = $software_id;
            $validator = Validator::make($request->all(), [
                'software_id' => 'nullable|string|size:36',
            ]);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            }
            else
            {

                $inputdata = $request->all();

                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                //print_r($inputdata);
                $totalrecords = EnSoftware::getsoftware($software_id, $inputdata, true);
                $result = EnSoftware::getsoftware($software_id, $inputdata, false);

                
                $data['data']['records'] = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;

                if ($totalrecords < 1)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software'));
                    $data['status'] = 'success';
                }
                $data['status'] = 'success';
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwares", "This is controller funtion used for Software.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwares", "This is controller funtion used for Software.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /*
    * This is controller funtion used to add the Software.

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id, software_name, software_category_id, software_manufacturer_id,description,ci_type,version
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_license    
    */
    public function softwareadd(Request $request)
    {
        //try
        //{
        $messages = [
            'software_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_type')), true),
            'software_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_name')), true),
			'software_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_name')), true),
			'software_category_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_category')), true),
            'software_manufacturer_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
			'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
			'ci_type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_citype')), true),
			'version.required' => showmessage('000', array('{name}'), array(trans('label.lbl_version')), true),
			'version.numeric' => showmessage('025', array('{name}'), array(trans('label.lbl_version')), true),
            
        ];
            $validator = Validator::make($request->all(), [
                'software_type_id' => 'required',
                'software_name' => 'required|composite_unique:en_software,software_name, '.$request->input('software_name'),
                'software_category_id' => 'required',
                'software_manufacturer_id' => 'required',
                //'license_type_id' => 'required',
                'description' => 'required',
                'ci_type' => 'required',
                'version' => 'required|numeric',
            ], $messages);

            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {
                $inputdata = $request->all();
                $software['software_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_type_id'].'")');
                $software['software_category_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_category_id'].'")');
                $software['software_manufacturer_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_manufacturer_id'].'")');
                //$software['license_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['license_type_id'].'")');

                $software['software_name'] = _isset($inputdata, 'software_name');
                $software['description'] = _isset($inputdata, 'description');
                $software['ci_type'] = _isset($inputdata, 'ci_type');
                $software['version'] = _isset($inputdata, 'version');
                $software['status'] = _isset($inputdata, 'status', 'y');

                $software_data = EnSoftware::create($software);
                //$software['software_id'] = DB::raw('UUID_TO_BIN("' . $software_data->software_id_text . '")');

                if (!empty($software_data['software_id'])) {
                    $software['software_id'] = DB::raw('UUID_TO_BIN("' . $software_data->software_id_text . '")');
                 //Add Software History    
                 $history_data = array();
                 $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');//'7117a498-41c3-11ea-9e9a-0242ac110003';
                 apilog($history_data['user_id']);
                 $history_data['software_id'] = $software['software_id'];
                 $history_data['action'] = "Added";
                 $history_data['message'] = showmessage('039');
                 EnSoftwareHistory::create($history_data);
                }

                if ($software_data->software_id_text != '')
                {
                    $software_id = $software_data->software_id_text;
                    $data['data']['insert_id'] = $software_id;
                    $data['message']['success'] = showmessage('104', array('{name}'), array('Software'));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('103', array('{name}'), array('Software'));
                    $data['status'] = 'error';
                }

            }

            return response()->json($data);
        /*}
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareadd", "This is controller funtion used to add softwares.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareadd", "This is controller funtion used to add softwares.", $request->all(), $e->getMessage());
            return response()->json($data);
        }*/

    }
    /* Provides a window to user to update the software information.

     * @author       Kavita Daware
     * @access       public
     * @param        software_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_software
     */
    public function softwareedit(Request $request)
    {
        try
        {

            $validator = Validator::make($request->all(), [
                'software_id' => 'required|string|size:36',
            ]);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {
                $inputdata = $request->all();
                $result = EnSoftware::getsoftware($request->input('software_id'), $inputdata);

                $data['data'] = $result->isEmpty() ? null : $result;

                if ($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software'));
                    $data['status'] = 'success';
                }
                else
                {

                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software'));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareedit", "Provides a window to user to update the software information..", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareedit", "Provides a window to user to update the software information..", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        
    }
    /*
     * Updates the Software Type information, which is entered by user on Edit Software window.

     * @author       Kavita Daware
     * @access       public
     * @param        software_type_id, software_name, software_category_id, software_manufacturer_id,description,ci_type,version
     * @param_type   POST array
     * @return       JSON
     * @tables       en_software
     */
    public function softwareupdate(Request $request)
    {
        try
        {

            $messages = [
                'software_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_type')), true),
				'software_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_name')), true),
				'software_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_name')), true),
                'software_category_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_category')), true),
                'software_manufacturer_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
				'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
				'ci_type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_citype')), true),
				'version.required' => showmessage('000', array('{name}'), array(trans('label.lbl_version')), true),
				'version.numeric' => showmessage('025', array('{name}'), array(trans('label.lbl_version')), true),
                
            ];
            $validator = Validator::make($request->all(), [
                'software_type_id' => 'required',
                'software_name' => 'required|composite_unique:en_software, software_name, '.$request->input('software_name').', software_id,'.$request->input('software_id'),
                'software_category_id' => 'required',
                'software_manufacturer_id' => 'required',
                //'license_type_id' => 'required',
                'description' => 'required',
                'ci_type' => 'required',
                'version' => 'required|numeric',

            ], $messages);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {

                $software_id_uuid = $request->input('software_id');
                $software_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                $request['software_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');

                $software_type_id = $request->input('software_type_id');

                $request['software_type_id'] = DB::raw('UUID_TO_BIN("'.$software_type_id.'")');

                $software_category_id = $request->input('software_category_id');

                $request['software_category_id'] = DB::raw('UUID_TO_BIN("'.$software_category_id.'")');

                $software_manufacturer_id = $request->input('software_manufacturer_id');

                $request['software_manufacturer_id'] = DB::raw('UUID_TO_BIN("'.$software_manufacturer_id.'")');

                $license_type_id = $request->input('license_type_id');

                //$request['license_type_id'] = DB::raw('UUID_TO_BIN("'.$license_type_id.'")');

                $result = EnSoftware::where('software_id', $software_id_bin)->first();

                if ($result)
                {
                    $result->update($request->all());
                    $result->save();
                   
                     //Update Software History    
                     $history_data = array();
                     $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                     $history_data['software_id'] = $request['software_id'];
                     $history_data['action'] = "Update";
                     $history_data['message'] = showmessage('040');
                     EnSoftwareHistory::create($history_data);
                    
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Software '));
                    $data['status'] = 'success';

                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array('Software'));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareupdate", "Updates the Software Type information, which is entered by user on Edit Software window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareupdate", "Updates the Software Type information, which is entered by user on Edit Software window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        
    }

    /* This function is used to delete software record.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software     
    */
    public function softwaredelete(Request $request,$software_id = null)
    {
        try
        {
            $request['software_id'] = $software_id;
            $messages = [
                'software_id.required' => showmessage('000', array('{name}'), array('Software Id'), true),
            ];

            $validator = Validator::make($request->all(), [
                'software_id' => 'required|string|size:36',
            ], $messages);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            }
            else
            {
                $data = EnSoftware::checkforrelation($software_id);
                 //Delete Software History    
                 $history_data = array();
                 $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                 $history_data['software_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                 $history_data['action'] = "Deleted";
                 $history_data['message'] = showmessage('041');
                 EnSoftwareHistory::create($history_data);
                //Add into UserActivityLog
                if ($data['data'])
                {
                    userlog(array('record_id' => $software_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Software'))));
					//$data['data'] = null;
                    $data['message']['success'] = showmessage('118', array('{name}'), array('Software'));
                    $data['status'] = 'success';
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaredelete", "This function is used to delete software record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaredelete", "This function is used to delete software record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /* This function is used to get ci template id.

    * @author       Kavita Daware
    * @access       public
    * @param        variable_name
    * @param_type   Integer
    * @return       JSON
    * @tables       en_ci_templ_default     
    */

    public function getcitempid(Request $request, $variable_name = null)
    {

        try
        {

                $bv_id = $request->input('bv_id');
                $location_id = $request->input('location_id');

                $query = DB::table('en_ci_templ_default')
                    ->select(DB::raw('BIN_TO_UUID(en_ci_templ_default.ci_templ_id) AS ci_templ_id'), 'variable_name')
                    ->leftjoin('en_assets', 'en_ci_templ_default.ci_templ_id', '=', 'en_assets.ci_templ_id')
                    ->where('variable_name', '=', $variable_name);
                if($bv_id != ""){
                    $query->where(DB::raw('BIN_TO_UUID(bv_id)'),$bv_id);
                }
                if($location_id != ""){
                    $query->where(DB::raw('BIN_TO_UUID(location_id)'),$location_id);
                }
                $result = $query->get();

                /*$result = DB::table('en_ci_templ_default')
                ->select(DB::raw('BIN_TO_UUID(en_ci_templ_default.ci_templ_id) AS ci_templ_id'), 'variable_name')
                ->leftjoin('en_assets', 'en_ci_templ_default.ci_templ_id', '=', 'en_assets.ci_templ_id')
                ->where('variable_name', '=', 'server')
                ->where(DB::raw('BIN_TO_UUID(bv_id)'),'13883632-2b95-11e9-9038-0242ac110004')
                ->where(DB::raw('BIN_TO_UUID(location_id)'),'0584834a-2b97-11e9-bc8c-0242ac110004')
                ->get();
             
                /*SELECT en_assets.bv_id,en_assets.location_id,en_ci_templ_default.ci_templ_id,en_ci_templ_default.variable_name FROM en_assets
LEFT JOIN en_ci_templ_default ON en_assets.ci_templ_id = en_ci_templ_default.ci_templ_id where variable_name ='server' and
bv_id = UUID_TO_BIN('13883632-2b95-11e9-9038-0242ac110004');

*/

            $queries = DB::getQueryLog();
            $data['last_query'] = end($queries);
            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array('Template software'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array('Template software'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getcitempid", "This function is used to get ci template id.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getcitempid", "This function is used to get ci template id.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }
    /* This function is used to save asset in software installation table.

    * @author       Kavita Daware
    * @access       public
    * @param        sw_install_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_installation     
    */
    public function swattachassetsave(Request $request)
    {
        //try
        //{
        $messages = [
                'selectassetids.required' => showmessage('000', array('{name}'), array(trans('label.lbl_asset_id')), true),
                
            ];
            $inputdata = $request->all();
            $validator = Validator::make($request->all(), [
                // 'sw_install_id' => 'required',
                'selectassetids' => 'required',
                //'software_id' => 'required',

            ], $messages);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {
                $getresult = EnSoftwareInstall::getswinstallation($inputdata);
                $software_id = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                $result = EnSoftwareInstall::where('software_id', $software_id)->first();
    
                if ($result)
                {
                    $ass = $getresult[0]->asset_id;
                    $curr_asset_id = json_to_array($ass);
                    
                    $asset = _isset($inputdata, 'selectassetids');
                   // $software['asset_id'] = json_encode($asset);

                    $asset_id = array_merge($curr_asset_id,  $asset);
                    $asset_id = array_unique(array_filter($asset_id));
                   
                    $asset_id = converttojson($asset_id, "array");

                    $update_asset_id = array("asset_id" => $asset_id);
                    
                    $result->update($update_asset_id);
                    $result->save();

                    //Add Software Asset History    
                    $history_data = array();
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                    $history_data['software_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                    $history_data['action'] = "Add Software Installation";
                    $history_data['message'] = showmessage('042');
                    EnSoftwareHistory::create($history_data);
                    
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Software Assets'));
                    $data['status'] = 'success';
                    
                }
                else
                {
                    $asset = _isset($inputdata, 'selectassetids');
                    $software['asset_id'] = json_encode($asset);
                    $software['status'] = _isset($inputdata, 'status', 'y');
                    $software['software_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_id'].'")');
                    $software_data = EnSoftwareInstall::create($software);


                    //Add Software Asset History    
                    $history_data = array();
                    $history_data['user_id'] = '7117a498-41c3-11ea-9e9a-0242ac110003';//DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                    $history_data['software_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                    $history_data['action'] = "Add Software Installation";
                    $history_data['message'] = showmessage('042');
                    EnSoftwareHistory::create($history_data);

                    if (!empty($software_data['sw_install_id']))
                    {
                        $sw_install_id = $software_data->software_id_text;
                        $data['data']['insert_id'] = $sw_install_id;
                        $data['message']['success'] = showmessage('104', array('{name}'), array('Software Installation'));
                        $data['status'] = 'success';
                    }
                    else
                    {
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('104', array('{name}'), array('Software Installation'));
                        $data['status'] = 'error';
                    }
                }

            }

            return response()->json($data);
        /*}
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swattachassetsave", "This is controller funtion used to add softwares installation.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swattachassetsave", "This is controller funtion used to add softwares installation.", $request->all(), $e->getMessage());
            return response()->json($data);
        }*/
    }

    /* This function is used to get records from software installation according to assets id fetch assets is from en_assets table.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id,asset_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_installation     
    */

    public function softwareinstallation(Request $request,$software_id = null)
    {
        //try
        //{
            $software_id = $request->input('software_id');
            
            $sw_resp = DB::table('en_software_installation')
                ->select('asset_id')
                ->where(DB::raw('BIN_TO_UUID(software_id)'), $software_id)
                ->where('status', '=', 'y')
                //->whereNotIn('asset_id', $as)
                ->first();
           
            //$selectassetids = $sw_resp->asset_id;
            $selectassetids = isset($sw_resp->asset_id) ? $sw_resp->asset_id : "";
            
            $asset = $selectassetids != "" ? json_decode($selectassetids, true) : array();
            //$asset = json_decode($selectassetids, true);

            if (is_array($asset) && count($asset) > 0)
            {
                $result = DB::table('en_assets')
                    ->select(DB::raw('BIN_TO_UUID(asset_id) AS asset_id'), DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), 'display_name', 'asset_tag')
                    ->whereIn(DB::raw('BIN_TO_UUID(asset_id)'), $asset)
                    ->get();
                $queries = DB::getQueryLog();
                $data['last_query'] = end($queries);
            }
            else
            {
                $result = array();
                $data['last_query'] = "";
            }

            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array('Asset software'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array('Asset software'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        /*}
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareinstallation", "This function is used to get records from software installation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareinstallation", "This function is used to get records from software installation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }*/
    }

    /* This function is used to remove asset from software installation.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id,asset_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_installation     
    */
    public function swassetremove(Request $request,$software_id = null, $asset_id = null)
    {
        try
        {
            $pdata = EnSoftwareInstall::getswinstallation($request->all());
            //print_r($pdata);
            //die;
            $validator = Validator::make($request->all(), [
                'software_id' => 'required',
                'asset_id' => 'required',

            ]);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            }
            else
            {

                $assetremove = EnSoftwareInstall::swassetremove($request->input('software_id'), $request->input('asset_id'));

                
                //$queries = DB::getQueryLog();
                //$last_query = end($queries);
                if ($assetremove)
                {
                     //Add Software Asset Remove History    
                     $history_data = array();
                     $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                     $history_data['software_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                     $asset_tag = $request->input('asset_tag');
                     $history_data['action'] = "Remove Software Asset.$asset_tag.";
                     $history_data['message'] = showmessage('043');
                     EnSoftwareHistory::create($history_data);
                    $data['data'] = null;
                    $data['status'] = 'success';
                    $data['message']['success'] = showmessage('118', array('{name}'), array('Software Asset'), true);
                }
                else
                {

                    $data['data'] = null;
                    $data['status'] = 'error';
                    $data['message']['error'] = showmessage('119', array('{name}'), array('Software Asset'), true);
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swassetremove", "This function is used to remove asset from software installation.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swassetremove", "This function is used to remove asset from software installation.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }

    /* This function is used to get all software history.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_history     
    */
  
    public function getswhistory(Request $request)
    {
        //try
        //{
            $software_id = $request->input('software_id');
            $validator = Validator::make($request->all(), [
                'software_id' => 'nullable|string|size:36',
            ]);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            }
            else
            {
                 $inputdata = $request->all();
                $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
                $totalrecords = EnSoftwareHistory::getswhistory($software_id,$inputdata, true);  
                $result = EnSoftwareHistory::getswhistory($software_id, $inputdata , false);  
                
                $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                $data['data']['totalrecords'] = $totalrecords;                
               
                if ($totalrecords < 0)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software history'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software history'));
                    $data['status'] = 'success';
                }

                // $inputdata = $request->all();
                // $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                
                // $totalrecords = EnSoftwareHistory::getswhistory($software_id, $inputdata, true);
                // $result = EnSoftwareHistory::getswhistory($software_id, $inputdata, false);
                // $queries    = DB::getQueryLog();
                // $data['last_query'] = end($queries);

                // //$software_id = $request->input('software_id');
                // //$result = EnSoftwareHistory::getswhistory($software_id);

                
                // $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                // $data['data']['totalrecords'] = $totalrecords;                
               
                // if ($totalrecords > 0)
                // {

                // //  if ($result)
                // // {

                // $data['data'] = $totalrecords;
                // $data['message']['success'] = showmessage('101', array('{name}'), array('software'));
                // $data['status'] = 'success';

                // }
                // else
                // {
                // $data['data'] = null;
                // $data['message']['error'] = showmessage('102', array('{name}'), array('software'));
                // $data['status'] = 'error';
                // }

                return response()->json($data);
            }
        /*}
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getswhistory", "This is controller funtion used for Software History.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getswhistory", "This is controller funtion used for Software History.", $request->all(), $e->getMessage());
            return response()->json($data);
        }*/
    }

    /* This function is used to get software record to display on software type on software dashboard.

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software    
    */
    public function swdashboard(Request $request)
    {  
        /*
        $result = DB::table('en_software AS s')   
            ->select(DB::raw('BIN_TO_UUID(en_software_types.software_type_id) as software_type_id'),'en_software_types.software_type',DB::raw('count(en_software_types.software_type) as count'))
            ->leftjoin('en_software_types', 's.software_type_id', '=', 'en_software_types.software_type_id') 
            ->groupBy('software_type')
            ->where('s.status', '!=', 'd')
           ->get(); 
           /*SELECT BIN_TO_UUID(en_software.software_type_id),software_type, Count(software_type)
          FROM en_software LEFT JOIN en_software_types
            ON en_software.software_type_id = en_software_types.software_type_id
         GROUP BY software_type*/


        //----------------------------------------------------------------
         //select BIN_TO_UUID(en_software.software_type_id) as software_type_id, `software_type`, count(en_software.software_type_id) as count , en_software_types.status as typestatus,en_software.status as swstatus from `en_software` right join `en_software_types` on `en_software`.`software_type_id` = `en_software_types`.`software_type_id` and en_software_types.status = 'y' and en_software.status = 'y' where en_software_types.status = 'y' group by `software_type`, `en_software`.`software_type_id`

                $result = DB::table('en_software')
                    ->select(DB::raw('BIN_TO_UUID(en_software.software_type_id) as software_type_id'),
                    'software_type',
                    DB::raw('count(en_software.software_type_id) as count'))
                    ->rightjoin('en_software_types', function($join)
                    {
                        $join->on('en_software.software_type_id', '=', 'en_software_types.software_type_id')
                            ->where('en_software_types.status', '=', 'y')
                            ->where('en_software.status', '=', 'y');
                    })
                    ->where('en_software_types.status', 'y')
                    ->groupBy('software_type')
                    ->groupBy('en_software.software_type_id')
                    ->get();
        //----------------------------------------------------------------


            //$queries = DB::getQueryLog();
            //$last_query = end($queries);
            //print_r($last_query);
            
           if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array(' software dashboard'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array(' software dashboard'));
                $data['status'] = 'error';
            }
            return response()->json($data);                                                          
    }


    /* This function is used to get software license record to display  licese type on software dashboard.
    * @author       Kavita Daware
    * @access       public
    * @param        license_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_license    
    */
    public function swdashboardlicense(Request $request)
    {  
            /*
            $result = DB::table('en_software_license AS s')   
                ->select('en_license_type.license_type',DB::raw('count(en_license_type.license_type) as count'))
                ->leftjoin('en_license_type', 's.license_type_id', '=', 'en_license_type.license_type_id') 
                ->groupBy('license_type')
               ->get(); 

               /*SELECT license_type, Count(license_type)
                  FROM en_software_license LEFT JOIN en_license_type
                    ON en_software_license.license_type_id = en_license_type.license_type_id
                 GROUP BY license_type;*/

                //----------------------------------------------------------------------
                //  SELECT license_type, Count(en_software_license.license_type_id)  as count
                //  FROM en_software_license right JOIN en_license_type 
                //  ON en_software_license.license_type_id = en_license_type.license_type_id 
                //  and en_software_license.status = 'y' and en_license_type.status = 'y'
                //  where en_license_type.status = 'y'
                //  GROUP BY license_type,en_software_license.license_type_id

                $result = DB::table('en_software_license')   
                ->select('en_license_type.license_type',DB::raw('count(en_software_license.license_type_id) as count'))
                ->rightjoin('en_license_type', function($join)
                    {
                        $join->on('en_software_license.license_type_id', '=', 'en_license_type.license_type_id')
                            ->where('en_software_license.status', '=', 'y')
                            ->where('en_license_type.status', '=', 'y');
                    })
                ->where('en_license_type.status', 'y')
                ->groupBy('license_type')
                ->groupBy('en_software_license.license_type_id')
                ->get();
                //----------------------------------------------------------------------



            //$queries = DB::getQueryLog();
            //$last_query = end($queries);
            
           if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'error';
            }
            return response()->json($data);                                                          
    }



        public function swdashboardswtype(Request $request)
    {  
            $software_type = $request->input('software_type');
            
            $result = DB::table('en_software AS s')   
                ->select(DB::raw('BIN_TO_UUID(s.software_id) AS software_id'), DB::raw('BIN_TO_UUID(en_software_types.software_type_id) as software_type_id'),DB::raw('BIN_TO_UUID(en_software_category.software_category_id) as software_category_id'),DB::raw('BIN_TO_UUID(en_software_manufacturer.software_manufacturer_id) as software_manufacturer_id'),DB::raw('BIN_TO_UUID(en_license_type.license_type_id) as license_type_id'),'software_name','s.description','s.ci_type','s.version','en_software_types.software_type','en_software_category.software_category','en_software_manufacturer.software_manufacturer','en_license_type.license_type')
                ->leftjoin('en_software_types', 's.software_type_id', '=', 'en_software_types.software_type_id') 
                ->leftjoin('en_software_category', 's.software_category_id', '=', 'en_software_category.software_category_id') 
                ->leftjoin('en_software_manufacturer', 's.software_manufacturer_id', '=', 'en_software_manufacturer.software_manufacturer_id') 
                ->leftjoin('en_license_type', 's.license_type_id', '=', 'en_license_type.license_type_id')
                ->where('en_software_types.software_type', '=', $software_type)
                ->get();


            //$queries = DB::getQueryLog();
            //$last_query = end($queries);
            
           if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array(' software type'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array(' software type'));
                $data['status'] = 'error';
            }
            return response()->json($data);                                                          
        }
            
        /* This function is used to get software manufacturer record to display manufacturers on software dashboard.

    * @author       Kavita Daware
    * @access       public
    * @param        software_manufacturer_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_manufacturer    
    */
    public function swdashboardmanufacturer(Request $request)
    {  
       /* 
            $result = DB::table('en_software AS s')   
                ->select(DB::raw('BIN_TO_UUID(en_software_manufacturer.software_manufacturer_id) as software_manufacturer_id'),'en_software_manufacturer.software_manufacturer',DB::raw('count(en_software_manufacturer.software_manufacturer) as count'))
                ->leftjoin('en_software_manufacturer', 's.software_manufacturer_id', '=', 'en_software_manufacturer.software_manufacturer_id') 
                ->groupBy('software_manufacturer')
                ->where('s.status', '!=', 'd')
               ->get(); 

               /*SELECT software_manufacturer, Count(software_manufacturer)
                  FROM en_software LEFT JOIN en_software_manufacturer
                    ON en_software.software_manufacturer_id = en_software_manufacturer.software_manufacturer_id
                 GROUP BY software_manufacturer ;*/


                 //----------------------------------------------------------
                 //select BIN_TO_UUID(en_software.software_manufacturer_id) as software_manufacturer_id, `software_manufacturer`, count(en_software.software_manufacturer_id) as count from `en_software` right join `en_software_manufacturer` on `en_software`.`software_manufacturer_id` = `en_software_manufacturer`.`software_manufacturer_id` and `en_software_manufacturer`.`status` = 'y' and `en_software`.`status` = 'y' where `en_software_manufacturer`.`status` = 'y' group by `software_manufacturer`, `en_software`.`software_manufacturer_id`
                
                $result = DB::table('en_software')
                    ->select(DB::raw('BIN_TO_UUID(en_software.software_manufacturer_id) as software_manufacturer_id'),
                    'software_manufacturer',
                    DB::raw('count(en_software.software_manufacturer_id) as count'))
                    ->rightjoin('en_software_manufacturer', function($join)
                    {
                        $join->on('en_software.software_manufacturer_id', '=', 'en_software_manufacturer.software_manufacturer_id')
                            ->where('en_software_manufacturer.status', '=', 'y')
                            ->where('en_software.status', '=', 'y');
                    })
                    ->where('en_software_manufacturer.status', 'y')
                    ->groupBy('software_manufacturer')
                    ->groupBy('en_software.software_manufacturer_id')
                    ->get();
                 //----------------------------------------------------------

            //$queries = DB::getQueryLog();
            //$last_query = end($queries);



            
           if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array(' software dashboard manufacturer'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array(' software dashboard manufacturer'));
                $data['status'] = 'error';
            }
            return response()->json($data);                                                          
        }
    public function swreport(Request $request,$asset_id = NULL)
    {  
            $asset_id = $request->input('asset_id');
            $result = DB::table('en_software AS sla')
                ->select(DB::raw('BIN_TO_UUID(sla.software_id) AS software_id'),'sla.software_name','sla.version','en_software_license.license_key','en_software_installation.created_at','en_license_type.license_type')
                ->leftjoin('en_software_installation', 'sla.software_id', '=', 'en_software_installation.software_id')
                ->leftjoin('en_software_license_allocation', 'sla.software_id', '=', 'en_software_license_allocation.software_id')
                 ->leftjoin('en_software_license', 'sla.software_id', '=', 'en_software_license.software_id')
                 ->leftjoin('en_license_type', 'sla.license_type_id', '=', 'en_license_type.license_type_id')

                ->where('en_software_installation.asset_id', 'like', '%' . $asset_id . '%')
                //->where('en_software_license_allocation.asset_id', 'like', '%' . $asset_id . '%')
                //->where('sla.software_id', $software_id)
               ->get(); 

           
           if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array(' software asset report'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array(' software asset report'));
                $data['status'] = 'error';
            }
            return response()->json($data);                                                          
    }   
    public function licensedashboard(Request $request)
    {  

         $result = DB::table('en_ci_types')   
                ->select(DB::raw('BIN_TO_UUID(en_ci_types.ci_type_id) as ci_type_id'), 
                DB::raw('BIN_TO_UUID(en_ci_templ_custom.ci_templ_id) as ci_templ_id'),
                 DB::raw('BIN_TO_UUID(en_assets.ci_templ_id) as en_ci_templ_id'),
                DB::raw('BIN_TO_UUID( en_asset_details.asset_id) as asset_id'),
              DB::raw('JSON_EXTRACT(asset_details, "$.Osversion") as Osversion'),
                DB::raw('count(en_assets.ci_templ_id) as count'),       
                'en_ci_types.citype',
                'en_ci_templ_custom.ci_name')

              ->leftjoin('en_ci_templ_custom', 'en_ci_types.ci_type_id', '=', 'en_ci_templ_custom.ci_type_id') 
                ->leftjoin('en_assets', 'en_assets.ci_templ_id', '=', 'en_ci_templ_custom.ci_templ_id')    
                ->leftjoin('en_asset_details','en_assets.asset_id','=','en_asset_details.asset_id')           
              ->where('en_ci_templ_custom.status','=','y')
              ->where('en_ci_types.citype','=','Operating System')
             
                ->groupBy('Osversion')  
                ->get(); 
            
       
           if ($result)
            {

                $data['data'] = $result;

                $data['message']['success'] = showmessage('101', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;

                $data['message']['error'] = showmessage('102', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'error';
            }


            return response()->json($data);                                                          
    }


    //---------New Function for Database license---------//

   
     public function databasedashboard(Request $request)
    {  

         $result = DB::table('en_ci_types')   
                ->select(DB::raw('BIN_TO_UUID(en_ci_types.ci_type_id) as ci_type_id'), 
                DB::raw('BIN_TO_UUID(en_ci_templ_custom.ci_templ_id) as ci_templ_id'),
                 DB::raw('BIN_TO_UUID(en_assets.ci_templ_id) as en_ci_templ_id'),
                DB::raw('BIN_TO_UUID( en_asset_details.asset_id) as asset_id'),
                 DB::raw('JSON_EXTRACT(asset_details, "$.DBversion") as DBversion'),
                DB::raw('count(en_assets.ci_templ_id) as count'),       
                'en_ci_types.citype',
                'en_ci_templ_custom.ci_name')

              ->leftjoin('en_ci_templ_custom', 'en_ci_types.ci_type_id', '=', 'en_ci_templ_custom.ci_type_id') 
                ->leftjoin('en_assets', 'en_assets.ci_templ_id', '=', 'en_ci_templ_custom.ci_templ_id')    
                ->leftjoin('en_asset_details','en_assets.asset_id','=','en_asset_details.asset_id')           
              ->where('en_ci_templ_custom.status','=','y')
              ->where('en_ci_types.citype','=','Database License')
                ->groupBy('DBversion')  
                ->get(); 
            
       
           if ($result)
            {

                $data['data'] = $result;

                $data['message']['success'] = showmessage('101', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;

                $data['message']['error'] = showmessage('102', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'error';
            }


            return response()->json($data);                                                          
    }



    /************************Cpanel/Plesk Dashboard***************/
     public function cpaneldashboard(Request $request)
    {  

         $result = DB::table('en_ci_types')   
                ->select(DB::raw('BIN_TO_UUID(en_ci_types.ci_type_id) as ci_type_id'), 
                DB::raw('BIN_TO_UUID(en_ci_templ_custom.ci_templ_id) as ci_templ_id'),
                 DB::raw('BIN_TO_UUID(en_assets.ci_templ_id) as en_ci_templ_id'),
                DB::raw('BIN_TO_UUID( en_asset_details.asset_id) as asset_id'),
                 DB::raw('JSON_EXTRACT(asset_details, "$.CpanelVersion") as CpanelVersion'),
                DB::raw('JSON_EXTRACT(asset_details, "$.Status") as Status'),
                DB::raw('count(en_assets.ci_templ_id) as count'),       
                'en_ci_types.citype',
                'en_ci_templ_custom.ci_name')

              ->leftjoin('en_ci_templ_custom', 'en_ci_types.ci_type_id', '=', 'en_ci_templ_custom.ci_type_id') 
                ->leftjoin('en_assets', 'en_assets.ci_templ_id', '=', 'en_ci_templ_custom.ci_templ_id')    
                ->leftjoin('en_asset_details','en_assets.asset_id','=','en_asset_details.asset_id')           
              ->where('en_ci_templ_custom.status','=','y')
              ->where('en_ci_types.citype','=','Cpanel License')
                ->groupBy('CpanelVersion')  
                ->get(); 
            
       
           if ($result)
            {

                $data['data'] = $result;

                $data['message']['success'] = showmessage('101', array('{name}'), array(' Cpanel dashboard license'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;

                $data['message']['error'] = showmessage('102', array('{name}'), array(' Cpanel dashboard license'));
                $data['status'] = 'error';
            }


            return response()->json($data);                                                          
    }

    // For store Dashboard Nayana Pardeshi

     public function getstoredashboard(Request $request)
    {  

        


                 $result  =   DB::table('en_form_data_pr')->
                           select('en_form_data_pr.status',DB::raw('count(en_form_data_pr.status) as count'))
                           ->groupBy('en_form_data_pr.status')
                           ->get();
       
           if ($result)
            {

                $data['data'] = $result;

                $data['message']['success'] = showmessage('101', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;

                $data['message']['error'] = showmessage('102', array('{name}'), array(' software dashboard license'));
                $data['status'] = 'error';
            }


            return response()->json($data);                                                          
    }
   
}
