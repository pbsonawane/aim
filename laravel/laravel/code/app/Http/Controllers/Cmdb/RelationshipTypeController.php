<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * RelationshipType Controller class is implemented to do Relationship Type operations.
 * @author Darshan Chaure
 * @package RelationshipType
 */
class RelationshipTypeController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Darshan Chaure
     * @access public
     * @package RelationshipType
     * @param \App\Services\ITAM\ItamService $itam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam           = $itam;
        $this->iam            = $iam;
        $this->emlib          = new Emlib;
        $this->request        = $request;
        $this->request_params = $this->request->all();
    }
    /**
     * RelationshipType Controller function is implemented to initiate a page to get list of Relationship Type.
     * @author Darshan Chaure
     * @access public
     * @package RelationshipType
     * @return string
     */

    public function relationshiptypes()
    {
        $topfilter              = ['gridsearch' => true, 'jsfunction' => 'relationshiptypeList()', 'gridadvsearch' => false];
        $data['emgridtop']      = $this->emlib->emgridtop($topfilter, '', ["rel_type"]);
        $data['pageTitle']      = trans('title.relationshiptype');
        $data['includeView']    = view("Cmdb/relationshiptypes", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Relationship Type.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function relationshiptypeList()
    {
      try
      {
        $paging         = [];
        $limit          = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $page           = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword  = _isset($this->request_params, 'searchkeyword');
//      $exporttype     = _isset($this->request_params, 'exporttype');
        $is_error       = false;
        $msg            = '';
        $content        = "";
        $limit_offset   = limitoffset($limit, $page);
        $page           = $limit_offset['page'];
        $limit          = $limit_offset['limit'];
        $offset         = $limit_offset['offset'];

        $form_params['limit']   = $paging['limit']  = $limit;
        $form_params['page']    = $paging['page']   = $page;
        $form_params['offset']  = $paging['offset'] = $offset;
        $form_params['searchkeyword']               = $searchkeyword;

        $options                = ['form_params' => $form_params];
        $relationshiptype__resp = $this->itam->getrelationshiptype($options);

        if ($relationshiptype__resp['is_error'])
        {
            $is_error           = $relationshiptype__resp['is_error'];
            $msg                = $relationshiptype__resp['msg'];
        }
        else
        {
            $is_error                 = false;
            $relationshiptypes        = _isset(_isset($relationshiptype__resp, 'content'), 'records');
            $paging['total_rows']     = _isset(_isset($relationshiptype__resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'relationshiptypeList()';
            
            $view                     = 'Cmdb/relationshiptypelist';
            $content                  = $this->emlib->emgrid($relationshiptypes, $view, $columns = [], $paging);
        }

        $response["html"]       = $content;
        $response["is_error"]   = $is_error;
        $response["msg"]        = $msg;
        echo json_encode($response);
      }
      catch (\Exception $e)
      {
          $response["html"]      = '';
          $response["is_error"]  = true;
          $response["msg"]       = $e->getmessage();

          save_errlog("relationshiptypeList","This controller function is implemented to relationship type list.",$this->request_params,$e->getmessage());  
          echo json_encode($response, true);
      }
      catch (\Error $e)
      {
          $response["html"]      = '';
          $response["is_error"]  = true;
          $response["msg"]       = $e->getmessage();

          save_errlog("relationshiptypeList","This controller function is implemented to relationship type list.",$this->request_params,$e->getmessage());  
          echo json_encode($response, true);
      }
    }
   /**
    * This controller function is used to load relationship type add form.
    * @author Darshan Chaure
    * @access public
    * @package relationshiptype
    * @return string
    */
   public function relationshiptypeadd(Request $request)
   {
       $data['rel_type_id']          = '';
       $relationshiptypedata         = [];
       $data['relationshiptypedata'] = $relationshiptypedata;
       $html                         = view("Cmdb/relationshiptypeadd", $data);
       echo $html;
   }
   /**
    * This controller function is used to save relationship type data in database.
    * @author Darshan Chaure
    * @access public
    * @package relationshiptype
    * @param string $rel_type relationship type
    * @param string $inverse_rel_type inverse relationship type
    * @param string $description relationship type Description
    * @return json
    */
   public function relationshiptypeaddsubmit(Request $request)
   {
      try{
       if(!empty(config('app.env')) && config('app.env') != 'production') $request['is_default'] = 'y';
       $data = $this->itam->addrelationshiptype(['form_params' => $request->all()]);
       echo json_encode($data, true);
      }
      catch (\Exception $e)
      {
          $data["html"]      = '';
          $data["is_error"]  = true;
          $data["msg"]       = $e->getmessage();

          save_errlog("relationshiptypeaddsubmit","This controller function is implemented to save relationship type.",$this->request_params,$e->getmessage());  
          echo json_encode($data, true);
      }
      catch (\Error $e)
      {
          $data["html"]      = '';
          $data["is_error"]  = true;
          $data["msg"]       = $e->getmessage();

          save_errlog("relationshiptypeaddsubmit","This controller function is implemented to save relationship type.",$this->request_params,$e->getmessage());  
          echo json_encode($data, true);
      }
   }
    /**
     * This controller function is used to load relationshiptype edit form with existing data for selected relationshiptype
    * @author Darshan Chaure
    * @access public
    * @package relationshiptype
    * @param \Illuminate\Http\Request $request
    * @param $relationship_type_id relationshiptype Unique Id
    * @return string
    */
   public function relationshiptypeedit(Request $request)
   {
       $relationship_type_id = $request->id;
       $input_req            = ['rel_type_id' => $relationship_type_id];
       $data                 = $this->itam->editrelationshiptype(['form_params' => $input_req]);
       $data['rel_type_id']  = $relationship_type_id;
       $data['relationshiptypedata'] = $data['content'];
       $html                 = view("Cmdb/relationshiptypeadd", $data);
       echo $html;
   }
   /**
    * This controller function is used to update relationshiptype data in database.
    * @author Darshan Chaure
    * @access public
    * @package relationshiptype
    * @param UUID $relationship_type_id relationshiptype  Unique Id
    * @param string $rel_type relationshiptype
    * @param string $inverse_rel_type inverse relationshiptype
    * @param string $description relationshiptype Description
    * @return json
    */
   public function relationshiptypeeditsubmit(Request $request)
   {
      try{
       $data = $this->itam->updaterelationshiptype(['form_params' => $request->all()]);
       echo json_encode($data, true);
      }
      catch (\Exception $e)
      {
          $data["html"]      = '';
          $data["is_error"]  = true;
          $data["msg"]       = $e->getmessage();

          save_errlog("relationshiptypeeditsubmit","This controller function is implemented to update relationship type.",$this->request_params,$e->getmessage());  
          echo json_encode($data, true);
      }
      catch (\Error $e)
      {
          $data["html"]      = '';
          $data["is_error"]  = true;
          $data["msg"]       = $e->getmessage();

          save_errlog("relationshiptypeeditsubmit","This controller function is implemented to update relationship type.",$this->request_params,$e->getmessage());  
          echo json_encode($data, true);
      }
   }
   /**
    * This controller function is used to delete relationshiptype data from database.
    * @author Darshan Chaure
    * @access public
    * @package relationshiptype
    * @param UUID $relationship_type_id relationshiptype Unique Id
    * @return json
    */
   public function relationshiptypedelete(Request $request)
   {
      try{
       $data = $this->itam->deleterelationshiptype(['form_params' => $request->all()]);
       echo json_encode($data, true);
      }
      catch (\Exception $e)
      {
          $data["html"]      = '';
          $data["is_error"]  = true;
          $data["msg"]       = $e->getmessage();

          save_errlog("relationshiptypedelete","This controller function is implemented to delete relationship type.",$this->request_params,$e->getmessage());  
          echo json_encode($data, true);
      }
      catch (\Error $e)
      {
          $data["html"]      = '';
          $data["is_error"]  = true;
          $data["msg"]       = $e->getmessage();

          save_errlog("relationshiptypedelete","This controller function is implemented to delete relationship type.",$this->request_params,$e->getmessage());  
          echo json_encode($data, true);
      }
   }
}
