<?php
function getIndex($indexname,$monitoring,$fromtime,$totime="",$format=true)
{
	$array_of_dates = array();
	$df = "dmY";
	if($monitoring == "today")
		$array_of_dates[] = $indexname.date($df);
	else if($monitoring == "yesterday")
		$array_of_dates[] = $indexname.date($df,$fromtime);
	else //if($monitoring == "week_to_date" || $monitoring == "month_to_date")
	{
		$first_date =  date("Y-m-d",$fromtime);
		$your_date = strtotime($first_date);
		$count = 0;
		if($monitoring == "regular")
			$today = strtotime(date("Y-m-d",$totime));
		else
			$today = strtotime(date("Y-m-d"));
		if($your_date < $today)
		{
			$datediff = $today - $your_date;
			$count =  floor($datediff / (60 * 60 * 24));
		}
		$array_of_dates[] = $indexname.date($df,strtotime($first_date));
		for($i=1;$i <= $count; $i++)
		{
			$array_of_dates[] = $indexname.date($df,strtotime("+".$i." day", strtotime($first_date)));
		}
	}
	/*else if($monitoring == "year_to_date")
	{

	}
	else if($monitoring == "yesterday")
	{

	}
	else if($monitoring == "last_10_min")
	{}
	else if($monitoring == "last_30_min")
	{}
	else if($monitoring == "last_1_hour")
	{}
	else if($monitoring == "last_6_hour")
	{}
	else if($monitoring == "last_24_hour")
	{}
	else if($monitoring == "last_7_days")
	{}
	else if($monitoring == "last_15_days")
	{}
	else if($monitoring == "last_30_days")
	{}	*/
	$array_of_dates = implode(",",$array_of_dates);
	return $array_of_dates;
}
function deviceName($system_settings, $data)
{
	$name_tags = $system_settings['device_name'];
	extract($data);
	$final_tag_array = array();
	$device_name_1 = $device_name_2 = $device_name = "";
	$map_tag = array("title" => "title", "additional title" => "add_title", "hostname" => "description");
	$tag_array = explode(',',trim($name_tags));

	$device_name_1 = $data[0][$map_tag[$tag_array[0]]];
	$device_name_2 = $data[0][$map_tag[$tag_array[1]]];
	if($final_tag_array[0] != '')
	$device_name_1 = $data[0][$final_tag_array[0]];
	if($final_tag_array[1] != '')
	$device_name_2 = $data[0][$final_tag_array[1]];

	if (strlen($device_name_1) >= 20)
	$device_name_1 = substr($device_name_1,0,20).'...';
	if (strlen($device_name_2) >= 20)
	$device_name_2 = substr($device_name_2,0,20).'...';

	if($device_name_1 != '')
	$device_name = $device_name_1;
	if($device_name_2 != '')
	{
		if($html == 'yes')
		{
			$device_name .= '<br>';
			if($plain == '')
			$device_name .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$device_name .= '<font color="#FF0000">['.trim($device_name_2).']</font>';
		}
		else
		$device_name .= ' ['.trim($device_name_2).']';
	}
	if($device_name == '')
	$device_name = $data[0]['title'];
	return $device_name;

}

