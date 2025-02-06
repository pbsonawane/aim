<?php
namespace App\Http\Controllers\asset;

use App\Http\Controllers\Controller;
use App\Models\EnAssetDetails;
use App\Models\EnAssetHistory;
use App\Models\EnAssets;
use App\Models\EnCiTemplCustom;
use App\Models\EnCiTemplDefault;
use App\Models\EnContract;
use App\Models\EnImportNotifications;
use App\Models\EnRelationshipType;
use App\Models\EnVendors;
use App\Services\RemoteApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;

class AssetController extends Controller
{
    public $multiassets;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        // $this->iam = $iam;
        $this->remote_api = new RemoteApi();
        DB::connection()->enableQueryLog();
        $this->multiassets = array();
        $this->ci = array('server', 'desktop', 'laptop');
        $this->ciitem = array('ethernet', 'ram', 'hdd');
        $this->status = array('in_store', 'in_use', 'return', 'in_repair', 'expired', 'disposed');
    }
    /*
     *This is controller funtion used to List Cost Centers.
     * @author      Amit Khairnar
     * @access       public
     * @param        URL : asset_id [Optional]
     * @param_type   Integer
     * @return       JSON
     * @tables       en_assets
     */

    public function assets(Request $request)
    {
        ini_set('max_execution_time', '1000');
        ini_set("memory_limit", "-1");
        try
        {
            $validator = Validator::make($request->all(), [
                'asset_id' => 'nullable|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {

                $inputdata = $request->all();
                $inputdata['asset_id'] = trim(_isset($inputdata, 'asset_id'));
                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                $inputdata['ci_templ_id'] = trim(_isset($inputdata, 'ci_templ_id'));
                $inputdata['asset_status'] = trim(_isset($inputdata, 'asset_status'));
                $inputdata['bv_id'] = trim(_isset($inputdata, 'bv_id'));
                $inputdata['location_id'] = trim(_isset($inputdata, 'location_id'));
                $inputdata['parent_asset_id'] = trim(_isset($inputdata, 'parent_asset_id'));
                $inputdata['po_id'] = trim(_isset($inputdata, 'po_id'));
                $inputdata['asset_sku'] = trim(_isset($inputdata, 'asset_sku'));

                $callfor = trim(_isset($inputdata, 'callfor'));
                $callfor_id = trim(_isset($inputdata, 'callfor_id'));
                $skip_ids = array();

                if ($callfor == 'assetrelationshipadd' && $callfor_id != '') {
                    $existing_id = DB::table('en_asset_relationship')
                        ->select(DB::raw('BIN_TO_UUID(parent_asset_id) AS pa_id'), DB::raw('BIN_TO_UUID(child_asset_id) AS ca_id'))
                        ->where('parent_asset_id', DB::raw('UUID_TO_BIN("' . $callfor_id . '")'))
                        ->orWhere('child_asset_id', DB::raw('UUID_TO_BIN("' . $callfor_id . '")'))
                        ->get();

                    $existing_id = json_decode($existing_id, true);

                    if (is_array($existing_id) && count($existing_id) > 0) {
                        for ($i = 0; $i < count($existing_id); $i++) {
                            if (!in_array(($existing_id[$i])['pa_id'], $skip_ids, true)) {
                                array_push($skip_ids, ($existing_id[$i])['pa_id']);
                            }
                            if (!in_array(($existing_id[$i])['ca_id'], $skip_ids, true)) {
                                array_push($skip_ids, ($existing_id[$i])['ca_id']);
                            }
                        }
                    }
                    $inputdata['skip_ids'] = $skip_ids;
                }                
                $totalrecords = EnAssets::getassets($inputdata, true);
                $result = EnAssets::getassets($inputdata, false);                            
                $queries = DB::getQueryLog();
                $data['last_query'] = end($queries);
                $data['data']['records'] = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;
                if ($totalrecords < 1) {
                    $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
                } else {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
                }
                $data['status'] = 'success';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assets", "This controller function is implemented to get  Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assets", "This controller function is implemented to get Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    public function getitembycategory(Request $request)
    {

        try
        {
            if (!empty($request->input('item_product_id'))) {

                $prod_id = array_map(function ($team_id) {
                    if (!empty($team_id)) {
                        return DB::raw('UUID_TO_BIN("' . $team_id . '")');
                    }
                }, explode(',', $request->input('item_product_id')));

                $items = DB::table('en_assets')
                    ->select(DB::raw('BIN_TO_UUID(asset_id) AS pa_id'), 'display_name', 'asset_unit')
                    ->whereIn('asset_id', $prod_id)
                    ->where('asset_status', 'in_procurement')
                // ->groupBy('display_name')
                    ->groupBy('asset_sku')
                    ->orderBy('display_name','ASC')
                    ->get();

                $data['data'] = $items;
                $data['message']['success'] = 'Record fetch';
                $data['status'] = 'success';
                return response()->json($data);
            } else {
                $validator = Validator::make($request->all(), [
                    'ci_templ_id' => 'allow_uuid|string|size:36',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    $data['data'] = null;
                    $data['message']['error'] = $error;
                    $data['status'] = 'error';
                    return response()->json($data);
                } else {

                    $inputdata = $request->all();
                    $ci_templ_id = trim(_isset($inputdata, 'ci_templ_id'));

                    if (!empty($ci_templ_id)) {
                        $items = DB::table('en_assets')
                            ->select(DB::raw('BIN_TO_UUID(asset_id) AS pa_id'), 'display_name', 'asset_sku', 'asset_unit')
                            ->where('ci_templ_id', DB::raw('UUID_TO_BIN("' . $ci_templ_id . '")'))
                            ->groupBy('asset_sku')
                            ->orderBy('display_name','ASC')
                            ->get();
                    }
                    $data['data'] = $items;
                    $data['message']['success'] = 'Record fetch';
                    $data['status'] = 'success';
                    return response()->json($data);
                }
            }

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        }
    }

    public function addConsumableAsset(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            apilog(json_encode($inputdata));

            $unicode = getAssetId();
            $inputdata['asset_tag'] = $inputdata['asset_prefix'] . '#' . $unicode;
            $validator = $this->_validate_Asset("add", $inputdata);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {
            $result = DB::table('en_assets AS a1') 
                ->join('en_asset_details AS a2', 'a1.asset_id', '=', 'a2.asset_id') 		
                ->select(DB::raw('BIN_TO_UUID(a1.asset_id) AS asset_id'),'a1.asset_tag','a1.asset_qty')                
                ->where('a1.asset_sku', '=', $inputdata['asset_sku'])
                ->where('a1.display_name', '=', $inputdata['title'])
                ->get()->toArray();
                $asset_id = $result[0]->asset_id;
                $asset_tag = $result[0]->asset_tag;
                $asset_qty = $result[0]->asset_qty;

                $NewAssetQty = intval($asset_qty) + intval($inputdata['totalAssetReceivedCount']);
            
                // Add Partially received                
                $asset_sku = $inputdata['asset_sku'];
                $actualqty = $inputdata['actualqty'];
                $pr_po_id = $inputdata['pr_po_id'];
                $resultPartially = DB::table('en_consumable_received') 
                                ->select('id',DB::raw('BIN_TO_UUID(asset_id) AS asset_id'),
                                DB::raw('BIN_TO_UUID(po_id) AS po_id'),
                                'asset_sku','total_count', 'partially_received')                
                                ->where('asset_sku', '=', $inputdata['asset_sku'])
                                ->where('po_id', '=', DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'))
                                ->get()->toArray();
                if(empty($resultPartially))
                {
                    // Add Data in en_consumable_received
                    $resultPartiallyInsert = DB::table('en_consumable_received')->insert([
                        'asset_id' => DB::raw('UUID_TO_BIN("'.$asset_id.'")'),
                        'po_id' =>  DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'),
                        'asset_sku' =>  $asset_sku,
                        'total_count' =>  $actualqty,
                        'partially_received' =>  $inputdata['totalAssetReceivedCount'],                      
                    ]);
                }else{
                    $id = $resultPartially[0]->id;
                    $partially_received = $resultPartially[0]->partially_received;
                    $NewPartiallyReceived = intval($partially_received) + intval($inputdata['totalAssetReceivedCount']);
                    $resultPartiallyUpdate = DB::table('en_consumable_received')
                                ->where('id', $id)
                                ->update(['partially_received' => $NewPartiallyReceived]);
                }
                // 
                $resultUpdate = DB::table('en_assets')
                                ->where('asset_id', DB::raw('UUID_TO_BIN("'.$asset_id.'")'))
                                ->update(['asset_qty' => $NewAssetQty]);

                $data['data'] = $resultUpdate;
                $data['status'] = 'success';
                $data['message']['success'] = 'success';
            }
            return response()->json($data);
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("addasset", "This controller function is implemented to Add Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("addasset", "This controller function is implemented to Add Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        }  
    }
    //================== Cost Center List END ======
    /*
     * This is controller funtion used to accept the values for new Asset.
     * @author      Amit Khairnar
     * @access       public
     * @param        ALL DATA
     * @param_type   POST array
     * @return       JSON
     */

    public function addasset(Request $request)
    {
        apilog('----------------------------in function addasset--------------------');
        try
        {
            $inputdata = $request->all();

            apilog(json_encode($inputdata));

            $unicode = getAssetId();
            $inputdata['asset_tag'] = $inputdata['asset_prefix'] . '#' . $unicode;
            $validator = $this->_validate_Asset("add", $inputdata);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {
                if (_isset($inputdata, 'pr_po_id') && $inputdata['pr_po_id'] != "") {
                    $attribute_val = array('make'=>'','serial_number'=>'');
                    $savedata['po_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['pr_po_id'] . '")');
                } else {
                    $attribute_val = $this->setassetdata($inputdata);
                }
                //$savedata['location_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['location_id'] . '")');
                $savedata['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['ci_type_id'] . '")');
                //$savedata['bv_id']       = DB::raw('UUID_TO_BIN("' . $inputdata['bv_id'] . '")');
                $savedata['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['ci_templ_id'] . '")');

                if ($inputdata['vendor_id'] != "") {
                    $savedata['vendor_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['vendor_id'] . '")');
                }

                $savedata['asset_tag'] = $inputdata['asset_tag'];
                $savedata['display_name'] = $inputdata['title'];
                $savedata['asset_sku'] = $inputdata['asset_sku'];
                $savedata['ci_templ_type'] = $inputdata['cutype'];
                // if($savedata['ci_type_id'] = '5a2e3d7f-9b13-4c25-a1b8-47896e2c9a4d')
                // {
                //     $savedata['asset_status'] = 'in_use';
                // }
                $savedata['asset_status'] = 'in_store';
                $savedata['status'] = 'Y';
                $savedata['asset_details'] = json_encode($attribute_val);
                $savedata['auto_discovered'] = 'n';
                $savedata['purchasecost'] = $inputdata['purchasecost'];
                $savedata['acquisitiondate'] = $inputdata['acquisitiondate'];
                $savedata['expirydate'] = $inputdata['expirydate'];
                $savedata['warrantyexpirydate'] = $inputdata['warrantyexpirydate'];
                $assetdata = EnAssets::create($savedata); // add table data

                if (!empty($assetdata['asset_id'])) {
                    $savedata['asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');
                    $assetdetaildata = EnAssetDetails::create($savedata);

                    //Add Asset History
                    $history_data = array();
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['asset_id'] = $savedata['asset_id'];
                    $history_data['action'] = "Add";
                    $history_data['message'] = showmessage('030'); //"Asset Added in to stock";
                    EnAssetHistory::create($history_data);
                    // Multiple Asset ADD

                    /* Will not Chcek PO*/
                    //   print_r($this->multiassets);exit;
                    if (is_array($this->multiassets) && count($this->multiassets) > 0) {
                        foreach ($this->multiassets as $masset) {
                            $mattribute_val = $this->setassetdata($masset);
                            //$masset['location_id'] = $savedata['location_id'];
                            //$masset['bv_id'] = $savedata['bv_id'];
                            $masset['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_type_id'] . '")');
                            $masset['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_templ_id'] . '")');
                            if ($inputdata['vendor_id'] != "") {
                                $masset['vendor_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['vendor_id'] . '")');
                            }

                            $masset['ci_templ_type'] = $masset['cutype'];
                            $masset['asset_status'] = 'in_use';
                            $masset['status'] = 'Y';
                            $masset['asset_details'] = json_encode($mattribute_val);
                            $masset['auto_discovered'] = 'n';
                            // $masset['purchasecost'] = "";
                            //$masset['acquisitiondate'] = "";
                            //$masset['expirydate'] = "";
                            // $masset['warrantyexpirydate'] = "";
                            $masset['parent_asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');

                            $massetdata = EnAssets::create($masset); // add table data
                            if (!empty($massetdata['asset_id'])) {
                                $masset['asset_id'] = DB::raw('UUID_TO_BIN("' . $massetdata->asset_id_text . '")');
                                $assetdetaildata = EnAssetDetails::create($masset);
                                //History add for child asset
                                $history_data = array();
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $masset['asset_id'];
                                $history_data['action'] = "Add";
                                $history_data['message'] = showmessage('030'); //asset added";
                                EnAssetHistory::create($history_data);
                                //History add for child asset
                                $history_data = array();
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $masset['asset_id'];
                                $history_data['action'] = "Attach";
                                $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($massetdata['asset_tag'], $savedata['asset_tag']), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                                EnAssetHistory::create($history_data);

                                //History add for child asset
                                $history_data = array();
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $masset['asset_id'];
                                $history_data['action'] = "change status";
                                $history_data['message'] = showmessage('033'); //"Asset status changed in stock to in use.." Component.";
                                EnAssetHistory::create($history_data);

                                //History add for parent asset
                                $history_data = array();
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $savedata['asset_id'];
                                $history_data['action'] = "Attach";
                                $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($massetdata['asset_tag'], $savedata['asset_tag']), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                                EnAssetHistory::create($history_data);
                            }
                        }
                    }
                }

                if (!empty($assetdata['asset_id'])) {

                    $asset_id = $assetdata->asset_id_text;
                    $data['data']['insert_id'] = $asset_id;
                    $data['message']['success'] = showmessage('104', array('{name}'), array("Asset"));
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $assetdata->asset_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array(trans('label.lbl_asset')), true)));
                } else {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('103', array('{name}'), array(trans('label.lbl_asset')), true);
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("addasset", "This controller function is implemented to Add Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("addasset", "This controller function is implemented to Add Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function setassetdata($inputdata)
    {
        $custom_atr = $default_atr = $attribute_val = array();
        if ($inputdata['cutype'] != '' && $inputdata['ci_templ_id'] != '' && $inputdata['ci_type_id'] != '') {
            if ($inputdata['cutype'] == 'default') {
                $result = EnCiTemplDefault::getcitemplatesD($inputdata['ci_templ_id'], $inputdata['ci_type_id']);

                if (count($result) > 0) {
                    foreach ($result as $res) {
                        $default_atr = json_decode($res->default_attributes, true);
                        if ($res->custom_attributes != '') {
                            $custom_atr = json_decode($res->custom_attributes, true);
                        }

                    }
                }
            } else {
                $result = EnCiTemplCustom::getcitemplatesC($inputdata['ci_templ_id'], $inputdata['ci_type_id']);
                if (count($result) > 0) {
                    foreach ($result as $res) {
                        $custom_atr = json_decode($res->custom_attributes, true);
                    }
                }
            }
            if (is_array($default_atr) && count($default_atr) > 0) {
                foreach ($default_atr as $attribute) {
                    $attribute_val[$attribute['veriable_name']] = $inputdata[$attribute['veriable_name']];
                }
            }
            if (is_array($custom_atr) && count($custom_atr) > 0) {
                foreach ($custom_atr as $attribute) {
                    $attribute_val[$attribute['veriable_name']] = $inputdata[$attribute['veriable_name']];
                }
            }

        }
        return $attribute_val;
    }

    public function _validate_mutiAsset($action, $inputdata)
    {
        //print_r($inputdata); exit;
        $totalerror = array();
        if (count($inputdata['assets_ci_templ_id']) > 0) {
            foreach ($inputdata['assets_ci_templ_id'] as $key => $ci_templ_id) {
                $setdata = array();
                $unicode = getAssetId();

                $setdata['ci_templ_id'] = $inputdata['assets_ci_templ_id'][$key];
                $setdata['ci_type_id'] = $inputdata['assets_ci_type_id'][$key];
                $setdata['cutype'] = $inputdata['assets_types'][$key];

                $attrdata = $this->getattr($setdata['cutype'], $setdata['ci_templ_id'], $setdata['ci_type_id']);
                //print_r($attrdata);
                $default_atr = $attrdata['default'];
                $custom_atr = $attrdata['custom'];
                $asset_name = $attrdata['asset_name'];
                $assetdata = array();
                if (is_array($default_atr) && count($default_atr) > 0) {
                    foreach ($default_atr as $attribute) {
                        if (count($inputdata[$setdata['ci_templ_id'] . '#' . $attribute['veriable_name']]) > 0) {
                            foreach ($inputdata[$setdata['ci_templ_id'] . '#' . $attribute['veriable_name']] as $k => $val) {
                                $assetdata[$k][$attribute['veriable_name']] = $val;
                            }

                        }
                    }
                }
                if (is_array($custom_atr) && count($custom_atr) > 0) {
                    foreach ($custom_atr as $attribute) {
                        if (count($inputdata[$setdata['ci_templ_id'] . '#' . $attribute['veriable_name']]) > 0) {
                            foreach ($inputdata[$setdata['ci_templ_id'] . '#' . $attribute['veriable_name']] as $k => $val) {
                                $assetdata[$k][$attribute['veriable_name']] = $val;
                            }

                        }
                    }
                }

                // print_r($assetdata) ;
                // print_r($inputdata); die();
                if (is_array($assetdata) && count($assetdata) > 0) {
                    foreach ($assetdata as $askey => $asset) {
                        $unicode = getAssetId();
                        $setdata['asset_tag'] = $inputdata['assets_prefix'][$key] . '#' . $unicode;
                        $assetid = $askey + 1;
                        $e = $setdata['ci_templ_id'] . '#multiassetid';
                        if (isset($inputdata, $e));
                        $as_id = $inputdata[$e];
                        $setdata['asset_id'] = $as_id[$askey];
                        if (count($asset) > 0) {
                            foreach ($asset as $asetkey => $asset_val) {
                                $setdata[$asetkey] = $asset_val;
                            }

                            //$aid = $assetid + $asetkey;
                        }
                        if ($setdata['cutype'] == "default") {
                            $asset_name = trans('citree.' . str_replace(" ", "_", $asset_name));
                        }
                        $setdata['asset_name'] = $asset_name . ' ' . $assetid;
                        // apilog(json_encode($setdata));
                        $assets[] = $setdata;
                        if ($as_id[$askey] == "") {
                            $returndata = $this->_validate_Asset('add', $setdata, 'multi');
                        } else {
                            $returndata = $this->_validate_Asset('edit', $setdata, 'multi');
                        }

                        if ($returndata->fails()) {
                            $error = $returndata->errors()->all();
                            //echo $setdata['asset_name'];
                            // print_r($setdata);
                            foreach ($error as $errket => $err) {
                                $totalerror[] = $err;
                            }
                        }
                    }
                }
            }
        }
        $this->multiassets = $assets;
        //print_r($this->multiassets); exit;
        return $totalerror;
    }

    /**
     * This is controller funtion used to Validate asset.
     * @author       Amit Khairnar
     * @access       public
     * @param        all DATA
     * @return       JSON
     */

    public function getattr($cutype, $ci_templ_id, $ci_type_id)
    {
        $asset_name = "";
        $custom_atr = $default_atr = array();
        if ($cutype == 'default') {
            $result = EnCiTemplDefault::getcitemplatesD($ci_templ_id, $ci_type_id);

            //print_r($result); exit;
            if (count($result) > 0) {
                foreach ($result as $res) {
                    $default_atr = json_decode($res->default_attributes, true);
                    if ($res->custom_attributes != '') {
                        $custom_atr = json_decode($res->custom_attributes, true);
                    }
                    $asset_name = $res->ci_name;
                }
            }

        } else {
            $result = EnCiTemplCustom::getcitemplatesC($ci_templ_id, $ci_type_id);
            if (count($result) > 0) {
                foreach ($result as $res) {
                    $custom_atr = json_decode($res->custom_attributes, true);
                    $asset_name = $res->ci_name;
                }
            }
        }
        $data['custom'] = $custom_atr;
        $data['default'] = $default_atr;
        $data['asset_name'] = $asset_name;

        return $data;
    }
    public function _validate_Asset($action, $inputdata, $type = "single")
    {
        $messages = [
            //'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_business_verticals')), true),//'The Business Vertical field should be required',
            'ci_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_citemplate')), true), //'The CI Template field should be required',
            'ci_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_citype')), true), //'The CI Type field should be required',
            //'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),//'The Location field should be required',
            'cutype.required' => showmessage('000', array('{name}'), array(trans('label.lbl_type')), true), //'The Type field should be required',
            'quantity.numeric' => showmessage('025', array('{name}'), array(trans('label.lbl_quantity')), true), //'The Quantity field should be numeric',
            'purchasecost.numeric' => showmessage('025', array('{name}'), array(trans('label.lbl_purchasecost')), true),
            'purchasecost.min' => showmessage('038', array('{name}'), array(trans('label.lbl_purchasecost')), true),

            'title.required' => showmessage('000', array('{name}'), array(trans('label.lbl_title')), true), //'The Title field should be required',
            'title.allow_alpha_numeric_space_dash_underscore_only' => showmessage('007', array('{name}'), array(trans('label.lbl_title')), true), // 'The Title may only contain letters and numbers as well as dashes and underscores.',
            'title.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_title')), true), //'The Title field should be required',

            //'acquisitiondate.date'      = showmessage('011', array('{name}'), array(trans('label.lbl_acquisition_date')), true),
            //'expirydate.date'           = showmessage('011', array('{name}'), array(trans('label.lbl_expiry_date')), true),
            //'warrantyexpirydate.date'   = showmessage('011', array('{name}'), array(trans('label.lbl_warranty_expiry_date')), true),
            //'expirydate.after_or_equal' = showmessage('before_date', array('{date1}','{date2}'), array(trans('label.lbl_acquisition_date'),trans('label.lbl_expiry_date')), true),
            //'warrantyexpirydate.after_or_equal' = showmessage('before_date', array('{date1}','{date2}'), array(trans('label.lbl_acquisition_date'),trans('label.lbl_warranty_expiry_date')), true),
        ];
        //$validation_rules['title'] = 'required';//|allow_alphal_numeric_dash_underscore_only';
        if ($action === 'add') {
            $validation_rules['asset_tag'] = 'required|composite_unique:en_assets, asset_tag, ' . $inputdata['asset_tag'] . '';
        }
        if (!_isset($inputdata, 'pr_po_id')) {
            if ($type != 'multi') {
                if ($action === 'add') {
                    // $validation_rules['title'] = 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_assets, display_name, '.$inputdata['title'].'';

                    // $validation_rules['title'] = 'required|allow_alpha_numeric_space_dash_underscore_only:en_assets, display_name, '.$inputdata['title'].'';

                } else if ($action === 'edit') {
                    // $validation_rules['title'] = 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_assets, display_name, '.$inputdata['title'].',asset_id,'.$inputdata['asset_id'];

                    // $validation_rules['title'] = 'required|allow_alpha_numeric_space_dash_underscore_only:en_assets, display_name, '.$inputdata['title'].',asset_id,'.$inputdata['asset_id'];
                }
            }
        }
        //$validation_rules['title'] = 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_assets, display_name, '.$inputdata['title'].'';
        $validation_rules['title'] = 'required';
        //|alpha_dash|composite_unique:en_assets, display_name, '.$inputdata['title'].'';
        $validation_rules['ci_templ_id'] = 'required|string|min:36|max:36';
        $validation_rules['ci_type_id'] = 'required|string|min:36|max:36';
        $validation_rules['cutype'] = 'required';

        $validation_rules['acquisitiondate'] = 'nullable|date';
        $validation_rules['expirydate'] = 'nullable|date|after_or_equal:acquisitiondate';
        $validation_rules['warrantyexpirydate'] = 'nullable|date|after_or_equal:acquisitiondate';

        $custom_atr = $default_atr = array();

        /* Don't Check For PO */
        if ($inputdata['cutype'] != '' && $inputdata['ci_templ_id'] != '' && $inputdata['ci_type_id'] != '') {

            $attrdata = $this->getattr($inputdata['cutype'], $inputdata['ci_templ_id'], $inputdata['ci_type_id']);

            $default_atr = $attrdata['default'];
            $custom_atr = $attrdata['custom'];

            if (is_array($default_atr) && count($default_atr) > 0) {
                foreach ($default_atr as $attribute) {
                    $valstring = '';

                    $validations = $attribute['validation'];
                    if (is_array($validations) && count($validations) > 0) {
                        foreach ($validations as $val) {
                            if ($val == 'unique') {
                                $valstring .= 'asset_unique_field:' . $attribute['veriable_name'] . ',' . $inputdata[$attribute['veriable_name']] . ',' . $inputdata['ci_templ_id'] . ',' . $attribute['attribute'] . '|';
                            } else {
                                $valstring .= $val . '|';
                            }

                        }

                    }

                    if (_isset($inputdata, 'asset_name')) {
                        if ($type == "multi") {
                            $asnmaes = str_replace(" ", "_", $inputdata['asset_name']);
                        } else {
                            $asnmaes = trans('citree.' . str_replace(" ", "_", $inputdata['asset_name']));
                        }

                        $attrname = $asnmaes . ' ' . trans('citree.' . str_replace(" ", "_", $attribute['attribute']));
                    } else {
                        $attrname = trans('citree.' . str_replace(" ", "_", $attribute['attribute']));
                    }

                    $validation_rules[$attribute['veriable_name']] = trim($valstring, '|');
                    $messages[$attribute['veriable_name'] . '.required'] = showmessage('000', array('{name}'), array($attrname), true); //'The ' . $attrname . ' field should be required.';
                    $messages[$attribute['veriable_name'] . '.alpha_num'] = showmessage('026', array('{name}'), array($attrname), true); //'The ' . $attrname . ' may only contain letters and numbers.';
                    $messages[$attribute['veriable_name'] . '.alpha'] = showmessage('027', array('{name}'), array($attrname), true); //'The ' . $attrname . ' may only contain letters.';
                    $messages[$attribute['veriable_name'] . '.numeric'] = showmessage('025', array('{name}'), array($attrname), true); //'The ' . $attrname . ' may only contain numbers.';
                    $messages[$attribute['veriable_name'] . '.email'] = showmessage('028', array('{name}'), array($attrname), true); //'The ' . $attrname . ' may only contain email.';
                    $messages[$attribute['veriable_name'] . '.allow_positive_numeric_only'] = showmessage('msg_validate_posno', array('{name}'), array($attrname), true);
                }
            }

            if (is_array($custom_atr) && count($custom_atr) > 0) {
                foreach ($custom_atr as $attribute) {
                    $valstring = '';

                    $validations = $attribute['validation'];
                    if (is_array($validations) && count($validations) > 0) {
                        foreach ($validations as $val) {
                            if ($val == 'unique') {
                                $valstring .= 'asset_unique_field:' . $attribute['veriable_name'] . ',' . $inputdata[$attribute['veriable_name']] . ',' . $inputdata['ci_templ_id'] . ',' . $attribute['attribute'] . '|';
                            } else {
                                $valstring .= $val . '|';
                            }
                        }
                    }
                    if (_isset($inputdata, 'asset_name')) {
                        $attrname = $inputdata['asset_name'] . ' ' . $attribute['attribute'];
                    } else {
                        $attrname = $attribute['attribute'];
                    }

                    $validation_rules[$attribute['veriable_name']] = trim($valstring, '|');
                    $messages[$attribute['veriable_name'] . '.required'] = showmessage('000', array('{name}'), array($attrname), true);
                    $messages[$attribute['veriable_name'] . '.alpha_num'] = showmessage('026', array('{name}'), array($attrname), true);
                    $messages[$attribute['veriable_name'] . '.alpha'] = showmessage('027', array('{name}'), array($attrname), true);
                    $messages[$attribute['veriable_name'] . '.numeric'] = showmessage('025', array('{name}'), array($attrname), true);
                    $messages[$attribute['veriable_name'] . '.email'] = showmessage('028', array('{name}'), array($attrname), true);
                    $messages[$attribute['veriable_name'] . '.allow_positive_numeric_only'] = showmessage('msg_validate_posno', array('{name}'), array($attrname), true);
                }
            }
        }

        if ($type != "multi") //Check PO
        {
            //$validation_rules['location_id'] = 'required|string|min:36|max:36';
            //$validation_rules['bv_id'] = 'required|string|min:36|max:36';
            //$validation_rules['purchasecost'] = 'numeric|min:0';
        }

        /* if ($inputdata['quantity'] != '' && $inputdata['quantity'] != null) {
        $validation_rules['quantity'] = 'numeric';
        }*/

        $validator = Validator::make($inputdata, $validation_rules, $messages);
        /* Don't chcek for PO*/
        if (_isset($inputdata, 'assets_ci_templ_id')) {
            $validator->after(function ($validator) {
                $request = request();
                $extraerror = $this->_validate_mutiAsset('add', $request);
                if (is_array($extraerror) && count($extraerror) > 0) {
                    foreach ($extraerror as $errorkey => $error) {
                        $validator->errors()->add($errorkey, $error, true);
                    }

                }
            });
        }
        return $validator;
    }

    //==================Aasset ======

    /*
     * This is controller funtion used to delete the Cost Center.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : cc_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_cost_Centers

     */

    public function assetdelete(Request $request)
    {
        try
        {
            $insertdata = $request->all();
            $asset_id = $insertdata['asset_id'];
            $validator = Validator::make($request->all(), [
                'asset_id' => 'required|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {
                $asset_id_uuid = $request->input('asset_id');
                $request['asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")');
                $res = EnAssets::where('asset_id', $request['asset_id'])->first();
                if ($res) {

                    //delete dependent relationship with other assets, set en_asset_relationship.status = 'd'
                    $reldata = EnRelationshipType::get_asset_relationship($asset_id_uuid);
                    $reldata = json_decode($reldata, true);

                    if (!empty($reldata) && count($reldata) > 0) {
                        for ($i = 0; $i < count($reldata); $i++) {
                            $request['asset_relationship_id'] = ($reldata[$i])['asset_relationship_id'];
                            $request['asset_id'] = $asset_id;
                            $request['rel_type'] = ($reldata[$i])['rel_type'];
                            $request['parent_asset_name'] = ($reldata[$i])['parent_asset_name'];
                            $request['child_asset_name'] = ($reldata[$i])['child_asset_name'];

                            $result = app('App\Http\Controllers\cmdb\RelationshipTypeController')->deleteassetrelationship($request);
                        }
                    }

                    //$pod->delete();
                    //Add Asset History
                    $history_data = array();
                    $history_data['asset_id'] = $request['asset_id'];
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['action'] = "Delete";
                    $history_data['message'] = showmessage('031'); //"Asset Delete in to stock";
                    EnAssetHistory::create($history_data);

                    //delete asset, set status = 'd'
                    $res->update(array('status' => 'd'));
                    $res->save();

                    $data['data']['deleted_id'] = $asset_id_uuid;
                    $data['message']['success'] = showmessage('118', array('{name}'), array(trans('label.lbl_asset')), true);
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $asset_id_uuid, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array(trans('label.lbl_asset')), true)));
                } else {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assetdelete", "This controller function is implemented to delete Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assetdelete", "This controller function is implemented to delete Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    //================== Cost Center Delete END ======

    /**
     * Provides a window to user to update the asset information.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : cc_id
     * @return       JSON

     */
    public function editasset(Request $request)
    {
        try
        {
            $insertdata = $request->all();
            $asset_id = $insertdata['asset_id'];
            $validator = Validator::make($request->all(), [
                'asset_id' => 'required|allow_uuid|string|size:36',
                //'ci_templ_id' => 'required|string|size:36',
                //'ci_type_id' => 'required|string|size:36'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {
                $result = EnAssets::getassets($insertdata);
                $checkdata['parent_asset_id'] = $asset_id;
                $parentasset = EnAssets::getassets($checkdata);

                $data['data']['childs'] = $parentasset->isEmpty() ? null : $parentasset;
                //print_r($data['data']['childs']);
                $data['data']['records'] = $result->isEmpty() ? null : $result;
                if ($data['data']['records']) {
                    $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
                    $data['status'] = 'success';
                } else {
                    $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("editasset", "This controller function is implemented to edit Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("editasset", "This controller function is implemented to edit Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * Updates the Pods information, which is entered by user on Edit Cost Centers window.
     * @author       Amit Khairnar
     * @access       public
     * @param        cc_code, cc_name, cc_description, owner_id,locations,departments, status
     * @return       JSON
     */

    public function updateasset(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            $validator = $this->_validate_Asset("edit", $inputdata);

            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {

                $asset_id_uuid = $request->input('asset_id');
                $request['asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")');
                $assetresult = EnAssets::where('asset_id', $request['asset_id'])->first();
                $assetdetailresult = EnAssetDetails::where('asset_id', $request['asset_id'])->first();

                if ($assetresult) {
                    $attribute_val = $this->setassetdata($inputdata);
                    //$savedata['location_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['location_id'] . '")');
                    //$savedata['ci_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['ci_type_id'].'")');
                    //$savedata['bv_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['bv_id'] . '")');
                    //$savedata['ci_templ_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['ci_templ_id'].'")');
                    //$savedata['asset_tag'] = $inputdata['asset_tag'];
                    $savedata['display_name'] = $inputdata['title'];

                    //$savedata['ci_templ_type'] = $inputdata['cutype'];
                    //$savedata['asset_status'] = 'in_store';
                    //$savedata['status'] = 'Y';
                    $savedata['asset_details'] = json_encode($attribute_val);
                    if ($inputdata['vendor_id'] != "") {
                        $savedata['vendor_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['vendor_id'] . '")');
                    } else {
                        $savedata['vendor_id'] = "";
                    }

                    $savedata['purchasecost'] = $inputdata['purchasecost'];
                    $savedata['acquisitiondate'] = $inputdata['acquisitiondate'];
                    $savedata['expirydate'] = $inputdata['expirydate'];
                    $savedata['warrantyexpirydate'] = $inputdata['warrantyexpirydate'];
                    $savedata['asset_sku'] = $inputdata['asset_sku'];
                    //$savedata['auto_discovered'] = 'n';
                    $assetresult->update($savedata);
                    $assetresult->save();
                    $assetdetailresult->update($savedata);
                    $assetdetailresult->save();
                    //Add Asset History
                    $history_data = array();
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['asset_id'] = $request['asset_id'];
                    $history_data['action'] = trans('label.lbl_update'); //'Update';
                    $history_data['message'] = showmessage("032"); //"Asset updated successfully.";
                    EnAssetHistory::create($history_data);
                    //Multiple asset update
                    if (is_array($this->multiassets) && count($this->multiassets) > 0) {
                        foreach ($this->multiassets as $masset) {
                            $mattribute_val = $this->setassetdata($masset);
                            $masset['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_type_id'] . '")');
                            $masset['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_templ_id'] . '")');
                            $masset['location_id'] = $savedata['location_id'];
                            $masset['bv_id'] = $savedata['bv_id'];
                            if ($inputdata['vendor_id'] != "") {
                                $masset['vendor_id'] = DB::raw('UUID_TO_BIN("' . $inputdata['vendor_id'] . '")');
                            }

                            $masset['ci_templ_type'] = $masset['cutype'];
                            $masset['asset_status'] = 'in_use';
                            $masset['status'] = 'Y';
                            $masset['asset_details'] = json_encode($mattribute_val);
                            $masset['auto_discovered'] = 'n';
                            //$masset['purchasecost'] = "";
                            //$masset['acquisitiondate'] = "";
                            //$masset['expirydate'] = "";
                            //$masset['warrantyexpirydate'] = "";
                            $masset['parent_asset_id'] = $request['asset_id'];
                            if ($masset['asset_id'] == '') // multi add new asset
                            {
                                unset($masset['asset_id']);
                                $massetdata = EnAssets::create($masset); // add table data
                                if (!empty($massetdata['asset_id'])) {
                                    $masset['asset_id'] = DB::raw('UUID_TO_BIN("' . $massetdata->asset_id_text . '")');
                                    $assetdetaildata = EnAssetDetails::create($masset);
                                }
                            } else // multi update asser
                            {
                                $mutiasset_bin_id = DB::raw('UUID_TO_BIN("' . $masset['asset_id'] . '")');
                                $multiassetresult = EnAssets::where('asset_id', $mutiasset_bin_id)->first();
                                unset($masset['asset_tag']);
                                unset($masset['asset_id']);
                                $multiassetdetailresult = EnAssetDetails::where('asset_id', $mutiasset_bin_id)->first();
                                if ($multiassetresult) {
                                    $multiassetresult->update($masset);
                                    $multiassetresult->save();
                                    $multiassetdetailresult->update($masset);
                                    $multiassetdetailresult->save();
                                }
                            }
                        }
                    }

                    if ($inputdata['deletedasset'] != "") //Remove asset
                    {
                        $deletedasset = array_unique(explode("##", trim($inputdata['deletedasset'], "##")));
                        if (is_array($deletedasset) && count($deletedasset) > 0) {
                            foreach ($deletedasset as $deasset) {
                                $request['asset_id'] = $deasset;
                                $result = app('App\Http\Controllers\asset\AssetController')->assetdelete($request);
                                //Add Asset History
                                /* $history_data = array();
                            $history_data['user_id'] = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                            $history_data['asset_id'] =  $request['asset_id'];
                            $history_data['action'] = trans("label.lbl_delete"); //'Delete';
                            $history_data['message'] = showmessage("031"); //"Asset Deleted successfully.";
                            EnAssetHistory::create($history_data);*/
                            }
                        }
                    }
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Asset'));
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $asset_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'), array(trans('label.lbl_asset')), true)));
                } else {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
                    $data['status'] = 'error';
                }

            }
            return response()->json($data);
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("updateasset", "This controller function is implemented to update Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("updateasset", "This controller function is implemented to update Asset.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }
    /**
     * Sigle asset dashboard used ythis function to get all data from DB
     * @author       Amit Khairnar
     * @access       public
     * @param        ci_temple_id, asset_id
     * @return       JSON
     */

    public function assetdashboard(Request $request)
    {
        try
        {
            $finalarray = array();
            $result = app('App\Http\Controllers\cmdb\CiTypesController')->getciitems($request);
            $original = $result->original;
            $data = $original['data'];
            $records = $data['records'];

            $result1 = EnAssets::select(DB::raw('count(*) as total'), DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), 'asset_status')
                ->where('status', '!=', 'd')
                ->where('asset_status', '!=', 'in_procurement')
                ->groupBy('ci_templ_id', 'asset_status')
                ->get()->toArray();
            $queries = DB::getQueryLog();

            $last_query = end($queries);

            apilog('---Asset dashbord---');

            apilog(json_encode($last_query));
            //apilog(json_encode($result));
            if (is_array($result1) && count($result1) > 0) {
                foreach ($result1 as $re) {
                    $templats[$re['ci_templ_id']][$re['asset_status']] = $re['total'];
                }
            }
            if (is_array($records) && count($records) > 0) {
                foreach ($records as $citemp) {
                    if (is_array($citemp['children']) && count($citemp['children']) > 0) {
                        foreach ($citemp['children'] as $ci) {
                            $finalarray[$citemp['key']]['title'] = $citemp['title'];
                            $finalarray[$citemp['key']]['ci_templ_id'] = $citemp['ci_templ_id'];
                            $finalarray[$citemp['key']]['ci_type_id'] = $citemp['ci_type_id'];
                            $finalarray[$citemp['key']]['children'][$ci['key']] = $ci;
                            $finalarray[$citemp['key']]['children'][$ci['key']]['asset_staus'] = _isset($templats, $ci['key']);
                        }
                    }
                }
            }
            $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
            $data['status'] = 'success';
            $data['data'] = $finalarray;
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assetdashboard", "This controller function is get Asset dashboard data.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("assetdashboard", "This controller function is Asset dashboard data.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }

    /*****License Dashboard*****/

     public function licensedashboard(Request $request)
    {
        try
        {
            $finalarray = array();
            $result = app('App\Http\Controllers\cmdb\CiTypesController')->getciitems($request);
            $original = $result->original;
            $data = $original['data'];
            $records = $data['records'];

            $result1 = EnAssets::select(DB::raw('count(*) as total'), DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), 'asset_status')
                ->where('status', '!=', 'd')
                ->where('asset_status', '!=', 'in_procurement')
                ->groupBy('ci_templ_id', 'asset_status')
                ->get()->toArray();
            $queries = DB::getQueryLog();

            $last_query = end($queries);

            apilog('---Asset dashbord---');

            apilog(json_encode($last_query));
            //apilog(json_encode($result));
            if (is_array($result1) && count($result1) > 0) {
                foreach ($result1 as $re) {
                    $templats[$re['ci_templ_id']][$re['asset_status']] = $re['total'];
                }
            }
            if (is_array($records) && count($records) > 0) {
                foreach ($records as $citemp) {
                    if (is_array($citemp['children']) && count($citemp['children']) > 0) {
                        foreach ($citemp['children'] as $ci) {
                            $finalarray[$citemp['key']]['title'] = $citemp['title'];
                            $finalarray[$citemp['key']]['ci_templ_id'] = $citemp['ci_templ_id'];
                            $finalarray[$citemp['key']]['ci_type_id'] = $citemp['ci_type_id'];
                            $finalarray[$citemp['key']]['children'][$ci['key']] = $ci;
                            $finalarray[$citemp['key']]['children'][$ci['key']]['asset_staus'] = _isset($templats, $ci['key']);
                        }
                    }
                }
            }
            $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
            $data['status'] = 'success';
            $data['data'] = $finalarray;
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("licensedashboard", "This controller function is get Asset dashboard data.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("licensedashboard", "This controller function is Asset dashboard data.", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }


    /***************************/




    /**
     * Attach asset save function.
     * @author       Amit Khairnar
     * @access       public
     * @param        asset_id,location_id,ci_templ_id,bv_id,sectedasset_ids,asset_citemple_id
     * @return       JSON
     */

    public function attachassetsave(Request $request)
    {
        $inputdata = $request->all();

        $messages = [
            'asset_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_asset_id')), true),
            'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location_id')), true),
            'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_bv_id')), true),
            'ci_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ci')), true),
            'asset_ci_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ci')), true),
            'selectassetids.required' => showmessage('000', array('{name}'), array(trans('label.lbl_selected_asset')), true),
        ];

        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|allow_uuid|string|size:36',
            'location_id' => 'required|allow_uuid|string|size:36',
            'bv_id' => 'required|allow_uuid|string|size:36',
            'ci_templ_id' => 'required|allow_uuid|string|size:36',
            'asset_ci_templ_id' => 'required|allow_uuid|string|size:36',
            "selectassetids" => 'required',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $ci_templ_id = $inputdata['ci_templ_id'];
            $result = app('App\Http\Controllers\cmdb\CiTypesController')->citemplates($request);
            $original = $result->original;
            $resdata = $original['data'];
            $childasset = $resdata['records'];
            // print_r($childasset); die();
            $asset_ci_templ_id = $inputdata['asset_ci_templ_id'];
            $request->request->add(['ci_templ_id' => $asset_ci_templ_id]);
            $result = app('App\Http\Controllers\cmdb\CiTypesController')->citemplates($request);
            $original = $result->original;
            $resdata = $original['data'];
            $parent_asset = $resdata['records'];
            // die($parent_asset[0]->ci_name);
            //print_r($records); die();

            $tag = $inputdata['tag'];
            $location_id = $inputdata['location_id'];
            $bv_id = $inputdata['bv_id'];
            $asset_id = $inputdata['asset_id'];
            $selectassetids = $inputdata['selectassetids'];
            if (is_array($selectassetids) && count($selectassetids) > 0) {
                $masset['parent_asset_id'] = DB::raw('UUID_TO_BIN("' . $asset_id . '")');
                $masset['asset_status'] = "in_use";
                foreach ($selectassetids as $id) {
                    if (trim($id) != "") {
                        $assetdata = EnAssets::where('asset_id', $masset['parent_asset_id'])->first();
                        $mutiasset_bin_id = DB::raw('UUID_TO_BIN("' . $id . '")');
                        $multiassetresult = EnAssets::where('asset_id', $mutiasset_bin_id)->first();
                        // var_dump($multiassetresult); die();
                        if ($assetdata) {
                            if ($multiassetresult) {
                                $tagnm = $multiassetresult->asset_tag;
                                $multiassetresult->update($masset);
                                $multiassetresult->save();
                                //Add Asset History each asset
                                $history_data = array();
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $mutiasset_bin_id;
                                $history_data['action'] = "Attach";
                                $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($tagnm, $tag), true);
                                EnAssetHistory::create($history_data);

                                //Add Asset History each asset
                                $history_data = array();
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $mutiasset_bin_id;
                                $history_data['action'] = "change status";
                                $history_data['message'] = showmessage('033');
                                EnAssetHistory::create($history_data);

                                //Add Asset History to parent asset
                                $history_data = array();

                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['asset_id'] = $masset['parent_asset_id'];
                                $history_data['action'] = "Attach";
                                $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($tagnm, $tag), true);
                                EnAssetHistory::create($history_data);
                                $enreldata = EnRelationshipType::where('rel_type', 'Attached to')->first();

                                $rel_type_id = $enreldata->rel_type_id_text;
                                if ($enreldata) {

                                    $request->request->add(['asset_id' => $asset_id]);
                                    $request->request->add(['relationship_type_id' => $rel_type_id]);
                                    $request->request->add(['child_asset_id' => $id]);
                                    $request->request->add(['ci_templ_id' => $ci_templ_id]);
                                    $request->request->add(['relationship_type_name' => $enreldata->rel_type]);
                                    $request->request->add(['parent_asset_name' => $tag]);
                                    $request->request->add(['child_asset_name' => $tagnm]);
                                    if (in_array($parent_asset[0]->variable_name, $this->ci)) {
                                        if (!in_array($childasset[0]->variable_name, $this->ciitem)) {

                                            $resultdata = app('App\Http\Controllers\cmdb\RelationshipTypeController')->addassetrelationship($request);
                                        }
                                    } else {

                                        $resultdata = app('App\Http\Controllers\cmdb\RelationshipTypeController')->addassetrelationship($request);

                                    }
                                }
                            }
                        }
                    }
                }
                $data['data'] = null;
                $data['message']['success'] = showmessage('146', array('{name}'), array(trans('label.lbl_asset')), true);
                $data['status'] = 'success';
            } else {
                $data['data'] = null;
                $data['message']['error'] = showmessage('147', array('{name}'), array(trans('label.lbl_asset')), true);
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }
    /**
     * This function used for free asset.
     * @author       Amit Khairnar
     * @access       public
     * @param        asset_id,parent_asset_id
     * @return       JSON
     */
    public function assetfree(Request $request)
    {
        $insertdata = $request->all();
        $asset_id = $insertdata['asset_id'];
        $parent_asset_id = $insertdata['parent_asset_id'];
        $validator = Validator::make($request->all(), [
            'parent_asset_id' => 'required|allow_uuid|string|size:36',
            'asset_id' => 'required|allow_uuid|string|size:36',

        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $asset_id_uuid = $request->input('asset_id');
            $request['asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")');
            $request['parent_asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('parent_asset_id') . '")');

            $result = DB::table('en_asset_relationship')
                ->where('parent_asset_id', $request['parent_asset_id'])
                ->where('child_asset_id', $request['asset_id'])
                ->update(['status' => 'd']);

            $res = EnAssets::where('asset_id', $request['asset_id'])->first();
            if ($res) {

                $history_data['asset_id'] = $request['parent_asset_id'];
                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                $history_data['action'] = "Detach";
                $history_data['message'] = showmessage('037', array('{name}'), array($res->asset_tag), true); //"Asset detached";
                EnAssetHistory::create($history_data);

                $history_data = array();
                $history_data['asset_id'] = $request['asset_id'];
                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                $history_data['action'] = "Detach";
                $history_data['message'] = showmessage('035'); //"Asset detached";
                EnAssetHistory::create($history_data);

                $history_data['action'] = "change status";
                $history_data['message'] = showmessage('036'); // status changed.
                EnAssetHistory::create($history_data);

                $res->update(array('status' => 'y', 'asset_status' => 'in_store', 'parent_asset_id' => null));
                $res->save();
                $data['data']['deleted_id'] = $asset_id_uuid;
                $data['message']['success'] = showmessage('118', array('{name}'), array(trans('label.lbl_asset')), true);
                $data['status'] = 'success';
                //Add into UserActivityLog
                (array('record_id' => $asset_id_uuid, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array(trans('label.lbl_asset')), true)));
            } else {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
                $data['status'] = 'error';
            }
            return response()->json($data);
        }
    }

    public function oneassetdashboard(Request $request)
    {
        $insertdata = $request->all();
        $asset_id = $insertdata['asset_id'];
    }
    /**
     * This function used for su\tatus change for any asset.
     * @author       Amit Khairnar
     * @access       public
     * @param        asset_id,location_id,bv_id,department_id,status, parent_asset_id,comment
     * @return       JSON
     */

    public function statuschangesubmit(Request $request)
    {
        
        $insertdata = $request->all();
        $messages = [
            'asset_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_asset_id')), true),
            'location_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
            'bv_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_business_verticals')), true),
            'department_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_associated_department')), true),
            'comment.required' => showmessage('000', array('{name}'), array(trans('label.lbl_comment')), true),
            'status.required' => showmessage('000', array('{name}'), array(trans('label.lbl_Asset_status')), true),
            //'parent_asset_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_associated_asset')), true),
            'requesters_id.required' => showmessage('000', array('{name}'), array('Requester name required'), true),
        ];
        $validation_rules['asset_id'] = 'required|string|min:36|max:36';
        $validation_rules['status'] = 'required';
        if ($insertdata['status'] == "in_use") {
            if ($insertdata['requesters_id'] == "") {
                $validation_rules['requesters_id'] = 'required|string|min:36|max:36';
            }
            if ($insertdata['department_id'] == "") {
                $validation_rules['department_id'] = 'required|string|min:36|max:36';
            }
        }
        if ($insertdata['status'] == "in_store") {
            $validation_rules['bv_id'] = 'required|string|min:36|max:36';
            $validation_rules['location_id'] = 'required|string|min:36|max:36';
        }
        $validation_rules['comment'] = 'required|';

        $validator = Validator::make($request->all(), $validation_rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $citemplats = app('App\Http\Controllers\cmdb\CiTypesController')->getallcitemplates();
            $original = $citemplats->original;
            $citemplats = json_decode($original['data'], true);
            $cutemp = array();
            if (is_array($citemplats) && count($citemplats) > 0) {
                foreach ($citemplats as $citemp) {
                    $cutemp[$citemp['ci_templ_id']] = $citemp['variable_name'];
                }
            }
            $isConsumable = false;

            $asset_data['asset_id'] = $insertdata['asset_id'];
            // 
            $assetIds = DB::raw('UUID_TO_BIN("' . $asset_data['asset_id'] . '")');
            $results = DB::select( DB::raw("SELECT t1.asset_sku,t1.display_name,
            t1.asset_qty,t2.primary_category_name 
            FROM en_assets t1, en_sku_mst t2 WHERE t1.asset_sku=t2.sku_code 
            and t1.asset_id = $assetIds  limit 1") );
            $asset_sku  = $results[0]->asset_sku;
            $display_name  = $results[0]->display_name;
            $asset_qty  = $results[0]->asset_qty;
            $primary_category_name  = $results[0]->primary_category_name;
            $request_asset_qty = 1;
            $remaining_asset_qty = intval($asset_qty) - intval($request_asset_qty);

            // 
            $res = EnAssets::getassets($asset_data);
            $parent_asset_data['parent_asset_id'] = $insertdata['asset_id'];
            $resultdata = EnAssets::getassets($parent_asset_data);
            if ($res) {
                $asset_details = json_decode($res[0]->asset_details, true);
                if($primary_category_name != 'Consumable')
                {
                    if (empty($asset_details['serial_number'])) {
                        $data['data'] = null;
                        $data['message']['error'] = 'Please fill the serial number first after that assing the asset.';
                        $data['status'] = 'error';
                        return response()->json($data);
                    }
                }

                $resasset = EnAssets::where('asset_id', DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")'))->first();
                $existing_data = DB::table('en_assets_assign')
                    ->select(DB::raw('BIN_TO_UUID(asset_id) as asset_id'),
                        DB::raw('BIN_TO_UUID(id) as id'),
                        DB::raw('BIN_TO_UUID(requestername_id) as requestername_id'),
                        'status', 'assign_date')
                    ->where('asset_id', DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")'))
                    ->orderBy('created_at', 'DESC')->first();
                $temp_status = $insertdata['status'];

                if ($insertdata['status'] == "return") {
                    // DB::table('en_assets')
                    //     ->where('asset_id', '=', DB::raw('UUID_TO_BIN("' . $existing_data->asset_id . '")'))
                    //     ->update([
                    //         'asset_status' => $insertdata['status'],
                    //         'updated_at' => date('Y-m-d H:i:s'),
                    //     ]);

                    if (!empty($existing_data->status) && $existing_data->status == 'in_use') {
                        DB::table('en_assets_assign')
                            ->where('id', '=', DB::raw('UUID_TO_BIN("' . $existing_data->id . '")'))
                            ->update([
                                'status' => $insertdata['status'],
                                'return_date' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                    }
                    $insertdata['status'] = 'in_store';
                }                
                
                if ($insertdata['status'] == "in_store") {
                    if ($temp_status != 'return') {
                        if (!empty($existing_data->status) && $existing_data->status == 'in_use') {
                            $data['data'] = $existing_data;
                            $data['message']['error'] = 'Asset assigned to employee you can`t change status as in store!';
                            $data['status'] = 'error';
                            return response()->json($data);
                        }
                    }
                    //Asset change history add
                    $history_data = array();
                    $history_data['asset_id'] = $insertdata['asset_id'];
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['action'] = "Status change";
                    $history_data['message'] = showmessage('msg_change_stat', array('{status}', '{status1}'), array(trans('label.' . $insertdata['pre_status']), trans('label.' . $insertdata['status'])), true);
                    $history_data['comment'] = $insertdata['comment'];
                    EnAssetHistory::create($history_data);
                    // if parent asset is available than deatch history add
                    // echo $res[0]->parent_asset_id; die("go");
                    if ($res[0]->parent_asset_id != "") {
                        $parentresasset = EnAssets::where('asset_id', DB::raw('UUID_TO_BIN("' . $res[0]->parent_asset_id . '")'))->first();
                        //echo $parentresasset->asset_tag; die("go");
                        if ($parentresasset) {
                            $history_data = array();
                            $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $res[0]->parent_asset_id . '")');
                            $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                            $history_data['action'] = "Detach";
                            $history_data['message'] = showmessage('037', array('{name}'), array($parentresasset->asset_tag), true); //"Asset detached";
                            $history_data['comment'] = $insertdata['comment'];
                            //print_r($history_data);die();
                            $detachhistory = EnAssetHistory::create($history_data);
                            apilog("***********************************************");
                            apilog(json_encode($detachhistory));
                        }
                        $history_data = array();
                        $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")');
                        $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $history_data['action'] = "Detach";
                        $history_data['message'] = showmessage('035'); //"Asset detached";
                        $history_data['comment'] = $insertdata['comment'];
                        EnAssetHistory::create($history_data);
                    }



                    //update asset in store status
                    $resasset->update(array(
                        'asset_status' => $insertdata['status'],
                        // 'location_id' => DB::raw('UUID_TO_BIN("' . $request->input('location_id') . '")'),
                        // 'bv_id' => DB::raw('UUID_TO_BIN("' . $request->input('bv_id') . '")'),
                        'parent_asset_id' => null,
                        'department_id' => null,
                    ));
                    $resasset->save();                   
                    
                    //Asset change history add
                    $history_data = array();
                    $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")');
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['action'] = "Status change";
                    $history_data['message'] = showmessage('msg_change_stat', array('{status}', '{status1}'), array(trans('label.' . $insertdata['pre_status']), trans('label.' . $insertdata['status'])), true);
                    $history_data['comment'] = $insertdata['comment'];
                    EnAssetHistory::create($history_data);


                    // End Instore
                } elseif ($insertdata['status'] == "in_use") {
                    // Nikhil Code for Consumable stock count change
                    if($primary_category_name == 'Consumable')
                    {
                        DB::table('en_assets')
                        ->where('asset_id', $assetIds)                        
                        ->update([
                            'asset_qty' => $remaining_asset_qty,
                        ]);
                    }
                    // Nikhil Code for Asset stock count change
                    if ($request->input('instock_asset_pr_requester_id') != "" &&
                        $request->input('instock_asset_pr_item_product') != "" &&
                        $request->input('instock_asset_prid') != "" &&
                        $request->input('instock_asset_pr_department_id') != "") 
                        {
                            if($request->input('instock_asset_pr_requester_id') == $request->input('requesters_id'))
                            {
                                
                                $pr_id = $request->input('instock_asset_prid');
                                $item_product = $request->input('instock_asset_pr_item_product');

                                $resultAssetDetail = DB::table('en_pr_po_asset_details')
                                ->select('asset_details')
                                ->where('pr_po_id', DB::raw('UUID_TO_BIN("' . $pr_id . '")'))
                                ->where(DB::raw('JSON_EXTRACT(asset_details,"$.item_product")'),'=',$item_product)
                                ->get()->toArray();
                                $NewResultArray = json_decode($resultAssetDetail[0]->asset_details,true);
                                // 
                                $UpdateAssetDetailsArray = array();
                                if(isset($NewResultArray['is_delivered_instock']))
                                {                                   
                                    $OldDelivered_item_qty = intval($NewResultArray['is_delivered_instock']['delivered_item_qty']);
                                    $NewDelivered_item_qty = $OldDelivered_item_qty + 1;
                                    $NewResultArray['is_delivered_instock']['delivered_item_qty'] = $NewDelivered_item_qty;

                                    $ItemQuantity = intval($NewResultArray['item_qty']);
                                    $NewResultArray['item_qty'] = $ItemQuantity - 1;

                                    if($NewResultArray['is_delivered_instock']['delivered_item_qty'] == $NewResultArray['is_delivered_instock']['original_item_qty'])
                                    {
                                        $NewResultArray['is_delivered_instock']['delivered_status'] = 'Delivered';                                        
                                    }
                                    $UpdateAssetDetailsArray = json_encode($NewResultArray);
                                }else{       
                                   
                                    $extraArray =  
                                    array("is_delivered_instock"=> array(
                                        "original_item_qty"=> $NewResultArray['item_qty'], 
                                        "delivered_item_qty"=>"1", 
                                        "delivered_status"=>""));
                                    //  Update ItemQuantity
                                    $ItemQuantity = intval($NewResultArray['item_qty']);
                                    $NewResultArray['item_qty'] = $ItemQuantity - 1;
                                    // End
                                    $fullArray = array_merge($NewResultArray,$extraArray);
                                    $UpdateAssetDetailsArray = json_encode($fullArray);
                                }                            
                                DB::table('en_pr_po_asset_details')
                                ->where('pr_po_id', DB::raw('UUID_TO_BIN("' . $pr_id . '")'))
                                ->where(DB::raw('JSON_EXTRACT(asset_details,"$.item_product")'),'=',$item_product)
                                ->update([
                                    'asset_details' => $UpdateAssetDetailsArray,
                                ]);
                            }
                        }
                    // Nikhil Code for Asset stock count change

                    $cuparent_asset_id = $cudepartment_id = $parentdata = "";
                    if ($request->input('parent_asset_id') != "") {
                        $cuparent_asset_id = DB::raw('UUID_TO_BIN("' . $request->input('parent_asset_id') . '")');
                        //$parentdata = EnAssets::where('asset_id',  $cuparent_asset_id)->first();
                        $parentdata = EnAssets::select(DB::raw('BIN_TO_UUID(ci_templ_id) AS ci_templ_id'), 'asset_tag')->where('asset_id', $cuparent_asset_id)->first();
                        //echo $parentdata1->asset_tag;die("gooooo");
                    }

                    if ($request->input('department_id') != "") {
                        $cudepartment_id = DB::raw('UUID_TO_BIN("' . $request->input('department_id') . '")');
                        //$parentdata = EnAssets::where('asset_id',  $cuparent_asset_id)->first();
                    }

                    //Associate asset change if pre asset_id avilable than deatch history add
                    if ($insertdata['pre_parent_asset_id'] != '') {
                        if ($insertdata['pre_parent_asset_id'] != $insertdata['pre_parent_asset_id']) {

                            $parentdta = EnAssets::where('asset_id', DB::raw('UUID_TO_BIN("' . $insertdata['pre_parent_asset_id'] . '")'))->first();
                            if ($parentdta) {
                                $history_data = array();
                                $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pre_parent_asset_id') . '")');
                                $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                $history_data['action'] = "Detach";
                                $history_data['message'] = showmessage('037', array('{name}'), array($parentdta->asset_tag), true); //"Asset detached";
                                $history_data['comment'] = $insertdata['comment'];
                                EnAssetHistory::create($history_data);
                            }

                            $history_data = array();
                            $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")');
                            $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                            $history_data['action'] = "Detach";
                            $history_data['message'] = showmessage('035'); //"Asset detached";
                            $history_data['comment'] = $insertdata['comment'];
                            EnAssetHistory::create($history_data);
                        }

                    }
                    if($primary_category_name != 'Consumable')
                    {
                        $resasset->update(array(
                            'asset_status' => $insertdata['status'],
                            'parent_asset_id' => $cuparent_asset_id,
                            'department_id' => $cudepartment_id,
                        ));
                        $resasset->save();

                        if (!empty($existing_data->status) && $existing_data->status == 'in_use') {
                            $data['data'] = $existing_data;
                            $data['message']['error'] = 'Asset already assigned!';
                            $data['status'] = 'error';
                            return response()->json($data);
                        } else {
                            $uuid = $this->guidv4();
                            DB::table('en_assets_assign')->insert([
                                'id' => DB::raw('UUID_TO_BIN("' . $uuid . '")'),
                                'asset_id' => DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")'),
                                'requestername_id' => DB::raw('UUID_TO_BIN("' . $insertdata['requesters_id'] . '")'),
                                'department_id' => DB::raw('UUID_TO_BIN("' . $insertdata['department_id'] . '")'),
                                'status' => $insertdata['status'],
                                'assign_date' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }else{
                        $uuid = $this->guidv4();
                        DB::table('en_assets_assign')->insert([
                            'id' => DB::raw('UUID_TO_BIN("' . $uuid . '")'),
                            'asset_id' => DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")'),
                            'requestername_id' => DB::raw('UUID_TO_BIN("' . $insertdata['requesters_id'] . '")'),
                            'department_id' => DB::raw('UUID_TO_BIN("' . $insertdata['department_id'] . '")'),
                            'status' => $insertdata['status'],
                            'assign_date' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    if ($parentdata) {
                        //Child asset history add
                        $history_data = array();
                        $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")');
                        $history_data['action'] = "Attach";
                        $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($resasset->asset_tag, $parentdata->asset_tag), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                        EnAssetHistory::create($history_data);

                        //History add for parent asset
                        $history_data = array();
                        $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $history_data['asset_id'] = $cuparent_asset_id;
                        $history_data['action'] = "Attach";
                        $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($resasset->asset_tag, $parentdata->asset_tag), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                        EnAssetHistory::create($history_data);

                        //Add relationship
                        $enreldata = EnRelationshipType::where('rel_type', 'Attached to')->first();

                        $rel_type_id = $enreldata->rel_type_id_text;
                        if ($enreldata) {

                            $request->request->add(['asset_id' => $insertdata['parent_asset_id']]);
                            $request->request->add(['relationship_type_id' => $rel_type_id]);
                            $request->request->add(['child_asset_id' => $insertdata['asset_id']]);
                            $request->request->add(['ci_templ_id' => $parentdata->ci_templ_id]);
                            $request->request->add(['relationship_type_name' => $enreldata->rel_type]);
                            $request->request->add(['parent_asset_name' => $parentdata->asset_tag]);
                            $request->request->add(['child_asset_name' => $resasset->asset_tag]);
                            //if(in_array(rent_asset[0]->variable_name, $this->ci))
                            if (in_array($cutemp[$parentdata->ci_templ_id], $this->ci)) {
                                if (!in_array($cutemp[$res[0]->ci_templ_id], $this->ciitem)) {

                                    $resultdata = app('App\Http\Controllers\cmdb\RelationshipTypeController')->addassetrelationship($request);
                                }
                            } else {
                                $resultdata = app('App\Http\Controllers\cmdb\RelationshipTypeController')->addassetrelationship($request);
                            }
                        }

                        //End relation ship
                    }
                    $history_data = array();
                    $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")');
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['action'] = "Status change";
                    $history_data['message'] = showmessage('msg_change_stat', array('{status}', '{status1}'), array(trans('label.' . $insertdata['pre_status']), trans('label.' . $insertdata['status'])), true);
                    $history_data['comment'] = $insertdata['comment'];
                    EnAssetHistory::create($history_data);

                } elseif ($insertdata['status'] == "disposed") {
                    // die("IIIINNNNNNNN");
                    $history_data = array();
                    $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")');
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['action'] = "Status change";
                    $history_data['message'] = showmessage('msg_change_stat', array('{status}', '{status1}'), array(trans('label.' . $insertdata['pre_status']), trans('label.' . $insertdata['status'])), true);
                    $history_data['comment'] = $insertdata['comment'];
                    EnAssetHistory::create($history_data);
                    $resasset->update(array('asset_status' => $insertdata['status']));
                    $resasset->save();

                    //all child asset status change
                    //if asset is (server,desktop & laptop ) its child like (ethernet, ram , HDD ) this status disposed and other free
                    if ($resultdata) {
                        if (in_array($cutemp[$res[0]->ci_templ_id], $this->ci)) {
                            foreach ($resultdata as $result) {
                                if (in_array($cutemp[$result->ci_templ_id], $this->ciitem)) //(ethernet,ram,hdd)
                                {

                                    // print_r($result);
                                    $childasset = EnAssets::where('asset_id', DB::raw('UUID_TO_BIN("' . $result->asset_id . '")'))->first();
                                    if ($childasset) {
                                        // echo "*************";

                                        $history_data = array();
                                        $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $result->asset_id . '")');
                                        $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                        $history_data['action'] = "Status change";
                                        $history_data['message'] = showmessage('msg_change_stat', array('{status}', '{status1}'), array(trans('label.' . $insertdata['pre_status']), trans('label.' . $insertdata['status'])), true);
                                        $history_data['comment'] = $insertdata['comment'];
                                        EnAssetHistory::create($history_data);
                                        $childasset->update(array('asset_status' => $insertdata['status']));
                                        $childasset->save();
                                    }

                                } else {
                                    //other asset
                                }
                            }
                        } else {
                            //other type child asset not change status
                        }

                    }
                } else {
                    //Add asset status change history
                    $history_data = array();
                    $history_data['asset_id'] = DB::raw('UUID_TO_BIN("' . $insertdata['asset_id'] . '")');
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['action'] = "Status change";
                    $history_data['message'] = showmessage('msg_change_stat', array('{status}', '{status1}'), array(trans('label.' . $insertdata['pre_status']), trans('label.' . $insertdata['status'])), true);
                    $history_data['comment'] = $insertdata['comment'];
                    EnAssetHistory::create($history_data);
                    $resasset->update(array('asset_status' => $insertdata['status']));
                    $resasset->save();

                    DB::table('en_assets_assign')
                        ->where('id', '=', DB::raw('UUID_TO_BIN("' . $existing_data->id . '")'))
                        ->update([
                            'status' => $insertdata['status'],
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                }

                $data['data'] = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_Asset_status')), true);
                $data['status'] = 'success';
                return response()->json($data);
            }

        }
    }

    public function assetcontract(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);

        } else {

            $inputdata = $request->all();
            $asset_id = trim(_isset($inputdata, 'asset_id'));
            $asset_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('asset_id') . '")');

            $result = EnContract::getassetcontract($asset_id, false);
            $queries = DB::getQueryLog();

            $last_query = end($queries);

            apilog('---Asset contract---');

            apilog(json_encode($last_query));
            apilog(json_encode($result));
            $data['data']['records'] = $result->isEmpty() ? null : $result;
            if (count($result) > 1) {
                $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_asset')), true);
            } else {
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_asset')), true);
            }
            $data['status'] = 'success';

            return response()->json($data);
        }
    }

    //IMport OLD
    /**
     * This function used for import asset save.
     * @author       Amit Khairnar
     * @access       public
     * @param        farray, files_content, fbv,floc,fvendot,fdept
     * @return       JSON
     */
    public function importsave(Request $request)
    {
        ini_set('max_execution_time', '1000');
        ini_set("memory_limit", "-1");
        $insertdata = $request->all();
        $farray = isset($insertdata['farray']) ? $insertdata['farray'] : '';
        //print_r($farray); die("in");
        $assets = array();
        $fbv = isset($insertdata['fbv']) ? $insertdata['fbv'] : '';
        $floc = isset($insertdata['floc']) ? $insertdata['floc'] : '';
        $fvendor = isset($insertdata['fvendor']) ? $insertdata['fvendor'] : '';
        $fdept = isset($insertdata['fdept']) ? $insertdata['fdept'] : '';
        $filedata = isset($insertdata['files_content']) ? base64_decode($insertdata['files_content']) : '';
        $fnalarray = explode("\n", $filedata);
        if (is_array($fnalarray) && count($fnalarray) > 0) {
            foreach ($fnalarray as $k => $fdata) {
                if (trim($fdata) != "" && $k > 0) {

                    $assetdata = explode(",", $fdata);
                    //print_r($assetdata);
                    //print_r($farray); die('go');
                    if (is_array($farray) && count($farray) > 0) {
                        $asset = array();
                        foreach ($farray as $key => $val) {
                            if ($val >= 0 && is_numeric($val)) {
                                $asset[$key] = _isset($assetdata, $val, $val);
                                if (in_array($key, array('bv_id', 'location_id', 'vendor_id', 'department_id'))) {
                                    if (trim($asset[$key]) != "") {
                                        // die($asset[$key]);
                                        if ($key == 'bv_id') {
                                            $asset[$key] = _isset($fbv, $asset[$key], "");
                                        } elseif ($key == 'location_id') {
                                            $asset[$key] = _isset($floc, $asset[$key], "");
                                        } elseif ($key == 'vendor_id') {
                                            $asset[$key] = _isset($fvendor, $asset[$key], "");
                                        } elseif ($key == 'department_id') {
                                            $asset[$key] = _isset($fdept, $asset[$key], "");
                                        } else {
                                            $asset[$key] = $asset[$key];
                                        }

                                    }
                                }
                                if (in_array($key, array('acquisitiondate', 'expirydate', 'warrantyexpirydate'))) {
                                    if (trim($asset[$key]) != "") {
                                        $asset[$key] = date("Y-m-d H:i:s", strtotime($asset[$key]));
                                    }
                                }
                            } else {
                                if (is_array($val) && count($val) > 0) {
                                    foreach ($val as $k => $v) { //if($v == '0')
                                        // echo $v.'<br>';
                                        if ($v >= 0 && is_numeric($v)) {
                                            $asset[$key][$k] = _isset($assetdata, $v, $v);
                                        } else {
                                            $asset[$key][$k] = $v;
                                        }

                                    }
                                } else {
                                    $asset[$key] = $val;
                                }

                            }
                        }
                        $assets[] = $asset;
                    }
                }

            }
            //print_r($assets); die('go');
        }

        //print_r($assets);
        //echo count($assets);
        // die(count($assets));
        $importassets = array();
        $totalasset = count($assets);
        if (is_array($assets) && count($assets) > 0) {
            foreach ($assets as $asset) {
                $savedata = array();
                $attribute_val = "";
                $unicode = getAssetId();
                $asset_tag = $asset['asset_tag'] = $asset['asset_prefix'] . '#' . $unicode;
                $attribute_val = $this->setassetdata($asset);
                if (_isset($asset, 'location_id', "") && $asset['location_id'] != "") {
                    $savedata['location_id'] = DB::raw('UUID_TO_BIN("' . $asset['location_id'] . '")');
                }

                $savedata['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $asset['ci_type_id'] . '")');
                if (_isset($asset, 'bv_id', "") && $asset['bv_id'] != "") {
                    $savedata['bv_id'] = DB::raw('UUID_TO_BIN("' . $asset['bv_id'] . '")');
                }

                $savedata['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $asset['ci_templ_id'] . '")');

                if (_isset($asset, 'vendor_id', "") && $asset['vendor_id'] != "") {
                    $savedata['vendor_id'] = DB::raw('UUID_TO_BIN("' . $asset['vendor_id'] . '")');
                }

                if (_isset($asset, 'department_id', "") && $asset['department_id'] != "") {
                    $savedata['department_id'] = DB::raw('UUID_TO_BIN("' . $asset['department_id'] . '")');
                }

                if (_isset($asset, 'asset_status')) {
                    if (in_array($asset['asset_status'], $this->status)) {
                        $custat = $asset['asset_status'];
                    } else {
                        $custat = "in_store";
                    }
                } else {
                    $custat = "in_store";
                }

                $savedata['asset_tag'] = $asset_tag;
                $savedata['display_name'] = $asset['title'];
                $savedata['asset_sku'] = $asset['sku_title'];
                $savedata['ci_templ_type'] = $asset['cutype'];
                $savedata['asset_status'] = $custat; //'in_store';
                $savedata['status'] = 'Y';
                $savedata['asset_details'] = json_encode($attribute_val);
                $savedata['auto_discovered'] = 'n';
                $savedata['purchasecost'] = $asset['purchasecost'];
                $savedata['acquisitiondate'] = $asset['acquisitiondate'];
                $savedata['expirydate'] = $asset['expirydate'];
                $savedata['warrantyexpirydate'] = $asset['warrantyexpirydate'];
                $assetdata = EnAssets::create($savedata); // add table data
                $importassets[] = $assetdata->asset_id_text;
                if (!empty($assetdata['asset_id'])) {
                    $savedata['asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');
                    $assetdetaildata = EnAssetDetails::create($savedata);

                    //Add Asset History
                    $history_data = array();
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $history_data['asset_id'] = $savedata['asset_id'];
                    $history_data['action'] = "Add";
                    $history_data['message'] = showmessage('030'); //"Asset Added in to stock";
                    EnAssetHistory::create($history_data);
                    if (_isset($asset, 'assets_ci_templ_id')) {

                        $extraerror = $this->_validate_mutiAsset('add', $asset);

                        // Multiple Asset ADD

                        if (is_array($this->multiassets) && count($this->multiassets) > 0) {
                            foreach ($this->multiassets as $masset) {
                                $mattribute_val = $this->setassetdata($masset);
                                if ($asset['location_id'] != "") {
                                    $masset['location_id'] = $savedata['location_id'];
                                }

                                if ($asset['bv_id'] != "") {
                                    $masset['bv_id'] = $savedata['bv_id'];
                                }

                                $masset['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_type_id'] . '")');
                                $masset['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_templ_id'] . '")');
                                if ($asset['vendor_id'] != "") {
                                    $masset['vendor_id'] = $savedata['vendor_id'];
                                }

                                $masset['ci_templ_type'] = $masset['cutype'];
                                $masset['asset_status'] = 'in_use';
                                $masset['status'] = 'Y';
                                $masset['asset_details'] = json_encode($mattribute_val);
                                $masset['auto_discovered'] = 'n';
                                // $masset['purchasecost'] = "";
                                //$masset['acquisitiondate'] = "";
                                //$masset['expirydate'] = "";
                                // $masset['warrantyexpirydate'] = "";
                                $masset['parent_asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');

                                $massetdata = EnAssets::create($masset); // add table data
                                if (!empty($massetdata['asset_id'])) {
                                    $masset['asset_id'] = DB::raw('UUID_TO_BIN("' . $massetdata->asset_id_text . '")');
                                    $assetdetaildata = EnAssetDetails::create($masset);
                                    //History add for child asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                    $history_data['asset_id'] = $masset['asset_id'];
                                    $history_data['action'] = "Add";
                                    $history_data['message'] = showmessage('030'); //asset added";
                                    EnAssetHistory::create($history_data);
                                    //History add for child asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                    $history_data['asset_id'] = $masset['asset_id'];
                                    $history_data['action'] = "Attach";
                                    $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($massetdata['asset_tag'], $savedata['asset_tag']), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                                    EnAssetHistory::create($history_data);

                                    //History add for child asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                    $history_data['asset_id'] = $masset['asset_id'];
                                    $history_data['action'] = "change status";
                                    $history_data['message'] = showmessage('033'); //"Asset status changed in stock to in use.." Component.";
                                    EnAssetHistory::create($history_data);

                                    //History add for parent asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                                    $history_data['asset_id'] = $savedata['asset_id'];
                                    $history_data['action'] = "Attach";
                                    $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($massetdata['asset_tag'], $savedata['asset_tag']), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                                    EnAssetHistory::create($history_data);
                                }
                            }
                        }
                        $this->multiassets = array();
                    }
                }

            }

        }
        $importcnt = count($importassets);
        $failedcount = $totalasset - $importcnt;
        if ($importcnt < 0) {
            $data['data']['total'] = $totalasset;
            $data['data']['import'] = $importcnt;
            $data['data']['failed'] = $failedcount;
            $data['message']['error'] = showmessage('103', array('{name}'), array(trans('label.lbl_asset')));
            $data['status'] = 'error';
        } else {
            $data['data']['total'] = $totalasset;
            $data['data']['import'] = $importcnt;
            $data['data']['failed'] = $failedcount;
            $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_asset')));
            $data['status'] = 'success';
        }
        return response()->json($data);
    }

    //Import New
    /**
     * This function used for import asset save.
     * @author       Amit Khairnar
     * @access       public
     * @param        farray, files_content, fbv,floc,fvendot,fdept
     * @return       JSON
     */
    public function importprocess(Request $request)
    {
        $insertdata = $request->all();

        $farray = $insertdata['farray'];
        $notification_id = $insertdata['notification_id'];
        $filename = $insertdata['filename'];
        $filefullpath = public_path('uploads/import/') . '/' . $filename;
        $user_id = $insertdata['ciuser_id'];
        $jwttoken = genratejwttoken($user_id);
        $supportdata = $this->getbvlocdatacenter($jwttoken);

        $fnalarray = $assets = array();
        $fbv = $supportdata['businessvertical'];
        $floc = $supportdata['location'];
        $fvendor = $supportdata['vendor'];
        $fdept = $supportdata['department'];
        if (file_exists($filefullpath)) {
            $filedata = file_get_contents($filefullpath);
            if (trim($filedata) != '') {
                $fnalarray = explode("\n", $filedata);
            } else {
                $fnalarray = array();
            }
        }
        if (is_array($fnalarray) && count($fnalarray) > 0) {
            foreach ($fnalarray as $k => $fdata) {
                if (trim($fdata) != "" && $k > 0) {
                    $assetdata = explode(",", $fdata);
                    if (is_array($farray) && count($farray) > 0) {
                        $asset = array();
                        foreach ($farray as $key => $val) {
                            if ($val >= 0 && is_numeric($val)) {
                                $asset[$key] = _isset($assetdata, $val, $val);
                                if (in_array($key, array('bv_id', 'location_id', 'vendor_id', 'department_id'))) {
                                    if (trim($asset[$key]) != "") {
                                        $asset[$key] = strtolower(trim($asset[$key]));
                                        // die($asset[$key]);
                                        if ($key == 'bv_id') {
                                            $asset[$key] = _isset($fbv, $asset[$key], "");
                                        } elseif ($key == 'location_id') {
                                            $asset[$key] = _isset($floc, $asset[$key], "");
                                        } elseif ($key == 'vendor_id') {
                                            $asset[$key] = _isset($fvendor, $asset[$key], "");
                                        } elseif ($key == 'department_id') {
                                            $asset[$key] = _isset($fdept, $asset[$key], "");
                                        } else {
                                            $asset[$key] = $asset[$key];
                                        }

                                    }
                                }
                                if (in_array($key, array('acquisitiondate', 'expirydate', 'warrantyexpirydate'))) {
                                    if (trim($asset[$key]) != "") {
                                        $asset[$key] = date("Y-m-d H:i:s", strtotime($asset[$key]));
                                    }
                                }
                            } else {
                                if (is_array($val) && count($val) > 0) {
                                    foreach ($val as $k => $v) {
                                        if ($v >= 0 && is_numeric($v)) {
                                            $asset[$key][$k] = _isset($assetdata, $v, $v);
                                        } else {
                                            $asset[$key][$k] = $v;
                                        }

                                    }
                                } else {
                                    $asset[$key] = $val;
                                }

                            }
                        }
                        $assets[] = $asset;
                    }
                }
            }
        }
        $importassets = array();
        $totalasset = count($assets);
        if (is_array($assets) && count($assets) > 0) {
            foreach ($assets as $asset) {
                $savedata = array();
                $attribute_val = "";
                $unicode = getAssetId();
                $asset_tag = $asset['asset_tag'] = $asset['asset_prefix'] . '#' . $unicode;
                $attribute_val = $this->setassetdata($asset);
                if (_isset($asset, 'location_id', "") && $asset['location_id'] != "") {
                    $savedata['location_id'] = DB::raw('UUID_TO_BIN("' . $asset['location_id'] . '")');
                }

                $savedata['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $asset['ci_type_id'] . '")');
                if (_isset($asset, 'bv_id', "") && $asset['bv_id'] != "") {
                    $savedata['bv_id'] = DB::raw('UUID_TO_BIN("' . $asset['bv_id'] . '")');
                }

                $savedata['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $asset['ci_templ_id'] . '")');

                if (_isset($asset, 'vendor_id', "") && $asset['vendor_id'] != "") {
                    $savedata['vendor_id'] = DB::raw('UUID_TO_BIN("' . $asset['vendor_id'] . '")');
                }

                if (_isset($asset, 'department_id', "") && $asset['department_id'] != "") {
                    $savedata['department_id'] = DB::raw('UUID_TO_BIN("' . $asset['department_id'] . '")');
                }

                if (_isset($asset, 'asset_status')) {
                    if (in_array($asset['asset_status'], $this->status)) {
                        $custat = $asset['asset_status'];
                    } else {
                        $custat = "in_store";
                    }
                } else {
                    $custat = "in_store";
                }

                $savedata['asset_tag'] = $asset_tag;
                $savedata['display_name'] = $asset['title'];
                $savedata['ci_templ_type'] = $asset['cutype'];
                $savedata['asset_status'] = $custat; //'in_store';
                $savedata['status'] = 'Y';
                $savedata['asset_details'] = json_encode($attribute_val);
                $savedata['auto_discovered'] = 'n';
                $savedata['purchasecost'] = $asset['purchasecost'];
                $savedata['acquisitiondate'] = $asset['acquisitiondate'];
                $savedata['expirydate'] = $asset['expirydate'];
                $savedata['warrantyexpirydate'] = $asset['warrantyexpirydate'];
                $assetdata = EnAssets::create($savedata); // add table data
                $importassets[] = $assetdata->asset_id_text;
                if (!empty($assetdata['asset_id'])) {
                    $savedata['asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');
                    $assetdetaildata = EnAssetDetails::create($savedata);

                    //Add Asset History
                    $history_data = array();
                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('ciuser_id') . '")');
                    $history_data['asset_id'] = $savedata['asset_id'];
                    $history_data['action'] = "Add";
                    $history_data['message'] = showmessage('030'); //"Asset Added in to stock";
                    EnAssetHistory::create($history_data);
                    if (_isset($asset, 'assets_ci_templ_id')) {

                        $extraerror = $this->_validate_mutiAsset('add', $asset);

                        // Multiple Asset ADD

                        if (is_array($this->multiassets) && count($this->multiassets) > 0) {
                            foreach ($this->multiassets as $masset) {
                                $mattribute_val = $this->setassetdata($masset);
                                if ($asset['location_id'] != "") {
                                    $masset['location_id'] = $savedata['location_id'];
                                }

                                if ($asset['bv_id'] != "") {
                                    $masset['bv_id'] = $savedata['bv_id'];
                                }

                                $masset['ci_type_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_type_id'] . '")');
                                $masset['ci_templ_id'] = DB::raw('UUID_TO_BIN("' . $masset['ci_templ_id'] . '")');
                                if ($asset['vendor_id'] != "") {
                                    $masset['vendor_id'] = $savedata['vendor_id'];
                                }

                                $masset['ci_templ_type'] = $masset['cutype'];
                                $masset['asset_status'] = 'in_use';
                                $masset['status'] = 'Y';
                                $masset['asset_details'] = json_encode($mattribute_val);
                                $masset['auto_discovered'] = 'n';
                                // $masset['purchasecost'] = "";
                                //$masset['acquisitiondate'] = "";
                                //$masset['expirydate'] = "";
                                // $masset['warrantyexpirydate'] = "";
                                $masset['parent_asset_id'] = DB::raw('UUID_TO_BIN("' . $assetdata->asset_id_text . '")');

                                $massetdata = EnAssets::create($masset); // add table data
                                if (!empty($massetdata['asset_id'])) {
                                    $masset['asset_id'] = DB::raw('UUID_TO_BIN("' . $massetdata->asset_id_text . '")');
                                    $assetdetaildata = EnAssetDetails::create($masset);
                                    //History add for child asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('ciuser_id') . '")');
                                    $history_data['asset_id'] = $masset['asset_id'];
                                    $history_data['action'] = "Add";
                                    $history_data['message'] = showmessage('030'); //asset added";
                                    EnAssetHistory::create($history_data);
                                    //History add for child asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('ciuser_id') . '")');
                                    $history_data['asset_id'] = $masset['asset_id'];
                                    $history_data['action'] = "Attach";
                                    $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($massetdata['asset_tag'], $savedata['asset_tag']), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                                    EnAssetHistory::create($history_data);

                                    //History add for child asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('ciuser_id') . '")');
                                    $history_data['asset_id'] = $masset['asset_id'];
                                    $history_data['action'] = "change status";
                                    $history_data['message'] = showmessage('033'); //"Asset status changed in stock to in use.." Component.";
                                    EnAssetHistory::create($history_data);

                                    //History add for parent asset
                                    $history_data = array();
                                    $history_data['user_id'] = DB::raw('UUID_TO_BIN("' . $request->input('ciuser_id') . '")');
                                    $history_data['asset_id'] = $savedata['asset_id'];
                                    $history_data['action'] = "Attach";
                                    $history_data['message'] = showmessage('029', array('{name}', '{name1}'), array($massetdata['asset_tag'], $savedata['asset_tag']), true); //"This Asset Attach to ". $savedata['asset_tag']." Component.";
                                    EnAssetHistory::create($history_data);
                                }
                            }
                        }
                        $this->multiassets = array();
                    }
                }
            }
        }
        $importcnt = count($importassets);
        $failedcount = $totalasset - $importcnt;
        if ($importcnt < 0) {
            $data['data']['total'] = $totalasset;
            $data['data']['import'] = $importcnt;
            $data['data']['failed'] = $failedcount;
            $data['message']['error'] = showmessage('103', array('{name}'), array(trans('label.lbl_asset')));
            $data['status'] = 'error';
        } else {
            $data['data']['total'] = $totalasset;
            $data['data']['import'] = $importcnt;
            $data['data']['failed'] = $failedcount;
            $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_asset')));
            $data['status'] = 'success';
        }
        $setdata['total'] = $totalasset;
        $setdata['import'] = $importcnt;
        $setdata['failed'] = $failedcount;

        $notification_id_bin = DB::raw('UUID_TO_BIN("' . $notification_id . '")');
        $res = EnImportNotifications::where('notification_id', $notification_id_bin)->first();
        if ($res) {
            $res->update(array('result' => json_encode($setdata), 'status' => 'y'));
            $res->save();
        }

        //return response()->json($data);
    }

    public function imcall(Request $request)
    {
        // apilog("*************IN ImCALL*********************");
        $im = new ImportAssetsService();
        $im->importasset($request, "75514b38-a4d4-11ea-88ef-0242ac110004");
    }

    /**
     * Function to return BV,Datacenter and location data
     * @author Shadab Khan
     * @access public
     * @package reports
     * @return json
     */
    public function getbvlocdatacenter($token)
    {
        $locationArr = $departmentArr = $businessVerticalArr = array();
        $iam_service_apiurl = config('enconfig.iam_service_apiurl');
        $token = 'encoded ' . $token;
        $options = ['token' => $token, 'form_params' => array('order_byregion' => true, 'order_bybu' => true)];
        $bvlocdcresponse = $this->remote_api->apicall("POST", $iam_service_apiurl, 'getdclocbvdata', $options);
        $response = _isset(_isset($bvlocdcresponse, 'content'), 'records');
        if ($response) {
            //============= Locations
            if (isset($response['loc']) && is_array($response['loc']) && !empty($response['loc'])) {
                $locationDetailsArr = $response['loc'];
                foreach ($locationDetailsArr as $lo) {
                    if ($lo['location_name'] != "") {
                        $lo['location_name'] = strtolower($lo['location_name']);
                    }
                    $locationArr[$lo['location_name']] = $lo['location_id'];
                }
            }
            //============= Business Vertical
            if (isset($response['bv']) && is_array($response['bv']) && !empty($response['bv'])) {
                $businessVerticalDetailsArr = $response['bv'];
                $bu_name = '';
                foreach ($businessVerticalDetailsArr as $bv) {
                    if ($bv['bv_name'] != "") {
                        $bv['bv_name'] = strtolower($bv['bv_name']);
                    }
                    $businessVerticalArr[$bv['bv_name']] = $bv['bv_id'];
                }
            }
            //=================Department
            if (isset($response['dep']) && is_array($response['dep']) && !empty($response['dep'])) {
                $departmentArr = $response['dep'];
                $department_name = '';
                foreach ($departmentArr as $dp) {
                    if ($dp['department_name'] != "") {
                        $dp['department_name'] = strtolower($dp['department_name']);
                    }
                    $departmentArr[$dp['department_name']] = $dp['department_id'];
                }
            }
            //=================Vendor DATA ===================================
            $result = Envendors::getvendors("", array());
            $vendordata = $result->isEmpty() ? null : $result;
            $vendordata = json_decode(json_encode($vendordata)); //it will return you stdclass object
            $vendordata = json_decode(json_encode($vendordata), true); //it will return you data in array
            $vendorarr = array();
            if (is_array($vendordata) && count($vendordata) > 0) {
                foreach ($vendordata as $vn) {
                    $vendorarr[$vn['vendor_name']] = $vn['vendor_id'];
                }
            }
        }
        return array('location' => $locationArr, 'businessvertical' => $businessVerticalArr, 'department' => $departmentArr, 'vendor' => $vendorarr);
    }

    public function getskuitemcount($sku)
    {

        try
        {
            if (!empty($sku) && !preg_match('/[^A-Za-z0-9]/', $sku)) {

                $items = DB::table('en_assets')
                    ->select('*')
                    ->where('asset_sku', $sku)
                    ->where('asset_status', 'in_store')
                    ->get()->count();

                $data['data'] = $items;
                $data['message']['success'] = 'Record fetch';
                $data['status'] = 'success';
                return response()->json($data);
            } else {
                $data['data'] = 'please provide valid sku code';
                $data['message']['error'] = 'sku not found';
                $data['status'] = 'error';
                return response()->json($data);

            }

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        }
    }
    public function getassetsbyskus(Request $request)
    {
        try
        {
            // $asset_skus = $request->input('asset_skus');
            // if (!empty($asset_skus)) {
                
                // $items = DB::select( DB::raw("SELECT asset_sku,count(asset_sku) as total_assets, bin_to_uuid(ci_templ_id) as ci_templ_id FROM `en_assets` 
                //     WHERE asset_status='in_store' and (asset_sku !='' or asset_sku != null) group by asset_sku") );
                
                $items = DB::select( DB::raw("(SELECT ea.asset_sku,esm.primary_category_name,count(ea.asset_sku) as total_assets, bin_to_uuid(ea.ci_templ_id) as ci_templ_id FROM en_assets ea, en_sku_mst esm 
                WHERE ea.asset_sku = esm.sku_code and ea.asset_status='in_store' and esm.primary_category_name!='Consumable' and (ea.asset_sku !='' or ea.asset_sku != null) group by ea.asset_sku) 
                UNION  
                (SELECT ea.asset_sku,esm.primary_category_name,ea.asset_qty as total_assets, bin_to_uuid(ea.ci_templ_id) as ci_templ_id FROM en_assets ea, en_sku_mst esm 
                WHERE ea.asset_sku = esm.sku_code and ea.asset_status='in_store' and esm.primary_category_name='Consumable' and (ea.asset_sku !='' or ea.asset_sku != null) group by ea.asset_sku)") );
                // $items = EnAssets::select(DB::raw('count(*) as total_assets'), 'asset_sku', DB::raw('bin_to_uuid(ci_templ_id) as ci_templ_id'))->where('asset_sku', $asset_skus)->where('asset_status', '=', 'in_store')->get();
                // if (!$items->isEmpty()) {
                    $data['data'] = $items;
                    $data['message']['success'] = 'Record fetch';
                    $data['status'] = 'success';

                // } else {
                //     throw new \Exception('SKU code not found');
                // }
                return response()->json($data);

            // } else {
            //     $data['data'] = 'please provide valid SKU code';
            //     $data['message']['error'] = 'SKU code not found';
            //     $data['status'] = 'error';
            //     return response()->json($data);

            // }

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        }
    }
    public function assetTracking(Request $request)
    {
        try
        {
            $searchkeyword = $request->input('searchkeyword');
            if (!empty($searchkeyword)) {
                $items = DB::table('en_asset_details')
                    ->select(DB::raw('BIN_TO_UUID(en_assets.asset_id) as asset_id'), DB::raw('BIN_TO_UUID(en_assets.ci_templ_id) as ci_templ_id'), 'asset_details', 'asset_sku', 'asset_tag', 'display_name')
                    ->join('en_assets', 'en_assets.asset_id', '=', 'en_asset_details.asset_id')
                    ->where(DB::raw('JSON_EXTRACT(asset_details, "$.serial_number")'), '=', $searchkeyword)
                // ->whereRaw('JSON_EXTRACT(asset_details, "$.serial_number") LIKE "%'.$searchkeyword.'%"')
                    ->get();
                if (!$items->isEmpty()) {
                    $data['data'] = $items;
                    $data['message']['success'] = 'Record fetch';
                    $data['status'] = 'success';

                } else {
                    throw new \Exception('Serial number not found');
                }
                return response()->json($data);

            } else {
                $data['data'] = 'please provide valid serial number';
                $data['message']['error'] = 'Serial number not found';
                $data['status'] = 'error';
                return response()->json($data);

            }

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        }
    }
    public function assetTrackingByEmpId(Request $request)
    {
        try
        {
            $employee_id = $request->input('employee_id');
            if (!empty($employee_id)) {
                $items = DB::table('en_assets_assign')
                    ->select(DB::raw('BIN_TO_UUID(en_assets.asset_id) as asset_id'), DB::raw('BIN_TO_UUID(en_assets.ci_templ_id) as ci_templ_id'), 'en_assets_assign.assign_date', 'en_assets_assign.status', 'en_assets_assign.return_date', 'en_assets_assign.created_at', 'en_assets.display_name', 'en_ci_requesternames.employee_id', DB::raw('concat(fname," ",lname) as full_name'))
                    ->join('en_assets', 'en_assets.asset_id', '=', 'en_assets_assign.asset_id')
                    ->join('en_ci_requesternames', 'en_ci_requesternames.requestername_id', '=', 'en_assets_assign.requestername_id')
                    ->where('en_ci_requesternames.employee_id', '=', $employee_id)
                    ->orderBy('en_assets_assign.created_at', 'DESC')
                    ->get();
                if (!$items->isEmpty()) {
                    $data['data'] = $items;
                    $data['message']['success'] = 'Record fetch';
                    $data['status'] = 'success';

                } else {
                    throw new \Exception('Employee id not found');
                }
                return response()->json($data);

            } else {
                $data['data'] = 'please provide valid employee id';
                $data['message']['error'] = 'Employee id not found';
                $data['status'] = 'error';
                return response()->json($data);

            }

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        }
    }

    private function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

} // Class End
