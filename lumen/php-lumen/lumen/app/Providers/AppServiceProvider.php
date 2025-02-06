<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Services\Configsettings;
use App\Services\eNsysconfig\Enconfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function boot()
    { 
        $configArr = (array) new Configsettings;                
        apilog(json_encode(config('app')));
        $itemsArr = (array) new Enconfig;  
        $items = isset($itemsArr['item']) ? $itemsArr['item'] : array();        
        if(count($items) > 0)
        {
            foreach($items as $k => $item)
            {
            //Config::set("enconfig.".$k, $item); //Laravel
                config(['enconfig.'.$k => $item]); //Lumen
            }                   
        }
        
        //echo  $culang = getLocale();
        /* REgular Expression For UUID" */
        Validator::extend('allow_uuid', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $value);
        });
        
        /* Regular Expression to allow only positive numbers */
        Validator::extend('allow_positive_numeric_only', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^[0-9]{1,}(?:\.[0-9]{1,})?$/", $value);
        },"Only positive numbers are allowed.");

        /* REgular Expression For UUID" */
        Validator::extend('allow_url', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value);
        });
        
        //Regular Expression : Allow only alphabets & space, But not before first word.
        Validator::extend('allow_alpha_space_only', function ($attribute, $value, $parameters, $validator)
        {

            return (bool) preg_match("/^[a-zA-Z][a-zA-Z ]*$/", $value);
            //return (bool) preg_match("/[\p{L}-]/u", $value);
        });

        /* REgular Expressio to allow alphabets, numbers ,dash, underscores & space */
        Validator::extend('allow_alpha_numeric_space_dash_underscore_only', function ($attribute, $value, $parameters, $validator)
        {
            //return (bool) preg_match( "/^[a-zA-Z][a-z0-9 -_]*$/", $value);
            //return (bool) preg_match( "/^[a-z][a-z0-9-_ ]*$/", $value);
            //return (bool) preg_match("/^[a-zA-Z][a-zA-Z0-9-_ ]*$/", $value);
            //return (bool) preg_match( "/^[a-zA-Z][a-z0-9 -_]*$/", $value);
            $curlang = app('translator')->getLocale();
            if ($curlang !="" && $curlang == "en")
            {
                return (bool) preg_match( "/^[a-zA-Z][a-zA-Z0-9- _]*$/", $value);
            }
            else
            {
                return (bool) preg_match("/[\p{L}-]/u", $value);
            }
        });

        /* REgular Expressio to allow alphabets, numbers ,dash, underscores & "NO Space"  here can start with alphabets only */
        Validator::extend('allow_alphal_numeric_dash_underscore_only', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^[a-zA-Z][a-zA-Z0-9-_]*$/", $value);
        });
        /* REgular Expressio to allow alphabets, numbers ,dash, underscores & "NO Space"  here can start with no/alphabets*/
        Validator::extend('start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-_]*$/", $value);
        });

        /* REgular Expressio to allow alphabets, numbers ,dash & "NO Space" */
        Validator::extend('allow_alphal_numeric_dash_only', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^[A-Za-z0-9-]*$/", $value);
        });

        /* */
        Validator::extend('regex', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/(^$|^\d+(,\d{1,2})?$)/", $value);
        });

        // Regular Expression : Dont allow any Scripts Tag
        Validator::extend('html_tags_not_allowed', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/<(.|\n)*?>/", $value) ? false : true;
        });

        // Regular Expression : Allow only Uppercase & Underscore only between words, Not before First word & after the Last Word
        Validator::extend('key_value', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^[A-Z]+(?:_[A-Z]+)*$/", $value);
        });

        // Regular Expression : 
        Validator::extend('comma_separated_allow', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^\d+(?:,\d+)*$/", $value);
        });

        /* IP Subnet Validation*/
        Validator::extend('ip_subnet', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/([0-9]|[1-2][0-9]|3[0-2]))?$/", $value);

        });

        /* IP Validation */
        Validator::extend('ip', function ($attribute, $value, $parameters, $validator)
        {
            return (bool) preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $value);

        });
        Validator::extend('asset_unique_field', function ($attribute, $value, $parameters, $validator)
        {
            $parameters[2] = isset($parameters[2]) ? $parameters[2] : '';
            // remove first parameter and assume it is the table name
            $valid_uuid = (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $parameters[2]);
            //$table = array_shift( $parameters );
            $query = DB::table('en_assets AS a')
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id')
                ->select('a.asset_id')
                ->where('a.status', '!=', 'd')
                ->where('ad.asset_details->'.trim($parameters[0]), '=', trim($parameters[1]));
            if (isset($parameters[2]) != "" && $valid_uuid === true)
            {
                $query->where('a.ci_templ_id', '=', DB::raw('UUID_TO_BIN("'.$parameters[2].'")'));
            }

            $data = $query->get();
            return count($data) ? false : true;
           
            //return $value == 'foo';
        }, 'The Asset Attributes value has already been taken.');

        Validator::extend('composite_unique', function ($attribute, $value, $parameters, $validator)
        {
            // remove first parameter and assume it is the table name
            $table         = array_shift($parameters);
            $parameters[3] = isset($parameters[3]) ? $parameters[3] : '';

            $valid_uuid    = (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $parameters[3]);

            $query = DB::table($table)
                ->select(trim($parameters[0]))
                ->where('status', '!=', 'd')
                ->where(trim($parameters[0]), '=', trim($parameters[1]));
            if (isset($parameters[2]) != "" && isset($parameters[3]) != "" && $valid_uuid === true)
            {
                $query->where(trim($parameters[2]), '!=', DB::raw('UUID_TO_BIN("'.$parameters[3].'")'));
            }

            $data = $query->get();
            return count($data) ? false : true;
        }, 'The name / key has already been taken.');
        // composite_unique without UUID for tables where autoincreament id is set
         Validator::extend('composite_unique_without_uuid', function ($attribute, $value, $parameters, $validator)
        {

            // remove first parameter and assume it is the table name
            $table = array_shift($parameters);
            $query = DB::table($table)
                ->select(trim($parameters[0]))
                ->where('status', '!=', 'd')
                ->where(trim($parameters[0]), '=', trim($parameters[1]));
            if (isset($parameters[2]) != "" && isset($parameters[3]) != "")
            {
                $query->where(trim($parameters[2]), '!=', $parameters[3] );
            }

            $data = $query->get();
            return count($data) ? false : true;
            //return $value == 'foo';
        }, 'The name / key has already been taken.');
        //
        Validator::extend('composite_unique_without_status', function ($attribute, $value, $parameters, $validator)
        {
            // remove first parameter and assume it is the table name
            $table         = array_shift($parameters);
            $parameters[3] = isset($parameters[3]) ? $parameters[3] : '';
            $valid_uuid = (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $parameters[3]);

            $query = DB::table($table)
                ->select(trim($parameters[0]))
                ->where(trim($parameters[0]), '=', trim($parameters[1]));
            if (isset($parameters[2]) != "" && isset($parameters[3]) != "" && $valid_uuid === true)
            {
                $query->where(trim($parameters[2]), '!=', DB::raw('UUID_TO_BIN("'.$parameters[3].'")'));
            }

            $data = $query->get();
            return count($data) ? false : true;
            //return $value == 'foo';
        }, 'The name / key has already been taken.');


        Validator::extend('password_policy', function ($attribute, $value, $parameters, $validator)
        {
            $resultpass = EnPasswordPolicy::validate_pass($value);

            if ($resultpass['error_cnt'] > 0)
            {
                foreach ($resultpass['error'] as $error)
                {
                    $validator->errors()->add('password', $error);

                }

                return false;
            }
            else
            {
                return true;
            }

        }, ' ');

        /*  Foreign key exists in Table */
        Validator::extend('foreign_key_exists', function ($attribute, $value, $parameters, $validator)
        {
            // remove first parameter and assume it is the table name
            $table          = array_shift($parameters);
            $parameters[1]  = isset($parameters[1]) ? $parameters[1] : '';
            $valid_uuid     = (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $parameters[1]);

            if (isset($parameters[1]) != "" && $valid_uuid === true)
            {
                $query = DB::table($table)
                    ->select(DB::raw('BIN_TO_UUID(trim('.$parameters[0].')) as '.$parameters[0]))
                    ->where('status', '!=', 'd')
                    ->where(trim($parameters[0]), '=', DB::raw('UUID_TO_BIN("'.$parameters[1].'")'));

                $data = $query->get();
                return count($data) ? true : false;
            }
            else
            {
                return false;
            }
            //return $value == 'foo';
        }, 'The id does not exists.');


    }

    public function register()
    {
        //
    }
}
