<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnContacts extends Model
{
    /*
     * This is model function is used get all departments data with its foregin key data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        contact_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_contacts
     */
    use HasBinaryUuid;
    public $incrementing = false;

    protected $table = 'en_ci_contacts';
    //public $timestamps = false;
    protected $fillable = [
        'prefix', 'fname', 'lname', 'email', 'contact1', 'contact2', 'associated_with', 'status',
    ];
    protected $primaryKey = 'contact_id';
    public function getKeyName()
    {
        return 'contact_id';
    }
    /*
     * This is model function is used get all Role's data with its foregin key data
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        contact_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_contacts
     */
    protected function getcontacts($contact_id = null, $inputdata = [], $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_contacts')
            ->select(DB::raw('BIN_TO_UUID(contact_id) AS contact_id'), 'prefix', 'fname', 'lname', 'email', 'contact1', 'contact2', 'associated_with', 'status')
            ->where('en_ci_contacts.status', '!=', 'd');

        $query->where(function ($query) use ($searchkeyword, $contact_id) {
            $query->where(function ($query) use ($searchkeyword, $contact_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('en_ci_contacts.fname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.lname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.email', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.contact1', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.contact2', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.associated_with', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($contact_id, function ($query) use ($contact_id) {
                return $query->where('en_ci_contacts.contact_id', '=', DB::raw('UUID_TO_BIN("' . $contact_id . '")'));
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
    protected function getcontacts_shipto($contact_id = null, $inputdata = [], $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_contacts')
            ->select(DB::raw('BIN_TO_UUID(contact_id) AS contact_id'), 'prefix', 'fname', 'lname', 'email', 'contact1', 'contact2', 'associated_with', 'status')
            ->where('en_ci_contacts.status', '!=', 'd')
            ->where(['en_ci_contacts.associated_with' => 'Ship To']);

        $query->where(function ($query) use ($searchkeyword, $contact_id) {
            $query->where(function ($query) use ($searchkeyword, $contact_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('en_ci_contacts.fname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.lname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.email', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.contact1', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.contact2', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.associated_with', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($contact_id, function ($query) use ($contact_id) {
                return $query->where('en_ci_contacts.contact_id', '=', DB::raw('UUID_TO_BIN("' . $contact_id . '")'));
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
    protected function getcontacts_billto($contact_id = null, $inputdata = [], $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_contacts')
            ->select(DB::raw('BIN_TO_UUID(contact_id) AS contact_id'), 'prefix', 'fname', 'lname', 'email', 'contact1', 'contact2', 'associated_with', 'status')
            ->where('en_ci_contacts.status', '!=', 'd')
            ->where(['en_ci_contacts.associated_with' => 'Bill To']);

        $query->where(function ($query) use ($searchkeyword, $contact_id) {
            $query->where(function ($query) use ($searchkeyword, $contact_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('en_ci_contacts.fname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.lname', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.email', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.contact1', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.contact2', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ci_contacts.associated_with', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($contact_id, function ($query) use ($contact_id) {
                return $query->where('en_ci_contacts.contact_id', '=', DB::raw('UUID_TO_BIN("' . $contact_id . '")'));
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
     * @param        contact_id
     * @param_type   Integer
     * @return       Array
     * @tables       en_ci_contacts
     */
    protected function checkforrelation($contact_id)
    {
        if ($contact_id) {
            $contact_data = EnContacts::where('contact_id', DB::raw('UUID_TO_BIN("' . $contact_id . '")'))->where('status', '!=', 'd')->first();
            if ($contact_data) {

                $contact_data->update(['status' => 'd']);
                $contact_data->save();
                $data['data']['deleted_id'] = $contact_id;
                $data['message']['success'] = showmessage('118', ['{name}'], ['Contact']);
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('119', ['{name}'], ['Contact']);
                $data['status']           = 'error';
            }
        } else {
            $data['data']             = null;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Contact']);
            $data['status']           = 'error';
        }
        return $data;
    }
}
