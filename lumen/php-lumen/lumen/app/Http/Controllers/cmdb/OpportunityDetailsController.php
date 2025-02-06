<?php
namespace App\Http\Controllers\cmdb;

use App;
use App\Http\Controllers\Controller;
use App\Models\EnoppListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpportunityDetailsController extends Controller
{
    public function __construct()
    {
        $debug = env('APP_ENV', true);
        DB::connection()->enableQueryLog();
        $this->custate = config('enconfig.current_env');
        apilog($this->custate);
        $this->api_crm_url = config('enconfig.api_crm_url');
    }
    /* For Details page showing on view action */
    public function getDetails(Request $request)
    {
        try
        {
            $currenttime           = strtotime(date('Y-m-d H:i:s'));
            $prevtime              = strtotime(date("Y-m-d H:i:s") . " -60 minutes");
            $OppDetailsdata        = array();
            $req['opportunity_id'] = $request['opportunity_id'];
            $result                = $this->call_rest_api($this->api_crm_url."get_opp_details_aim.php", $req);
             //$result                = $this->call_rest_api("https://115.124.96.115:4108/uat/get_opp_details_aim.php", $req);
			// $result                = $this->call_rest_api("https://115.124.96.115:4108/production/get_opp_details_aim.php", $req);
            
			
			$result_array          = json_decode($result, true);
            $api_data              = array();
            $OppDetailsdata        = $result_array['result'];
            $http_status           = 200;
            $error                 = "";

            if ($error) {
                $log = curl_error($curl);
            } else {
                if ($http_status >= 200 && $http_status < 300) {

                    $api_data        = $OppDetailsdata['item_details']['phases'];
                    $api_data_for_db = array();
                    $dup_find        = array();
                    foreach ($api_data as $phase_key => $phase_val) {
                        foreach ($phase_val['group'] as $group_key => $group_val) {
                            foreach ($group_val['items'] as $items_key => $items_val) {

                                $sku = $items_val['sku_code'];

                                $query = DB::table('en_assets')
                                    ->select('asset_id')
                                    ->where('status', '!=', 'd')
                                    ->where('asset_status', 'in_store')
                                    ->where('asset_sku', $sku);

                                $countSQL      = $query->get()->count();
                                $items_val['in_stock'] = $countSQL;

                                $item_quantity = $items_val['item_quantity'];
                                if (in_array($sku, $dup_find)) {
                                    
                                    $skukey                                    = array_search($sku, $dup_find, true);
                                    $api_data_for_db[$skukey]['item_quantity'] = $api_data_for_db[$skukey]['item_quantity'] + $item_quantity;

                                } else {
                                    $api_data_for_db[] = $items_val;

                                    $dup_find[] = $sku;
                                }

                            }
                        }
                    }
                    $select_opportunity_id = $request['opportunity_id'];
                    $query1                = DB::table('en_opportunity_listing')->select('id')->where('opportunity_id', $select_opportunity_id);
                    $result1               = $query1->first();

                    $update                     = EnoppListing::find($result1->id);
                    $update->basic_details      = json_encode($OppDetailsdata['basic_details']);
                    $update->item_json          = json_encode($api_data_for_db);
                    $update->details_updated_at = date("Y-m-d H:i:s");
                    $update->update();

                    $data['data']               = $api_data_for_db;
                    $data['message']['success'] = 'Opportunity Details Found';
                    $data['status']             = 'success';
                    return response()->json($data);
                } elseif ($http_status === 401) {
                    $log = 'status(' . $http_status . ') --> ' . $url;
                }
            }

        } catch (\Exception $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            return response()->json($data);
        }
    }
    /* Cron Setup to update details on each  Opportunity */
    public function getDetailsForDB()
    {
        try
        {
            set_time_limit(10000000);
            $OppDetailsdata = $result1 = array();

            $query1 = DB::table('en_opportunity_listing')->select('id', 'opportunity_id')
                ->whereNull('details_updated_at')->orderby('id', 'asc');
            $result1 = $query1->limit(15)->get()->toArray();

            if (empty($result1)) {
                //->where('details_updated_atd', '<', date("Y-m-d H:i:s"))

                $prevtime = strtotime(date("Y-m-d H:i:s") . " -15 minutes");
                $from     = date('Y-m-d H:i:s', $prevtime);
                $to       = date("Y-m-d H:i:s");

                $query1 = DB::table('en_opportunity_listing')->select('id', 'opportunity_id')
                    ->whereBetween('details_updated_at', [$from, $to])
                    ->orderby('id', 'asc');
                $result1 = $query1->limit(15)->get()->toArray();
            }
            $api_data_for_db = array();
            $dup_find        = array();
            if (!empty($result1)) {
                foreach ($result1 as $key => $value) {
                    $req['opportunity_id'] = $value->opportunity_id;
                    $pk                    = $value->id;
                    $result                = $this->call_rest_api($this->api_crm_url."get_opp_details_aim.php", $req);
                    //$result                = $this->call_rest_api("https://115.124.96.115:4108/uat/get_opp_details_aim.php", $req);
                    $result_array   = json_decode($result, true);
                    $api_data       = array();
                    $OppDetailsdata = $result_array['result'];
                    $http_status    = 200;
                    $error          = "";

                    if ($error) {
                        $log = curl_error($curl);
                    } else {
                        if ($http_status >= 200 && $http_status < 300) {
                            $api_data = $OppDetailsdata['item_details']['phases'];

                            foreach ($api_data as $phase_key => $phase_val) {
                                foreach ($phase_val['group'] as $group_key => $group_val) {
                                    foreach ($group_val['items'] as $items_key => $items_val) {
                                        $sku           = $items_val['sku_code'];
                                        $item_quantity = $items_val['item_quantity'];

                                        $query = DB::table('en_assets')
                                            ->select('asset_id')
                                            ->where('status', '!=', 'd')
                                            ->where('asset_status', 'in_store')
                                            ->where('asset_sku', $sku);

                                        $countSQL      = $query->get()->count();
                                        $items_val['in_stock'] = $countSQL;

                                        if (in_array($sku, $dup_find)) {
                                            $skukey                                    = array_search($sku, $dup_find, true);
                                            $api_data_for_db[$skukey]['item_quantity'] = $api_data_for_db[$skukey]['item_quantity'] + $item_quantity;
                                        } else {
                                            $api_data_for_db[] = $items_val;
                                            $dup_find[]        = $sku;
                                        }

                                    }
                                }
                            }
                            $update                     = EnoppListing::find($pk);
                            $update->basic_details      = json_encode($OppDetailsdata['basic_details']);
                            $update->item_json          = json_encode($api_data_for_db);
                            $update->details_updated_at = date("Y-m-d H:i:s");
                            $update->update();

                        } elseif ($http_status === 401) {
                            $log = 'status(' . $http_status . ') --> ' . $url;
                        }

                    }
                }
                $data['data']               = 'success';
                $data['message']['success'] = 'Opportunity Details Added In DB';
                $data['status']             = 'success';
                return response()->json($data);
            } else {
                $data['data']            = 'NA';
                $data['message']['fail'] = 'Opportunity Details Not Found';
                $data['status']          = 'fail';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            return response()->json($data);
        }
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
