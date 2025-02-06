<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnEmailTemplate extends Model
{
    /*  
    * This is model function is used get all email template data

    * @author       Snehal C
    * @access       public
    * @param        template_id
    * @param_type   Integer
    * @return       array
    * @tables       en_email_template
    */

    use HasBinaryUuid;
    public $incrementing = false;
    
   protected $table = 'en_email_template';
    //public $timestamps = false;
    protected $fillable = [
        'template_id', 'template_name', 'template_key', 'template_category', 'configure_email_id', 'email_ids','subject','email_body','status'];
    protected $primaryKey = 'template_id';

    public function getKeyName()
    {
        return 'template_id';
    }   

    /* This function is used to get the  email templates
    * @author       Snehal C
    * @access       protected
    * @param        template_id, inputdata, count
    * @param_type   Integer
    * @return       Array
    * @tables       en_email_template
    */
    protected function getemailtemplates($template_id, $inputdata=[], $count=false)
    {
        apilog('---send mail module');
        apilog(json_encode($inputdata));
        $searchkeyword = _isset($inputdata,'searchkeyword');
        $advtemplate_category = _isset($inputdata, "advtemplate_category");
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_email_template')   
                ->select(DB::raw('BIN_TO_UUID(template_id) AS template_id'), 'template_name','template_key', 'template_category', 'configure_email_id','email_ids','subject','email_body', 'status')             
                ->where('en_email_template.status', '!=', '');

                $query->when($advtemplate_category, function ($query) use ($advtemplate_category)
                {
                    return $query->where('template_category', $advtemplate_category);       
                });
                
                $query->where(function ($query) use ($searchkeyword, $template_id){
                    $query->where(function ($query) use ($searchkeyword, $template_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_email_template.template_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.template_key', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.template_category', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.email_ids', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.subject', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.email_body', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($template_id, function ($query) use ($template_id)
                        {
                            
							 return $query->where('en_email_template.template_id', '=', DB::raw('UUID_TO_BIN("'.$template_id.'")'));
                        });});
						
                            
                       
						
                $query->when(!$count, function ($query) use ($inputdata)
                        {
							if(isset($inputdata['template_key']) && $inputdata['template_key'] != ""){
								
								return $query->where('en_email_template.template_key', '=', $inputdata["template_key"]);
							}
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

    /* This function is used to get the  email template categories
    * @author       Snehal C
    * @access       public
    * @param        template_id, inputdata, count
    * @param_type   Integer
    * @return       Array
    * @tables       en_email_template
    */

    protected function getemailtemplatescategory($template_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_email_template')   
                ->select(DB::raw('DISTINCT template_category'))           
                ->where('en_email_template.status', '!=', '');

                $query->where(function ($query) use ($searchkeyword, $template_id){
                    $query->where(function ($query) use ($searchkeyword, $template_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_email_template.template_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.template_key', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.template_category', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.email_ids', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_email_template.email_body', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($template_id, function ($query) use ($template_id)
                        {
                            return $query->where('en_email_template.template_id', '=', DB::raw('UUID_TO_BIN("'.$template_id.'")'));
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

    /* This function is used to delete the email template
    * @author       Snehal C
    * @access       public
    * @param        template_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_email_template
    */

    protected function checkforrelation($template_id)
    {
        if($template_id)
        {

            DB::table('en_email_template')->where('template_id', DB::raw('UUID_TO_BIN("'.$template_id.'")'))->delete();                
            $data['data']['deleted_id'] = $template_id;
            $data['message']['success']= showmessage('118', ['{name}'], ['Template']);
            $data['status'] = 'success';
               
                         
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Template']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   

     /* This function is used to delete the email template
    * @author       Snehal C
    * @access       public
    * @param        template_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_email_template
    */

    protected function changetemplatestatus($template_id, $status)
    {
        if($template_id)
        {

            DB::table('en_email_template')
            ->where('template_id', DB::raw('UUID_TO_BIN("'.$template_id.'")'))
            ->update(['status' => $status]);

            if($status == 'e'){
                $status_name = 'Template Enabled';
            }else{
                $status_name = 'Template Disabled';
            }
           //DB::table('en_email_template')->where('template_id', DB::raw('UUID_TO_BIN("'.$template_id.'")'))->delete();                
            $data['data']['deleted_id'] = $template_id;
            $data['message']['success']= showmessage('140', ['{name}'], [$status_name]);
            $data['status'] = 'success';
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('105', ['{name}'], ['Status']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   

}