$sshconn = $conn = false;
function php_ssh_exec_cmd($cmd,$device_details = array(),$pre="")
{
	global $sshconn,$conn;
	$output = array();
	$output['error'] = '';
	$output['output'] = '';
	if(is_array($device_details) && count($device_details) > 0)
	{
		$probe_ip = $device_details['ip'];
		$key_login = $device_details['key_login'];
		$key_path = $device_details['key_path'];
		if($key_login == 'n')
		{
			$port = $device_details['port'];
			if($port == '')
				$port = 22;
			$username = $device_details['username'];
			$password = $device_details['password'];

			if(!$sshconn)
			{
				$sshconn = @ssh2_connect($probe_ip, $port);
				$conn = @ssh2_auth_password($sshconn, $username,$password);
			}
			if(!$conn)
			{
				$output['error'] =  "#fail: unable to establish connection";
				return $output;
			}
			$stream = ssh2_exec($sshconn, $cmd);
			$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
			// Enable blocking for both streams
			stream_set_blocking($stream, true);
			stream_set_blocking($errorStream, true);
			// Whichever of the two below commands is listed first will receive its appropriate output.  The second command receives nothing
			$result = stream_get_contents($stream);
			$error_result = stream_get_contents($errorStream);
			// Close the streams
			fclose($errorStream);
			fclose($stream);
			if($result != '')
				$output['output'] = $result;
			else
				$output['error'] = $error_result;
			return $output;
		}
		else
		{
			if(!$sshconn)
			{
				$sshconn = @ssh2_connect($probe_ip, $port,array('hostkey' => 'ssh-rsa'));
				$conn = @ssh2_auth_pubkey_file($sshconn,$username,$key_path);
			}
			if(!$conn)
			{
				$output['error'] =  "#fail: unable to establish connection";
				return $output;
			}
			$stream = ssh2_exec($sshconn, $cmd);
			$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
			// Enable blocking for both streams
			stream_set_blocking($stream, true);
			stream_set_blocking($errorStream, true);
			// Whichever of the two below commands is listed first will receive its appropriate output.  The second command receives nothing
			$result = stream_get_contents($stream);
			$error_result = stream_get_contents($errorStream);
			// Close the streams
			fclose($errorStream);
			fclose($stream);

			if($error_result != '')
				$output['error'] = $error_result;
			else
				$output['output'] = $result;
		}
	}
	else
	{
		$output['error'] =  "#fail: Required details are empty!";
	}
	return $output;
}

