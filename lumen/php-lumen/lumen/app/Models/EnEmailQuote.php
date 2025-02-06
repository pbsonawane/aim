<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnEmailQuote extends Model
{
    /*  
    * This is model function is used get all departments data with its foregin key data

    * @author       Amit Khairnar
    * @access       public
    * @param        vendor_id
    * @param_type   Integer
    * @return       array
    * @tables       en_ci_vendors
    */

    use HasBinaryUuid;
    public $incrementing = false;
    
   protected $table = 'en_email_body_quotes';
    //public $timestamps = false;
    protected $fillable = [
        'quote_id','quotes' ];
    protected $primaryKey = 'quote_id';

    public function getKeyName()
    {
        return 'quote_id';
    }   

    protected function getemailquotes($quote_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_email_body_quotes')   
               ->select(DB::raw('BIN_TO_UUID(quote_id) AS quote_id'), 'quotes')
               ->orderBy('quote_id', 'desc');
               

                $query->where(function ($query) use ($searchkeyword, $quote_id){
                    $query->where(function ($query) use ($searchkeyword, $quote_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_email_body_quotes.quotes', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($quote_id, function ($query) use ($quote_id)
                        {
                            return $query->where('en_email_body_quotes.quote_id', '=', DB::raw('UUID_TO_BIN("'.$quote_id.'")'));
                        });});
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