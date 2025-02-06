<?php
namespace App\Http\Controllers\cmdb;

use App;
use App\Http\Controllers\Controller;
use App\Models\EnoppListing;
use Illuminate\Support\Facades\DB;

// API CRON URL >> http://172.16.25.27:30181/opportunitylisting

class OpportunityListingController extends Controller
{
    public function __construct()
    {
        $debug = env('APP_ENV', true);
        DB::connection()->enableQueryLog();
        $this->custate = config('enconfig.current_env');
        apilog($this->custate);
        $this->api_crm_url = config('enconfig.api_crm_url');
    }
    public function listing()
    {
        set_time_limit(10000000);
        $currenttime = strtotime(date('Y-m-d H:i:s'));
        $prevtime    = strtotime(date("Y-m-d H:i:s") . " -60 minutes");
        $data        = array();
        $result      = $this->call_rest_api($this->api_crm_url."opp_listing_aim.php", $data);
        //$result      = $this->call_rest_api("http://115.124.96.115:4108/uat/opp_listing_aim.php", $data);
        $t           = json_decode($result, true);
        $data        = $t['result']['opp_listing'];
        $http_status = 200;
        $error       = "";

        if ($error) {
            $log = curl_error($curl);
        } else {
            if ($http_status >= 200 && $http_status < 300) {
                $insert = array();
                //dd($data);
                foreach ($data as $value) {

                    $opportunity_id = $value['opportunity_id'];
                    $get_opp_data   = EnoppListing::where('opportunity_id', $opportunity_id)->get()->toArray();

                    if (empty($get_opp_data)) {
                        $insert['opportunity_id']     = $value['opportunity_id'];
                        $insert['opportunity_code']   = $value['opportunity_code'];
                        $insert['lead_id']            = $value['lead_id'];
                        $insert['status_id']          = $value['status_id'];
                        $insert['opportunity_status'] = $value['opportunity_status'];
                        $insert['opportunity_stage']  = $value['opportunity_stage'];
                        $insert['created_date']       = $value['created_date'];
                        $insert['created_by_name']    = $value['created_by_name'];
                        $insert['created_by']         = $value['created_by'];
                        $insert['updated_at']         = date("Y-m-d H:i:s");
                        EnoppListing::create($insert);
                    } else {

                        $r                          = $get_opp_data[0]['id'];
                        $update                     = EnoppListing::find($r);
                        $update->opportunity_id     = $value['opportunity_id'];
                        $update->opportunity_code   = $value['opportunity_code'];
                        $update->lead_id            = $value['lead_id'];
                        $update->status_id          = $value['status_id'];
                        $update->opportunity_status = $value['opportunity_status'];
                        $update->opportunity_stage = $value['opportunity_stage'];
                        $update->created_date       = $value['created_date'];
                        $update->created_by_name    = $value['created_by_name'];
                        $update->created_by         = $value['created_by'];
                        $update->updated_at         = date("Y-m-d H:i:s");
                        $update->update();
                    }
                }
                return "New records created successfully";
            } elseif ($http_status === 401) {
                $log = 'status(' . $http_status . ') --> ' . $url;
            }
        }
        return $log;
    }

    public function call_rest_api($rest_url, $post_array = array())
    {

        defined('CRM_API_AUTH') or define('CRM_API_AUTH', 'authorization: Basic Y3JtaWFwaWNsaWVudDo2QUc/eFIkczQ7UDkkPz8hSw=='); // crm api auth header
        $auth_header = CRM_API_AUTH;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $rest_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => json_encode($post_array),
            CURLOPT_FAILONERROR    => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => array(
                $auth_header,
                "cache-control: no-cache",
                "content-type: Content-Type:application/json",
            ),
        ));
        $response = curl_exec($curl);
        $error    = curl_error($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }
}
