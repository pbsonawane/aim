<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnBillTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class BilltoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        DB::connection()->enableQueryLog();
    }
/*
 *This is controller funtion used to List Bill To.

 * @author       Bhushan Amrutkar
 * @access       public
 * @param        URL : billto_id [Optional]
 * @param_type   Integer
 * @return       JSON
 * @tables       en_bill_to
 */

    public function billtos(Request $request, $billto_id = null)
    {
        $request['billto_id'] = $billto_id;
        $validator            = Validator::make($request->all(), [
            'billto_id' => 'nullable|allow_uuid|string|size:36',
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
            $totalrecords               = EnBillTo::getbilltos($billto_id, $inputdata, true);
            $result                     = EnBillTo::getbilltos($billto_id, $inputdata, false);

            $queries            = DB::getQueryLog();
            $data['last_query'] = end($queries);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 1) {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Bill To'));
            } else {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Bill To'));
            }

            $data['status'] = 'success';
            return response()->json($data);
        }
    }

    //================== Bill To List END ======

    /*
     * This is controller funtion used to accept the values for new Bill To.
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        locations, address, pan_no, gstn,  status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_bill_to
     */

    public function billtoadd(Request $request)
    {
        $messages = [
            'locations.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
            'company_name.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_company_name')), true),
            'company_name.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_company_name')), true),
            'address.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'      => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),
            'pan_no.required'                    => showmessage('000', array('{name}'), array(trans('label.lbl_pan_no')), true),
            'pan_no.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_pan_no')), true),
            'pan_no.regex'                       => showmessage('000', array('{name}'), array(trans('label.lbl_pan_no_invalid')), true),
            'gstn.required'                      => showmessage('000', array('{name}'), array(trans('label.lbl_gstn')), true),
            'gstn.html_tags_not_allowed'         => showmessage('001', array('{name}'), array(trans('label.lbl_gstn')), true),

        ];
        $validator = Validator::make($request->all(), [
            'billto_id'    => 'nullable|allow_uuid|string|size:36',
            'locations'    => 'required',
            'company_name' => 'required|html_tags_not_allowed',
            'address'      => 'required|html_tags_not_allowed',
            'pan_no'       => 'required|html_tags_not_allowed|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/',
            'gstn'         => 'required|html_tags_not_allowed',
        ], $messages);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $inputdata           = $request->all();
            $billto              = $inputdata;
            $billto['locations'] = DB::raw('UUID_TO_BIN("' . $inputdata['locations'] . '")');
            $billto_data         = EnBillTo::create($billto);

            if (!empty($billto_data['billto_id'])) {
                $billto_id                  = $billto_data->billto_id_text;
                $data['data']['insert_id']  = $billto_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Bill To'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                userlog(array('record_id' => $billto_data->billto_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array('Bill To'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Bill To'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }
    //================== Bill To ADD END ======

    /*
     * This is controller funtion used to delete the Bill To.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : billto_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_bill_to

     */

    public function billtodelete(Request $request, $billto_id = null)
    {
        $request['billto_id'] = $billto_id;
        $validator            = Validator::make($request->all(), [
            'billto_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $data = EnBillTo::checkforrelation($billto_id);
            //Add into UserActivityLog
            if ($data['data']) {
                //userlog(array('record_id' => $vendor_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);
        }
    }
    //================== Bill To Delete END ======

    /*
     * Provides a window to user to update the Cost Cneter's information.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : billto_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_bill_to
     */
    public function billtoedit(Request $request, $billto_id = null)
    {

        //$request['billto_id'] = $billto_id;
        $validator = Validator::make($request->all(), [
            'billto_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $result       = EnBillTo::getbilltos($request->input('billto_id'));
            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Bill To'));
                $data['status']             = 'success';
            } else {
                $data['message']['error'] = showmessage('101', array('{name}'), array('Bill To'));
                $data['status']           = 'error';
            }
            return response()->json($data);
        }
    }
    //===== Bill To Edit END ===========

    /*
     * Updates the Pods information, which is entered by user on Edit Bill To window.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        cc_code, cc_name, cc_description, owner_id,locations,departments, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_bill_to
     */

    public function billtoupdate(Request $request)
    {
        $billto_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('billto_id') . '")');
        $messages      = [
            'locations.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
            'company_name.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_company_name')), true),
            'company_name.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_company_name')), true),
            'address.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'      => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),
            'pan_no.required'                    => showmessage('000', array('{name}'), array(trans('label.lbl_pan_no')), true),
            'pan_no.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_pan_no')), true),
            'pan_no.regex'                       => showmessage('000', array('{name}'), array(trans('label.lbl_pan_no_invalid')), true),
            'gstn.required'                      => showmessage('000', array('{name}'), array(trans('label.lbl_gstn')), true),
            'gstn.html_tags_not_allowed'         => showmessage('001', array('{name}'), array(trans('label.lbl_gstn')), true),

        ];
        $validator = Validator::make($request->all(), [
            'billto_id'    => 'nullable|allow_uuid|string|size:36',
            'locations'    => 'required',
            'company_name' => 'required|html_tags_not_allowed',
            'address'      => 'required|html_tags_not_allowed',
            'pan_no'       => 'required|html_tags_not_allowed|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/',
            'gstn'         => 'required|html_tags_not_allowed',
        ], $messages);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $billto_id_uuid       = $request->input('billto_id');
            $request['billto_id'] = DB::raw('UUID_TO_BIN("' . $request->input('billto_id') . '")');

            $result = EnBillTo::where('billto_id', $request['billto_id'])->first();
            if ($result) {
                $inputdata            = $request->all();
                $request              = $inputdata;
                $request["locations"] = DB::raw('UUID_TO_BIN("' . $inputdata['locations'] . '")');
                $result->update($request);
                $result->save();
                $data['data']               = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Bill To'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                //userlog(array('record_id' => $billto_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Bill To'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Bill To'));
                $data['status']           = 'error';
            }
            return response()->json($data);
        }
    }

} // Class End
