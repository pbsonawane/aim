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
use App\Models\EnAssetDetails;
use App\Models\EnAssets;
use Validator;
use App;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class CiskuController extends Controller
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

        $this->api_crm_url = config('enconfig.api_crm_url');
       // $this->custate = "dev";
    }


    public function storesku()
    {
        set_time_limit(10000000);
        $currenttime = strtotime(date('Y-m-d H:i:s'));
        $prevtime = strtotime(date("Y-m-d H:i:s")." -60 minutes");
       /* $currenttime = strtotime(date('Y-m-d H:i:s'));
        $prevtime = strtotime(date("Y-m-d H:i:s")." -7 month -10 days");*/
        $endpoint = $this->api_crm_url.'sku_api_rest.php';

        $method = 'POST';
        $url= $this->api_crm_url.'sku_api_rest.php';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        // if ($data)
        curl_setopt($curl, CURLOPT_POSTFIELDS, []);

        // $userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
           //curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
           curl_setopt($curl, CURLOPT_URL, $url);
           curl_setopt($curl, CURLOPT_HEADER, false);
           curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'authorization: Basic Y3JtaWFwaWNsaWVudDo2QUc/eFIkczQ7UDkkPz8hSw=='
           ));
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
           curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
           // EXECUTE:
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
           #curl_setopt($curl, CURLOPT_TIMEOUT, 5); //timeout in seconds
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 50000); //timeout in Milliseconds

           $result = curl_exec($curl);
          
          $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          

        $err =  curl_error($curl);

           curl_close($curl);
       /* END:: curl call using core platform */ 
              
          $t = json_decode($result, true);
           /*echo "<pre>";
              print_r($t);
              exit;*/
           
           if ($err) {
              $log = $err;
              
           } else {
            
                if ($http_status >= 200 && $http_status < 300) 
                {

                    $insert_value =[];
                    $catgories =[];
                    $all_skus =[];
                    $i = 1;
                    foreach ($t['result']['sku_details'] as $value) 
                    {
                          $all_skus[] = $value;
                          $skutime = strtotime($value['created_on']);
                        
                        if(true)
                        // if($skutime >= $prevtime && $skutime <= $currenttime)
                        {   
                        
                        
                            $select = EnCiTemplDefault::select('ci_name')
                            ->from('en_ci_templ_default')
                            ->where('ci_name','=',$value['primary_category_name'])
                            ->get()->toArray();
                            if(empty($select))
                            {
                              $json = '[ { "unit": "", "attribute": "Manufacturer", "input_type": "text", "validation": "", "veriable_name": "make" }, { "unit": "", "attribute": "Serial Number", "input_type": "text", "validation": "", "veriable_name": "serial_number" } ]';
                              $inputdata['ci_type_id'] = DB::raw('UUID_TO_BIN("ea6bf3a8-0a05-11e9-92e6-0242ac110002")');
                              // $inputdata['skucode'] = $value['skucode'];
                              $inputdata['ci_name'] = $value['primary_category_name'];
                              $inputdata['prefix'] = $value['primary_category_abbreviation'];
                              // $inputdata['prefix'] = strtoupper(substr($key, 1, 4));
                              $inputdata['variable_name'] = strtolower(str_replace($value['primary_category_name'],' ','_'));
                              $inputdata['default_attributes'] = $json;
                              $inputdata['status'] = 'y';
                              $inputdata['created_at'] = $currenttime;
                              $inputdata['updated_at'] = $currenttime;
                              EnCiTemplDefault::create($inputdata);
                            }

                          $ci_cat = EnCiTemplDefault::where('prefix','=',$value['primary_category_abbreviation'])->where('ci_name','=',$value['primary_category_name'])->get()->toArray();
                          $select = EnAssets::where('asset_sku','=',$value['skucode'])->get()->toArray();

                            if(empty($select) && !empty($ci_cat))
                            {
                              $unicode = getAssetId();
                              $asset_arr['asset_sku'] =  $value['skucode'];
                              $asset_arr['asset_tag'] = $value['primary_category_abbreviation'].'#'.$unicode;
                              $asset_arr['display_name'] = $value['core_product_name'];
                              // $asset_arr['ci_templ_id'] = '0xea6bf3a80a0511e992e60242ac110002';
                               $asset_arr['ci_templ_id'] =  DB::raw('UUID_TO_BIN("'.$ci_cat[0]['ci_templ_id'].'")');
                               $asset_arr['bv_id'] = DB::raw('UUID_TO_BIN("d7df036a-0a10-11ec-ad77-4e89be533080")');
                               $asset_arr['location_id'] = DB::raw('UUID_TO_BIN("e0ce8c54-0c9d-11ec-905c-4e89be533080")');
                              //$asset_arr['asset_id'] = DB::raw('UUID_TO_BIN(ea6bf3a8-0a05-11e9-92e6-0242ac110002)');
                              $asset_arr['ci_templ_type'] = 'default';
                              $asset_arr['asset_unit'] = $value['measurement_unit_name'];
                              $asset_arr['asset_status'] = 'in_procurement';
                              $asset_arr['status'] = 'y';
                              
                              $asset_arr['created_at'] = $currenttime;
                              $asset_arr['updated_at'] = $currenttime;
                              $assetdata = EnAssets::create($asset_arr); // add table data

                              /*$asset_arr_details[] = '';

                              $inputdata1['cutype'] = 'default';
                              $inputdata1['ci_templ_id'] = '';
                              $inputdata1['ci_type_id'] = 'ea6bf3a8-0a05-11e9-92e6-0242ac110002';
                              $attribute_val = app('App\Http\Controllers\AssetController')->setassetdata($inputdata1);
                              print_r($attribute_val);*/
                             
                              $asset_arr_details['asset_details']       = '{"make": "","serial_number": ""}';
                              if (!empty($assetdata['asset_id'])) {
                                  $asset_arr_details['asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');
                                  $assetdetaildata = EnAssetDetails::create($asset_arr_details);
                                }

                            $insert_value='("'.$value['skucode'].'",
                            "'.$value['skucodeid'].'",
                            "'.$value['core_product_id'].'",
                            "'.addslashes($value['core_product_name']).'",
                            "'.addslashes($value['coreproduct_description']).'",
                            "'.$value['primary_category_id'].'",
                            "'.addslashes($value['primary_category_name']).'",
                            "'.addslashes($value['primary_category_abbreviation']).'",
                            "'.$value['secondary_category_id'].'",
                            "'.addslashes($value['secondary_category_name']).'",
                            "'.addslashes($value['secondary_category_abbreviation']).'",
                            "'.$value['tertiary_category_id'].'",
                            "'.addslashes($value['tertiary_category_name']).'",
                            "'.addslashes($value['tertiary_category_abbreviation']).'",
                            "'.$value['fourth_category_id'].'",
                            "'.addslashes($value['fourth_category_name']).'",
                            "'.addslashes($value['fourth_category_abbreviation']).'",
                            "'.$value['fifth_category_id'].'",
                            "'.addslashes($value['fifth_category_name']).'",
                            "'.addslashes($value['fifth_category_abbreviation']).'",
                            "'.$value['measurement_unit_id'].'",
                            "'.addslashes($value['measurement_unit_name']).'",
                            "'.$value['measurement_unit_code'].'",
                            "'.$value['created_on'].'",
                            "'.$value['updated_on'].'",
                            "'.date('Y-m-d H:i:s').'",
                            "'.date('Y-m-d H:i:s').'",1);';   
                               $i++;
                                $sql = 'INSERT INTO `en_sku_mst`(`sku_code`, `sku_code_id`, `core_product_id`, `core_product_name`, `coreproduct_description`, `primary_category_id`, `primary_category_name`, `primary_category_abbreviation`, `secondary_category_id`, `secondary_category_name`, `secondary_category_abbreviation`, `tertiary_category_id`, `tertiary_category_name`, `tertiary_category_abbreviation`, `fourth_category_id`, `fourth_category_name`, `fourth_category_abbreviation`, `fifth_category_id`, `fifth_category_name`, `fifth_category_abbreviation`, `measurement_unit_id`, `measurement_unit_name`, `measurement_unit_code`, `crm_created_dt`, `crm_updated_dt`,`created_at`,`updated_at`,`is_added_by_cron`) VALUES '.$insert_value;
                               DB::insert($sql);
                            }
                           

                           

                        }
                       
                    }
                   
                    

                    if(!empty($insert_value))
                    {
                        
                      // echo  $sql = 'INSERT INTO `en_sku_mst`(`sku_code`, `sku_code_id`, `core_product_id`, `core_product_name`, `coreproduct_description`, `primary_category_id`, `primary_category_name`, `primary_category_abbreviation`, `secondary_category_id`, `secondary_category_name`, `secondary_category_abbreviation`, `tertiary_category_id`, `tertiary_category_name`, `tertiary_category_abbreviation`, `fourth_category_id`, `fourth_category_name`, `fourth_category_abbreviation`, `fifth_category_id`, `fifth_category_name`, `fifth_category_abbreviation`, `measurement_unit_id`, `measurement_unit_name`, `measurement_unit_code`, `crm_created_dt`, `crm_updated_dt`,`created_at`,`updated_at`,`is_added_by_cron`) VALUES '.implode(',',$insert_value);
                        // DB::insert($sql);

                        $log = "New records created successfully";
                    }else{
                        $log = "Records not found";
                    }
                  
                }elseif ($http_status === 401) {
                    $log = 'status(' . $http_status . ') --> ' . $url;
                }
            }
   
            return $log;
    }


}