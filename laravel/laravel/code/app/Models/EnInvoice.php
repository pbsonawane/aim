<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnInvoice extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_po_invoice';
   //	public $timestamps = false;	
    protected $fillable = [
        'invoice_id', 'po_id', 'details', 'status', 'created_by'
    ];
	
	protected $primaryKey = 'po_id';
	public function getKeyName()
    {
        return 'invoice_id';
    }
	
	protected function getinvoices($po_id, $invoice_id = "", $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_po_invoice')   
                ->select(DB::raw('BIN_TO_UUID(invoice_id) AS invoice_id'), DB::raw('BIN_TO_UUID(po_id) AS po_id'), DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'details', 'status')            
                ->where('en_po_invoice.status', '=', 'y');
				$query->where(function ($query) use ($invoice_id, $po_id){
                  $query->where(function ($query) use ($invoice_id, $po_id) {
					/*$query->when($searchkeyword, function ($query) use ($searchkeyword)
						{
							
							 return $query->where('en_po_invoice.status', 'like', '%' . $searchkeyword . '%');
	
						});       */
						$query->when($invoice_id, function ($query) use ($invoice_id)
						{
							return $query->where('en_po_invoice.invoice_id', '=', DB::raw('UUID_TO_BIN("'.$invoice_id.'")'));
						});
					}); 
					$query->when($po_id, function ($query) use ($po_id)
					{
						return $query->where('en_po_invoice.po_id', '=', DB::raw('UUID_TO_BIN("'.$po_id.'")'));
					});
					
				});
				
                $query->when(!$count, function ($query) use ($inputdata)
				{
					if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
					{
						return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
					}
				});
                $data = $query->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    	}


}