<?php
namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Contact Controller class is implemented to do Contact operations.
 * @author Bhushan Amruktar
 * @package Contact
 */
class ContactController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Bhushan Amruktar
     * @access public
     * @package Contact
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
     * Contact Controller function is implemented to initiate a page to get list of Contacts
     * @author Bhushan Amruktar
     * @access public
     * @package Contact
     * @return string
     */

    public function contacts()
    {
        $topfilter           = ['gridsearch' => true, 'jsfunction' => 'contactList()', 'gridadvsearch' => false];
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', ["fname"]);
        $data['pageTitle']   = trans('title.contacts');
        $data['includeView'] = view("Cmdb/contacts", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Contacts.
     * @author Bhushan Amruktar
     * @access public
     * @package Contact
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function contactlist()
    {
        $paging        = [];
        $fromtime      = $totime      = '';
        $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype    = _isset($this->request_params, 'exporttype');
        $page          = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');
        $is_error      = false;
        $msg           = '';
        $content       = "";
        $limit_offset  = limitoffset($limit, $page);
        $page          = $limit_offset['page'];
        $limit         = $limit_offset['limit'];
        $offset        = $limit_offset['offset'];

        $form_params['limit']         = $paging['limit']         = $limit;
        $form_params['page']          = $paging['page']          = $page;
        $form_params['offset']        = $paging['offset']        = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        $options      = ['form_params' => $form_params];
        $contact_resp = $this->itam->getcontacts($options);
        
        if ($contact_resp['is_error']) {
            $is_error = $contact_resp['is_error'];
            $msg      = $contact_resp['msg'];
        } else {
            $is_error                 = false;
            $contacts                 = _isset(_isset($contact_resp, 'content'), 'records');
            $paging['total_rows']     = _isset(_isset($contact_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'contactList()';

            $view    = 'Cmdb/contactlist';
            $content = $this->emlib->emgrid($contacts, $view, $columns = [], $paging);
        }
        $response["html"]     = $content;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load contact add form.
     * @author Bhushan Amruktar
     * @access public
     * @package contact
     * @return string
     */
    public function contactadd(Request $request)
    {
        $data['contact_id']  = '';
        $contactdata         = [];
        $data['contactdata'] = $contactdata;
        $html                = view("Cmdb/contactadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save contact data in database.
     * @author Bhushan Amruktar
     * @access public
     * @package contact
     * @param string $contact_id
     * @return json
     */
    public function contactaddsubmit(Request $request)
    {
        $data = $this->itam->addcontact(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load contact edit form with existing data for selected contact
     * @author Bhushan Amruktar
     * @access public
     * @package contact
     * @param \Illuminate\Http\Request $request
     * @param $contact_id contact Unique Id
     * @return string
     */
    public function contactedit(Request $request)
    {
        $contact_id = $request->id;
        $input_req  = ['contact_id' => $contact_id];
        $data       = $this->itam->editcontact(['form_params' => $input_req]);

        $data['contact_id']  = $contact_id;
        $data['contactdata'] = $data['content'];

        $html = view("Cmdb/contactadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update contact data in database.
     * @author Bhushan Amruktar
     * @access public
     * @package contact
     * @param UUID $contact_id contact  Unique Id
     * @return json
     */
    public function contacteditsubmit(Request $request)
    {
        $data = $this->itam->updatecontact(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete contact data from database.
     * @author Bhushan Amruktar
     * @access public
     * @package contact
     * @param UUID $contact_id Unique Id
     * @return json
     */
    public function contactdelete(Request $request)
    {
        $data = $this->itam->deletecontact(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