function secondstowords($seconds)
{
	$ret = "";
	/*             * * get the days ** */
	$days = intval(intval($seconds) / (3600 * 24));
	if ($days > 0)
	{
		$ret .= "$days days ";
	}
	/*             * * get the hours ** */
	$hours = (intval($seconds) / 3600) % 24;
	if ($hours > 0)
	{
		$ret .= "$hours hours ";
	}
	/*             * * get the minutes ** */
	$minutes = (intval($seconds) / 60) % 60;
	if ($minutes > 0)
	{
		$ret .= "$minutes minutes ";
	}
	return $ret;
}
function roundval($val,$precision="2")
{
	return $val != '' ? round($val,$precision) : $val;
}
function _isset($data, $index="",$default="")
{
	if(is_array($data) && isset($data[$index]))
	{
		if(is_array($data[$index]))
			return $data[$index];
		if(trim($data[$index]) != '')
			return $data[$index];
		else if(trim($data[$index]) == '' && trim($default) != '')
			return $default;
		return 	'';
    }
    else
    {
        return $default;
    }
}
function gettimefromsec($seconds)
{
	$time = '';
	if($seconds != '')
	{
		$time = time() - $seconds;
		$time = date("M j, Y, g:i a", $time);
	}
	return $time;
}
function processGroupName($group_name)
{
	return strtolower($group_name);
}
function severity_color($index_by_id="no")
{
	$severity_color = array(
							"attention" => array("id"=> 2, "name" => "attention","title" => "Attention", "thr_value" => 60, "class" => "severiy-attention", "color" => "#3bafda", "bgclass" => "progress-bar-info"),
							"trouble" => array("id"=> 3, "name" => "trouble","title" => "Trouble", "thr_value" => 70, "class" => "severiy-trouble", "color" => "#eda107", "bgclass" => "progress-bar-warning "),
							"critical" => array("id"=> 4, "name" => "critical","title" => "Critical", "thr_value" => 85, "class" => "severiy-critical", "color" => "#e9573f", "bgclass" => "progress-bar-danger"),
							"normal" => array("id"=> 1, "name" => "normal","title" => "Normal", "thr_value" => 999999, "class" => "severiy-normal", "color" => "#70ca63", "bgclass" => "progress-bar-success"));
	if($index_by_id == "yes")
	{
		$severity_color = keytoarray($severity_color,'id');
	}
	return $severity_color;
}
function trimlength($string, $len=30)
{
	if (strlen($string) >= $len)
		$string = substr($string,0,$len).'...';
	return $string;
}
function timefilter($tffilter)
{
	extract($tffilter);
	if($is_zoom == "yes")
	{
		$tf_start = $tf_start / 1000;
		$tf_end = $tf_end / 1000;
		$from_time = explode(".",$tf_start)[0];
		$to_time = explode(".",$tf_end)[0];
	}
	else if($from_time == '' && $to_time == '' || $tmoption != "custom")
	{
		$tmoption = $tmoption == "" || $tmoption == "custom" ? "last_1_hour" : $tmoption;
		if($tmoption == "today_9_to_6")
		{
			$to_time = strtotime(date('Y-m-d 18:00'));
			$from_time = strtotime(date('Y-m-d 09:00'));
		}
		else
		{
			$Dates = getDates();
			if(is_array($Dates[$tmoption]))
			{
				$from_time = strtotime($Dates[$tmoption]['start']);
				$to_time = strtotime($Dates[$tmoption]['end']);
			}
			else
			{
				$from_time = strtotime($Dates[$tmoption]);
				$to_time = strtotime(date('Y-m-d H:i'));
			}
		}
	}
	return $tffilter = array("tmoption" => $tmoption, "from_time" => $from_time, "to_time" => $to_time, "is_zoom" => $is_zoom);
}
function getzoominterval($count)	//$count save days count. number of days in selected date range
{
	if($count <= 1)
		$esinterval = getIntervals("last_1_hour");
	else if($count == 2)
		$esinterval = getIntervals("last_1_hour");
	else if($count >= 3 && $count <= 7)
		$esinterval = getIntervals("last_7_days");
	else if($count >= 8 && $count <= 31)
		$esinterval = getIntervals("last_7_days");
	else if($count >= 32 && $count <= 62)
		$esinterval = getIntervals("zoom_two_month");
	else if($count >= 63 && $count <= 94)
		$esinterval = getIntervals("zoom_three_month");
	else if($count >= 95 && $count <= 186)
		$esinterval = getIntervals("zoom_six_month");
	else if($count >= 187)
		$esinterval = getIntervals("zoom_one_year");
	else
		$esinterval = getIntervals("last_7_days");
	return $esinterval;
}
function iszoomable($esinterval)
{
	if($esinterval == "30s" || $esinterval == "1m")
		return "false";
	else
		return "true";
}
function convertmstosec($timeinms = "")
{
	return $timeinms = $timeinms != '' ? explode(".",$timeinms / 1000)[0] : '';
}

