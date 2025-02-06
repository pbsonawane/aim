<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnContacts;
use App\Models\EnPrPoAssetDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ContactController extends Controller
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
     *This is controller funtion used for Contacts.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : contact_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_contacts
     */
    public function contacts(Request $request, $contact_id = null)
    {
        $requset['contact_id'] = $contact_id;
        $validator             = Validator::make($request->all(), [
            'contact_id' => 'nullable|allow_uuid|string|size:36',
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
            $totalrecords               = EnContacts::getcontacts($contact_id, $inputdata, true);
            $result                     = EnContacts::getcontacts($contact_id, $inputdata, false);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Contact'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Contact'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }

    public function contacts_shipto(Request $request, $contact_id = null)
    {
        $requset['contact_id'] = $contact_id;
        $validator             = Validator::make($request->all(), [
            'contact_id' => 'nullable|allow_uuid|string|size:36',
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
            $totalrecords               = EnContacts::getcontacts_shipto($contact_id, $inputdata, true);
            $result                     = EnContacts::getcontacts_shipto($contact_id, $inputdata, false);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Contact'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Contact'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }

    public function contacts_billto(Request $request, $contact_id = null)
    {
        $requset['contact_id'] = $contact_id;
        $validator             = Validator::make($request->all(), [
            'contact_id' => 'nullable|allow_uuid|string|size:36',
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
            $totalrecords               = EnContacts::getcontacts_billto($contact_id, $inputdata, true);
            $result                     = EnContacts::getcontacts_billto($contact_id, $inputdata, false);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Contact'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Contact'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }
    /*
     * This is controller funtion used to accept the values for new Department. This function is called when user enters new values for department and submits that form.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        prefix, fname, lname, email, contact1, contact2, associated_with, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_contacts
     */
    public function contactadd(Request $request)
    {
        $messages = [
            'prefix.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_prefix')), true),
            'fname.required'               => showmessage('000', array('{name}'), array(trans('label.lbl_contact_fname')), true),
            'fname.allow_alpha_space_only' => showmessage('009', array('{name}'), array(trans('label.lbl_contact_fname')), true),
            'fname.html_tags_not_allowed'  => showmessage('000', array('{name}'), array(trans('label.lbl_contact_fname')), true),
            'lname.required'               => showmessage('000', array('{name}'), array(trans('label.lbl_contact_lname')), true),
            'lname.allow_alpha_space_only' => showmessage('009', array('{name}'), array(trans('label.lbl_contact_lname')), true),
            'lname.html_tags_not_allowed'  => showmessage('000', array('{name}'), array(trans('label.lbl_contact_lname')), true),
            'email.required'               => showmessage('000', array('{name}'), array(trans('Email')), true),
            'email.regex'                  => 'Please enter valid email address',
            'contact1.required'            => showmessage('000', array('{name}'), array(trans('label.lbl_contact1')), true),
            'contact1.digits'              => showmessage(trans('msg_contactno_10digit')),
            'contact2.digits'              => 'Invalid Contact 2, Only 10 Digits allowed',
            'associated_with.required'     => showmessage('000', array('{name}'), array(trans('label.lbl_associated_with')), true),
        ];
        $validator = Validator::make($request->all(), [
            'contact_id'      => 'nullable|allow_uuid|string|size:36',
            'prefix'          => 'required',
            'fname'           => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'lname'           => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'email'           => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'contact1'        => 'required|digits:10',
            'contact2'        => 'digits:10',
            'associated_with' => 'required',

        ], $messages);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $contact_data = EnContacts::create($request->all());
            if (!empty($contact_data['contact_id'])) {
                $contact_id                 = $contact_data->contact_id_text;
                $data['data']['insert_id']  = $contact_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Contact'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                // userlog(array('record_id' => $contact_data->contact_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Contact'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Contact'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    /* Provides a window to user to update the contact information.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : contact_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_ci_contacts
     */
    public function contactedit(Request $request, $contact_id = null)
    {
        //$request['contact_id'] = $contact_id;
        $validator = Validator::make($request->all(), [
            'contact_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $result = EnContacts::getcontacts($request->input('contact_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Contact'));
                $data['status']             = 'success';
            } else {

                $data['message']['error'] = showmessage('101', array('{name}'), array('Contact'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    //===== designationedit END ===========
    /*
     * Updates the contact information, which is entered by user on Edit contact window.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        prefix, fname, lname, email, contact1, contact2, associated_with, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_contacts
     */
    public function contactupdate(Request $request)
    {
        $contact_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('contact_id') . '")');
        $messages       = [
            'prefix.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_prefix')), true),
            'fname.required'               => showmessage('000', array('{name}'), array(trans('label.lbl_contact_fname')), true),
            'fname.allow_alpha_space_only' => showmessage('009', array('{name}'), array(trans('label.lbl_contact_fname')), true),
            'fname.html_tags_not_allowed'  => showmessage('000', array('{name}'), array(trans('label.lbl_contact_fname')), true),
            'lname.required'               => showmessage('000', array('{name}'), array(trans('label.lbl_contact_lname')), true),
            'lname.allow_alpha_space_only' => showmessage('009', array('{name}'), array(trans('label.lbl_contact_lname')), true),
            'lname.html_tags_not_allowed'  => showmessage('000', array('{name}'), array(trans('label.lbl_contact_lname')), true),
            'email.required'               => showmessage('000', array('{name}'), array(trans('Email')), true),
            'email.regex'                  => 'Please enter valid email address',
            'contact1.required'            => showmessage('000', array('{name}'), array(trans('label.lbl_contact1')), true),
            'contact1.digits'              => 'The Contact 1 must be 10 digits',
            'contact2.digits'              => 'The Contact 2 must be 10 digits',
            'associated_with.required'     => showmessage('000', array('{name}'), array(trans('label.lbl_associated_with')), true),
        ];
        $validator = Validator::make($request->all(), [
            'contact_id'      => 'nullable|allow_uuid|string|size:36',
            'prefix'          => 'required',
            'fname'           => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'lname'           => 'required|allow_alpha_space_only|html_tags_not_allowed',
            'email'           => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'contact1'        => 'required|digits:10',
            'contact2'        => 'digits:10',
            'associated_with' => 'required',

        ], $messages);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $contact_id_uuid       = $request->input('contact_id');
            $contact_id_bin        = DB::raw('UUID_TO_BIN("' . $request->input('contact_id') . '")');
            $request['contact_id'] = DB::raw('UUID_TO_BIN("' . $request->input('contact_id') . '")');
            $result                = EnContacts::where('contact_id', $contact_id_bin)->first();

            if ($result) {
                $result->update($request->all());
                $result->save();
                $data['data']               = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Contact'));
                $data['status']             = 'success';
                // userlog(array('record_id' => $contact_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Contact'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }
    //===== Contactupdate END ===========

    /* This is controller funtion used to delete the designation.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : contact_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_contacts
     */

    public function contactdelete(Request $request, $contact_id = null)
    {
        $request['contact_id'] = $contact_id;
        $validator             = Validator::make($request->all(), [
            'contact_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $data = EnContacts::checkforrelation($contact_id);
            //Add into UserActivityLog
            if ($data['data']) {
                //userlog(array('record_id' => $contact_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }
    
} // Class End
