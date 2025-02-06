<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

class OpportunityListingController extends Controller
{

    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam           = $itam;
        $this->iam            = $iam;
        $this->emlib          = new Emlib;
        $this->request        = $request;
        $this->request_params = $this->request->all();
    }

    public function opportunities()
    {
        $topfilter           = ['gridsearch' => true, 'jsfunction' => 'opportunityList()', 'gridadvsearch' => false];
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', ["address"]);
        $data['pageTitle']   = 'Opportunity Listing';
        $data['includeView'] = view("Cmdb/opportunity", $data);
        return view('template', $data);
    }

    public function opportunitylist()
    {
        $paging        = [];
        $fromtime      = $totime      = '';
        $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype    = _isset($this->request_params, 'exporttype');
        $page          = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');

        $is_error     = false;
        $msg          = '';
        $content      = "";
        $limit_offset = limitoffset($limit, $page);
        $page         = $limit_offset['page'];
        $limit        = $limit_offset['limit'];
        $offset       = $limit_offset['offset'];

        $form_params['limit']         = $paging['limit']         = $limit;
        $form_params['page']          = $paging['page']          = $page;
        $form_params['offset']        = $paging['offset']        = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        $options = ['form_params' => $form_params];

        $opportunity_resp = $this->itam->getOpportunities($options);
        $Opportunities    = _isset(_isset($opportunity_resp, 'content'), 'records');
        if ($Opportunities == '') {
            $Opportunities = [];
        }

        if ($opportunity_resp['is_error']) {
            $is_error = $opportunity_resp['is_error'];
            $msg      = $opportunity_resp['msg'];
        } else {
            $is_error                 = false;
            $paging['total_rows']     = _isset(_isset($opportunity_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'opportunityList()';
            $view                     = 'Cmdb/opportunitylist';
            $content                  = $this->emlib->emgrid($Opportunities, $view, $columns = [], $paging);
        }
        $response["html"]     = $content;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        echo json_encode($response);

    }
    public function getOpportunityDetails($opportunity_id)
    {
        $form_params['opportunity_id'] = $opportunity_id;
        $options                       = ['form_params' => $form_params];
        $details                       = $this->itam->getopportunityDetails($options);
        $data['opp_details']           = $details['content'];
       // $topfilter                     = array('gridsearch' => true, 'jsfunction' => 'prList() , prDetailsLoad()');
        $topfilter                     = ['gridsearch' => true, 'jsfunction' => ''];
        $data['emgridtop']             = $this->emlib->emgridtop($topfilter);
        $data['pageTitle']             = 'Opportunity Details';
        $data['includeView']           = view("Cmdb/opportunitydetails", $data);
        return view('template', $data);
    }

    public function getRuntimeopportunities(Request $request)
    {
        $form_params['opportunity_id'] = '1';
        $options                       = ['form_params' => $form_params];
        $details                       = $this->itam->getRuntimeopportunities($options);
       
    }

}
