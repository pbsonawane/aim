<?php

namespace App\Http\Controllers\Emailtemplate;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Email Template Controller class is implemented to do Email template operations.
 * @author Snehal C
 * @package Email Tempalte
 */
class EmailTemplateController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Snehal C
     * @access public
     * @package Email Tempalte
     * @param \App\Services\ITAM\ItamService $itam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }


     /**
     * Emailtemplate Controller function is implemented to initiate a page to get list of templates .
     * @author Snehal C
     * @access public
     * @package emailtemplate
     * @return string
     */

    public function emailtemplates()
    {
        //send_email_function('third','snehal.chaturvedi@esds.co.in', array('{name}' => "snehal", '{ASST_NAME}' => 'ADMIN')); die;
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'emailtemplatelist()', 'gridadvsearch' => true);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("template_category"));
        $data['pageTitle'] = trans('title.emailtemplate');
        $data['includeView'] = view("Emailtemplate/emailtemplatelist", $data);
        return view('template', $data);
    }

     /**
     * Emailtemplate Controller function is implemented to  get list of templates .
     * @author Snehal C
     * @access public
     * @package emailtemplate
     * @return string
     */
    public function emailtemplatelist(){


            $paging = array();
            $fromtime = $totime = '';
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
            $exporttype = _isset($this->request_params, 'exporttype');
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');

            $is_error = false;
            $msg = '';
            $content = "";
            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];

            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;

            $form_params['advtemplate_category'] = _isset($this->request_params, 'advtemplate_category');
            $options = ['form_params' => $form_params];
            $emailtemplate_resp = $this->itam->getemailtemplates($options);
          /*  print_r($emailtemplate_resp);
            apilog('----template result----');
            apilog(json_encode($emailtemplate_resp));*/
            if ($emailtemplate_resp['is_error'])
            {
                $is_error = $emailtemplate_resp['is_error'];
                $msg = $emailtemplate_resp['msg'];

                if($msg == "Emailtemplate record/s not found."){
                    $is_error = false;
                    $emailtemplates = array();
                 $paging['total_rows'] = count($emailtemplates);
               // $paging['showpagination'] = true;
                $paging['jsfunction'] = 'emailtemplatelist()';
                $view = 'Emailtemplate/emailtemplatejslist';
                $content = $this->emlib->emgrid($emailtemplates, $view, $columns = array(), $paging);
                }
                
            }
            else
            {
                $is_error = false;
                $emailtemplates = _isset(_isset($emailtemplate_resp, 'content'), 'records');
               // echo "<pre>";print_r($emailtemplate_resp);die;

                $paging['total_rows'] = _isset(_isset($emailtemplate_resp, 'content'), 'totalrecords');
               // $paging['showpagination'] = true;
                $paging['jsfunction'] = 'emailtemplatelist()';
              
                $view = 'Emailtemplate/emailtemplatejslist';
                $template_id = isset($emailtemplates[0]['template_id']) ? $emailtemplates[0]['template_id'] : "";
                $content = $this->emlib->emgrid($emailtemplates, $view, $columns = array(), $paging);
            }
            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            $response['template_id'] = isset($template_id) ? $template_id : '';
            echo json_encode($response);
    }

    /**
     * This controller function is used to email template add form.
     * @author Snehal C
     * @access public
     * @package Email Template
     * @return string
     */
    public function emailtemplateadd()
    {
       
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $data['templatecategorydata'] = array();
        $data['template_category'] = '';
        $options = ['form_params' => $form_params];
        $templatecategory_resp = $this->itam->getetemplatecategory($options);
        if ($templatecategory_resp['is_error']){
            $templatecategory = array();
        }else{
            $templatecategory = _isset(_isset($templatecategory_resp, 'content'), 'records');
        }
        $data['templatecategorydata'] = array();
        $data['templatecategory'] = $templatecategory;


        $data['emailquotedata'] = array();
        $data['email_quote'] = '';
        $emailquotes_resp = $this->itam->getemailquotes($options);
        if ($emailquotes_resp['is_error']){
            $emailquotes = array();
        }else{
            $emailquotes = _isset(_isset($emailquotes_resp, 'content'), 'records');
        }
        $data['emailquotedata'] = array();
        $data['emailquotes'] = $emailquotes;
       // echo "<pre>";print_r($data['emailquotes']); die;

        $data['template_id'] = '';
        $templatedata = array();
        $data['templatedata'] = $templatedata;
        $html = view("Emailtemplate/emailtemplate", $data);
        echo $html; 
    }
    /**
     * This controller function is used to save email template data in database.
     * @author Snehal C
     * @access public
     * @package Email Template
     * @param string $request
     * @return json
     */
    public function emailtemplateaddsubmit(Request $request)
    {
        $data = $this->itam->addemailtemplate(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

     /**
     * This controller function is used to add email template data in database.
     * @author Snehal C
     * @access public
     * @package Email Template
     * @param string $request
     * @return json
     */

    public function emailquoteaddsubmit(Request $request){

        $data = $this->itam->addemailquote(array('form_params' => $request->all()));
        $data1= "";
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $options = ['form_params' => $form_params];
        $emailquotes = '';
        $emailquotes_resp = $this->itam->getemailquotes($options);
        if ($emailquotes_resp['is_error']){
            $emailquotes = array();
        }else{
            $emailquotes = _isset(_isset($emailquotes_resp, 'content'), 'records');
        }
        //echo "<pre>";print_r($emailquotes);die;
        $data1 = '<select  id="variables" size="20"  multiple="multiple" class="form-control medwidth">';
        if(is_array($emailquotes) && count($emailquotes) > 0){
            foreach ($emailquotes as $key => $quotes){  

              $data1 = $data1.'<option value="'.$quotes['quote_id'].'">'.ucfirst($quotes['quotes']).'</option>';
                         
            }
        } 


        echo $data1;

    }


     /**
     * This controller function is used to edit email template data in database.
     * @author Snehal C
     * @access public
     * @package Email Template
     * @param string $request
     * @return json
     */
    public function emailtemplateedit(Request $request)
    {
        $template_id = $request->id;
        $input_req = array('template_id' => $template_id);
        $data = $this->itam->editemailtemplate(array('form_params' => $input_req));

        $data['template_id'] = $template_id;
        $data['templatedata'] = $data['content'];
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $data['templatecategorydata'] = array();
        $data['template_category'] = '';
        $options = ['form_params' => $form_params];
        $templatecategory_resp = $this->itam->getetemplatecategory($options);
        if ($templatecategory_resp['is_error']){
            $templatecategory = array();
        }else{
            $templatecategory = _isset(_isset($templatecategory_resp, 'content'), 'records');
        }
        $data['templatecategorydata'] = array();
        $data['templatecategory'] = $templatecategory;


        $data['emailquotedata'] = array();
        $data['email_quote'] = '';
        $emailquotes_resp = $this->itam->getemailquotes($options);
        if ($emailquotes_resp['is_error']){
            $emailquotes = array();
        }else{
            $emailquotes = _isset(_isset($emailquotes_resp, 'content'), 'records');
        }
        $data['emailquotedata'] = array();
        $data['emailquotes'] = $emailquotes;
       // echo "<pre>";print_r($data['emailquotes']); die;

        $html = view("Emailtemplate/emailtemplate", $data);
        echo $html;
    }

     /**
     * This controller function is used to update email template data in database.
     * @author Snehal C
     * @access public
     * @package email template
     * @param string $request
     * @return json
     */
    public function emailtemplateeditsubmit(Request $request)
    {

        $data = $this->itam->updateemailtemplate(array('form_params' => $request->all()));
       echo json_encode($data, true);
    }


    /**
     * This controller function is used to delete template data from database.
     * @author Snehal C
     * @access public
     * @package Email Template
     * @param string $request
     * @return json
     */
    public function emailtemplatedelete(Request $request)
    {
        $data = $this->itam->deleteemailtemplate(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }


    /**
     * This controller function is used to change the status of email template
     * @author Snehal C
     * @access public
     * @package Email Template
     * @param string $request
     * @return json
     */

    public function emailtemplatechangestatus(Request $request){
        $data = $this->itam->updateemailtemplatestatus(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

}