function barcolorbs($value)
{
	$bar_percent_color = array(1 => 'progress-bar-success', 25 => 'progress-bar-moderate', 55 => 'progress-bar-warning', 85 => 'progress-bar-danger');
	if ($value < 25)
		return $bar_percent_color[1];
	elseif ($value >= 25 && $value < 55)
		return $bar_percent_color[25];
	elseif ($value >= 55 && $value < 85)
		return $bar_percent_color[55];
	if ($value >= 85)
		return $bar_percent_color[85];
}
function percentbarbs($value,$unit="%")
{
	$bar = '';
	if ($unit == '%')
	{
		if ($value == '')
			$value = 0;
		$class = barcolorbs($value);
		if ($value > 100)
		{
			$value = 100;
			$dp_value = ">100".$unit;
		}
		else
			$dp_value = round($value,2).$unit;
		$bar_wd = round(($value * 100) / 100, 2);
		$bar = '<div class="progress mtn mbn emprogress">
					<div class="progress-bar emprogressbar '.$class.'" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '.$bar_wd.'px;">'.$dp_value.'
					</div>
				</div>';
	}
	else
		$bar = round($value,2).' '.$unit;
	return $bar;
}
function str_compare($str1, $str2)
{
	$str1 = strtolower(trim($str1));
	$str2 = strtolower(trim($str2));
	if (strstr($str1, $str2))
		return true;
	else
		return false;
}
function bytesconverter($from)
{
	$number = substr($from, 0, -2);
	if ($number > 0)
	{
		switch(strtoupper(substr($from, -2)))
		{
			case "KB":
				return $number * 1024;
			case "MB":
				return $number * pow(1024, 2);
			case "GB":
				return $number * pow(1024, 3);
			case "TB":
				return $number * pow(1024, 4);
			case "PB":
				return $number * pow(1024, 5);
			default:
				return $from;
		}
	}
	return $number;
}
function getIntervals($tmoption)
{
	$intervals = array();
	$intervals['today'] = "1m";//"30m";
	$intervals['last_24_hour'] = "1m";//"30m";
	$intervals['last_7_days'] = "1h";
	$intervals['last_15_days'] = "3h";
	$intervals['last_30_days'] = "3h";
	$intervals['week_to_date'] = "1d";
	$intervals['month_to_date'] = "3h";
	$intervals['yesterday'] = "1m";//"30m";
	$intervals['last_3_days'] = "3h";
	$intervals['last_1_hour'] = "1m";
	$intervals['last_2_hour'] = "1m";
	$intervals['last_6_hour'] = "1m";
	$intervals['last_12_hour'] = "10m";
	$intervals['last_'.$hours.'_hour'] = "30m";
	$intervals['last_18_hour'] = "1m";//"30m";
	$intervals['last_10_min'] = "30s";
	$intervals['last_30_min'] = "30s";
	$intervals['last_6_month'] = "1d";
	$intervals['today_9_to_6'] = "1m";

	$intervals['zoom_one_month'] = "3h";
	$intervals['zoom_two_month'] = "12h";
	$intervals['zoom_three_month'] = "18h";
	$intervals['zoom_six_month'] = "1d";
	$intervals['zoom_one_year'] = "1w";
	$intervals['zoom_more_year'] = "1w";
	$intervals['custom'] = "1h";
	return isset($intervals[$tmoption]) ? $intervals[$tmoption] : "1h";
}
function getDates($filter=array())
{
	$hours = isset($filter['hours']) ? $filter['hours'] : 1;
	$date = date("Y-m-d H:i");
	$week =  date('W', strtotime($date));
	$year =  date('Y', strtotime($date));
	$Dates = array();
	$Dates['today'] = date("Y-m-d 00:00");
	$Dates['last_24_hour'] = date("Y-m-d H:i",strtotime("$date -1 day"));
	$Dates['last_3_days'] = date("Y-m-d 00:00",strtotime("$date -3 day"));
	$Dates['last_7_days'] = date("Y-m-d 00:00",strtotime("$date -7 day"));
	$Dates['last_15_days'] = date("Y-m-d 00:00",strtotime("$date -15 day"));
	$Dates['last_30_days'] = date("Y-m-d 00:00",strtotime("$date -30 day"));
	$Dates['last_60_days'] = date("Y-m-d 00:00",strtotime("$date -60 day"));
	$Dates['last_90_days'] = date("Y-m-d 00:00",strtotime("$date -90 day"));
	$Dates['week_to_date'] = date("Y-m-d 00:00", strtotime("{$year}-W{$week}-0"));   //Returns the date of sunday in week
	$Dates['month_to_date'] = date('Y-m-01 00:00'); // hard-coded '01' for first day
	$Dates['year_to_date'] = date('Y-01-01 00:00');

	$Dates['yesterday'] = date("Y-m-d 00:00",strtotime("-1 day"));
	$Dates['day_b4_yest'] = date("Y-m-d 00:00",strtotime("-2 day"));
	$Dates['last_3_days'] = date("Y-m-d 00:00",strtotime("$date -3 day"));
	$Dates['last_1_hour'] = date("Y-m-d H:i",strtotime("$date -1 hours"));
	$Dates['last_2_hour'] = date("Y-m-d H:i",strtotime("$date -2 hours"));
	$Dates['last_6_hour'] = date("Y-m-d H:i",strtotime("$date -6 hours"));
	$Dates['last_12_hour'] = date("Y-m-d H:i",strtotime("$date -12 hours"));
	$Dates['last_24_hour'] = date("Y-m-d H:i",strtotime("$date -24 hours"));
	$Dates['last_'.$hours.'_hour'] = date("Y-m-d H:i",strtotime("$date -".$hours." hours"));
	$Dates['last_18_hour'] = date("Y-m-d H:i",strtotime("$date -18 hours"));
	$Dates['last_10_min'] = date("Y-m-d H:i",strtotime("$date -10 min"));
	$Dates['last_15_min'] = date("Y-m-d H:i",strtotime("$date -15 min"));
	$Dates['last_30_min'] = date("Y-m-d H:i",strtotime("$date -30 min"));
	$Dates['last_6_month'] = date("Y-m-d H:i",strtotime("$date -6 month"));
	$Dates['last_1_year'] = date("Y-m-d H:i",strtotime("$date -1 year"));
	$Dates['last_2_year'] = date("Y-m-d H:i",strtotime("$date -2 year"));
	$Current = Date('N');
	$DaysToSunday = 7 - $Current;
	$DaysFromMonday = $Current - 1;
	$Sunday = Date('Y-m-d 00:00', StrToTime("+ {$DaysToSunday} Days"));
	$Monday = Date('Y-m-d 00:00', StrToTime("- {$DaysFromMonday} Days"));
	$Dates['this_week'] = array("start" => $Monday, "end" => $Sunday);
	$Dates['this_month'] = array("start" => date('Y-m-01 00:00:00',strtotime('this month')), "end" => date('Y-m-t 12:59:59',strtotime('this month')));
	$Dates['this_year'] = array("start" => date('Y-01-01 00:00:00'), "end" => date('Y-12-31 12:59:59'));
	return $Dates;
}

