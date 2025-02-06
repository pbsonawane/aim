<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnSoftware;
use App\Models\EnSoftwareInstall;
use App\Models\EnSoftwareHistory;
use App\Models\EnSoftwareLicense;
use App\Models\EnSoftwareLicenseAllocate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class SoftwareLicenseController extends Controller
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
     *This is controller funtion used for Software license.

     * @author       Kavita Daware
     * @access       public
     * @param        software_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_software_license
     */
    public function softwarelicense(Request $request,$software_id = null)
    {
        try
        {
            //$requset['software_license_id'] = $software_license_id;
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
               // $totalrecords = EnSoftwareLicense::getsoftwarelicense($software_license_id, $inputdata, true);
                //$result = EnSoftwareLicense::getsoftwarelicense($software_license_id, $inputdata, false);
                $software_id = $request->input('software_id');
                $result = EnSoftwareLicense::getsoftwarelicense($software_id);


                $queries = DB::getQueryLog();
                $last_query = end($queries);

               // $data['data']['query'] = $last_query;

               // $data['data']['records'] = $result->isEmpty() ? null : $result;
                //$data['data']['totalrecords'] = $totalrecords;

            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('102', array('{name}'), array('software'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('software'));
                $data['status'] = 'error';
            }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarelicense", "This is controller funtion used for Software license.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarelicense", "This is controller funtion used for Software license.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /*
    * This is controller funtion used to add the Software license.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id, software_manufacturer_id, license_type_id, vendor_id,department_id ,location_id,bv_id, max_installation,purchase_cost,description,acquisition_date,expiry_date,license_key
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_license    
    */
    public function softwarelicenseadd(Request $request)
    {
        try
        {
        $messages = [
            'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_business_verticals')), true),
            'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
            'software_manufacturer_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
            'license_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_license_type')), true),
            'vendor_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_vendor')), true),
            'department_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_department')), true),
			'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
			'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_business_verticals')), true),
			'max_installation.required' => showmessage('000', array('{name}'), array(trans('label.lbl_max_installation')), true),
			'purchase_cost.required' => showmessage('000', array('{name}'), array(trans('label.lbl_purchasecost')), true),
			'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
			'acquisition_date.required' => showmessage('000', array('{name}'), array(trans('label.lbl_acquisition_date')), true),
			'expiry_date.required' => showmessage('000', array('{name}'), array(trans('label.lbl_expiry_date')), true),
			'license_key.required' => showmessage('000', array('{name}'), array(trans('label.lbl_license_key')), true),
            'software_id.allow_uuid' => showmessage('011', array('{name}'), array(trans('label.lbl_software_id')), true),
            'max_installation.allow_positive_numeric_only' => showmessage('011', array('{name}'), array(trans('label.lbl_max_installation')), true),
            'purchase_cost.allow_positive_numeric_only'    => showmessage('011', array('{name}'), array(trans('label.lbl_purchasecost')), true),
        ];
            $validator = Validator::make($request->all(), [

                'software_id' => 'required|allow_uuid',
                'software_manufacturer_id' => 'required',
                'license_type_id' => 'required',
                'vendor_id' => 'required',
                'department_id' => 'required',
                'location_id' => 'required',
                'bv_id' => 'required',
                'max_installation' => 'required|allow_positive_numeric_only',
                'purchase_cost' => 'required|allow_positive_numeric_only',
                'description' => 'required',
                'acquisition_date' => 'required|date',
                'expiry_date' => 'required|date',
                'license_key' => 'required',



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
                $software['software_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_id'].'")');
                $software['software_manufacturer_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_manufacturer_id'].'")');
                $software['license_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['license_type_id'].'")');
                $software['vendor_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['vendor_id'].'")');
                $software['department_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['department_id'].'")');
                $software['location_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['location_id'].'")');
                $software['bv_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['bv_id'].'")');

                $software['max_installation'] = _isset($inputdata, 'max_installation');
                $software['description'] = _isset($inputdata, 'description');
                $software['purchase_cost'] = _isset($inputdata, 'purchase_cost');
                $software['acquisition_date'] = _isset($inputdata, 'acquisition_date');
                $software['expiry_date'] = _isset($inputdata, 'expiry_date');
                $software['license_key'] = _isset($inputdata, 'license_key');
                $software['status'] = _isset($inputdata, 'status', 'y');

                $software_data = EnSoftwareLicense::create($software);
               

                if (!empty($software_data['software_license_id'])) {
                    $software['software_license_id'] = DB::raw('UUID_TO_BIN("' . $software_data->software_license_id_text . '")');
                
                 //Add Software History    
                 $history_data = array();
                 $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                 $history_data['software_license_id'] = $software['software_license_id'];
                 $history_data['action'] = "License Added";
                 $history_data['message'] = showmessage('044');
                 EnSoftwareHistory::create($history_data);
                }

                if ($software_data->software_license_id_text != '')
                {
                    $software_license_id = $software_data->software_license_id_text;
                    $data['data']['insert_id'] = $software_license_id;
                    $data['message']['success'] = showmessage('104', array('{name}'), array('Software License'));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('103', array('{name}'), array('Software License'));
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
            save_errlog("softwarelicenseadd", "This is controller funtion used to add softwares license.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarelicenseadd", "This is controller funtion used to add softwares license.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }
    /* Provides a window to user to update the software information.

     * @author       Kavita Daware
     * @access       public
     * @param        software_license_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_software_license
     */
    public function softwarelicenseedit(Request $request)
    {
        try
        {

            $validator = Validator::make($request->all(), [
                'software_license_id' => 'required|string|size:36',
                        

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
                $result = EnSoftwareLicense::getsoftwarelicenseedit($request->input('software_license_id'), $inputdata);
                //print_r($result);

                $data['data'] = $result->isEmpty() ? null : $result;

                if ($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software License'));
                    $data['status'] = 'success';
                }
                else
                {

                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software License'));
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
     * Updates the Software license, which is entered by user on Edit Software license window.

     * @author       Kavita Daware
     * @access       public
     * @param        software_id, software_manufacturer_id, license_type_id, vendor_id,department_id ,location_id,bv_id, max_installation,purchase_cost,description,acquisition_date,expiry_date,license_key
     * @param_type   POST array
     * @return       JSON
     * @tables       en_software_license
     */
    public function softwarelicenseupdate(Request $request)
    {
        try
        {

             $messages = [
                'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_business_verticals')), true),
                'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
                'software_manufacturer_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
                'license_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_license_type')), true),
                'vendor_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_vendor')), true),
                'department_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_department')), true),
				'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
				'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_business_verticals')), true),
				'max_installation.required' => showmessage('000', array('{name}'), array(trans('label.lbl_max_installation')), true),
				'purchase_cost.required' => showmessage('000', array('{name}'), array(trans('label.lbl_purchasecost')), true),
				'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
				'acquisition_date.required' => showmessage('000', array('{name}'), array(trans('label.lbl_acquisition_date')), true),
				'expiry_date.required' => showmessage('000', array('{name}'), array(trans('label.lbl_expiry_date')), true),
				'license_key.required' => showmessage('000', array('{name}'), array(trans('label.lbl_license_key')), true),
            ];
                $validator = Validator::make($request->all(), [

                    'software_id' => 'required',
                    'software_manufacturer_id' => 'required',
                    'license_type_id' => 'required',
                    'vendor_id' => 'required',
                    'department_id' => 'required',
                    'location_id' => 'required',
                    'bv_id' => 'required',
                    'max_installation' => 'required',
                    'purchase_cost' => 'required',
                    'description' => 'required',
                    'acquisition_date' => 'required',
                    'expiry_date' => 'required',
                    'license_key' => 'required',



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

                $software_license_id = $request->input('software_license_id');

                $request['software_license_id'] = DB::raw('UUID_TO_BIN("'.$software_license_id.'")');

                $software_id = $request->input('software_id');

                $request['software_id'] = DB::raw('UUID_TO_BIN("'.$software_id.'")');

                $software_manufacturer_id = $request->input('software_manufacturer_id');

                $request['software_manufacturer_id'] = DB::raw('UUID_TO_BIN("'.$software_manufacturer_id.'")');

                $license_type_id = $request->input('license_type_id');

                $request['license_type_id'] = DB::raw('UUID_TO_BIN("'.$license_type_id.'")');

                $vendor_id = $request->input('vendor_id');

                $request['vendor_id'] = DB::raw('UUID_TO_BIN("'.$vendor_id.'")');

                $department_id = $request->input('department_id');

                $request['department_id'] = DB::raw('UUID_TO_BIN("'.$department_id.'")');

                $location_id = $request->input('location_id');

                $request['location_id'] = DB::raw('UUID_TO_BIN("'.$location_id.'")');

                $bv_id = $request->input('bv_id');

                $request['bv_id'] = DB::raw('UUID_TO_BIN("'.$bv_id.'")');


                $result = EnSoftwareLicense::where('software_license_id', $request['software_license_id'])->first();

                if ($result)
                {
                    $result->update($request->all());
                    $result->save();
                   
                     //Update Software History    
                     $history_data = array();
                     $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                     $history_data['software_license_id'] = $request['software_license_id'];
                     $history_data['action'] = "Update license";
                     $history_data['message'] = showmessage('045');
                     EnSoftwareHistory::create($history_data);
                    
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Software License'));
                    $data['status'] = 'success';

                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array('Software License'));
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
            save_errlog("softwarelicenseupdate", "Updates the Software license, which is entered by user on Edit Software license window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarelicenseupdate", "Updates the Software license, which is entered by user on Edit Software license window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    /* This function is used to delete software license record.

    * @author       Kavita Daware
    * @access       public
    * @param        software_license_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_license     
    */

    public function softwarelicensedelete(Request $request,$software_license_id = null)
    {
        try
        {
            $request['software_license_id'] = $software_license_id;
            $messages = [
                'software_license_id.required' => showmessage('000', array('{name}'), array('Software License Id'), true),
            ];
            $validator = Validator::make($request->all(), [
                'software_license_id' => 'required|string|size:36',
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
                $data = EnSoftwareLicense::checkforrelation($software_license_id);
                 //Delete Software History    
                 $history_data = array();
                 $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                 $history_data['software_license_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_license_id').'")');
                 $history_data['action'] = "Deleted Software License";
                 $history_data['message'] = showmessage('041');
                 EnSoftwareHistory::create($history_data);
                //Add into UserActivityLog
                if ($data['data'])
                {
                    userlog(array('record_id' => $software_license_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Software'))));
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarelicensedelete", "This function is used to delete software license record..", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarelicensedelete", "This function is used to delete software license record..", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /* This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_license_allocation 
    */
    public function softwarelicensellocate(Request $request)
    {
        //try
        //{
            $inputdata = $request->all();
            $validator = Validator::make($request->all(), [
                // 'sw_install_id' => 'required',
                'selectassetids' => 'required',
                //'software_id' => 'required',

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
                
                $getresult = EnSoftwareLicenseAllocate::getswlicenseallocate($inputdata);
                // $software_id = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                // $result = EnSoftwareLicenseAllocate::where('software_id', $software_id)->first();

                $software_license_id = DB::raw('UUID_TO_BIN("'.$request->input('software_license_id').'")');
                $result = EnSoftwareLicenseAllocate::where('software_license_id', $software_license_id)->first();
    
                if ($result)
                {
                    $ass = $getresult[0]->asset_id;
                    $curr_asset_id = json_to_array($ass);
                    
                    $asset = _isset($inputdata, 'selectassetids');
                   // $software['asset_id'] = json_encode($asset);

                    $asset_id = array_merge($curr_asset_id,  $asset);
                    $asset_id = array_unique(array_filter($asset_id));
                    //print_r($asset_id );
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
                    //$queries    = DB::getQueryLog();
                    // $data['last_query'] = end($queries);
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Software Assets'));
                    $data['status'] = 'success';
                   
                }
                else
                {
                    $asset = _isset($inputdata, 'selectassetids');
                    $software['asset_id'] = json_encode($asset);
                    $software['status'] = _isset($inputdata, 'status', 'y');
                    $software['software_license_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_license_id'].'")');
                    $software['software_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['software_id'].'")');
                    $software_data = EnSoftwareLicenseAllocate::create($software);


                      //Add Software Asset History    
                     $history_data = array();
                     $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                     $history_data['software_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_id').'")');
                     $history_data['action'] = "Software License Allocated";
                     $history_data['message'] = showmessage('047');
                     EnSoftwareHistory::create($history_data);

                    // $queries    = DB::getQueryLog();
                    //$data['last_query'] = end($queries);

                    if (!empty($software_data['sw_license_allocation_id']))
                    {
                        $sw_license_allocation_id = $software_data->software_id_text;
                        $data['data']['insert_id'] = $sw_license_allocation_id;
                        $data['message']['success'] = showmessage('104', array('{name}'), array('Software Allocation'));
                        $data['status'] = 'success';
                    }
                    else
                    {
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('104', array('{name}'), array('Software Allocation'));
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
            save_errlog("swattachassetsave", "This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swattachassetsave", "This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }*/
    }

    /* This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_installation     
    */


    public function getswallocation(Request $request,$software_id = null)
    {
        $result = array();
       // try
        //{

            $software_id = $request->input('software_id');
            $sw_resp = DB::table('en_software_license_allocation')
                    ->select('asset_id')
                    ->where(DB::raw('BIN_TO_UUID(software_id)'), $software_id)
                    ->where('status', '=', 'y')
                //->select('en_software_license_allocation.asset_id','en_software_license.license_key')
               // ->leftjoin('en_software_license', 'en_software_license_allocation.software_license_id', '=', 'en_software_license.software_license_id') 
                //->where(DB::raw('BIN_TO_UUID(en_software_license_allocation.software_id)'), $software_id)
                //->where('en_software_license_allocation.status', '=', 'y')
                ->get();
            $queries = DB::getQueryLog();
            $last_query = end($queries);
            
           
           
           $assets_arr = array();
           foreach($sw_resp as  $sw){
                $selectassetids = isset($sw->asset_id) ? $sw->asset_id : "";
                $assets = $selectassetids != "" ? json_decode($selectassetids, true) : array();
                if (is_array($assets) && count($assets) > 0)
                {
                    foreach($assets as $asset)
                    {
                        $assets_arr[] = $asset;
                    }
                }
                $unique_asset_arr = array_unique($assets_arr);

                   // foreach($assets_arr as $asset)
                   // {
                        $result = DB::table('en_assets')
                            ->select(DB::raw('BIN_TO_UUID(en_assets.asset_id) AS asset_id'), DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), 'display_name', 'asset_tag','en_software_license.license_key')
                            ->leftjoin('en_software_license_allocation', 'en_assets.asset_id', '=', 'en_software_license_allocation.asset_id') 
                            ->leftjoin('en_software_license', 'en_software_license_allocation.software_license_id', '=', 'en_software_license.software_license_id') 
                            ->whereIn(DB::raw('BIN_TO_UUID(en_assets.asset_id)'), $unique_asset_arr)
                            ->get();
                            $queries = DB::getQueryLog();
            $last_query = end($queries);
                   // }
                     
                
               /* else
                {
                    $result = array();
                    $data['last_query'] = "";
                }*/
            }
            apilog('OOOOOOOOOOOOOOOOOOOOOOOO');
            apilog(json_encode($result));
            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array('software allocation'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array('software allocation'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        /*}
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getswallocation", "This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getswallocation", "This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }*/
    }
     /* This function is used to delete record from software installation table.

    * @author       Kavita Daware
    * @access       public
    * @param        asset_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_installation
    */


public function swallocateassetremove(Request $request,$software_id = null, $asset_id = null)
    {
        try
        {
            $pdata = EnSoftwareLicenseAllocate::getswlicenseallocate($request->all());
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

                $assetremove = EnSoftwareLicenseAllocate::swallocateassetremove($request->input('software_id'), $request->input('asset_id'));

                
                $queries = DB::getQueryLog();
                $last_query = end($queries);
               // print_r( $last_query );
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
                    $data['message']['success'] = showmessage('118', array('{name}'), array('Software Asset Allocation'), true);
                }
                else
                {

                    $data['data'] = null;
                    $data['status'] = 'error';
                    $data['message']['error'] = showmessage('119', array('{name}'), array('Software Asset Allocation'), true);
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swallocateassetremove", "This function is used to delete record from software installation table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swallocateassetremove", "This function is used to delete record from software installation table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }

    /* This function is used to delete record from software installation and software license allocation table.

    * @author       Kavita Daware
    * @access       public
    * @param        asset_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_installation, en_software_license_allocation   
    */

    public function swdeallocateuninstall(Request $request,$software_id = NULL)
    {   
        try
        {
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
                $result = EnSoftwareInstall::swassetremove($request->input('software_id'), $request->input('asset_id'));
                
                $result1 = EnSoftwareLicenseAllocate::swallocateassetremove($request->input('software_id'), $request->input('asset_id'));

                if ($result && $result1)
                {

                    $data['data'] = null;
                    $data['message']['success'] = showmessage('118', array('{name}'), array('software allocation deallocation'));
                    $data['status'] = 'success';

                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('119', array('{name}'), array('software allocation deallocation'));
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swdeallocateuninstall", "This function is used to delete record from software installation and software license allocation table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("swdeallocateuninstall", "This function is used to delete record from software installation and software license allocation table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

            
    }


    /* This function is used to get software record to display on asset dashboard.

    * @author       Kavita Daware
    * @access       public
    * @param        asset_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software    
    */
    public function swonassetdashboard(Request $request,$asset_id = NULL)
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

            $queries = DB::getQueryLog();
            $last_query = end($queries);
            //print_r($last_query);
            
           if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array(' software asset dashboard'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array(' software asset dashboard'));
                $data['status'] = 'error';
            }
            return response()->json($data);                                                          
        }
    /* This function is used to get purchase count according to software.

    * @author       Kavita Daware
    * @access       public
    * @param        software_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_license    
    */


    public function swpurchasecount(Request $request,$software_id = null)
    {

            $software_id = $request->input('software_id');
            //$result = EnSoftwareLicense::swpurchasecount($software_id);

            $inputdata = $request->all();
            $result = EnSoftwareLicense::swpurchasecount($request->input('software_id'), $inputdata);

            $queries    = DB::getQueryLog();
            $data['last_query'] = end($queries);
            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array('software'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array('software'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        
    }
     /* This function is used to get allocation of all softwares.

    * @author       Kavita Daware
    * @access       public
    * @return       JSON
    * @tables       en_software_license_allocation    
    */


    public function getswallocationallsw()
    {
        try
        {
            //$inputdata = $request->all();

           $result = EnSoftwareLicenseAllocate::getswallocationcount();
            $queries    = DB::getQueryLog();
            $data['last_query'] = end($queries);

            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array('All softwares allocation count'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array('All softwares allocation'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getswallocationallsw", "This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getswallocationallsw", "This function is used to get records from software allocation according to assets id fetch assets is from en_assets table.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /* This function is used to get purchase count of all software.

    * @author       Kavita Daware
    * @access       public
    * @return       JSON
    * @tables       en_software_license    
    */

    public function swpurchasecountallsw()
    {

            
            $result = EnSoftwareLicense::swpurchasecountallsw();

            $queries    = DB::getQueryLog();
            $data['last_query'] = end($queries);
            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('101', array('{name}'), array('software'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('102', array('{name}'), array('software'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        
    }
    public function swlicensemaxacount(Request $request,$software_license_id = '')
    {  
        $software_license_id = $request->input('software_license_id');
    
        $result = DB::table("en_software_license AS sl")
                ->select(DB::raw('JSON_LENGTH(asset_id) as allocationmaxcount'))
                ->leftjoin('en_software_license_allocation', 'sl.software_license_id', '=', 'en_software_license_allocation.software_license_id') 
                ->where('sl.status', '!=', 'd')
                ->where(DB::raw('BIN_TO_UUID(sl.software_license_id)'), $software_license_id)
                ->get();


            /*SELECT en_software_license.software_license_id,
            en_software_license.software_id,JSON_LENGTH(`asset_id`)
            FROM en_software_license
            LEFT JOIN en_software_license_allocation
            ON en_software_license.software_license_id = en_software_license_allocation.software_license_id where software_license_id = UUID_TO_BIN('".$software_license_id."');*/

        $queries = DB::getQueryLog();
        $last_query = end($queries);
        //print_r($last_query);
                
       if ($result)
        {

            $data['data'] = $result;
            $data['message']['success'] = showmessage('101', array('{name}'), array(' software license max count'));
            $data['status'] = 'success';

        }
        else
        {
            $data['data'] = null;
            $data['message']['error'] = showmessage('102', array('{name}'), array(' software license max count'));
            $data['status'] = 'error';
        }
        return response()->json($data);                                                          
    }      
}