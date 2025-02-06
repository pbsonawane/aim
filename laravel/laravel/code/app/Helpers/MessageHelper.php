<?php
use App\Http\Controllers\Controller;
function showmessage($type, $replace_array = ['{name}'], $veriables = [], $onlymsg = false, $is_default = false )
{
    if ($type != '')
    {
       
        if($is_default)
        {
            $msg = trans($type);
        }
        else
        {
             $msg = trans('messages.'.$type);
        }
        if(is_array($veriables) && count($veriables)>0)
        {
             $msg = str_replace($replace_array, $veriables, $msg);
        }
       
       return $msg;

    }
}
