<?php
namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

class PrQuotationComparison extends Controller
{
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam           = $itam;
        $this->iam            = $iam;
        $this->emlib          = new Emlib;
        $this->request        = $request;
        $this->request_params = $this->request->all();
    }
    public function qc_view(Request $request)
    {
        try
        {
            //$form_params['pr_po_id'] = $inputdata['pr_po_id'];

            $inputdata               = $request->all();
            $pr_id                   = $request->pr_id;
            $form_params['pr_po_id'] = $pr_id;
            $options                 = ['form_params' => $form_params];
            $data['quotation_data']                    = $this->itam->quotation_comparison_details($options);
            $array          = json_decode($data['quotation_data']['content'], true);
            $approvalstatus = array_column($array, 'approval');
            $approvalstatus = array_unique($approvalstatus);
            $statusquotation = implode(" ",$approvalstatus);
            $data['quotation_status']                    = trim($statusquotation);            
            $attachmentoptions1   = ['form_params' => array('pr_po_id' => $pr_id, 'attachment_type' => 'qu')];
            $prpoattachment_resp1 = $this->itam->prpoattachment($attachmentoptions1);

            $data['prpoattachment1'] = isset($prpoattachment_resp1['content']) ? $prpoattachment_resp1['content'] : null;

           
        } catch (\Exception $e) {
            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";
            //save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";
            //save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            //echo '<pre>'; print_r(json_decode($data['content'])); echo '</pre>';die;
            $data['data']        = $data;
            $data['pageTitle']   = 'Quotation Comparison Detail';
            $data['includeView'] = view("Cmdb/quotation_comparison_view", $data);
            return view('template', $data);
        }
    }
}
