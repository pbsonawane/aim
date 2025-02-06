<?php	
/* Auther: Vishal chaudhari
 * Check Services running on devices
*/

include_once("config.php");
echo "\n======================== Check service status ===============================\n";
echo "\nCall:\ncheck_service.php <timeout> <debug>";
echo "\n\n 1. emrpe (Monitoring)\n\n";
$payload_len = 8192;
$time_start = slog_time();
$active_agent_logpath = $emsca_path."/var/spool/";
$timeout = isset($argv[1]) && $argv[1] != '' ? '-t '.$argv[1] : '';
$debug_msg = isset($argv[2]) ? true : false;
$module = "SYSLOG";
if(!module_check($module))
{
	echo "\n[!] ".$module." is not enabled\n";
	continue;	
}else
{
	$sql_param = "SELECT agent_id,agent_ip,os_type FROM ".$tbl_prefix."logmon_agents agents WHERE status = 'y' AND agent_type = 'agentbased'";
	$result_param = sqlexecute($sql_param);
	if (numrows($result_param) > 0)
	{
		$dataset = recordset($result_param);
		foreach ($dataset as $res_param)
		{	
			$emrpe = $emsca = 'n';
			$agent_id = $res_param['agent_id'];
			$agent_ip = $res_param['agent_ip'];
			$os_type = $res_param['os_type'];
			
			$script_file = '';
			
			// check emrpe enanlbes
			if($os_type == 'windows')
				$script_file = $emsca_path."bin/check_nrpe -2 -P ".$payload_len." ";
			else if($os_type == 'ssh')
				$script_file = $emsca_path."bin/check_emrpe";
			
			$cmd = $script_file." -H '".$agent_ip."' ".$timeout;
				
			if($debug_msg)
				echo "\n\nemrpe command".$cmd;
			$output = exec_command($cmd);
			$output = trim($output,"\n");
			if($debug_msg)
				echo "\n\nCommand output ======== ".$output;
			
			if ((strpos($output, "seem to be doing fine") !== false || strpos($output, "EMRPE") !== false ) && strpos($output, "CHECK_EMRPE") === false ) 
				$emrpe = 'y';
			else
				$emrpe = 'n';
			
			if($debug_msg)
				echo "\n\nemrpe status ============= ".$emrpe;
			
			$filename = $emsca_path."/var/spool/active".$agent_ip;
			$output1 = '';
			if(file_exists($filename))
			{
				$cmd = "find ".$filename." -cmin -30";
				$output = exec_command($cmd);
				if($debug_msg)
					echo "\n\nEmsca command => ".$cmd;
				$output1 = trim($output);
			}
			if($emrpe == 'y')
			{
				$cmd = $script_file." -H '".$agent_ip."' ".$timeout." -c emscacontrol -a status";
				$output = exec_command($cmd);
				if($debug_msg)
				echo "\n\nemsca control command".$cmd." output=>".$output;
				if (strpos($output, "stopped") !== false) 
					$emscastatus = 'n';
				else if (strpos($output, "running") !== false) 
					$emscastatus = 'y';
				if($debug_msg)
                                echo "\n\nemsca status => after check  output=>".$emscastatus;
			}	
			else	
				$emscastatus = 'y';
			
			if($debug_msg)
                                echo "\n\noutput file check ".$output1." ===  ".$filename;

			if($output1 == $filename && $emscastatus == "y")
				$emsca = 'y';
			
			if($debug_msg)
				echo "\n\nemsca status ============= ".$emsca;
			

			$sql_upd = "update ".$tbl_prefix."logmon_agents SET emrpe = '".$emrpe."', emsca = '".$emsca."' WHERE agent_id = '".$agent_id."'";
			if($debug_msg)
				echo "\n\nupdate db query => ".$sql_upd;
			$up_emrpe = sqlexecute($sql_upd);
		}	
	}	
}

?>

