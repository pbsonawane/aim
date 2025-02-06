<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\EnSystemSettings;
use App\Services\IAM\IamService;

class Configsettings
{
    public function __construct($ci = true)
    {       
        return $this->set_config_values();        
    }
    public function set_config_values()
    {
     
        $systemsettings = EnSystemSettings::getSystemSetting();
        apilog("in Configsettings".json_encode($systemsettings));
		$systemsettings = $systemsettings->isEmpty() ? null : $systemsettings;
        if ($systemsettings)
        {
            foreach ($systemsettings as $arr)
            {
                $conf = json_decode($arr->configuration, true);
                foreach ($conf as $key => $value)
                {                                    
                    config(['app.'.$key =>  $value]);
                }
                
            }
        }
	}
}
?>