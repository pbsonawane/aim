<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnRelationshipType;
use App\Models\EnAssetHistory;
use Validator;

class RelationshipTypeController extends Controller
{
    
    /**
     * Create a new controller instance.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @return void
     */
    public function __construct()
    {
         DB::connection()->enableQueryLog();     
    }

    /**
     * This controller function is implemented to get list of Relationship Type.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param int $rel_type_id
     * @return json
     * @tables       en_Relationship_type
     */
    public function relationshiptypes(Request $request,$rel_type_id = null)
    {
        try
        {
          $request['rel_type_id'] = $rel_type_id;
          $validator              = Validator::make($request->all(), [
              'rel_type_id'=> 'nullable|allow_uuid|string|size:36'
          ]);
           if($validator->fails())
          {
              $error                    = $validator->errors(); 
              $data['data']             = null;
              $data['message']['error'] = $error;
              $data['status']           = 'error';
              return response()->json($data); 
          }
          else
          {        
               
              $inputdata                  = $request->all();
              $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
              $totalrecords   = EnRelationshipType::getrelationshiptype($rel_type_id,$inputdata, true);  
              $result         = EnRelationshipType::getrelationshiptype($rel_type_id, $inputdata , false);  
              
              $data['data']['records']      = $result->isEmpty() ? NULL : $result;
              $data['data']['totalrecords'] = $totalrecords;                
             
              if ($totalrecords < 0)
              {
                  $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_relationshiptype')));
                  $data['status']           = 'error';
              }
              else
              {
                  $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_relationshiptype')));
                  $data['status']             = 'success';
              }
              return response()->json($data);
          }
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("relationshiptypes","This controller function is implemented to get Relationship types.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("relationshiptypes","This controller function is implemented to get Relationship types.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion used to add the relationship type.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param int $rel_type_id
     * @param string $rel_type
     * @param string $inverse_rel_type
     * @param string $description
     * @return json
     * @tables en_relationship_type
     */
    public function relationshiptypeadd(Request $request) 
    {
        try
        {

          $messages = [
            'rel_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
            'rel_type.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
            'rel_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
            'inverse_rel_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_inverserelationtype')), true),
            'inverse_rel_type.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_inverserelationtype')), true),
            'inverse_rel_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_inverserelationtype')), true),
            'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_relationshipdesc')), true),
            'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_relationshipdesc')), true),
            ];

          $validator = Validator::make($request->all(), [
			       'rel_type_id'=> 'nullable|allow_uuid|string|size:36',
              'rel_type'          => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_relationship_type, rel_type, '.$request->input('rel_type'),  
              'inverse_rel_type'  => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_relationship_type, inverse_rel_type, '.$request->input('inverse_rel_type'),  
              'description'       => 'required|html_tags_not_allowed' ,  
          ],$messages);          
           if ($validator->fails())
          {
              $error          = $validator->errors();
              $data['data']   = null;
              $data['message']['error'] = $error;
              $data['status'] = 'error';
          }
          else
          { 
              $relationshiptype_data = EnRelationshipType::create($request->all());  
              if(!empty($relationshiptype_data['rel_type_id']))
              {
                  $rel_type_id                = $relationshiptype_data->relationship_type_text;
                  $data['data']['insert_id']  = $rel_type_id;
                  $data['message']['success'] = showmessage('104', array('{name}'),array(trans('label.lbl_relationshiptype')));
                  $data['status']             = 'success';
              }
              else
              {
                  $data['data']   = NULL;
                  $data['message']['error'] = showmessage('103', array('{name}'),array(trans('label.lbl_relationshiptype')));
                  $data['status'] = 'error';
              }
          }
          return response()->json($data);
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("relationshiptypeadd","This controller function is implemented to add the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("relationshiptypeadd","This controller function is implemented to add the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion Provides a window to user to update the relationship information.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param int $relationship_type_id
     * @return json
     * @tables en_relationship_type
     */
    public function relationshiptypeedit(Request $request)
    {
        try
        {
          //$request['rel_type_id'] = $rel_type_id;
          $validator = Validator::make($request->all(), [ 
              'rel_type_id' => 'required|allow_uuid|string|size:36'
              ]);
          if ($validator->fails())
          {
              $error          = $validator->errors();
              $data['data']   = null;
              $data['message']['error'] = $error;
              $data['status'] = 'error';
          }
          else
          {    
              $result       = EnrelationshipType::getrelationshiptype($request->input('rel_type_id'));  
              $data['data'] = $result->isEmpty() ? NULL : $result;
              
            
              if($data['data'])
              {
                $data['message']['success'] = showmessage('102', array('{name}'),array(trans('label.lbl_relationshiptype')));
                $data['status'] = 'success';            
              }
              else
              {
                 
                $data['message']['error'] = showmessage('101', array('{name}'),array(trans('label.lbl_relationshiptype')));
                $data['status'] = 'error';          
              }
          }
          return response()->json($data);
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("relationshiptypeedit","This controller function is implemented to edit the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("relationshiptypeedit","This controller function is implemented to edit the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }  

    /**
     * This is controller funtion Updates the relationship information, which is entered by user on Edit relationship window.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param int $relationship_type_id
     * @param string $relationship_type
     * @param string $relationship_description
     * @return json
     * @tables en_relationship_type
     */
    public function relationshiptypeupdate(Request $request)
    {
        try
        {

           $messages = [
            'rel_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
            'rel_type.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
            'rel_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
            'inverse_rel_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_inverserelationtype')), true),
            'inverse_rel_type.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_inverserelationtype')), true),
            'inverse_rel_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_inverserelationtype')), true),
            'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_relationshipdesc')), true),
            'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_relationshipdesc')), true),
            ];

           $validator = Validator::make($request->all(), [  
		    'rel_type_id' => 'required|allow_uuid|string|size:36',
              'rel_type'         => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_relationship_type, rel_type, '.$request->input('rel_type').', rel_type_id,'.$request->input('rel_type_id'),  
              'inverse_rel_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_relationship_type, inverse_rel_type, '.$request->input('inverse_rel_type').', rel_type_id,'.$request->input('rel_type_id'),
              'description'      => 'required|html_tags_not_allowed' ,  
          ],$messages);          
          if ($validator->fails())
          {
              $error          = $validator->errors();
              $data['data']   = null;
              $data['message']['error'] = $error;
              $data['status'] = 'error';
          }
          else
          { 
              $relationship_type_id_uuid = $request->input('rel_type_id');
              $relationship_type_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('rel_type_id').'")');
              $request['rel_type_id']    = DB::raw('UUID_TO_BIN("'.$request->input('rel_type_id').'")');
              $result                    = EnrelationshipType::where('rel_type_id', $relationship_type_id_bin)->first();
          
              if($result)
              {
                  $result->update($request->all());            
                  $result->save();             
                  $data['data']   = NULL;     
                  $data['message']['success'] = showmessage('106', array('{name}'),array(trans('label.lbl_relationshiptype')));      
                  $data['status'] = 'success'; 
      
              }
              else
              {             
                  $data['data']   = NULL;             
                  $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_relationshiptype')));     
                  $data['status'] = 'error'; 
              } 
          }  
          return response()->json($data); 
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("relationshiptypeupdate","This controller function is implemented to update the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("relationshiptypeupdate","This controller function is implemented to update the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion is used to delete a relationship type.
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param int $rel_type_id
     * @return json
     * @tables en_relationship_type
     */
    public function relationshiptypedelete(Request $request)
    {
        try
        {
          $messages = [
              'rel_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_relationshiptype')), true),
          ];
         
          $validator = Validator::make($request->all(), [
              'rel_type_id' => 'required|allow_uuid|string|size:36',
          ], $messages);
          if ($validator->fails())
          {
              $error          = $validator->errors();
              $data['data']   = null;
              $data['message']['error'] = $error;
              $data['status'] = 'error';
              return response()->json($data);
          }
          else
          {
            $relationship_type_id_uuid = $request->input('rel_type_id');
            $relationship_type_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('rel_type_id').'")');
            $request['rel_type_id']    = DB::raw('UUID_TO_BIN("'.$request->input('rel_type_id').'")');
            $result                    = EnrelationshipType::where('rel_type_id', $relationship_type_id_bin)->first();
            
            $count_reltype = DB::table('en_asset_relationship')->where('rel_type_id',$relationship_type_id_bin)->count();

            //check if relationshiptype id record exists in 'en_asset_relationship' table, if exists then can not delete relative record.
            if($count_reltype > 0){
              $data['data']   = NULL;             
              $data['message']['error'] = showmessage('121', array('{name}'),array(trans('label.lbl_assetrelationship')));
              $data['status'] = 'error';
              return response()->json($data);
            }

            if($result)
            {
                $result->update(['status' => 'd']);
                $result->save();             
                $data['data']   = NULL;     
                $data['message']['success'] = showmessage('118', array('{name}'),array(trans('label.lbl_relationshiptype')));      
                $data['status'] = 'success'; 
    
            }
            else
            {             
                $data['data']   = NULL;             
                $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_relationshiptype')));     
                $data['status'] = 'error'; 
            }
            return response()->json($data);
          }
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("relationshiptypedelete","This controller function is implemented to delete the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("relationshiptypedelete","This controller function is implemented to delete the relationship type.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    /**
    * This is controller funtion used to get asset relationship data
    * @author       Darshan Chaure
    * @access       public
    * @param        string asset_id
    * @return       JSON
    * @tables       en_asset_relationship
    */
    public function get_asset_relationship(Request $request)
    {
      try
      {
        $asset_id = $request->input('asset_id');
        $asset_id = isset($asset_id) ? $asset_id : '';
        $result   = EnRelationshipType::get_asset_relationship($asset_id);

       
        $result   = $result->isEmpty() ? NULL : $result;

        if (!is_null($result))
        {
            $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_assetrelationship')));
            $data['status']             = 'success';
        }
        else
        {
            $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_assetrelationship')));
            $data['status']           = 'error';
        }

        $data['data'] = $result;
        return response()->json($data);
      }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("get_asset_relationship","This controller function is implemented to get asset relationship data.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            save_errlog("get_asset_relationship","This controller function is implemented to get asset relationship data.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    
    /**
    * This is controller funtion used to delete asset relationship data
    * @author       Darshan Chaure
    * @access       public
    * @param        string asset_relationship_id
    * @param_type   uuid
    * @return       JSON
    * @tables       en_asset_relationship
    */
    public function deleteassetrelationship(Request $request)
    {
        try
        {
          $messages = [
              'asset_relationship_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_assetrelationship')), true),
          ];
         
          $validator = Validator::make($request->all(), [
              'asset_relationship_id' => 'required|allow_uuid|string|size:36',
          ], $messages);
          if ($validator->fails())
          {
              $error          = $validator->errors();
              $data['data']   = null;
              $data['message']['error'] = $error;
              $data['status'] = 'error';
              return response()->json($data);
          }
          else
          {
            $asset_relationship_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('asset_relationship_id').'")');
            $asset_id_bin              = DB::raw('UUID_TO_BIN("'.$request->input('asset_id').'")');
            $request['asset_relationship_id'] = DB::raw('UUID_TO_BIN("'.$request->input('asset_relationship_id').'")');

            $result = DB::table('en_asset_relationship')->where('asset_relationship_id', $asset_relationship_id_bin)->first();

            if($result)
            {
                // $result->update(['status' => 'd']);
                // $result->save();
                $result         = DB::table('en_asset_relationship')
                                  ->where('asset_relationship_id', $asset_relationship_id_bin)
                                  ->update(['status' => 'd']);
                $data['data']   = NULL;     
                $data['message']['success'] = showmessage('118', array('{name}'),array(trans('label.lbl_assetrelationship')));      
                $data['status'] = 'success';

                //Add Asset History    
                $history_data = array();

                if($request->input('loggedinuserid')){
                  $userid = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
                }else{
                  $userid = null;
                }
                
                $history_data['user_id']  = $userid;
                $history_data['asset_id'] = $asset_id_bin;
                $history_data['action']   = config('enconfig.action_delete');
                $history_data['message']  = showmessage('msg_assetrel_deleted', array('{reltype}','{parent}','{child}'), array($request->input('rel_type'),$request->input('parent_asset_name'),$request->input('child_asset_name')), true);
                EnAssetHistory::create($history_data);
            }
            else
            {             
                $data['data']   = NULL;             
                $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_assetrelationship')));     
                $data['status'] = 'error'; 
            }
            return response()->json($data);
          }
        }
        catch(\Exception $e){
            $data['data']               = null;
            $data['message']['error']   = $e->getMessage();
            $data['status']             = 'error';
            save_errlog("deleteassetrelationship","This controller function is implemented to delete asset relationship.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
        catch(\Error $e){
            $data['data']   = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("deleteassetrelationship","This controller function is implemented to delete asset relationship.",$request->all(),$e->getMessage());
            return response()->json($data);
        }
    }

    /**
    * This is controller funtion used to save asset relationship data
    * @author       Darshan Chaure
    * @access       public
    * @param        asset_id relationshiptype_id child_asset_id
    * @param_type   uuid
    * @return       JSON
    * @tables       en_asset_relationship
    */
    public function addassetrelationship(Request $request)
    {
        try
        {

          if($request->input('ci_templ_id') == 'USER'){

            $err_msg_asset = showmessage('000', array('{name}'), array(trans('label.lbl_user')), true);
          }else{
            $err_msg_asset  = showmessage('000', array('{name}'), array(trans('label.lbl_asset')), true);
          }
          $messages = [
              'asset_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_asset')), true),
              'relationship_type_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_relationship')), true),
              'child_asset_id.required' => $err_msg_asset,
              'ci_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_ci')), true),
          ];
          $validator = Validator::make($request->all(), [
              'asset_id'             => 'required|string|size:36',
              'relationship_type_id' => 'required|string|size:36',
              'child_asset_id'       => 'required|string|size:36',
              'ci_templ_id'          => 'required',
          ], $messages);
          if ($validator->fails())
          {
              $error          = $validator->errors();
              $data['data']   = null;
              $data['message']['error'] = $error;
              $data['status'] = 'error';
              return response()->json($data);
          }
          else
          {
            $uuid_id_bin        = DB::raw('UUID_TO_BIN(UUID())');
            $asset_id_bin       = DB::raw('UUID_TO_BIN("'.$request->input('asset_id').'")');
            $rel_type_id_bin    = DB::raw('UUID_TO_BIN("'.$request->input('relationship_type_id').'")');
            $child_asset_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('child_asset_id').'")');
            $ci_templ_id = $request->input('ci_templ_id');            
            $response           = array();

            $data = DB::table('en_asset_relationship')->insert(
                ['asset_relationship_id' => $uuid_id_bin, 'parent_asset_id' => $asset_id_bin, 'child_asset_id' => $child_asset_id_bin, 'rel_type_id' => $rel_type_id_bin, 'ci_templ_id' => $ci_templ_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );

            if($data){
              $response['data']               = null;
              $response['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_assetrelationship')), true);
              $response['status']             = 'success';

              //Add Asset History    
              $history_data = array();

              if($request->input('loggedinuserid')){
                $userid = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
              }else{
                $userid = null;
              }
              
              $history_data['user_id']  = $userid;
              $history_data['asset_id'] = $asset_id_bin;
              $history_data['action']   = config('enconfig.action_create');
              $history_data['message']  = showmessage('msg_assetrel_created', array('{reltype}','{parent}','{child}'), array($request->input('relationship_type_name'),$request->input('parent_asset_name'),$request->input('child_asset_name')), true);
              EnAssetHistory::create($history_data);
            }else{
              $response['data']               = null;
              $response['message']['error']   = showmessage('103', array('{name}'), array(trans('label.lbl_assetrelationship')), true);
              $response['status']             = 'error';
            }
            return response()->json($response);
          }
        }
        catch(\Exception $e){
            $response                       = array();
            $response['data']               = null;
            $response['message']['error']   = $e->getMessage();
            $response['status']             = 'error';
            save_errlog("addassetrelationship","This controller function is implemented to save asset relationship.",$request->all(),$e->getMessage());
            return response()->json($response);
        }
        catch(\Error $e){
            $response           = array();
            $response['data']   = null;
            $response['message']['error'] = $e->getMessage();
            $response['status'] = 'error';
            save_errlog("addassetrelationship","This controller function is implemented to save asset relationship.",$request->all(),$e->getMessage());
            return response()->json($response);
        }
    }
}