function app_curl_call($url)
{
	$user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
	$options = array(

	CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
	CURLOPT_POST           =>false,        //set to GET
	CURLOPT_USERAGENT      => $user_agent, //set user agent
	CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
	CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
	CURLOPT_RETURNTRANSFER => true,     // return web page
	CURLOPT_HEADER         => false,    // don't return headers
	CURLOPT_FOLLOWLOCATION => true,     // follow redirects
	CURLOPT_ENCODING       => "",       // handle all encodings
	CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
	CURLOPT_TIMEOUT        => 120,      // timeout on response
	CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
	CURLOPT_SSL_VERIFYPEER => false
	);
	print_msg($options);
	$ch      = curl_init( $url);
	curl_setopt_array( $ch, $options );
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );

	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	return $header;
}
function getIpFromRange($from_ip,$to_ip)
{
	$host_str = "";
	$ip_diff = ip2long($to_ip) - ip2long($from_ip);
	if (in_array($ip_diff, range(0, 255)))
	{
		$host_str = "";
		$aIPList = array();
		if ((ip2long($from_ip) !== -1) && (ip2long($to_ip) !== -1)) // As of PHP5, -1 => False
		{
			for($i = ip2long($from_ip); $i <= ip2long($to_ip); $i++)
			{
				$aIPList[] = long2ip($i);
				$host_str .= long2ip($i).',';
			}
			$host_str = trim($host_str,',');
		}
	}
	return $host_str;
}
function converter($size,$with_space="", $unit)
{
	$names = array('B', 'KB', 'MB', 'GB', 'TB');
	if(!in_array($unit,$names))
	{
		return "$size".$unit;
	}
	$times = 0;
	while($size > 1024)
	{
		$size = round(($size * 100) / 1024) / 100;
		$times++;
	}
	if ((int) $size > 0)
	{
		if($with_space == 'y')
		return "$size ".$names[$times];
		else
		return "$size".$names[$times];
	}
	else
		return "-";
}
function calculate_from_to_dates($timerange="", $customtime="", $is_zoom="", $tf_start="",$tf_end="")
{
    $from_time = '';
    $to_time = '';
	$tmoption = $timerange;
	if($customtime != "")
	{
		$tmoption = "custom";
		$customtime = explode(" - ", $customtime);
		if(!strtotime($customtime[0]))
			die("Invalid from date");
		if(	( isset($customtime[1]) && !strtotime($customtime[0]) ) || !isset($customtime[1]))
			die("Invalid to date");

		$from_time = strtotime(trim($customtime[0]));
		$to_time = strtotime(trim($customtime[1]));
		if(!$from_time || !$to_time)
			$from_time = $to_time = "";
		$timerange = "";
	}
	else if($tmoption == "")
		$tmoption = 'last_6_hour';
	$tffilter = array("tmoption" => $tmoption, "from_time" => $from_time, "to_time" => $to_time, "is_zoom" => $is_zoom,"tf_start" => $tf_start,"tf_end" => $tf_end,"timerange" => $timerange);
	$tffilter = timefilter($tffilter);

	return $tffilter;
}
function substr_in_array($needle, $haystack)
{
    /*** cast to array ***/
    $needle = (array) $needle;
		$haystack = (array) $haystack;
		/*** map with preg_quote ***/
    $needle = array_map('preg_quote', $needle);

    /*** loop of the array to get the search pattern ***/
    foreach ($needle as $pattern)
    {
        if (count(preg_grep("#$pattern#", $haystack)) > 0)
			return true;
    }
    /*** if it is not found ***/
    return false;
}
function oprlist($default='',$array=false)
{
	$opr_option_str = '';
	$opr_option = array();
	$opr_option[''] = '-Operator-';
	$opr_option['>'] = '>';
	$opr_option['<'] = '<';
	$opr_option['!='] = '!=';
	$opr_option['=='] = '==';
	$opr_option['<>'] = '<>';
	$opr_option['is_one_of'] = 'is one of';
	$opr_option['is_not_one_of'] = 'is not one of';
	if($array)
		return $opr_option;
	foreach($opr_option as $i => $val)
	{
		$selected = $default == $i ? ' selected ' : '';
		$opr_option_str .= '<option value="'.$i.'" '.$selected.'>'.$val.'</option>';
	}
	return $opr_option_str;
}
function getTimestampByDay($day, $hour, $which='',$date=true)
{
	$timestamp = "";
	if( $day != '' )
	{
		if($date)
		{
			$hour = $hour == '' ? "H" : $hour;
			$timestamp = date("Y-m-d $hour:i:s", strtotime( "$which $day" ));
		}
		else
			$timestamp = strtotime( "$which $day" );
	}
	return $timestamp;
}

