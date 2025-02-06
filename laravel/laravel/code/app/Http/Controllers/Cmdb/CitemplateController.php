<?php
namespace App\Http\Controllers\Cmdb;
use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Redirect;
use View;

class CitemplateController extends Controller
{
   public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
    	$this->itam = $itam;        
    	$this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }

    /**
    * This controller function is implemented to call base page.
    * @author Amit Khairnar
    * @access public
    * @package CMDB
    * @return html
    */

    function citemplates()
    {
    	$data['pageTitle'] = trans('title.citemplates');
        $data['includeView'] = view("Cmdb/citemplates", $data);
        return view('template', $data);
    }

    /**
    * This controller function is implemented to get list of Citemplate data.
    * @author Amit Khairnar
    * @access public
    * @package CMDB
    * @return json
    */
    function citemplatedata()
    {
        try
        {
            $is_error = false;
            $msg = '';
            $data = [];
            $option = [];
            $citemplates = $this->itam->getcitemplates($option);
            //print_r($citemplates); die();
            $view = View::make("Cmdb/citemplatedata", $data);
            $contents = $view->render();
            $response["html"] = $contents;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            $response["cidata"] = _isset(_isset($citemplates,'content'),'j'); //$data['content']['records'];
        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response["cidata"] = "";
            save_errlog("citemplatedata","This controller function is implemented to get list of CITemplate.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response["cidata"] = "";
            save_errlog("citemplatedata","This controller function is implemented to get list of CITemplate.",$this->request_params,$e->getmessage());  
        }
        finally
        {
          echo json_encode($response);
        } 
        
    }
    /**
    * This controller function is implemented to get component add page
    * @author Amit Khairnar
    * @access public
    * @package CMDB
    * @return json
    */
    function compadd(Request $request)
    {

        $ci_type_id = $request->input('ci_type_id');
        $citype = $request->input('citype');
        $treetype = $request->input('treetype');
        $option = [];
        $citypes = $this->itam->citypes($option);

        $data["citypes"] = _isset(_isset($citypes,'content'),'records'); 

        $skucodes = $this->itam->skucodes($option);
       


        //  $skucodesname = $this->itam->skucodename($option);
         /*echo "<pre>";
          print_r($skucodes); die();*/

        $data["skucodes"] = _isset($skucodes,'content');
        $data['ci_type_id'] = $ci_type_id;
        $data['citype'] = $citype;
        $data['treetype'] = $treetype;
        $view = View::make("Cmdb/compadd", $data);
        $contents = $view->render();
        $response["html"] = $contents;
        $response["is_error"] = $is_error = "";
        $response["msg"] = $msg = "";
        return json_encode($response);
    }
    /**
    * This controller function is implemented to get component save
    * @author Amit Khairnar
    * @access public
    * @package CMDB
    * @return json
    */
    function savecomp(Request $request)
    {

        try
        {
            
            $ci_id = _isset($this->request_params, 'ci_id') ? $request->input('ci_id'): '';;
            $ci_type_id = _isset($this->request_params, 'ci_type_id') ? $request->input('ci_type_id'): '';
            $ci_name = _isset($this->request_params, 'ci_name') ? $request->input('ci_name'): '';
            $variable_name = _isset($this->request_params, 'variable_name') ? $request->input('variable_name'): '';
            $prefix = _isset($this->request_params, 'prefix') ? $request->input('prefix'): '';
            $ci_sku = _isset($this->request_params, 'ci_sku') ? $request->input('ci_sku'): '';
            $attribute = $request->input('attribute');
            $inpute_type = $request->input('inpute_type');
            $skucode = $request->input('skucode');
            $validations = $request->input('validations');
            $valarray = $request->input('valarray');
            $valdationsaray = $valids =  [];
            if(trim($valarray) != "" )
            {
                $valids = explode("*",$valarray);
            }
            if(is_array($valids) && count($valids) > 0)
            {
                foreach($valids as $valid)
                {
                    if(trim($valid) != "")
                    {
                        $va = explode(",",$valid);
                        $valdationsaray[] = $va;
                    }else
                    {
                        $valdationsaray[] = "";
                    }
                }
            }
            
            $unit = $request->input('unit');
            $v_name = $request->input('v_name');
            if(is_array($v_name) && count($v_name) > 0)
            {   
                $cnt = count($v_name);
                for($i=0; $i<$cnt; $i++)
                {
                    $item = [];
                    $item['attribute'] = _isset($attribute, $i) ? $attribute[$i]: '';
                    $item['input_type'] = _isset($inpute_type, $i) ? $inpute_type[$i]: '';
                    $item['unit'] = _isset($unit, $i) ? $unit[$i]: '';
                    $item['validation'] = _isset($valdationsaray, $i) ? $valdationsaray[$i]: '[]';  
                   // $item['validation'] = isset($validations) ? $validations: '[]'; 
                   $item['skucode'] = _isset($skucode, $i) ? $skucode[$i]: ''; 
                   $item['veriable_name'] = _isset($v_name, $i) ? $v_name[$i]: '';
                    $ci_items[$i] = $item;
                }
            }
            $inputarray['ci_items'] = $ci_items;
            $inputarray['ci_id'] = $ci_id;
            $inputarray['ci_type_id'] = $ci_type_id;
            $inputarray['ci_name'] = $ci_name;
            $inputarray['variable_name'] = $variable_name;
            $inputarray['prefix'] = $prefix;
            $inputarray['ci_sku'] = $ci_sku;

        
            $data = $this->itam->citemplateadd(['form_params' => $inputarray]);

            if(!empty($ci_id) && $data["is_error"] == false){
                $data["msg"] = showmessage('104', ['{name}'], [trans('label.Attribute')]);
            }
        }
        catch (\Exception $e)
        {
            $data["html"] = "";
            $data["is_error"] = true;
            $data["msg"] = $e->getmessage();
            save_errlog("savecomp","This controller function is implemented to  Component save.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $data["html"] = "";
            $data["is_error"] = true;
            $data["msg"] = $e->getmessage();
            save_errlog("savecomp","This controller function is implemented to Component save.",$this->request_params,$e->getmessage());  
        }
        finally
        {
           echo json_encode($data, true);
        } 
       
    }

    /**
    * This controller function is implemented to  edit component view
    * @author Amit Khairnar
    * @access public
    * @package CMDB
    * @return json
    */
 
    function editcomp(Request $request)
    {
        try
        {
            $data['ci_type_id'] = $request->input('ci_type_id');
            $data['ci_templ_id'] = $request->input('ci_templ_id');
            $data['citype'] = $request->input('citype');
            $data['ci_name'] = $request->input('ci_name');
            $data['ci_sku'] = $request->input('ci_sku');
            $data['status'] = $request->input('status');
            $data['type'] = $request->input('type');
            $data['treetype'] = $request->input('treetype');

            $option = [];

            $skucodes = $this->itam->skucodes($option);

            $data["skucodes"] = _isset($skucodes,'content');

            $view = View::make("Cmdb/editcomp", $data);
            $contents = $view->render();
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            //return json_encode($response);
        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("editcomp","This controller function is implemented to edit Component view.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("editcomp","This controller function is implemented to edit Component view.",$this->request_params,$e->getmessage());  
        }
        finally
        {
            return json_encode($response);
        } 
    }

    /**
    * This controller function is implemented to  update component 
    * @author Amit Khairnar
    * @access public
    * @package CMDB
    * @return json
    */

    function updatecomp(Request $request)
    {
        try
        {
            $inputarray['ci_type_id'] = $request->input('ci_type_id');
            $inputarray['ci_templ_id'] = $request->input('ci_id');
            $inputarray['ci_name'] = $request->input('ci_name');
            $inputarray['type'] = $request->input('type');
            $inputarray['ci_sku'] = $request->input('ci_sku');

            if(!empty($request->input('act'))){
                $inputarray['act'] = $request->input('act');
            }else{
                $inputarray['act'] = "";
            }
            
            $data = $this->itam->updateciname(['form_params' => $inputarray]);
            //echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            $data["html"] = "";
            $data["is_error"] = true;
            $data["msg"] = $e->getmessage();
            save_errlog("updatecomp","This controller function is implemented to update Component.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $data["html"] = "";
            $data["is_error"] = true;
            $data["msg"] = $e->getmessage();
            save_errlog("updatecomp","This controller function is implemented to update Component.",$this->request_params,$e->getmessage());  
        }
        finally
        {
             echo json_encode($data, true);
        } 
    }

    /**
    * This controller function is implemented to  get attributes
    * @access public
    * @package CMDB
    * @return json
    */

    function addattributes(Request $request)
    {
        try
        {
            $option = [];
            $option['ci_type_id'] = $request->input('ci_type_id');
            $option['ci_templ_id'] = $request->input('ci_id');
            $citemplates = $this->itam->getcitemplates(['form_params' => $option]);
            $data["cidata"] = _isset(_isset($citemplates,'content'),'records');
            $data['ci_type_id'] = $request->input('ci_type_id');
            $data['ci_id'] = $request->input('ci_id');
            $option = [];
            $skucodes = $this->itam->skucodes($option);
            $data["skucodes"] = _isset($skucodes,'content');
            $view = View::make("Cmdb/addattributes", $data);
            $contents = $view->render();
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
       // return json_encode($response);
        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("addattributes","This controller function is implemented to get attributes.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("addattributes","This controller function is implemented to get attributes.",$this->request_params,$e->getmessage());  
        }
        finally
        {
            return json_encode($response);
        } 
    }
     /**
    * This controller function is implemented to  edit attribute view
    * @access public
    * @package CMDB
    * @return json
    */

    function editAttribute(Request $request)
    {
       // try
      //  {
            $data['attr_name'] = $request->input('attr_name');
            $data['ci_templ_id'] = $request->input('ci_templ_id');
            $data['treetype'] = $request->input('treetype');
            $data['status'] = $request->input('status');
            $data['ci_type_id'] = $request->input('ci_type_id');
            $option['variable_name'] = $request->input('variable');

            $option['skucode'] = $request->input('skucodes');
            // $data['skucode'] = $request->input('skucodes');
            $option['type'] = $request->input('type');
            $option['ci_type_id'] = $request->input('ci_type_id');
            $option['ci_templ_id'] = $request->input('ci_templ_id');
            $citemplates = $this->itam->editci(['form_params' => $option]);


            $data["cidata"] = _isset($citemplates,'content');
            $data['item'] =  _isset(_isset($citemplates,'content'),'item');

            $data['item']['skucode']=$request->input('skucodes');

            $option = [];
            $skucodes = $this->itam->skucodes($option);
            $data["skucodes"] = _isset($skucodes,'content');
            $view = View::make("Cmdb/editAttribute", $data);
            $contents = $view->render();
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            return json_encode($response);
    /*    }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("addattributes","This controller function is implemented to get attributes.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("addattributes","This controller function is implemented to get attributes.",$this->request_params,$e->getmessage());  
        }
        finally
        {
            return json_encode($response);
        } */

            // /return json_encode($response);
    }
     /**
    * This controller function is implemented to  update attribute 
    * @access public
    * @package CMDB
    * @return json
    */
    function updateattribute(Request $request)
    {
        try
        {
            $ci_id = _isset($this->request_params, 'ci_id') ? $request->input('ci_id'): '';;
            $ci_type_id = _isset($this->request_params, 'ci_type_id') ? $request->input('ci_type_id'): '';
            $ci_name = _isset($this->request_params, 'ci_name') ? $request->input('ci_name'): '';
            $variable_name = _isset($this->request_params, 'variable_name') ? $request->input('variable_name'): '';
           
            $ci_sku = _isset($this->request_params, 'ci_sku') ? $request->input('ci_sku'): '';
        
            $prefix = _isset($this->request_params, 'prefix') ? $request->input('prefix'): '';
            $type = _isset($this->request_params, 'type') ? $request->input('type'): '';
            $attribute = $request->input('attribute');
            $inpute_type = $request->input('inpute_type');
            $validations = $request->input('validations');
            if(is_array($validations) && count($validations) > 0)
            {
                $validations = $validations;
            }
            else
            {
                $validations = [];
            }
            $unit = $request->input('unit');
            $skucode = $request->input('skucode');
            $v_name = $request->input('v_name'); 

            if(is_array($v_name) && count($v_name) > 0)
            {   
                $cnt = count($v_name);
                for($i=0; $i<$cnt; $i++)
                {
                    $item = [];
                    $item['attribute'] = _isset($attribute, $i) ? $attribute[$i]: '';
                    $item['input_type'] = _isset($inpute_type, $i) ? $inpute_type[$i]: '';
                    $item['unit'] = _isset($unit, $i) ? $unit[$i]: '';
                    $item['skucode'] = _isset($skucode, $i) ? $skucode[$i]: '';
                    //$item['validation'] = _isset($validations, $i) ? $validations[$i]: '';  
                    $item['validation'] = $validations;  
                    $item['veriable_name'] = _isset($v_name, $i) ? $v_name[$i]: '';
                    $ci_items[$i] = $item;
                }
            }
            $inputarray['ci_items'] = $ci_items;
            $inputarray['ci_id'] = $ci_id;
            $inputarray['ci_templ_id'] = $ci_id;
            $inputarray['ci_type_id'] = $ci_type_id;
            $inputarray['ci_name'] = $ci_name;
            $inputarray['variable_name'] = $variable_name;
            $inputarray['ci_sku'] = $ci_sku;
            $inputarray['prefix'] = $prefix;
            $inputarray['type'] = $type;

            $data = $this->itam->updateci(['form_params' => $inputarray]);

            if($data["is_error"] == false){
                $data["msg"] = showmessage('106', ['{name}'], [trans('label.Attribute')]);
            }
            //echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            $data["html"] = "";
            $data["is_error"] = true;
            $data["msg"] = $e->getmessage();
            save_errlog("addattributes","This controller function is implemented to edit attributes.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $data["html"] = "";
            $data["is_error"] = true;
            $data["msg"] = $e->getmessage();
            save_errlog("addattributes","This controller function is implemented to edit attributes.",$this->request_params,$e->getmessage());  
        }
        finally
        {
            echo json_encode($data, true);
        } 
    }

     /**
    * This controller function is implemented to  Delete CI
    * @access public
    * @package CMDB
    * @return json
    */

    function deleteci(Request $request)
    {
        $inputarray['ci_templ_id'] = _isset($this->request_params, 'ci_id') ? $request->input('ci_id'): '';
        $inputarray['variable_name'] = _isset($this->request_params, 'variable_name') ? $request->input('variable_name'): '';
        $inputarray['type'] = _isset($this->request_params, 'type') ? $request->input('type'): '';
        $data = $this->itam->deleteci(['form_params' => $inputarray]);

        if($data["is_error"] == false){
            $data["msg"] = showmessage('118', ['{name}'], [trans('label.Attribute')]);
        }
        echo json_encode($data, true);
    }
}
