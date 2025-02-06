<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\EnCiTypes;
use App\Models\EnCiTemplDefault;
use App\Models\EnCiTemplCustom;
use App\Models\EnCiTemplCustfield;
use App\Models\EnCommon;
use App\Models\Ensku;
use Validator;
use App;


class CiTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *

     * @return void
     */
    public function __construct()
    {
        //
        $debug = env('APP_ENV', true);
        DB::connection()->enableQueryLog();
        $this->custate = config('enconfig.current_env');
        apilog($this->custate);
       // $this->custate = "dev";
    }

    /*
    *This is controller funtion used for citemplates.

    * @author       Amit Khairnar
    * @access       public
    * @param        URL : ci_type_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_citypes
    */
    public function citemplates(Request $request)
    {
        
       /* try
        { */
            $chekerarr = array();
            $custate = $this->custate;
            $defaultattr = $customtattr = array();
            if($custate == 'dev')
            {
                $cuediable = true;
                $deeditable = true;
            }
            else
            {
                $deeditable = false;
                $cuediable = true;
            }   

           // echo $ci_type_id; exit;
            $inputdata = $request->all();
            //print_r($inputdata);
            $alldata = $farray = $f = array();
            $custom_atr = '';
            $in = 0;
            $ci_templ_id = _isset($inputdata, 'ci_templ_id');
            $ci_type_id = _isset($inputdata, 'ci_type_id');
            if($custate == 'dev')
            {
                 $defaultattr = EnCiTemplDefault::getcitemplatesD($ci_templ_id,  $ci_type_id); 
            }
            else
            {
                $defaultattr = EnCiTemplDefault::getcitemplatesD($ci_templ_id,  $ci_type_id); 
                $customtattr = EnCiTemplCustom::getcitemplatesC($ci_templ_id,  $ci_type_id); 
            }
               // print_r($inputdata);
                //print_r($defaultattr);exit;
                // $queries    = DB::getQueryLog();
               //  $last_query = end($queries);
                // echo 'Query<pre>';
               //  print_r($last_query);exit;

            if (count($defaultattr) > 0)     
            {
              
                foreach($defaultattr as $dattr)
                { 
                    $chekerarr[$dattr->citype] = $dattr->citype;
                    $alldata[] = $dattr;
                    $custom_atr = array();
                    $final_array = array();
                    $defal_atr = json_decode($dattr->default_attributes, true); 
                    if($dattr->custom_attributes != '')
                        $custom_atr = json_decode($dattr->custom_attributes, true);
                    $final_array['key'] = $dattr->ci_templ_id;
                    $final_array['title'] = $dattr->ci_name;//
                    // $final_array['title'] = trans('citree.'.str_replace(" ","_", $dattr->ci_name));//

                    $final_array['ci_sku'] = trans(str_replace(" ","_", $dattr->ci_sku));

                    $final_array['ci_type_id'] =$dattr->ci_type_id;
                    $final_array['citype'] =$dattr->citype;
                    $final_array['treetype'] ='component';
                    $final_array['type'] ='default';
                    $final_array['status'] = $deeditable;
                    $final_array['children'] = array();
           
                    if(is_array($defal_atr) && count($defal_atr) > 0)
                    {
                        foreach($defal_atr as $k => $da)
                        {
                            $tem['key'] =$dattr->ci_templ_id;
                            $tem['ci_type_id'] =$dattr->ci_type_id;
                            $tem['title'] = $da['attribute'];//$da['attribute'];
                            // $tem['title'] = trans('citree.'.str_replace(" ","_", $da['attribute']));//$da['attribute'];
                            $tem['variable'] =$da['veriable_name'];
                             $tem['skucode'] = isset($da['skucode'])?$da['skucode']:'';
                            $tem['type'] ='default';
                            $tem['status'] = $deeditable;
                            $tem['treetype'] ='attribute';
                            $final_array['children'][] = $tem;
                           
                        } 
                    }
                    
                    if($custate != 'dev')  
                    {
                        if(is_array($custom_atr) && count($custom_atr) > 0)
                        {
                            foreach($custom_atr as $k => $da)
                            {
                                $tem['key'] =$dattr->ci_templ_id;
                                $tem['ci_type_id'] =$dattr->ci_type_id;
                                $tem['title'] =$da['attribute'];
                                $tem['variable'] =$da['veriable_name'];
                                $tem['skucode'] = isset($da['skucode'])?$da['skucode']:'';
                                $tem['status'] = $cuediable;
                                $tem['type'] ='custfield';
                                $tem['treetype'] ='attribute';
                                //$item['childcoun']
                                $final_array['children'][] = $tem;
                            }  
                        }
                    } 

                    $in = count($chekerarr) - 1; 
                    if($in < 0){$in = 0;}
                    $in=$dattr->ci_type_id;;
                    $f[$in]['key'] = $dattr->ci_type_id;
                    $f[$in]['title'] =  $dattr->citype;//$dattr->citype;
                    $f[$in]['title'] = trans('citree.'.str_replace(" ","_", $dattr->citype));//$dattr->citype;
                    $f[$in]['treetype'] = "citype";
                    $f[$in]['children'][] = $final_array;
                    $farray[] = $final_array;
                 
                }
            } 

          
            //if(!$customtattr->isEmpty())
            if (count($customtattr) > 0)
            {
                
                foreach($customtattr as $dattr)
                { 
                    $chekerarr[$dattr->citype] = $dattr->citype;
                    $alldata[] = $dattr;
                    $final_array = array();
                    $defal_atr = json_decode($dattr->custom_attributes, true);      
                    $final_array['key'] = $dattr->ci_templ_id;
                    $final_array['title'] = $dattr->ci_name;
                    $final_array['ci_type_id'] =$dattr->ci_type_id;
                    $final_array['citype'] =$dattr->citype;

                    $final_array['ci_sku'] =$dattr->ci_sku;

                    $final_array['treetype'] ='component';
                    $final_array['type'] ='custom';
                    $final_array['status'] = $cuediable;
                    $final_array['children'] = array();
                    
                    if(is_array($defal_atr) && count($defal_atr) > 0){
                        foreach($defal_atr as $k => $da)
                        {
                            $tem['key'] = $dattr->ci_templ_id;
                            $tem['ci_type_id'] =$dattr->ci_type_id;
                            $tem['title'] = $da['attribute'];
                            $tem['variable'] = $da['veriable_name'];
                            $tem['skucode'] =  isset($da['skucode'])?$da['skucode']:'';
                            $tem['status'] = $cuediable;
                            $tem['type'] ='custom';
                            $tem['treetype'] ='attribute';
                            $final_array['children'][] = $tem;
                        }
                    }    
                   
                    $in = count($chekerarr) - 1;
                    if($in < 0){$in = 0;} 
                    $in = $dattr->ci_type_id;
                    $f[$in]['key'] = $dattr->ci_type_id;
                    $f[$in]['title'] = $dattr->citype;//trans('citree.'.str_replace(" ","_", $dattr->citype));//$dattr->citype;
                    $f[$in]['treetype'] = "citype";
                    $f[$in]['children'][] = $final_array;
                    $farray[] = $final_array;
                   
                }  

             } 
            if(is_array($f) && count($f) > 0)
            {
                $a1 = array_keys($f);
                foreach($a1 as $a)
                {
                    $d[] = $f[$a];
                }
            }
            else
            {
                $d = array();
            }
            
          
             if(count($farray) > 0)
             {
                $data['data']['records'] = $alldata;
                $data['data']['json'] = $farray;
                $data['data']['j'] = $d;
                $data['message']['success'] = showmessage('102', array('{name}'), array("test"));
             }
            else
            {
                $data['data']['json'] = '';
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_citemplate')), true);
            }
            $data['status'] = 'success';
            return response()->json($data);
        /*}
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("citemplates","This controller function is implemented to CI template Data.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("citemplates","This controller function is implemented to CI template Data.",$request->all(),$e->getMessage());
            return response()->json($data);
        }*/
    }

    /*
    *This is controller funtion used for getciitems.

    * @author       Amit Khairnar
    * @access       public
    * @param        URL : ci_type_id,ci_templ_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_citypes
    */
    public function getciitems(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            $final_array = $farray = $final = $addarray = array();
            $ci_templ_id = _isset($inputdata, 'ci_templ_id');
            $ci_type_id = _isset($inputdata, 'ci_type_id');
            $defaultattr = EnCiTemplDefault::getcitemplatesD($ci_templ_id, $ci_type_id); 
            $customtattr = EnCiTemplCustom::getcitemplatesC($ci_templ_id, $ci_type_id); 
            if (count($defaultattr) > 0)     
            {
                foreach($defaultattr as $dattr)
                { 
                    $itam = $final_array = array();
                    $itam['key'] = $dattr->ci_templ_id;
                    $itam['title'] = $dattr->ci_name;
                    // $itam['title'] = trans('citree.'.str_replace(" ","_", $dattr->ci_name));// $dattr->ci_name;
                    $itam[$dattr->ci_type_id]['name'] = $dattr->citype;
                    $itam['prefix'] = $dattr->prefix;
                    $itam['type'] ='item';
                    $itam['ci_templ_id'] = $dattr->ci_templ_id;
                    $itam['ci_type_id'] = $dattr->ci_type_id;
                    $itam['variable_name'] = $dattr->variable_name;
                    $addarray[$dattr->ci_type_id]['key'] = $dattr->ci_type_id;
                    $addarray[$dattr->ci_type_id]['title'] = $dattr->citype;
                    // $addarray[$dattr->ci_type_id]['title'] = trans('citree.'.str_replace(" ","_", $dattr->citype));//$dattr->citype;
                    $addarray[$dattr->ci_type_id]['prefix'] = $dattr->prefix;
                    $addarray[$dattr->ci_type_id]['ci_templ_id'] = $dattr->ci_templ_id;
                    $addarray[$dattr->ci_type_id]['ci_type_id'] = $dattr->ci_type_id;
                    $addarray[$dattr->ci_type_id]['type'] = 'citype';
                    $addarray[$dattr->ci_type_id]['variable_name'] = $dattr->variable_name;
                    $addarray[$dattr->ci_type_id]['children'][] = $itam;
                }
            }
            if (count($customtattr) > 0)
            {
                foreach($customtattr as $dattr)
                {    
                    $itam = $final_array = array();
                    $itam['key'] = $dattr->ci_templ_id;
                    $itam['title'] = $dattr->ci_name;
                    $itam['prefix'] = $dattr->prefix;
                    $itam['type'] ='item';
                    $itam['ci_templ_id'] = $dattr->ci_templ_id;
                    $itam['ci_type_id'] = $dattr->ci_type_id;
                    $itam['variable_name'] = $dattr->variable_name;
                    $addarray[$dattr->ci_type_id]['key'] = $dattr->ci_type_id;
                    $addarray[$dattr->ci_type_id]['title'] = $dattr->citype;
                    // $addarray[$dattr->ci_type_id]['title'] = trans('citree.'.str_replace(" ","_", $dattr->citype));//$dattr->citype;
                    $addarray[$dattr->ci_type_id]['prefix'] = $dattr->prefix;
                    $addarray[$dattr->ci_type_id]['ci_templ_id'] = $dattr->ci_templ_id;
                    $addarray[$dattr->ci_type_id]['ci_type_id'] = $dattr->ci_type_id;
                    $addarray[$dattr->ci_type_id]['type'] = 'citype';
                    $addarray[$dattr->ci_type_id]['variable_name'] = $dattr->variable_name;
                    $addarray[$dattr->ci_type_id]['children'][] = $itam;
                    //$farray[] = $final_array;    
                }  
           }
           if(is_array($addarray) && count($addarray) > 0)
           {
                foreach($addarray as $add)
                {
                    $final[] = $add;
                }
           }
         
            if(count($final) > 0)
             {
                $data['data']['records'] = $final;
                $data['message']['success'] =  showmessage('101', array('{name}'), array(trans("CI Template")),true);
             }
            else
            { 
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_citemplate')),true);
            }
            $data['status'] = 'success';
            return response()->json($data); 
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("getciitems","This controller function is implemented to get CI Items.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getciitems","This controller function is implemented to get CI Items.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    public function getcitemplate(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            $farray = array();
            $final_array = $this->gettemplate($inputdata['ci_templ_id'], $inputdata['ci_type_id']) ;   
            if(is_array($final_array) && count($final_array) > 0)
            {
                $changearray = array('server', 'laptop','desktop');
                if(in_array($final_array['variable_name'],$changearray))
                {
                    if(isset($final_array['attributes']) && is_array($final_array['attributes']) && count($final_array['attributes']) > 0)
                    {
                        foreach($final_array['attributes'] as $attr)
                        {

                            if($attr['veriable_name'] != '')
                            {
                                $defaultasset = EnCiTemplDefault::select(DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'),DB::raw('BIN_TO_UUID(ci_type_id) AS ci_type_id'),'variable_name','prefix')
                                            ->where('variable_name', $attr['veriable_name'])->first(); 
                                 
                              
                                $customasset = EnCiTemplCustom::select(DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'),DB::raw('BIN_TO_UUID(ci_type_id) AS ci_type_id'),'variable_name','prefix')
                                                ->where('variable_name', $attr['veriable_name'])->first(); 
                               
                                if($defaultasset) 
                                {
                                   $ci_templ_id = $defaultasset->ci_templ_id;
                                   $ci_type_id =$defaultasset->ci_type_id;
                                    
                                    $farray[] = $this->gettemplate($ci_templ_id, $ci_type_id); 
                                } 
                               if($customasset) 
                                {
                                    $ci_templ_id = $customasset->ci_templ_id;
                                    $ci_type_id =$customasset->ci_type_id;
                                    $farray[] = $this->gettemplate($ci_templ_id, $ci_type_id); 
                                }    
                            }   
                        } 
                    }
                }
            }
            
            if(count($final_array) > 0)
            {
                $data['data']['records'] = $final_array;
                $data['data']['assets'] = $farray;
                $data['message']['success'] =  showmessage('101', array('{name}'), array(trans('label.lbl_citemplate')), true);

            }
            else
            {
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_citemplate')), true);
            }
            $data['status'] = 'success';
            return response()->json($data);
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("getcitemplate","This controller function is implemented to get CI Template.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getcitemplate","This controller function is implemented to get CI Template.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    public function gettemplate($ci_templ_id,$ci_type_id)
    {
        try
        {
            if($ci_templ_id != '' && $ci_type_id != '')
            {
                $final_array = $custom_atr = array();
                $defaultresult = EnCiTemplDefault::getcitemplatesD($ci_templ_id, $ci_type_id); 
                $customresult = EnCiTemplCustom::getcitemplatesC($ci_templ_id, $ci_type_id);  
                if(count($defaultresult) > 0)
                {
                    foreach($defaultresult as $dattr)
                    { 
                        $defal_atr = json_decode($dattr->default_attributes, true); 
                        if($dattr->custom_attributes != '')
                            $custom_atr = json_decode($dattr->custom_attributes, true);
                        $final_array['ci_templ_id'] = $dattr->ci_templ_id;
                        $final_array['ci_name'] = $dattr->ci_name;
                        $final_array['prefix'] = $dattr->prefix;
                        $final_array['variable_name'] = $dattr->variable_name;
                        $final_array['ci_type_id'] =$dattr->ci_type_id;
                        $final_array['ci_type_id'] =$dattr->ci_type_id;
                        $final_array['type'] ='default';
                        if(is_array($defal_atr) && count($defal_atr) > 0)
                        {
                            foreach($defal_atr as $k => $da)
                            { 
                               $final_array['attributes'][]= $da;
                            } 
                        }
                        if(is_array($custom_atr) && count($custom_atr) > 0)
                        {
                            foreach($custom_atr as $k => $da)
                            {
                                $final_array['attributes'][]= $da;
                            }  
                        }
                    }
                }
                if(count($customresult) > 0)
                {
                    foreach($customresult as $dattr)
                    { 
                        $custom_atr = json_decode($dattr->custom_attributes, true);
                        $final_array['ci_templ_id'] = $dattr->ci_templ_id;
                        $final_array['ci_name'] = $dattr->ci_name;
                        $final_array['prefix'] = $dattr->prefix;
                        $final_array['variable_name'] = $dattr->variable_name;
                        $final_array['ci_type_id'] =$dattr->ci_type_id;
                        $final_array['ci_type_id'] =$dattr->ci_type_id;
                        $final_array['type'] ='custom';
                        if(is_array($custom_atr) && count($custom_atr) > 0)
                        {
                            foreach($custom_atr as $k => $da)
                            {
                                $final_array['attributes'][]= $da;
                            }  
                        }
                    }    
                }
            }
            return $final_array;
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("gettemplate","This controller function is implemented to get CI Template.",$request->all(),$e->getMessage());
            return array();
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("gettemplate","This controller function is implemented to get CI Template.",$request->all(),$e->getMessage());
            return array();
        }
    } 

    public function updateciname(Request $request)
    {
        try
        {
            $messages=[
            'ci_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ciname')), true),
            'ci_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_ciname')), true),
           
            'ci_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ci')), true),
            
        ];

         $validator = Validator::make($request->all(), [
                'ci_name' => 'required|allow_alpha_numeric_space_dash_underscore_only',
                'ci_templ_id' => 'required|string|size:36'
                
            ],$messages);
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
            $ci_templ_id_uuid = $request->input('ci_templ_id');
            $type = $request->input('type');
            $act = $request->input('act');
            $ci_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_templ_id').'")');  
            $saveValue['ci_name'] = $request->input('ci_name');
            $saveValue['ci_sku'] = $request->input('ci_sku');
         
            if(!empty($act) && $act == "del" && $type == 'custom'){
                $saveValue['status'] = "d";
            }

            $ci_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_templ_id').'")');  
            if($type == 'default')
                $templ_data = EnCiTemplDefault::where('ci_templ_id', $ci_templ_id_bin)->first();
            elseif($type == 'custom')
                $templ_data = EnCiTemplCustom::where('ci_templ_id', $ci_templ_id_bin)->first();
           
                      
            if($templ_data)
            {
                $templ_data->update($saveValue);            
                $templ_data->save();             
                $data['data'] = NULL;     
                $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_ci')), true);
                $data['status'] = 'success'; 
                //Add into UserActivityLog
                // userlog(array('record_id' => $department_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('CI'))));
            }
            else
            {             
                $data['data'] = NULL;             
                $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_ci')), true);
                $data['status'] = 'error'; 
            } 
            return response()->json($data); 
        }
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("updateciname","This controller function is implemented to update CI name.",$request->all(),$e->getMessage());
           return response()->json($data); 

        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("updateciname","This controller function is implemented to update CI name.",$request->all(),$e->getMessage());
            return response()->json($data); 
        }
    }   
    public function citypes(Request $request,$ci_type_id = null)
    {  
        try
        {             
            $requset['ci_type_id'] = $ci_type_id;
            $validator = Validator::make($request->all(), [
                'ci_type_id'=> 'nullable|string|size:36'
            ]);
             if($validator->fails())
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
                $totalrecords = EnCiTypes::getcitypes($ci_type_id,$inputdata, true);  
                $result = EnCiTypes::getcitypes($ci_type_id, $inputdata , false);  
                $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                $data['data']['totalrecords'] = $totalrecords;                
               
                if($totalrecords < 1) 
                    $data['message']['success'] =  showmessage('101', array('{name}'), array(trans('label.lbl_citype')), true);
                else
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_citype')), true);
                $data['status'] = 'success';
                return response()->json($data);
            }
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("citypes","This controller function is implemented to get CI type.",$request->all(),$e->getMessage());
           return response()->json($data); 
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("citypes","This controller function is implemented to get CI type.",$request->all(),$e->getMessage());
            return response()->json($data); 
        }            

    }

    public function citemplateadd(Request $request)
    { 
        $inputdata = $request->all();
        try
        {  
           $custate = $this->custate;
           $validator = $this->_validate_citemplate("add", $request->all());
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
                $inputdata['ci_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['ci_type_id'].'")');
                $jdata = json_encode($inputdata['ci_items']);
                $inputdata['default_attributes'] = $jdata;
                $inputdata['custom_attributes'] = $jdata;
                if (1==1)
                {
                    
                    if($inputdata['ci_name'] != '')
                    {
                        if($custate == 'dev')
                            $cidata = EnCiTemplDefault::create($inputdata);
                        else
                            $cidata = EnCiTemplCustom::create($inputdata);

                       /* $queries    = DB::getQueryLog();
                         $last_query = end($queries);
                         echo 'Query<pre>';
                         print_r($last_query);*/
                    }
                    elseif($inputdata['ci_id'] != '')
                    {
                        $ci_id_uuid = $request->input('ci_id');
                        $ci_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_id').'")');     
                        $request['ci_id'] = DB::raw('UUID_TO_BIN("'.$request->input('ci_id').'")');
                        $cusrntstate =  $this->checkdefaultandcustom($inputdata['ci_id']);
                        if($cusrntstate == 'default' && $custate == 'production')
                        {
                            $custfieldsresult = EnCiTemplCustfield::where('ci_templ_id', $ci_id_bin)->first();
                            if($custfieldsresult)
                            {
                                $cuattributes = $custfieldsresult['custom_attributes'];
                                if($cuattributes != '')
                                   {
                                        $default_attributes = json_decode($cuattributes, true);
                                        if(is_array($default_attributes) && count($default_attributes) > 0)
                                        {
                                            foreach($default_attributes as $att)
                                            {
                                                $updateattribute[] = $att;
                                            }
                                        }

                                   }
                                   if(is_array($inputdata['ci_items']) && count($inputdata['ci_items']) > 0)
                                   {
                                        foreach($inputdata['ci_items'] as $itam)
                                        {
                                            $updateattribute[] = $itam;
                                        }
                                   }
                                   $updatedata['custom_attributes'] = json_encode($updateattribute);
                                   $custfieldsresult->update($updatedata);            
                                   $custfieldsresult->save();
                            }
                            else
                            {
                                $inputdata['ci_templ_id'] = $ci_id_bin;
                                $cidata = EnCiTemplCustfield::create($inputdata);
                            } 
                            $data['data']['insert_id'] = 1;
                            $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_ci')), true);
                            $data['status'] = 'success'; 
                        }
                        else
                        {
                            if($custate == 'dev')
                                $result = EnCiTemplDefault::where('ci_templ_id', $ci_id_bin)->first();
                            else
                                $result = EnCiTemplCustom::where('ci_templ_id', $ci_id_bin)->first();

                            if($result)
                            {
                               $updateattribute = array();
                                if($custate == 'dev')
                                    $cuattributes = $result['default_attributes'];
                                else
                                    $cuattributes = $result['custom_attributes'];
                               if($cuattributes != '')
                               {
                                    $default_attributes = json_decode($cuattributes, true);
                                    if(is_array($default_attributes) && count($default_attributes) > 0)
                                    {
                                        foreach($default_attributes as $att)
                                        {
                                            $updateattribute[] = $att;
                                        }
                                    }
                               }
                               if(is_array($inputdata['ci_items']) && count($inputdata['ci_items']) > 0)
                               {
                                    foreach($inputdata['ci_items'] as $itam)
                                    {
                                        $updateattribute[] = $itam;
                                    }
                               }
                                if($custate == 'dev')
                                    $updatedata['default_attributes'] = json_encode($updateattribute);
                                else
                                    $updatedata['custom_attributes'] = json_encode($updateattribute);
                                $result->update($updatedata);            
                                $result->save(); 

                                $data['data']['insert_id'] = 1;
                                $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_ci')), true);
                                $data['status'] = 'success'; 
                            }    
                        }
                       // $result = EnCiTemplDefault::where('ci_templ_id', $ci_id_bin)->first();
                        return response()->json($data);
                    }

                    if ($cidata->ci_templ_id_text == '')
                    {
                        DB::rollBack();
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('103', array('{name}'), array(trans('label.lbl_ci')), true);
                        $data['status'] = 'error';
                    }
                    else
                    {
                        //userlog(array('record_id' => $userdata->user_id_text, 'data' => $inputdata, 'action' => 'create', 'message' => showmessage('104', array('{name}'), array('User'), true)));
                        DB::commit();
                        $data['data']['insert_id'] = 1;
                        $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_ci')), true);
                        $data['status'] = 'success';
                    }
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('100');
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        }
        catch(\Exception $e)
        {
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("citemplateadd","This controller function is implemented to add Ci Template.",$request->all(),$e->getMessage());
           return response()->json($data); 
        }
        catch(\Error $e)
        {
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("citemplateadd","This controller function is implemented to add Ci Template.",$request->all(),$e->getMessage());
            return response()->json($data); 
        }
    }

    public function editci(Request $request)
    {
        try
        { 
            $ci_templ_id = $request['ci_templ_id'];
            $ci_type_id = $request['ci_type_id'];
            $type = $request['type'];
            $variable_name = $request['variable_name'];
            $validator = Validator::make($request->all(), [ 
                'ci_templ_id' => 'required|string|size:36'
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
                if($type != '')
                {
                    if($type == 'default' || $type == 'custfield')
                    {
                        $result = EnCiTemplDefault::getcitemplatesD($ci_templ_id,$ci_type_id); 
                    }
                    elseif($type == 'custom')
                    {  
                        $result = EnCiTemplCustom::getcitemplatesC($ci_templ_id,$ci_type_id); 
                    }
                }
                
                if(!$result->isEmpty())
                {
                   foreach($result as $res)
                   {
                        if($type == 'default')
                            $attributes = json_decode($res->default_attributes, true); 
                        elseif($type == 'custom')
                            $attributes = json_decode($res->custom_attributes, true);
                        elseif($type == 'custfield')
                            $attributes = json_decode($res->custom_attributes, true);
                    }   

                    if(is_array($attributes) && count($attributes) > 0)
                    {
                        foreach($attributes as $attr)
                        {
                            if($attr['veriable_name'] == $variable_name)
                            {
                                $result['item'] = $attr;
                            }    
                        }
                    }
                    $result['type'] = $type;

                }
                
                 $data['data'] = $result->isEmpty() ? NULL : $result;
                
              
                  if($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_ci')), true);
                    $data['status'] = 'success';            
                }
                else
                {
                   
                    $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_ci')), true);
                    $data['status'] = 'error';          
                }
            }
            return response()->json($data); 
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("editci","This controller function is implemented to edit CI.",$request->all(),$e->getMessage());
           return response()->json($data); 
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("editci","This controller function is implemented to edit CI.",$request->all(),$e->getMessage());
            return response()->json($data); 
        }          
    }
    public function updateci(Request $request)
    {
        try
        {
            $validator = $this->_validate_citemplate("edit", $request->all());
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {
                $ci_items =  Input::get('ci_items');
                $cu_var = $ci_items[0]['veriable_name'];
                $type = $request->input('type');
                $ci_templ_id_uuid = $request->input('ci_templ_id');
                $ci_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_templ_id').'")');     
                //$request['department_id'] = DB::raw('UUID_TO_BIN("'.$request->input('department_id').'")');

                if($type == 'default' || $type == 'custfield')
                {
                    $result = EnCiTemplDefault::getcitemplatesD($ci_templ_id_uuid); 
                }
                elseif($type == 'custom')
                {
                    $result = EnCiTemplCustom::getcitemplatesC($ci_templ_id_uuid); 
                }
                if(!$result->isEmpty())
                {




             /*       $inputdata = $request->all();
                $inputdata['ci_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['ci_type_id'].'")');
                $jdata = json_encode($inputdata['ci_items']);
                $inputdata['default_attributes'] = $jdata;
                $inputdata['custom_attributes'] = $jdata;*/




                   foreach($result as $res)
                   {
                        if($type == 'default')
                            $attributes = json_decode($res->default_attributes, true); 
                        elseif($type == 'custom')
                            $attributes = json_decode($res->custom_attributes, true);
                        elseif($type == 'custfield')
                            $attributes = json_decode($res->custom_attributes, true);
                    }   
                   
                    if(is_array($attributes) && count($attributes) > 0)
                    {
                        foreach($attributes as $key=>$attr)
                        {
                            if($attr['veriable_name'] == $cu_var)
                            {
                                $attributes[$key]['attribute'] = $ci_items[0]['attribute'];
                                $attributes[$key]['unit'] = $ci_items[0]['unit'];
                                $attributes[$key]['input_type'] = $ci_items[0]['input_type'];
                                $attributes[$key]['validation'] = isset($ci_items[0]['validation']) ? $ci_items[0]['validation'] : "";
                                $attributes[$key]['skucode'] = $ci_items[0]['skucode'];
                            }    
                        }
                    }

                    $saveValue['default_attributes'] = json_encode($attributes);
                    $saveValue['custom_attributes'] = json_encode($attributes);
                    if($type == 'default')
                        $templ_data = EnCiTemplDefault::where('ci_templ_id', $ci_templ_id_bin)->first();
                    elseif($type == 'custom')
                        $templ_data = EnCiTemplCustom::where('ci_templ_id', $ci_templ_id_bin)->first();
                    elseif($type == 'custfield')
                       $templ_data = EnCiTemplCustfield::where('ci_templ_id', $ci_templ_id_bin)->first();
                        
                        //print_r($templ_data);exit;
                    if($templ_data)
                    {
                        $templ_data->update($saveValue);            
                        $templ_data->save();             
                        $data['data'] = NULL;     
                        $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_ci')), true);
                        $data['status'] = 'success'; 
                        //Add into UserActivityLog
                       // userlog(array('record_id' => $department_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('CI'))));
                    }
                    else
                    {             
                        $data['data'] = NULL;             
                        $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_ci')), true);
                        $data['status'] = 'error'; 
                    } 

                }
                
            }
           return response()->json($data);
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("updateci","This controller function is implemented to update CI.",$request->all(),$e->getMessage());
           return response()->json($data); 
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("updateci","This controller function is implemented to update CI.",$request->all(),$e->getMessage());
            return response()->json($data); 
        } 
    }

    public function deleteci(Request $request)
    {
        try
        {
            $ci_templ_id_uuid = $request->input('ci_templ_id');
            $variable_name = $request->input('variable_name');;
            $type = $request->input('type');
            $f_array = array();
            $ci_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_templ_id').'")');  
            $messages =  [    
                'ci_templ_id.required' => 'The CI ID field should be required' ,
                'variable_name.required' => 'The variable Name field should be required',
            ];
                
            $validator = Validator::make($request->all(), [ 
                'ci_templ_id' => 'required|string|size:36',
                'variable_name' => 'required', 
               // 'status' => 'required|in:y,n'           
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
                if($type == 'default' || $type == 'custfield')
                {
                    $result = EnCiTemplDefault::getcitemplatesD($ci_templ_id_uuid); 
                }
                elseif($type == 'custom')
                {
                    $result = EnCiTemplCustom::getcitemplatesC($ci_templ_id_uuid); 
                }
                if(!$result->isEmpty())
                {
                   foreach($result as $res)
                   {
                        if($type == 'default')
                            $attributes = json_decode($res->default_attributes, true); 
                        elseif($type == 'custom')
                            $attributes = json_decode($res->custom_attributes, true);
                        elseif($type == 'custfield')
                            $attributes = json_decode($res->custom_attributes, true);
                    }   
                   
                    if(is_array($attributes) && count($attributes) > 0)
                    {
                        foreach($attributes as $key=>$attr)
                        {
                            if($attr['veriable_name'] != $variable_name)
                            {
                                $f_array[]=$attr;
                            } 
                           
                        }
                    }

                    $saveValue['default_attributes'] = json_encode($f_array);
                    $saveValue['custom_attributes'] = json_encode($f_array);
                    if($type == 'default')
                        $templ_data = EnCiTemplDefault::where('ci_templ_id', $ci_templ_id_bin)->first();
                    elseif($type == 'custom')
                        $templ_data = EnCiTemplCustom::where('ci_templ_id', $ci_templ_id_bin)->first();
                    elseif($type == 'custfield')
                       $templ_data = EnCiTemplCustfield::where('ci_templ_id', $ci_templ_id_bin)->first();
                        
                        //print_r($templ_data);exit;
                    if($templ_data)
                    {
                        $templ_data->update($saveValue);            
                        $templ_data->save();             
                        $data['data'] = NULL;     
                        $data['message']['success'] = showmessage('118', array('{name}'), array(trans('label.lbl_ci')), true);
                        $data['status'] = 'success'; 
                        //Add into UserActivityLog
                       // userlog(array('record_id' => $department_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('CI'))));
                    }
                    else
                    {             
                        $data['data'] = NULL;             
                        $data['message']['error'] = showmessage('119', array('{name}'), array(trans('label.lbl_ci')), true);
                        $data['status'] = 'error'; 
                    } 
                    
                }
            }
            return response()->json($data);
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("deleteci","This controller function is implemented to delete CI.",$request->all(),$e->getMessage());
           return response()->json($data); 
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("deleteci","This controller function is implemented to delete CI.",$request->all(),$e->getMessage());
            return response()->json($data); 
        }  
    }
     
    public function _validate_citemplate($action, $inputdata)
    {
       
        $check_ciname = 0;
        $messages=[
            'ci_type_id.required' =>showmessage('000', array('{name}'), array(trans('label.lbl_citype')), true),//'The Ci Type field should be required',

            'ci_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ciname')), true),
            'ci_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ciname')), true),
            'ci_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_ciname')), true),
           
            'prefix.required' => showmessage('000', array('{name}'), array(trans('label.lbl_cinameprefix')), true),
            'prefix.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_cinameprefix')), true),
           
            'variable_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_Variable_name')), true),//'The Variable Name field should be required',
            'variable_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_Variable_name')), true),
        ];
        
        
        if($action  == 'add')
        {
            $validation_rules['prefix'] = 'required|allow_alpha_numeric_space_dash_underscore_only';
            $validation_rules['variable_name'] = 'required|allow_alpha_numeric_space_dash_underscore_only';
        }        
           
            $validation_rules['ci_type_id'] = 'required';
            if($inputdata['ci_id'] == '') 
               {
                    $validation_rules['ci_name'] = 'required|allow_alpha_numeric_space_dash_underscore_only';
                    $check_ciname = 1;
               } 
            else
                $validation_rules['ci_id'] = 'required';
            
            foreach (Input::get('ci_items') as $id=>$ci_items) 
            {
                $cid = $id+1;
                $validation_rules["ci_items.${id}.attribute"] = 'required|allow_alpha_numeric_space_dash_underscore_only';
                    $messages['ci_items.'.$id.'.attribute.required'] = showmessage('024', array('{name}','{id}'), array(trans('label.lbl_Attribute'),$cid), true);//'The Attribute field in row '.$cid.' should be required';
                    $messages['ci_items.'.$id.'.attribute.allow_alpha_numeric_space_dash_underscore_only'] = showmessage('003', array('{name}','{id}'), array(trans('label.lbl_Attribute'),$cid), true);
                $validation_rules["ci_items.${id}.veriable_name"] = 'required';
                    $messages['ci_items.'.$id.'.veriable_name.required'] = showmessage('024', array('{name}','{id}'), array(trans('label.lbl_Variable_name'),$cid), true);//'The Variable Name field in row '.$cid.' should be required';
                $validation_rules["ci_items.${id}.input_type"] = 'required';
                    $messages['ci_items.'.$id.'.input_type.required'] =showmessage('024', array('{name}','{id}'), array(trans('label.lbl_inputtype'),$cid), true);//'The Input Type field in row '.$cid.' should be required';

            }

        $validator = Validator::make($inputdata, $validation_rules,$messages);

       if($check_ciname > 0)
        {
                $validator->after(function($validator)
                {
                    if ($this->checkNameExists()) {
                        $validator->errors()->add('ci_name', showmessage('006', array('{name}'), array(trans('label.lbl_ciname')), true));//'The ci name has already been taken.'
                    }
                });
        }
        if($action  == 'add')
        {
            if($inputdata['variable_name'] != '' && $inputdata['ci_id'] == '')
            {
                $validator->after(function($validator)
                {
                    if ($this->checkvariableNameExists()) {
                        $validator->errors()->add('variable_name', showmessage('006', array('{name}'), array(trans('label.lbl_Variable_name')), true));//'The Variable Name has already been taken.'
                    }
                });
            }   
            
        } 
         $validator->after(function($validator) {
            
            $data = $this->checkattribute();
           
            if($data['variables'] > 0)
            {
                $validator->errors()->add('variable_name', showmessage('006', array('{name}'), array(trans('label.lbl_Variable_name')), true));////'The Variable Name has already been taken.'
            }
            if($data['attributes'] > 0)
            {
                $validator->errors()->add('attribute', showmessage('006', array('{name}'), array(trans('label.lbl_Attribute')), true));//'The Atrribute has already been taken.'
            }
        });
        return $validator;
    }

    public function checkvariableNameExists()
    {   
        $request = request();
        $ci_type_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_type_id').'")');  
        $check_defalt_exists = EnCommon::checkexists('en_ci_templ_default','variable_name',$request->input('variable_name'),'','','ci_type_id',$ci_type_id_bin);
        $check_custom_exists = EnCommon::checkexists('en_ci_templ_custom','variable_name',$request->input('variable_name'),'','','ci_type_id',$ci_type_id_bin);
        if($check_defalt_exists)
            return $check_defalt_exists;
        else
           return $check_custom_exists; 
    }   

    public function checkNameExists()
    {   
        $request = request();
        $ci_type_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('ci_type_id').'")');  
        $check_defalt_exists = EnCommon::checkexists('en_ci_templ_default','ci_name',$request->input('ci_name'),'','','ci_type_id',$ci_type_id_bin);
        $check_custom_exists = EnCommon::checkexists('en_ci_templ_custom','ci_name',$request->input('ci_name'),'','','ci_type_id',$ci_type_id_bin);
        if($check_defalt_exists)
            return $check_defalt_exists;
        else
           return $check_custom_exists; 
    }
    public function checkattribute()
    { 

        $request = request();
        
        $attrcout = 0;
        $varicount = 0;
        $allattrnvariables = $this->getallattributesveriablename(); 
        //print_r($allattrnvariables);exit;
        $attributes = $allattrnvariables['attributes'];
        $variables = $allattrnvariables['variables'];
        $ci_items =  Input::get('ci_items');//
      //  print_r($ci_items);die();
        foreach($ci_items as $item)
        {
            //if(trim($item['attribute']) != "")
                $cuattribute[]= $item['attribute'];
          
           // if(trim($item['veriable_name']) != "")
                $cuvariables[] = $item['veriable_name'];
            

        }
        if(count(array_unique($cuattribute)) == count($ci_items))
        {
            if(is_array($attributes) && count($attributes) > 0)
            {
                foreach($cuattribute as $att)
                {
                     if(in_array($att,$attributes)) 
                     {
                        $attrcout++;
                     } 
                }
            }
        }
        else
        {
            $attrcout++;
        }
        if(count(array_unique($cuvariables)) == count($ci_items))
        {
            if(is_array($variables) && count($variables) > 0)
            {
                foreach($cuvariables as $var)
                {
                     if(in_array($var,$variables)) 
                     {
                        $varicount++;
                     } 
                }
            }
        }
        else
        {
            $varicount++;
        }
        
        $data['variables'] = $varicount;
        $data['attributes'] = $attrcout;
       return $data;
        
    }

    public function getallattributesveriablename()
    {
        $cu_var ='';
        $attributes = array();
        $variables = array();
        $request = request();
        $ci_id = Input::get('ci_id');
        $type = Input::get('type');
        if($type != '')
        {   
             $ci_items =  Input::get('ci_items');
             $cu_var = $ci_items[0]['veriable_name'];
        }
        if($ci_id != '')
        {
            $defautattributes = EnCiTemplDefault::getcitemplatesD($ci_id);  
            //print_r($defautattributes);exit;
            if(!$defautattributes->isEmpty())
            {
                foreach($defautattributes as $default)
                { 
                    $defal_atr = json_decode($default->default_attributes, true);
                    if(is_array($defal_atr) && count($defal_atr) > 0 )
                    {       
                        foreach($defal_atr as $k => $da)
                        {
                            if($cu_var != '')
                            {
                                if($cu_var != $da['veriable_name'])
                                {
                                    $attributes[] = $da['attribute'];
                                    $variables[] = $da['veriable_name'];
                                }
                            }
                            else
                            {
                                $attributes[] = $da['attribute'];
                                $variables[] = $da['veriable_name'];
                            }    
                            
                        } 
                    }    
                }
            } 
            $customattributes = EnCiTemplCustom::getcitemplatesC($ci_id);  
            if(!$customattributes->isEmpty())
            {
                foreach($customattributes as $custom)
                { 
                    $custom_atr = json_decode($custom->custom_attributes, true);
                    if(is_array($custom_atr) && count($custom_atr) > 0 )
                    {       
                        foreach($custom_atr as $k => $da)
                        {
                            if($cu_var != '')
                            {
                                if($cu_var != $da['veriable_name'])
                                {
                                    $attributes[] = $da['attribute'];
                                    $variables[] = $da['veriable_name'];
                                }
                            }
                            else
                            {
                                $attributes[] = $da['attribute'];
                                $variables[] = $da['veriable_name'];
                            }    
                        } 
                    } 
                }
            }
            $custfields = EnCiTemplDefault::getcitemplatesD($ci_id);  
            if(!$custfields->isEmpty())
            {
                foreach($custfields as $custfield)
                { 
                    $customfields_atr = json_decode($custfield->custom_attributes, true);
                    if(is_array($customfields_atr) && count($customfields_atr) > 0 )
                    {       
                        foreach($customfields_atr as $k => $da)
                        {
                            if($cu_var != '')
                            {
                                if($cu_var != $da['veriable_name'])
                                {
                                    $attributes[] = $da['attribute'];
                                    $variables[] = $da['veriable_name'];
                                }
                            }
                            else
                            {
                                $attributes[] = $da['attribute'];
                                $variables[] = $da['veriable_name'];
                            }    
                        } 
                    }     
                }
            }
        }
        $dataarr['attributes'] = $attributes;
        $dataarr['variables'] =  $variables;  
        return $dataarr;
    }

    public function checkdefaultandcustom($ci_templ_id)
    {
        $defaultattr = EnCiTemplDefault::getcitemplatesD($ci_templ_id); 
        $customtattr = EnCiTemplCustom::getcitemplatesC($ci_templ_id); 
        if(!$defaultattr->isEmpty())
            return 'default';
        if(!$customtattr->isEmpty())
            return 'custom';
    }

    public function getallcitemplates()
    {
        $citempmplates_default = EnCiTemplDefault::select(DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), DB::raw('BIN_TO_UUID(ci_type_id) AS ci_type_id'),'ci_name', 'variable_name')->where('status','!=','d')->get();

        $citempmplates_custom = EnCiTemplCustom::select(DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), DB::raw('BIN_TO_UUID(ci_type_id) AS ci_type_id'),'ci_name', 'variable_name')->where('status','!=','d')->get();
        
        $arr1 = $citempmplates_default;
        $arr2 = $citempmplates_custom;

        if($arr2){
            foreach ($arr2 as $key => $value) {
                $arr1[] = $value;
            }
        }
        $citempmplates = $arr1;

        
        if($citempmplates)
        {

            $data['data'] = $citempmplates;     
            $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_ci')), true);
            $data['status'] = 'success'; 
        }
        else
        {             
            $data['data'] = NULL;             
            $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_ci')), true);
            $data['status'] = 'error'; 
        } 
         return response()->json($data); 
    }

    public function skucode(Request $request)
    {
        
     //$sku_custom = Ensku::select('sku_code','id')->orderBy('id', 'DESC')->pluck('sku_code','id');

     //$sku_custom = Ensku::select('sku_code','id','core_product_name')->orderBy('id', 'DESC')->get();
        $sku_custom = Ensku::orderBy('id', 'DESC')->pluck('core_product_name', 'sku_code', 'id');

         if($sku_custom)
        {

            $data['data'] = $sku_custom;     
            $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_ci')), true);
            $data['status'] = 'success'; 
        }
        else
        {             
            $data['data'] = NULL;             
            $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_ci')), true);
            $data['status'] = 'error'; 
        } 
         return response()->json($data); 
    }

    public function skucodename(Request $request)
    {
        
        $prefix = $request->input('prefix');
        $sku_custom = Ensku::select('sku_code','id','core_product_name')->where('primary_category_abbreviation','=',$prefix)->orderBy('id', 'DESC')->get()->toArray();

         if($sku_custom)
        {

            $data['data'] = $sku_custom;     
            $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_ci')), true);
            $data['status'] = 'success'; 
        }
        else
        {             
            $data['data'] = NULL;             
            $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_ci')), true);
            $data['status'] = 'error'; 
        } 
         return response()->json($data); 
    }

}