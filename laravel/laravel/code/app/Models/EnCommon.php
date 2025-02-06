<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnCommon extends Model
{
	protected function checkexists($table_name,$field_name="",$field_value="",$primary_field = "",$primary_val = "", $field_name1="", $field_value1="")
    {   
        //$record_cnt = EnPermissionCategories::where('module_id', DB::raw('UUID_TO_BIN("'.$module_id.'")'))->count();
        $query = DB::table($table_name) 
                ->select($field_name)                
                ->where('status', '!=', 'd')
                ->where($field_name,'=', $field_value);
                if($field_name1 != "" && $field_value1 != "")
                {
                    $query->where($field_name1,'=', $field_value1);
                }
        if($primary_field != '' && $primary_val != '') 
             $query->where($primary_field, '!=', DB::raw('UUID_TO_BIN("'.$primary_val.'")')); 
               
        $data = $query->get();       
        return count($data);
 
    }
}
