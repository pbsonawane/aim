<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnRequesternames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class RequesternameController extends Controller
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
     *This is controller funtion used for requesternames.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : requestername_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_requesternames
     */

    public function syncrequesteruser(Request $request)
    {        
        $user_id = $request['user_id'];
        $parent_id = $request['parent_id'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $emp_id = $request['emp_id'];
        $department_id = $request['department_id'];
        $department_id_bin = DB::raw('UUID_TO_BIN("' . $department_id . '")');
      
        $requestername_data = EnRequesternames::select(DB::raw('BIN_TO_UUID(requestername_id) AS requestername_id'))
        ->where('employee_id', '=', $emp_id)->first();
       
        if (empty($requestername_data)) {
            //            
            $requestername['departments'] = DB::raw('UUID_TO_BIN("' . $department_id . '")');
            $requestername['user_id'] = DB::raw('UUID_TO_BIN("' . $user_id . '")');
            $requestername['parent_id'] = DB::raw('UUID_TO_BIN("' . $parent_id . '")');
            $requestername['prefix'] = "";
            $requestername['fname'] = $firstname;
            $requestername['lname'] = $lastname;
            $requestername['employee_id'] = $emp_id;

            $requestername_data = EnRequesternames::create($requestername);
            $data['data'] = $requestername_data;
            $data['message']['success'] = "Record is Added";
            $data['status']           = 'success';
            return response()->json($data);
            
        }else{
            $department_id = DB::raw('UUID_TO_BIN("' . $department_id . '")');
            $user_id= DB::raw('UUID_TO_BIN("' . $user_id . '")');
            $parent_id = DB::raw('UUID_TO_BIN("' . $parent_id . '")');
            $result = DB::table('en_ci_requesternames')
            ->where('requestername_id',"=", DB::raw('UUID_TO_BIN("'.$requestername_data['requestername_id'].'")'))
            ->update([
                'departments' => $department_id,
                'user_id' => $user_id,
                'parent_id' => $parent_id,
                'fname' => $firstname,
                'lname' => $lastname,
                'employee_id' => $emp_id
            ]);
            $data['data']             = $result;
            $data['message']['success'] = "Record is Update";
            $data['status']           = 'success';
            return response()->json($data);
        }
        
    }

    public function getAssetDetail(Request $request)
    {
        $result = DB::table('en_assets')
        ->select(
			DB::raw('BIN_TO_UUID(asset_id) AS asset_id'),
			DB::raw('BIN_TO_UUID(department_id) AS department_id'),
			DB::raw('BIN_TO_UUID(po_id) AS po_id'),
            DB::raw('BIN_TO_UUID(parent_asset_id) AS parent_asset_id'),
            DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'),
			'asset_sku', 'asset_tag', 'display_name', 'asset_unit', 'asset_qty', 'asset_status', 'status'
            )
        ->where('asset_id', '=',DB::raw('UUID_TO_BIN("'.$request['asset_id'].'")'))
        ->get();
        

        //return result
        $data['data'] = $result;
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }

    public function getRequester(Request $request)
    {
        $result = DB::table('en_ci_requesternames')
        ->select(
			DB::raw('BIN_TO_UUID(requestername_id) AS requestername_id'),
			DB::raw('BIN_TO_UUID(user_id) AS user_id'),
			DB::raw('BIN_TO_UUID(parent_id) AS parent_id'),
            DB::raw('BIN_TO_UUID(departments) AS departments'),
			'fname', 'lname'
            )
        ->where('requestername_id', '=',DB::raw('UUID_TO_BIN("'.$request['requestername_id'].'")'))
        ->get();
        

        //return result
        $data['data'] = $result;
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }

    public function getrequesternames(Request $request, $requestername_id = null)
    {
        $requestername_id = $request['requestername_id'] ;        
        $validator                   = Validator::make($request->all(), [
            'requestername_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {

            $inputdata                  = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords               = EnRequesternames::getrequesternames($requestername_id, $inputdata, true);
            $result                     = EnRequesternames::getrequesternames($requestername_id, $inputdata, false);

            $queries            = DB::getQueryLog();
            $data['last_query'] = end($queries);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Requester Name'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Requester Name'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }
    public function requesternames(Request $request, $requestername_id = null)
    {
        $requset['requestername_id'] = $requestername_id;
        $validator                   = Validator::make($request->all(), [
            'requestername_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {

            $inputdata                  = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords               = EnRequesternames::getrequesternames($requestername_id, $inputdata, true);
            $result                     = EnRequesternames::getrequesternames($requestername_id, $inputdata, false);

            $queries            = DB::getQueryLog();
            $data['last_query'] = end($queries);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Requester Name'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Requester Name'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }

    /*
     * This is controller funtion used to accept the values for new Department. This function is called when user enters new values for department and submits that form.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        prefix, fname, lname, email, employee_id, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_cirequesternames
     */
    public function requesternameadd(Request $request)
    {
        $messages = [
            'departments.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_department')), true),
            'prefix.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_prefix')), true),
            'fname.required'                    => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_fname')), true),
            'fname.allow_alpha_space_only'      => showmessage('009', array('{name}'), array(trans('label.lbl_requestername_fname')), true),
            'fname.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_fname')), true),
            'lname.required'                    => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_lname')), true),
            'lname.allow_alpha_space_only'      => showmessage('009', array('{name}'), array(trans('label.lbl_requestername_lname')), true),
            'lname.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_lname')), true),
            'employee_id.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_employee_id')), true),
            'employee_id.html_tags_not_allowed' => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_employee_id')), true),
        ];
        $validator = Validator::make($request->all(), [
            'requestername_id' => 'nullable|allow_uuid|string|size:36',
            'departments'    => 'required',
            'prefix'           => 'required',
            'fname'            => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'lname'            => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'employee_id'      => 'required|html_tags_not_allowed',

        ], $messages);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $inputdata                    = $request->all();
            $requestername                = $inputdata;
            $requestername['departments'] = DB::raw('UUID_TO_BIN("' . $inputdata['departments'] . '")');
            $requestername_data           = EnRequesternames::create($requestername);
            if (!empty($requestername_data['requestername_id'])) {
                $requestername_id           = $requestername_data->requestername_id_text;
                $data['data']['insert_id']  = $requestername_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Requester Name'));
                $data['status']             = 'success';
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Requester Name'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    /* Provides a window to user to update the requestername information.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : requestername_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_ci_requesternames
     */
    public function requesternameedit(Request $request, $requestername_id = null)
    {
        //$request['requestername_id'] = $requestername_id;
        $validator = Validator::make($request->all(), [
            'requestername_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $result = EnRequesternames::getrequesternames($request->input('requestername_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Requester Name'));
                $data['status']             = 'success';
            } else {

                $data['message']['error'] = showmessage('101', array('{name}'), array('Requester Name'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    //===== designationedit END ===========
    /*
     * Updates the requestername information, which is entered by user on Edit requestername window.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        prefix, fname, lname, email, employee_id, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_requesternames
     */
    public function requesternameupdate(Request $request)
    {
        $requestername_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('requestername_id') . '")');
        $messages             = [
            'departments.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_department')), true),
            'prefix.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_prefix')), true),
            'fname.required'                    => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_fname')), true),
            'fname.allow_alpha_space_only'      => showmessage('009', array('{name}'), array(trans('label.lbl_requestername_fname')), true),
            'fname.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_fname')), true),
            'lname.required'                    => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_lname')), true),
            'lname.allow_alpha_space_only'      => showmessage('009', array('{name}'), array(trans('label.lbl_requestername_lname')), true),
            'lname.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_lname')), true),
            'employee_id.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_employee_id')), true),
            'employee_id.html_tags_not_allowed' => showmessage('000', array('{name}'), array(trans('label.lbl_requestername_employee_id')), true),
        ];
        $validator = Validator::make($request->all(), [
            'requestername_id' => 'nullable|allow_uuid|string|size:36',
            'departments'    => 'required',
            'prefix'           => 'required',
            'fname'            => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'lname'            => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'employee_id'      => 'required|html_tags_not_allowed',

        ], $messages);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $requestername_id_uuid       = $request->input('requestername_id');
            $requestername_id_bin        = DB::raw('UUID_TO_BIN("' . $request->input('requestername_id') . '")');
            $request['requestername_id'] = DB::raw('UUID_TO_BIN("' . $request->input('requestername_id') . '")');
            $result                      = EnRequesternames::where('requestername_id', $requestername_id_bin)->first();

            if ($result) {
                $inputdata            = $request->all();
                $request              = $inputdata;
                $request["departments"] = DB::raw('UUID_TO_BIN("' . $inputdata['departments'] . '")');
                $result->update($request);
                $result->save();
                $data['data']               = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Requester Name'));
                $data['status']             = 'success';
                // userlog(array('record_id' => $requestername_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Requester Name'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }
    //===== Requesternameupdate END ===========

    /* This is controller funtion used to delete the designation.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : requestername_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_requesternames
     */

    public function requesternamedelete(Request $request, $requestername_id = null)
    {
        $request['requestername_id'] = $requestername_id;
        $validator                   = Validator::make($request->all(), [
            'requestername_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $data = EnRequesternames::checkforrelation($requestername_id);
            //Add into UserActivityLog
            if ($data['data']) {
                //userlog(array('record_id' => $requestername_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }
} // Class End
