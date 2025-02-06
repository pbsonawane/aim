<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIBaseController as APIBaseController;
use App\Libraries\Emlib;
use App\Libraries\Maillib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;


class PostAPIController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
       
    }
    public function index(Request $request)
    {
         $paging = array();
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $issuperadmin = _isset($this->request_params, 'issuperadmin');
            $user_id = _isset($this->request_params, 'user_id');
            $timerange = _isset($this->request_params, 'timerange');
            $customtime = _isset($this->request_params, 'customtime');
            $msg = "";
            $content = "";
            $is_error = false;

            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];

            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;

            $save_param = $form_params;
            if (!empty($customtime)) {
                $cust_date = explode(' - ', $customtime);
                $form_params['customtime'] = ['start_date' => date('Y-m-d', strtotime($cust_date[0])), 'end_date' => date('Y-m-d', strtotime($cust_date[1]))];
            }
            if (!empty($timerange)) {
                if ($timerange == 'today') {
                    $form_params['timerange'] = date('Y-m-d');
                } else {
                    $clean_string = str_replace(" ", "_", $timerange);
                    if (strpos($clean_string, "_days")) {
                        $dt = str_replace('_', ' ', str_replace('last_', '-', $clean_string));
                        $final_dt = date('Y-m-d', strtotime($dt, strtotime(date('Y-m-d'))));
                        $form_params['timerange'] = $final_dt;
                    }
                }
            }
           
            $options = ['form_params' => $form_params];
          $pos_resp = $this->itam->track_api_list($options);

        return $this->sendResponse($pos_resp, 'Posts retrieved successfully.');
    }

   


 
 

  
}