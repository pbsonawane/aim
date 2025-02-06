<?php
namespace App\Http\Controllers\test;
use App\Http\Controllers\test\ManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class DatacenterController extends ManageController
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        DB::connection()->enableQueryLog();
    }
/*
    *This is controller funtion used list datacenters

    * @author       Namrata Thakur
    * @access       public
    * @param        URL : dc_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_datacenters     
    */

/**
 * @SWG\Tag(
 *   name="DC",
 *   description="Datacenter",   
 * )
 *  @SWG\Get(
 *   tags={"DC"},
 *   path="/datacenters/{dc_id}",
 *   summary="Get DC",
 *   operationId="datacenters",
 *      @SWG\Parameter(
 *          name="dc_id",
 *          in="path",
 *          required=true,
 *          type="integer",
 *          description="DC",
 *      ), 
 *   @SWG\Response(response=200, description="Record Found",
 *       @SWG\Schema(ref="#/definitions/en_datacenters"),
 *   ),  
 *   @SWG\Response(response=400, description="No Record Found."),
 * )
 *
 */ 
    public function datacenters(Request $request)
    {
        $data['data'] = "adadadadadavdad";             
		$data['message']['error'] = '';
		$data['status'] = 'error'; 
		return response()->json($data);
    }
}// Class End