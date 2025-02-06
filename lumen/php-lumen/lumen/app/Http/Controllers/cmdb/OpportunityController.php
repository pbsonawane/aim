<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnoppListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class OpportunityController extends Controller
{
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }
    public function opportunities(Request $request, $id = null)
    {
        $request['id'] = $id;
        $validator     = Validator::make($request->all(), [
            'id' => 'nullable',
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
            $totalrecords               = EnoppListing::getopportunities($id, $inputdata, true);
            $result                     = EnoppListing::getopportunities($id, $inputdata, false);

            $queries = DB::getQueryLog();

            $data['last_query']           = end($queries);
            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 1) {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Opportunity'));
            } else {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Opportunity'));
            }

            $data['status'] = 'success';
            return response()->json($data);
        }
    }
} // Class End