function getTimestampByMonth($day, $hour, $which='',$date=true, $count=1)
{
	$datestring = "%Y-%m-%d %H:%i:%s";
	if ($which == "next")
		$m = date("m") + $count;
	else if ($which == "previous")
		$m = date("m") - $count;
	else
		$m = date("m");
	$time = mktime(str_pad($hour, 2, "0", STR_PAD_LEFT), 0, 0, $m, str_pad($day, 2, "0", STR_PAD_LEFT), date("Y"));
	$next_report_time = mdate($datestring, $time);
	if($date)
	{
		$timestamp = $next_report_time;
	}
	else
		$timestamp = strtotime($next_report_time);
	return 	$timestamp;
}
function getTimestampByYear($month, $day, $hour, $which='',$date=true, $count=1)
{
	$datestring = "%Y-%m-%d %H:%i:%s";
	if ($which == "next")
		$y = date("y") + $count;
	else if ($which == "previous")
		$y = date("y") - $count;
	else
		$y = date("y");

	$time = mktime(str_pad($hour, 2, "0", STR_PAD_LEFT), 0, 0, str_pad($month, 2, "0", STR_PAD_LEFT), str_pad($day, 2, "0", STR_PAD_LEFT), $y);
	$next_report_time = mdate($datestring, $time);
	if($date)
	{
		$timestamp = $next_report_time;
	}
	else
		$timestamp = strtotime($next_report_time);
	return 	$timestamp;
}
function getSchedule($data)
{
	$datestring = "%Y-%m-%d %H:%i:%s";
	$schedule = array();
	$next_report_time = '';
	$scheduletype = $data['scheduletype'];
	if($scheduletype == "once")
	{
		$next_report_time = trim($data['oncedate'])." ".$data['oncehour'].":00:00";
		$noofhour = $data['oncenoofhour'];
		$end_time =  date("Y-m-d H:i",strtotime("$next_report_time +$noofhour hours"));
	}
	else if($scheduletype == "daily")
	{
		$time = mktime(str_pad($data['dailyhour'], 2, "0", STR_PAD_LEFT), 0, 0, date("m"), date("d"), date("Y"));
		$next_report_time = mdate($datestring, $time);
		$noofhour = $data['dailynoofhour'];
		if(strtotime($next_report_time) < time())
		{
			$date = strtotime("+1 day", strtotime($next_report_time));
			$next_report_time = date("Y-m-d H:i:s", $date);
		}
		$end_time =  date("Y-m-d H:i",strtotime("$next_report_time +$noofhour hours"));
	}
	else if($scheduletype == "weekly")
	{
		$weeklyday = $data['weeklyday'];
		$weeklyhour = $data['weeklyhour'];
		$noofhour = $data['weeklynoofhour'];
		$next_report_time = getTimestampByDay($weeklyday,$weeklyhour,'',true);
		if(strtotime($next_report_time) < time())
			$next_report_time = getTimestampByDay($weeklyday,$weeklyhour,'next',true);
		$end_time =  date("Y-m-d H:i",strtotime("$next_report_time +$noofhour hours"));
	}
	else if($scheduletype == "monthly")
	{
		$monthlyday = $data['monthlyday'];
		$monthlyhour = $data['monthlyhour'];
		$noofhour = $data['monthlynoofhour'];
		$next_report_time = getTimestampByMonth($monthlyday,$monthlyhour,'',true);
		if(strtotime($next_report_time) < time())
			$next_report_time = getTimestampByMonth($monthlyday,$monthlyhour,'next',true);
		$end_time =  date("Y-m-d H:i",strtotime("$next_report_time +$noofhour hours"));
	}
	else if($scheduletype == "yearly")
	{
		$yearlymonth = $data['yearlymonth'];
		$yearlyday = $data['yearlyday'];
		$yearlyhour = $data['yearlyhour'];
		$noofhour = $data['yearlynoofhour'];
		$next_report_time = getTimestampByYear($yearlymonth,$yearlyday,$yearlyhour,'',true);
		if(strtotime($next_report_time) < time())
			$next_report_time = getTimestampByYear($yearlymonth,$yearlyday,$yearlyhour,'next',true);
		$end_time =  date("Y-m-d H:i",strtotime("$next_report_time +$noofhour hours"));
	}
	$schedule['next_report_time'] = $next_report_time;
	$schedule['start_time'] = $next_report_time;
	$schedule['end_time'] = $end_time;
	return $schedule;
}
function is_error($status)
{
	return $status == "error" ? true : false;
}
function is_msg($message)
{
	return $message == "error" ? true : false;
}
function json_to_array($json)
{
    $result = array();
    if ($json !== '')
    {
        $result = isjson($json) ? json_decode($json, true) : $result;
    }
    return $result;
}
function converttojson($string, $fromtype = "string")
{
    if ($fromtype === 'string')
    {
        return $string !== '' ? json_encode(explode(",", $string)) : "[]";
    }
    else if ($fromtype === 'array')
    {
        return $string !== '' ? json_encode($string) : "[]";
    }
}