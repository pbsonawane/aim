<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnRequesternames extends Model
{
    /*
     * This is model function is used get all departments data with its foregin key data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        requestername_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_requesternames
     */
    use HasBinaryUuid;
    public $incrementing = false;

    protected $table = 'en_ci_requesternames';
    //public $timestamps = false;
    protected $fillable = [
        'departments', 'user_id', 'parent_id', 'prefix', 'fname', 'lname', 'employee_id', 'status',
    ];
    protected $primaryKey = 'requestername_id';
    public function getKeyName()
    {
        return 'requestername_id';
    }
  
    /*
     * This is model function is used get all Role's data with its foregin key data
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        requestername_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_requesternames
     */
    protected function getrequesternames($requestername_id = null, $inputdata = array(), $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        
        $query = DB::table('en_ci_requesternames')
            ->select(DB::raw('BIN_TO_UUID(requestername_id) AS requestername_id'), DB::raw('BIN_TO_UUID(departments) AS departments'), DB::raw('BIN_TO_UUID(user_id) AS user_id'), DB::raw('BIN_TO_UUID(parent_id) AS parent_id'), 'prefix', 'fname', 'lname', 'employee_id', 'status')
            ->where('en_ci_requesternames.status', '!=', 'd')->orderBy('en_ci_requesternames.fname', 'ASC');
        // if (isset($inputdata["department_id"])) {
        //      $query->where('en_ci_requesternames.departments', '=',DB::raw('UUID_TO_BIN("'.$inputdata["department_id"].'")'));
        // }
        $query->where(function ($query) use ($searchkeyword, $requestername_id) {
            $query->where(function ($query) use ($searchkeyword, $requestername_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('en_ci_requesternames.fname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_requesternames.lname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_requesternames.employee_id', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($requestername_id, function ($query) use ($requestername_id) {
                return $query->where('en_ci_requesternames.requestername_id', '=', DB::raw('UUID_TO_BIN("' . $requestername_id . '")'));
            });});
        $query->when(!$count, function ($query) use ($inputdata) {
            if (isset($inputdata["offset"]) && isset($inputdata["limit"])) {
                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
            }
        });
        $data = $query->get();

        if ($count) {
            return count($data);
        } else {
            return $data;
        }
    }
   
    
    /* This is Model funtion used to delete the rocord, But Before that check the deletion ID's have relation with another table(en_user_details)

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        requestername_id
     * @param_type   Integer
     * @return       Array
     * @tables       en_ci_requesternames
     */
    protected function checkforrelation($requestername_id)
    {
        if ($requestername_id) {
            $requestername_data = EnRequesternames::where('requestername_id', DB::raw('UUID_TO_BIN("' . $requestername_id . '")'))->where('status', '!=', 'd')->first();
            if ($requestername_data) {

                $requestername_data->update(array('status' => 'd'));
                $requestername_data->save();
                $data['data']['deleted_id'] = $requestername_id;
                $data['message']['success'] = showmessage('118', array('{name}'), array('Requester Name'));
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Requester Name'));
                $data['status']           = 'error';
            }
        } else {
            $data['data']             = null;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Requester Name'));
            $data['status']           = 'error';
        }
        return $data;
    }
}