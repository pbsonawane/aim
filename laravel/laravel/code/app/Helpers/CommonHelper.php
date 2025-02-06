<?php
use App\Services\eNsysconfig\Enlog;
use App\Services\eNsysconfig\Enconfig;
use App\Services\RemoteApi;
use App\Services\ITAM\ItamService;
use App\Libraries\Emlib;
use App\Libraries\Maillib;
use App\Services\ITAM\SysconfigService;

function getItemUnitWithSKU($item_sku_codes)
{
	$itam = new ItamService;
	$skuoptions = [
	'form_params' => array('asset_sku' => $item_sku_codes)];            
	$item_sku_codes_unit = $itam->assetskuunit($skuoptions);
	$contentcodeunit = _isset($item_sku_codes_unit, 'content');              
	$finalarray = array_column($contentcodeunit,'measurement_unit_name','sku_code');
	return $finalarray;
}

	//if (!defined('BASEPATH'))
	//	exit('No direct script access allowed');
function optionselected($value,$selected)
{
	return $value == $selected ? 'selected="selected"' : '';
}
function limitbox($limit=10,$jsfunction="",$show_all="y")
{
	$select = '<label><select name="limit" id="limit" class="form-control input-sm" onchange="javascript: setLimit(this,&quot;'.$jsfunction.'&quot;);">';
	$rows = array(5,10, 20, 50, 100, 500);
	if($show_all == 'y')
		$rows[] = 'All';
		//$rows = array(10, 20, 50, 100, 500);
	if (is_array($rows) && count($rows) > 0)
	{
		foreach($rows as $row)
		{
			$value = $row == 'All' ? strtolower($row) : $row;
			$select .= '<option value="'.$value.'" '.optionselected($value, $limit).'>'.$row.'</option>';
		}
	}
	$select .= '</select></label>';
	return $select;
}
function emnotification($message)
{
	ob_start();
	include(APPPATH.'errors/emnotification.php');
	$buffer = ob_get_contents();
	ob_end_clean();
	echo $buffer;
	exit;
}
function myconsole($log,$logusr="0")
{
	$CI =& get_instance();
	$username = $CI->session->userdata('EMUSERNAME');
	if ((trim($username) == trim($logusr) || trim($logusr) == 0))
	{
		echo "<pre>";
		echo '<div style="max-height:600px;max-width:100%;width:98%;height:300px;overflow:auto;resize:both;background-color:#333;border-radius:10px;box-shadow:0 0 5px 1px #333; padding:5px;"><div style="padding 3px 30px 5px 0;color:#FF8000;width:auto;height:20px; float:right; ">eMagic Console</div><div style="max-width:100%; height:92%;width:99%;overflow:auto; padding:5px; text-align:left;word-wrap: break-word;color:#FFF; background-color:#000;">';
		if (is_array($log)> 0)
			print_r($log);
		else if (is_object($log))
			var_dump($log);
		else
		{
			echo wordwrap($log,100,"\n<br />");
		}
		echo "</div></div></pre>";
	}
}
function setvalue($val, $chr)
{
	echo $val != '' ? $val : $chr;
}
function xml2array_emptyvalues($contents, $get_attributes = 1, $priority = 'tag')
{
	if (!$contents)
		return array();
	if (!function_exists('xml_parser_create'))
		return array();
		//Get the XML parser of PHP - PHP must have this module for the parser to work
	$parser = xml_parser_create('');
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);

		//Initializations
	$xml_array = array();
	$parents = array();
	$opened_tags = array();
	$arr = array();

		$current = &$xml_array; //Refference
		//Go through the tags.
		$repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
		if (count($xml_values) > 0)
		{
			foreach ($xml_values as $data)
			{
				unset($attributes, $value); //Remove existing values, or there will be trouble
				//This command will extract these variables into the foreach scope
				// tag(string), type(string), level(int), attributes(array).
				extract($data); //We could use the array by itself, but this cooler.

				$result = array();
				$attributes_data = array();

				if (isset($value))
				{
					if ($priority == 'tag')
						$result = $value;
					else
						$result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}

				//Set the attributes too.
				if (isset($attributes) and $get_attributes)
				{
					foreach ($attributes as $attr => $val)
					{
						if ($priority == 'tag')
							$attributes_data[$attr] = $val;
						else
							$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}
				if(is_array($result) && count($result) < 1)
					$result = '';

				//See tag status and do the needed.
				if ($type == "open")
				{//The starting of the tag '<tag>'
			$parent[$level - 1] = &$current;
			if (!is_array($current) or (!in_array($tag, array_keys($current))))
					{ //Insert New tag
						$current[$tag] = $result;
						if ($attributes_data)
							$current[$tag.'_attr'] = $attributes_data;
						$repeated_tag_index[$tag.'_'.$level] = 1;

						$current = &$current[$tag];
					}
					else
					{ //There was another element with the same tag name
						if (isset($current[$tag][0]))
						{//If there is a 0th element it is already an array
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
							$repeated_tag_index[$tag.'_'.$level]++;
						}
						else
						{//This section will make the value an array if multiple tags with the same name appear together
							if(is_array($result))
							$current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
						else
							$current[$tag] = array($current[$tag]);

						$repeated_tag_index[$tag.'_'.$level] = 2;

						if (isset($current[$tag.'_attr']))
							{ //The attribute of the last(0th) tag must be moved as well
								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}
						}
						$last_item_index = $repeated_tag_index[$tag.'_'.$level] - 1;
						$current = &$current[$tag][$last_item_index];
					}
				}
				elseif ($type == "complete")
				{ //Tags that ends in 1 line '<tag />'
					//See if the key is already taken.
			if (!isset($current[$tag]))
					{ //New Key
						$current[$tag] = $result;
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if ($priority == 'tag' and $attributes_data)
							$current[$tag.'_attr'] = $attributes_data;
					} else
					{ //If taken, put all things inside a list(array)
						if (isset($current[$tag][0]) and is_array($current[$tag]))
						{//If it is already an array...
							// ...push the new element into that array.
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

							if ($priority == 'tag' and $get_attributes and $attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag.'_'.$level].'_attr'] = $attributes_data;
							}
							$repeated_tag_index[$tag.'_'.$level]++;
						}
						else
						{ //If it is not an array...
							if(is_array($result))
							$current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
						else
							$current[$tag] = array($current[$tag]);

						$repeated_tag_index[$tag.'_'.$level] = 1;
						if ($priority == 'tag' and $get_attributes)
						{
							if (isset($current[$tag.'_attr']))
								{ //The attribute of the last(0th) tag must be moved as well
									$current[$tag]['0_attr'] = $current[$tag.'_attr'];
									unset($current[$tag.'_attr']);
								}

								if ($attributes_data)
								{
									$current[$tag][$repeated_tag_index[$tag.'_'.$level].'_attr'] = $attributes_data;
								}
							}
							$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
						}
					}
				}
				elseif ($type == 'close')
				{ //End of tag '</tag>'
			$current = &$parent[$level - 1];
		}
	}
}

return($xml_array);
}
function xml2array($contents, $get_attributes = 1, $priority = 'tag')
{
	if (!$contents)
		return array();
	if (!function_exists('xml_parser_create'))
		return array();
		//Get the XML parser of PHP - PHP must have this module for the parser to work
	$parser = xml_parser_create('');
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);

		//Initializations
	$xml_array = array();
	$parents = array();
	$opened_tags = array();
	$arr = array();

		$current = &$xml_array; //Refference
		//Go through the tags.
		$repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
		if (count($xml_values) > 0)
		{
			foreach ($xml_values as $data)
			{
				unset($attributes, $value); //Remove existing values, or there will be trouble
				//This command will extract these variables into the foreach scope
				// tag(string), type(string), level(int), attributes(array).
				extract($data); //We could use the array by itself, but this cooler.

				$result = array();
				$attributes_data = array();

				if (isset($value))
				{
					if ($priority == 'tag')
						$result = $value;
					else
						$result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}

				//Set the attributes too.
				if (isset($attributes) and $get_attributes)
				{
					foreach ($attributes as $attr => $val)
					{
						if ($priority == 'tag')
							$attributes_data[$attr] = $val;
						else
							$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}

				//See tag status and do the needed.
				if ($type == "open")
				{//The starting of the tag '<tag>'
			$parent[$level - 1] = &$current;
			if (!is_array($current) or (!in_array($tag, array_keys($current))))
					{ //Insert New tag
						$current[$tag] = $result;
						if ($attributes_data)
							$current[$tag.'_attr'] = $attributes_data;
						$repeated_tag_index[$tag.'_'.$level] = 1;
						$current = &$current[$tag];
					}
					else
					{ //There was another element with the same tag name
						if (isset($current[$tag][0]))
						{//If there is a 0th element it is already an array
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
							$repeated_tag_index[$tag.'_'.$level]++;
						}
						else
						{//This section will make the value an array if multiple tags with the same name appear together
							$current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
							$repeated_tag_index[$tag.'_'.$level] = 2;

							if (isset($current[$tag.'_attr']))
							{ //The attribute of the last(0th) tag must be moved as well
								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}
						}
						$last_item_index = $repeated_tag_index[$tag.'_'.$level] - 1;
						$current = &$current[$tag][$last_item_index];
					}
				}
				elseif ($type == "complete")
				{ //Tags that ends in 1 line '<tag />'
					//See if the key is already taken.
			if (!isset($current[$tag]))
					{ //New Key
						$current[$tag] = $result;
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if ($priority == 'tag' and $attributes_data)
							$current[$tag.'_attr'] = $attributes_data;
					}
					else
					{ //If taken, put all things inside a list(array)
						if (isset($current[$tag][0]) and is_array($current[$tag]))
						{//If it is already an array...
							// ...push the new element into that array.
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

							if ($priority == 'tag' and $get_attributes and $attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag.'_'.$level].'_attr'] = $attributes_data;
							}
							$repeated_tag_index[$tag.'_'.$level]++;
						}
						else
						{ //If it is not an array...
							$current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
							$repeated_tag_index[$tag.'_'.$level] = 1;
							if ($priority == 'tag' and $get_attributes)
							{
								if (isset($current[$tag.'_attr']))
								{ //The attribute of the last(0th) tag must be moved as well
									$current[$tag]['0_attr'] = $current[$tag.'_attr'];
									unset($current[$tag.'_attr']);
								}
								if ($attributes_data)
								{
									$current[$tag][$repeated_tag_index[$tag.'_'.$level].'_attr'] = $attributes_data;
								}
							}
							$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
						}
					}
				}
				elseif ($type == 'close')
				{ //End of tag '</tag>'
			$current = &$parent[$level - 1];
		}
	}
}
return($xml_array);
}
function listbox_search($all_str, $id, $prefix = '')
{
	$CI = & get_instance();
	$search_box = '<div style="display:none;width:auto;position:absolute;margin-top:-23px;" id="srch_'.$id.'" onmouseover="inlistbox_over(&quot;'.$id.'&quot;,&quot;'.$prefix.'&quot;);" onmouseout="inlistbox_out('.$all_str.',&quot;'.$id.'&quot;,&quot;'.$prefix.'&quot;);"><table border="0" cellspacing="0" cellpadding="1" style="border:2px solid #3399FF; border-collapse:collapse;background:#FFFFFF;"><tr><td><input name="srchtext_'.$id.'" type="text" onkeyup="javascript: inlistbox('.$all_str.',this.value,&quot;'.$id.'&quot;,&quot;'.$prefix.'&quot;);" onkeydown="javascript: inlistbox('.$all_str.',this.value,&quot;'.$id.'&quot;,&quot;'.$prefix.'&quot;);" id="srchtext_'.$id.'" style="width:125px;border:none; padding:3px; color:#000000; height:17px;background:#FFFFFF; font-weight:bold;" onfocus="inlistbox_over(&quot;'.$id.'&quot;,&quot;'.$prefix.'&quot;);" autocomplete="off" /></td><td><img src="'.$CI->config->item("theme_images").'search.png" border="0"/></td></tr></table></div>';
	return $search_box;
}
function listbox_dbsearch($filter=array())
{
	$CI = & get_instance();
	$placeholder = isset($filter['placeholder']) ?  $filter['placeholder'] : 'Search by Device name, Ip, Client Name';
	$textboxid = isset($filter['textboxid']) ?  $filter['textboxid'] : 'srch_text';
	$title = isset($filter['title']) ?  $filter['title'] : 'Click to fetch devices';
	$value = isset($filter['value']) ?  $filter['value'] : '';
	$attributes = isset($filter['attributes']) ?  $filter['attributes'] : ' onclick="javascript: search_obj();" ';

	$search_box = '<table cellspacing="0" cellpadding="1" border="0" style="border:1px solid #A8CEF4; border-collapse:collapse; background:#FFFFFF;z-index:1000000000;">
	<tbody>
	<tr>
	<td>
	<input type="text" value="'.$value.'" placeholder="'.$placeholder.'" autocomplete="off" class="form-control usearchbox" id="'.$textboxid.'" name="'.$textboxid.'">
	</td>
	<td>
	<img border="0" height="21px" '.$attributes.' title="'.$title.'" alt="'.$title.'" id="img_fetch_client" style="cursor:pointer;" src="'.$CI->config->item("theme_images").'/search_ic3.png">
	</td>
	</tr>
	</tbody></table>';
	return $search_box;
}
function div_search($id)
{
	$CI = & get_instance();
	$search_box = '<table border="0" cellspacing="0" cellpadding="1" style="border:2px solid #3399FF; border-collapse:collapse; background:#FFFFFF;z-index:1000000000;"><tr><td><input name="srch_text" type="text" onkeyup="javascript: searchindivs(this.value,&quot;'.$id.'&quot;);" style="border:none; width:100px;padding:3px; color:#000000; height:12px;background:#FFFFFF; font-weight:bold;" autocomplete="off"/></td><td><img src="'.$CI->config->item("theme_images").'search.png" border="0"/></td></tr></table>';
	return $search_box;
}

function div_search1($id)
{
	$CI = & get_instance();
	$search_box = '<table border="0" cellspacing="0" cellpadding="1" style="border:2px solid #3399FF; border-collapse:collapse; background:#FFFFFF;z-index:1000000000;"><tr><td><input name="srch_text" type="text" onkeyup="javascript: searchindivs1(this.value,&quot;'.$id.'&quot;);" style="border:none; width:100px;padding:3px; color:#000000; height:12px;background:#FFFFFF; font-weight:bold;" autocomplete="off"/></td><td><img src="'.$CI->config->item("theme_images").'search.png" border="0"/></td></tr></table>';
	return $search_box;
}

function isnumeric($value)
{
	if (is_numeric($value) && $value > 0)
		return $value;
}

function isstring($value)
{
	if (is_string($value) && !preg_match('/[0-9]+$/', $value))
		return $value;
}
function array_in_string($array,$content)
{
	$ret = '';
	$process_status = array();
	if (is_array($array) && count($array) > 0 && $content != '')
	{
		foreach ($array as $vmstatus)
		{
			if (strstr($content, $vmstatus))
			{
				array_push($process_status, $vmstatus);
			}
		}
		if (is_array($process_status) && count($process_status) > 0)
		{
			if (in_array('CLONENO', $process_status))
				return 'stop###';
			elseif (in_array('VMYES', $process_status))
				return 'success###';
			else
				return 'continue###';
		}
	}
}
function randpass($length=10)
{
	$letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890$&~';
	return trim(substr(str_shuffle($letters), 0, $length));
}

function mycsv($data_array,$field_array,$filename='data.csv',$separator=",")
{
	$data_str = '';
	if (is_array($data_array) && count($data_array) > 0)
	{
		if (is_array($field_array) && count($field_array) > 0)
		{
			$cols = 'Sr.'.$separator.implode($separator,array_values($field_array));
			$fields = array_keys($field_array);
		}

		$i=1;
		foreach($data_array as $row)
		{
			foreach($fields as $field_name)
			{
				$data_str .= $row[$field_name].$separator;
			}

			echo $data_str .= $i.$separator.trim($data_str,$separator)."\n";
			$i++;
		}
		$data_str = $cols."\n".trim($data_str,$separator);
	}
	return $data_str;
}
function sort_array_new($array, $sortkey, $order)
{
	$sorted_arr = array();
	if (is_array($array) && count($array) > 0)
	{
		if ($order == "DESC")
			$or = "arsort";
		else
			$or = "asort";
		foreach ($array as $key => $array_row)
		{
			$sort_values[$key] = $array_row[$sortkey];
		}
		$or($sort_values);
		reset($sort_values);
		while (list ($arr_key, $arr_val) = each($sort_values))
		{
			$sorted_arr[] = $array[$arr_key];
		}
	}
	return $sorted_arr;
}
function sort_with_key($array, $sortkey, $order)
{
	$sorted_arr = array();
	if (is_array($array) && count($array) > 0)
	{
		if ($order == "DESC")
			$or = "arsort";
		else
			$or = "asort";
		foreach ($array as $key => $array_row)
		{
			$sort_values[$key] = $array_row[$sortkey];
		}
		$or($sort_values);
		reset($sort_values);
		while (list ($arr_key, $arr_val) = each($sort_values))
		{
			$sorted_arr[$arr_key] = $array[$arr_key];
		}
	}
	return $sorted_arr;
}
function sortbwstats($a, $b)
{
	return $a['total'] - $b['total'];
}
function wrapstring($str, $width = 30, $break = "<br>", $cut = true)
{
	return wordwrap($str, $width, $break, $cut);
}
function barcolor($value)
{
	$bar_percent_color = array(1 => '#80FF80', 25 => '#D5D500', 55 => '#FF8000', 85 => '#FF0000');
	if ($value < 25)
		return $bar_percent_color[1];
	elseif ($value >= 25 && $value < 55)
		return $bar_percent_color[25];
	elseif ($value >= 55 && $value < 85)
		return $bar_percent_color[55];
	if ($value >= 85)
		return $bar_percent_color[85];
}
function percentbar($value,$unit="%")
{
	$bar = '';
	if ($unit == '%')
	{
		if ($value == '')
			$value = 0;
		$color = barcolor($value);
		if ($value > 100)
		{
			$value = 100;
			$dp_value = ">100".$unit;
		}
		else
			$dp_value = round($value,2).$unit;
		$bar_wd = round(($value * 100) / 100, 2);
		$bar = '<div style="width:100px; height:13px; border:1px solid '.$color.';"><div style="width:100%; color:#000; float:left;font-size:10px;text-align:center;margin-top:-1px;">'.$dp_value.'</div><div style="height:12px; background-color:'.$color.'; width:'.$bar_wd.'px; max-width:99px;text-align:center; font-size:10px; color:#000;" align="left"></div></div>';
	}
	else
		$bar = round($value,2).' '.$unit;
	return $bar;
}
function validpercent($value,$total,$unit="%")
{
	$dp_perc = '';
	if ($value > 0 && $total > 0)
	{
		$percent = ($value / $total) * 100;
		if (is_float($percent) && $percent > 0)
		{
			if ($percent > 100)
				$dp_perc = ">100".$unit;
			else
				$dp_perc = round($percent, 2).$unit;
		}
	}
	return $dp_perc;
}
	function ipsplit($ipstr,$char=",") // primay,management
	{
		$ips = array();
		$device_ips = $ipstr != '' ? explode($char,$ipstr) : array();
		if (count($device_ips) > 0 && is_array($device_ips))
		{
			$t = array();
			foreach($device_ips as $device_ip_row)
			{
				$t = explode(":",$device_ip_row);
				$ips[$t[0]] = $t[1];
			}
		}
		return $ips;
	}
	function pagination_msg($filter=array())
	{
		$from = isset($filter['from']) && $filter['from'] != '' ? $filter['from'] : 0;
		$to = isset($filter['to']) && $filter['to'] != '' ? $filter['to'] : 0;
		$total_rows = isset($filter['total_rows']) && $filter['total_rows'] != '' ? $filter['total_rows'] : 0;
		$is_valid_page = isset($filter['is_valid_page']) && $filter['is_valid_page'] != '' ? $filter['is_valid_page'] : '';
		$offset = isset($filter['offset']) && $filter['offset'] != '' ? $filter['offset'] : '';
		$limit = isset($filter['limit']) && $filter['limit'] != '' ? $filter['limit'] : '';
		if($is_valid_page == ""){
			$from = $offset + 1;
			$to = $offset + $limit;
		}
		if($to > $total_rows)
			$to = $total_rows;
		echo 'Showing '.$from.' to '.$to.' of '.$total_rows.' entries';
	}
	function processArray($array)
	{
		if(is_array($array))
		{
			$array = array_values(array_unique(array_filter($array)));
		}
		return $array;
	}
	function timerlistbox($default='') // interval = 30 mins
	{
		$options = '';
		for($i = 0; $i <= 24; $i++)
		{
			$selected = $default == $i ? ' selected ' : '';
			//$options .= '<option value="'.$i.'" '.$selected.'>'.date("h.i A", strtotime("$i:00")).'</option>';
			$options .= '<option value="'.$i.'" '.$selected.'>'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
		}
		return $options;
	}
	function weeklistbox($default='')
	{
		$options = '';
		$weeklist = array("1" => array("name" => "monday", "title" => "Monday"),
			"2" => array("name" => "tuesday", "title" => "Tuesday"),
			"3" => array("name" => "wednesday", "title" => "Wednesday"),
			"4" => array("name" => "thursday", "title" => "Thursday"),
			"5" => array("name" => "friday", "title" => "Friday"),
			"6" => array("name" => "saturday", "title" => "Saturday"),
			"7" => array("name" => "sunday", "title" => "Sunday"));

		foreach($weeklist as $k => $week)
		{
			$selected = $default == $week['name'] ? ' selected ' : '';
			$options .= '<option value="'.$week['name'].'" '.$selected.'>'.$week['title'].'</option>';
		}
		return $options;
	}
	function monthlybox($default='')
	{
		$options = '';
		for($d=1;$d<=31;$d++)
		{
			$selected = str_pad($d,2,'0',STR_PAD_LEFT) == $default ? ' selected ' : '';
			$options .= '<option value="'.str_pad($d,2,'0',STR_PAD_LEFT).'" '.$selected.'>'.str_pad($d,2,'0',STR_PAD_LEFT).'</option>';
		}
		return $options;
	}
	function monthlynamebox($default='',$get='')
	{
		$options = '';
		$monthlist = array("1" => array("name" => "january", "title" => "January"),
			"2" => array("name" => "february", "title" => "February"),
			"3" => array("name" => "march", "title" => "March"),
			"4" => array("name" => "april", "title" => "April"),
			"5" => array("name" => "may", "title" => "May"),
			"6" => array("name" => "june", "title" => "June"),
			"7" => array("name" => "july", "title" => "July"),
			"8" => array("name" => "august", "title" => "August"),
			"9" => array("name" => "september", "title" => "September"),
			"10" => array("name" => "october", "title" => "October"),
			"11" => array("name" => "november", "title" => "November"),
			"12" => array("name" => "december", "title" => "December"));
		if($get)
			return $monthlist;
		foreach($monthlist as $k => $month)
		{
			$selected = $default == $k ? ' selected ' : '';
			$options .= '<option value="'.$k.'" '.$selected.'>'.$month['title'].'</option>';
		}
		return $options;
	}
	function data_transfer_percent($data,$duration=60,$total)
	{
		$duration = $duration * 60; // convert to seconds
		$percent = $data > 0 && $total > 0 ? round((((($data * 8) / $duration) / $total) * 100), 2) : '0';
		return $percent;
	}
	function process_global_var()
	{
		$CI =& get_instance();
		$CI->load->library('vsfilter');
	}
	function bytes2gb($size)
	{
		$units = array(' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
		for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
			return round($size, 2).$units[$i];
	}
	function mb2gb($value)
	{
		if ($value > 0 && is_numeric($value))
			return round(($value/1024),2);
	}


	function bytes2gb_old($value)
	{
		if ($value > 0 && is_numeric($value))
			return round((($value/1024)/1024)/1024,2)."GB";
	}
	function str_replace_last1( $search , $replace , $str )
	{
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
	}

	function getExtension($str)
	{
		$i = strrpos($str, ".");
		if (!$i)
		{
			return "";
		}
		$l = strlen($str) - $i;
		$ext = substr($str, $i + 1, $l);
		return $ext;
	}
	function _validate_ip_address($ip, $msg)
	{
		$CI =& get_instance();
		$msg = $msg != '' ? $msg : 'IP Address';
		if($ip !='')
		{
			$ip_cidr = explode('/', $ip);

			$ip = trim($ip_cidr[0]) != '' ? trim($ip_cidr[0]) : 0;
			$CIDR = isset($ip_cidr[1]) ? trim($ip_cidr[1]) !='' ? trim($ip_cidr[1]) : '-1' : '';
			if($ip != '' && !$CI->input->valid_ip($ip))
			{
				//echo 'The '.$msg.' is invalid. Please enter a valid IP.';
				return false;
			}
			else
			{
				if($ip == '0.0.0.0' && $CIDR != '0')
				{
					//echo 'For all network use 0.0.0.0/0 in the '.$msg.' field.';
					return false;
				}

				if($CIDR != '')
				{
					if( $CIDR < 0 || $CIDR > 32 )
					{
						//echo 'The '.$msg.' is invalid. Entered valid CIDR.';
						return false;
					}
				}
				return true;
			}
		}
		return true;
	}
	function fetch_ip_frm_str($line)
	{
		if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $line, $ip_match)) {
			return $ip_match[0];
		}
	}
	function oneLayerArray($array)
	{
		if(is_array($array))
		{
			$oneLayerArray = array();
			foreach($array as $key => $val)
			{
				if(is_array($val))
				{
					foreach($val as $k => $v)
					{
						$oneLayerArray[$k] = $v;
					}
				}else{
					$oneLayerArray[$key] = $val;
				}
			}
			return $oneLayerArray;
		}
		return $array;
	}
	function getAllFiles($dir,$result,$relativepath,$scan_subdir=false,$srchstring='')
	{
		if(file_exists($dir))
		{
			$cdir = scandir($dir);
			foreach ($cdir as $key => $value)
			{
				if (!in_array($value,array(".","..")))
				{
					if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
					{
						if($scan_subdir)
							$result = getAllFiles($dir . DIRECTORY_SEPARATOR . $value,$result,$relativepath);
					}
					else
					{
						if(!startsWith($value,$srchstring))
						{
							continue;
						}
						if($relativepath == "true")
							$result[] = $dir."/".$value;
						else
							$result[] = $value;
					}
				}
			}
		}
		return $result;
	}
	function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}

	function endsWith($haystack, $needle) {
	    // search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}
	// delete old files
	function deloldfiles($files,$older=300)
	{
		$now   = time();
		if(is_array($files))
			foreach ($files as $file)
				if (is_file($file))
				{
    	  if ($now - filemtime($file) >= $older) // 1 hour
    	  unlink($file);
    	}
    }
    function convertSize($bytes, $precision = 2,	$from="B",$to="GB",$is_unit=false)
    {
    	$converted_val = '';
    	$kilobyte = 1024;
    	$megabyte = $kilobyte * 1024;
    	$gigabyte = $megabyte * 1024;
    	$terabyte = $gigabyte * 1024;
    	$low_to_high = array(

    		"B" => array("KB" => pow(1024,1), "MB" => pow(1024,2), "GB" => pow(1024,3), "TB" => pow(1024,4), "PB" => pow(1024,5)),
    		"KB" => array("MB" => pow(1024,1), "GB" => pow(1024,2), "TB" => pow(1024,3), "PB" => pow(1024,4)),
    		"MB" => array("GB" => pow(1024,1), "TB" => pow(1024,2), "PB" => pow(1024,3)),
    		"GB" => array("TB" => pow(1024,1), "PB" => pow(1024,2)),
    		"TB" => array("PB" => pow(1024,1)));

    	$high_to_low = array("PB" => array("TB" => pow(1024,1), "GB" => pow(1024,2), "MB" => pow(1024,3), "KB" => pow(1024,4),"B" => pow(1024,5)),
    		"TB" => array("GB" => pow(1024,1), "MB" => pow(1024,2), "KB" => pow(1024,3), "B" => pow(1024,4)),
    		"GB" => array("MB" => pow(1024,1), "KB" => pow(1024,2), "B" => pow(1024,3)),
    		"MB" => array("KB" => pow(1024,1),"B" => pow(1024,2)),
    		"KB" => array("B" => pow(1024,1)));
    	$is_unit = $is_unit ? $to : '';
    	if(isset($low_to_high[$from][$to]))
    	{
    		$converted_val =  round($bytes / $low_to_high[$from][$to], $precision) . $is_unit;
    	}else if(isset($high_to_low[$from][$to]))
    	{
    		$converted_val = $bytes * $high_to_low[$from][$to]. $is_unit;
    	}else
    	$converted_val = round($bytes , $precision);
    	return $converted_val;
    }

    function getStat($filename,$param='')
    {
    	$CI =& get_instance();
    	$CI->load->model('common_model', '', TRUE);
    	$stat = array();
    	$seperator = "#:#";
    	if(file_exists($filename))
    	{
    		if($param != '')
    		{
    			$command  = "grep -rh '".$param."#:#' ".$filename." | sed 's/".$param."#:#//g'";
    			$output = $CI->common_model->exec_command($command);
    			$stat[$param] = $output;
    		}
    		else
    		{
    			$data = file_get_contents($filename);
    			$data_arr = $data != '' ? explode("\n",$data) : array();
    			foreach($data_arr as $val)
    			{
    				if(trim($val ) == '' )
    					continue;
    				$extract = explode($seperator,$val);
    				$decode_json = '';
    				if(isset($extract[1]))
    				{
    					if(isJSON($extract[1]))
    					{
    						$decode_json = json_decode($extract[1], true);
    					}
    					else
    						$decode_json = $extract[1];
    				}
    				$stat[$extract[0]] = $decode_json;
    			}
    		}
    	}
    	return $stat;
    }
    function isJSON($string)
    {
    	return $string != '' && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    function httperror($code,$errormsg=true)
    {
    	$httpErrorCodes = array();
    	$httpErrorCodes['100'] = 'Continue - Only a part of the request has been received by the server, but as long as it has not been rejected, the client should continue with the request';
    	$httpErrorCodes['101'] = 'Switching Protocols - The server switches protocol ';
    	$httpErrorCodes['200'] = 'OK - The request is OK';
    	$httpErrorCodes['201'] = 'Created - The request is complete, and a new resource is created ';
    	$httpErrorCodes['202'] = 'Accepted - The request is accepted for processing, but the processing is not complete';
    	$httpErrorCodes['203'] = 'Non-authoritative Information';
    	$httpErrorCodes['204'] = 'No Content';
    	$httpErrorCodes['205'] = 'Reset Content';
    	$httpErrorCodes['206'] = 'Partial Content';
    	$httpErrorCodes['300'] = 'Multiple Choices - A link list. The user can select a link and go to that location. Maximum five addresses';
    	$httpErrorCodes['301'] = 'Moved Permanently - The requested page has moved to a new url';
    	$httpErrorCodes['302'] = 'Found - The requested page has moved temporarily to a new url';
    	$httpErrorCodes['303'] = 'See Other - The requested page can be found under a different url';
    	$httpErrorCodes['304'] = 'Not Modified';
    	$httpErrorCodes['305'] = 'Use Proxy';
    	$httpErrorCodes['306'] = 'Unused - This code was used in a previous version. It is no longer used, but the code is reserved';
    	$httpErrorCodes['307'] = 'Temporary Redirect - The requested page has moved temporarily to a new url';
    	$httpErrorCodes['400'] = 'Bad Request - The server did not understand the request';
    	$httpErrorCodes['401'] = 'Unauthorized - The requested page needs a username and a password';
    	$httpErrorCodes['402'] = 'Payment Required - You can not use this code yet';
    	$httpErrorCodes['403'] = 'Forbidden - Access is forbidden to the requested page';
    	$httpErrorCodes['404'] = 'Not Found - The server can not find the requested page';
    	$httpErrorCodes['405'] = 'Method Not Allowed - The method specified in the request is not allowed';
    	$httpErrorCodes['406'] = ' Not Acceptable - The server can only generate a response that is not accepted by the client';
    	$httpErrorCodes['407'] = 'Proxy Authentication Required - You must authenticate with a proxy server before this request can be served';
    	$httpErrorCodes['408'] = 'Request Timeout - The request took longer than the server was prepared to wait';
    	$httpErrorCodes['409'] = 'Conflict - The request could not be completed because of a conflict';
    	$httpErrorCodes['410'] = 'Gone - The requested page is no longer available';
    	$httpErrorCodes['411'] = 'Length Required - The "Content-Length" is not defined. The server will not accept the request without it';
    	$httpErrorCodes['412'] = 'Precondition Failed - The precondition given in the request evaluated to false by the server';
    	$httpErrorCodes['413'] = 'Request Entity Too Large - The server will not accept the request, because the request entity is too large';
    	$httpErrorCodes['414'] = 'Request-url Too Long - The server will not accept the request, because the url is too long. Occurs when you convert a "post" request to a "get" request with a long query information';
    	$httpErrorCodes['415'] = 'Unsupported Media Type - The server will not accept the request, because the media type is not supported';
    	$httpErrorCodes['416'] = 'Requested Range not satisfiable';
    	$httpErrorCodes['417'] = 'Expectation Failed';
    	$httpErrorCodes['500'] = 'Internal Server Error - The request was not completed. The server met an unexpected condition';
    	$httpErrorCodes['501'] = 'Not Implemented - The request was not completed. The server did not support the functionality required';
    	$httpErrorCodes['502'] = 'Bad Gateway - The request was not completed. The server received an invalid response from the upstream server';
    	$httpErrorCodes['503'] = 'Service Unavailable - The request was not completed. The server is temporarily overloading or down';
    	$httpErrorCodes['504'] = 'Gateway Timeout - The gateway has timed out';
    	$httpErrorCodes['505'] = 'HTTP Version Not Supported - The server does not support the "http protocol" version';
    	if($errormsg)
    		return 'HTTP Error('.$code.'): '.$httpErrorCodes[$code];
    	else
    		return isset($httpErrorCodes[$code]) ? $httpErrorCodes[$code] : '--';
    }
    function array_column_recursive(array $haystack, $needle)
    {
		/*
		$found = [];
		array_walk_recursive($haystack, function($value, $key) use (&$found, $needle) {
			if ($key == $needle)
				$found[] = $value;
		});
		return $found;
		*/
	}
	function vxss_clean($data)
	{
			// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

			// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

			// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

			// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

			// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
					// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

			// we are done...
		return $data;
	}
	if (!function_exists('array_column'))
	{
		function array_column(array $input, $columnKey, $indexKey = null)
		{
			$array = array();
			foreach ($input as $value) {
				if ( !array_key_exists($columnKey, $value)) {
					trigger_error("Key \"$columnKey\" does not exist in array");
					return false;
				}
				if (is_null($indexKey)) {
					$array[] = $value[$columnKey];
				}
				else {
					if ( !array_key_exists($indexKey, $value)) {
						trigger_error("Key \"$indexKey\" does not exist in array");
						return false;
					}
					if ( ! is_scalar($value[$indexKey])) {
						trigger_error("Key \"$indexKey\" does not contain scalar value");
						return false;
					}
					$array[$value[$indexKey]] = $value[$columnKey];
				}
			}
			return $array;
		}
	}
	// function to get array by key wise.
	function keytoarray($data, $key)
	{
		$key2array = array();
		if (is_array($data) && count($data) > 0)
		{
			foreach($data as $val)
			{
				$key2array[$val[$key]] = $val;
			}
		}
		return $key2array;
	}

	function printResponse($is_error,$msg="",$html="")
	{
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		$response["html"] = $html;
		echo json_encode($response);
	}
	function show_extra_details($extra_details,$flag="html")
	{
		if(is_array($extra_details))
		{
			foreach($extra_details as $val)
			{
				$ext_key = $val['extra_option'];
				$ext_key = strtolower(str_replace(array("_"),array(" "),$ext_key));
				$ext_key = preg_replace('/\bOs\b/u', 'OS', $ext_key);
				$ext_key = preg_replace('/\bos\b/u', 'OS', $ext_key);
				$ext_key = preg_replace('/\bssl\b/u', 'SSL', $ext_key);


				if($flag == "html")
				{
					?>
					<tr class="bordr_cl">
						<td bgcolor="#E2E1D4"><strong><?php echo ucwords($ext_key); ?></strong></td>
						<td><?php echo $val['extra_value'];?></td>
					</tr>
					<?php
				}
				else if($flag == "pdf")
				{
					?>
					<tr>
						<td style="border:0px solid #1E7AC1;background-color:#FFFFFF;color:#4D6BA8"><strong><?php echo ucwords($ext_key); ?></strong></td>
						<td style="border:0px solid #1E7AC1;background-color:#FFFFFF;color:#4D6BA8"><?php echo $val['extra_value'];?></td>
					</tr>
					<?php
				}
			}
		}
	}
	function process_value($exd_k)
	{
		$exd_k = strtolower(str_replace(array("_"),array(" "),$exd_k));
		$exd_k = preg_replace('/\bOs\b/u', 'OS', $exd_k);
		$exd_k = preg_replace('/\bos\b/u', 'OS', $exd_k);
		return ucfirst($exd_k);
	}
	function createurl($ip_add = '',$host_details,$ssl_enable = '', $user_name = '', $pass = '')
	{
		$cred_data = "";
		$port = $host_details['port'];
		$ssl_enable = $port == 443 ? "https" : ($port == 80 ? "http" : $ssl_enable) ;

		$port = $port == 443 || $port == 80 ? "" :  ':'.$port;
		if($host_details['cred_req'] == 'y' && $user_name != "" && $pass != "")
			$cred_data = $user_name.':'.$pass.'@';
		else if($host_details['cred_req'] == 'y')
		{
			$msg = "No Credential assigned to this host.";
			put_service_status($app_host_id, $msg);
			print_msg($msg);
			exit;
		}

		$url = $ssl_enable.'://'.$cred_data.$ip_add.$port;
		return $url;
	}
	function systemram()
	{
		$return_array = array();
		$data = explode("\n", file_get_contents("/proc/meminfo"));
		$meminfo = array();
		foreach ($data as $line) {
			list($key, $val) = explode(":", $line);
			$meminfo[$key] = trim($val);
		}
		if (is_array($meminfo) && count($meminfo) > 0)
		{
	    	$total = intval($meminfo['MemTotal']) * 1024; // in bytes
	    	$free = intval($meminfo['MemFree']) * 1024;
	    	$avail = intval($meminfo['MemAvailable']) * 1024;
	    	$used = $total - ($free + $avail);
	    	$return_array['total_memory'] = bytes2gb($total);
	    	$return_array['free_memory'] = bytes2gb($free);
	    	$return_array['used_memory'] = bytes2gb($used);
	    }
	    return $return_array;
	}
	function datediff($start,$end = false)
	{
		if(!$end) { $end = time(); }
		if(!is_numeric($start) || !is_numeric($end)) { return false; }
		$start  = date('Y-m-d H:i:s',$start);
		$end    = date('Y-m-d H:i:s',$end);
		$d_start    = new DateTime($start);
		$d_end      = new DateTime($end);
		$diff = $d_start->diff($d_end);
		$stats['year']    = $diff->format('%y');
		$stats['month']    = $diff->format('%m');
		$stats['day']      = $diff->format('%d');
		$stats['hour']     = $diff->format('%h');
		$stats['min']      = $diff->format('%i');
		$stats['sec']      = $diff->format('%s');
		return $stats;
	}

	function exec_command($command)
	{
		$rs = "";
		$hd = popen($command, "r");
		while(!feof($hd))
		{
			$rs .= fread($hd, 4096);
		}
		pclose($hd);
		return $rs;
	}
	function netMaskBox($value = '255.255.255.0')
	{
		$string = "";
		$string = '<option value="255.0.0.0" '. ( $value == "255.0.0.0" ? 'selected="selected"' : '' ) .'>255.0.0.0</option>
		<option value="255.192.0.0" '. ( $value == "255.192.0.0" ? 'selected="selected"' : '' ) .'>255.192.0.0</option>
		<option value="255.224.0.0" '. ( $value == "255.224.0.0" ? 'selected="selected"' : '' ) .'>255.224.0.0</option>
		<option value="255.240.0.0" '. ( $value == "255.240.0.0" ? 'selected="selected"' : '' ) .'>255.240.0.0</option>
		<option value="255.248.0.0" '. ( $value == "255.248.0.0" ? 'selected="selected"' : '' ) .'>255.248.0.0</option>
		<option value="255.252.0.0" '. ( $value == "255.252.0.0" ? 'selected="selected"' : '' ) .'>255.252.0.0</option>
		<option value="255.254.0.0" '. ( $value == "255.254.0.0" ? 'selected="selected"' : '' ) .'>255.254.0.0</option>
		<option value="255.255.0.0" '. ( $value == "255.255.0.0" ? 'selected="selected"' : '' ) .'>255.255.0.0</option>
		<option value="255.255.128.0" '. ( $value == "255.255.128.0" ? 'selected="selected"' : '' ) .'>255.255.128.0</option>
		<option value="255.255.192.0" '. ( $value == "255.255.192.0" ? 'selected="selected"' : '' ) .'>255.255.192.0</option>
		<option value="255.255.224.0" '. ( $value == "255.255.224.0" ? 'selected="selected"' : '' ) .'>255.255.224.0</option>
		<option value="255.255.240.0" '. ( $value == "255.255.240.0" ? 'selected="selected"' : '' ) .'>255.255.240.0</option>
		<option value="255.255.248.0" '. ( $value == "255.255.248.0" ? 'selected="selected"' : '' ) .'>255.255.248.0</option>
		<option value="255.255.252.0" '. ( $value == "255.255.252.0" ? 'selected="selected"' : '' ) .'>255.255.252.0</option>
		<option value="255.255.254.0" '. ( $value == "255.255.254.0" ? 'selected="selected"' : '' ) .'>255.255.254.0</option>
		<option value="255.255.255.0" '. ( $value == "255.255.255.0" ? 'selected="selected"' : '' ) .'>255.255.255.0</option>
		<option value="255.255.255.128" '. ( $value == "255.255.255.128" ? 'selected="selected"' : '' ) .'>255.255.255.128</option>
		<option value="255.255.255.192" '. ( $value == "255.255.255.192" ? 'selected="selected"' : '' ) .'>255.255.255.192</option>
		<option value="255.255.255.224" '. ( $value == "255.255.255.224" ? 'selected="selected"' : '' ) .'>255.255.255.224</option>
		<option value="255.255.255.240" '. ( $value == "255.255.255.240" ? 'selected="selected"' : '' ) .'>255.255.255.240</option>
		<option value="255.255.255.248" '. ( $value == "255.255.255.248" ? 'selected="selected"' : '' ) .'>255.255.255.248</option>
		<option value="255.255.255.252" '. ( $value == "255.255.255.252" ? 'selected="selected"' : '' ) .'>255.255.255.252</option>';
		return $string;
	}

	function getSchedulePostData()
	{
		$data = array();
		$CI =& get_instance();
		$scheduletype = trim($CI->input->post('scheduletype'));
		$data['scheduletype'] = $scheduletype;
		if($scheduletype == "once")
		{
			$data['oncedate'] = trim($CI->input->post('oncedate'));
			$data['oncehour'] = trim($CI->input->post('oncehour'));
			$data['oncenoofhour'] = trim($CI->input->post('oncenoofhour'));
		}
		else if($scheduletype == "daily")
		{
			$data['dailyhour'] = trim($CI->input->post('dailyhour'));
			$data['dailynoofhour'] = trim($CI->input->post('dailynoofhour'));
		}
		else if($scheduletype == "weekly")
		{
			$weeklyday = trim($CI->input->post('weeklyday'));
			$data['weeklyday'] = $weeklyday;
			$weeklyhour = trim($CI->input->post('weeklyhour'));
			$data['weeklyhour'] = $weeklyhour;
			$data['weeklynoofhour'] = trim($CI->input->post('weeklynoofhour'));
		}
		else if($scheduletype == "monthly")
		{
			$data['monthlyday'] = trim($CI->input->post('monthlyday'));
			$data['monthlyhour'] = trim($CI->input->post('monthlyhour'));
			$data['monthlynoofhour'] = trim($CI->input->post('monthlynoofhour'));
		}
		else if($scheduletype == "yearly")
		{
			$data['yearlymonth'] = trim($CI->input->post('yearlymonth'));
			$data['yearlyday'] = trim($CI->input->post('yearlyday'));
			$data['yearlyhour'] = trim($CI->input->post('yearlyhour'));
			$data['yearlynoofhour'] = trim($CI->input->post('yearlynoofhour'));
		}
		return $data;
	}
	function validateScheduleData($CI)
	{
		$scheduletype = trim($CI->input->post('scheduletype'));
		if($scheduletype == "once")
		{
			$CI->form_validation->set_rules('oncedate', 'Execute On', 'required|xss_clean');
			$CI->form_validation->set_rules('oncehour', 'Execute At', 'required|xss_clean');
		}
		else if($scheduletype == "daily")
		{
			$CI->form_validation->set_rules('dailyhour', 'Execute At', 'required|xss_clean');
		}
		else if($scheduletype == "weekly")
		{
			$CI->form_validation->set_rules('weeklyday', 'Execute Weekly', 'required|xss_clean');
			$CI->form_validation->set_rules('weeklyhour', 'Execute At', 'required|xss_clean');
		}
		else if($scheduletype == "monthly")
		{
			$CI->form_validation->set_rules('monthlyday', 'Execute Monthly', 'required|xss_clean');
			$CI->form_validation->set_rules('monthlyhour', 'Execute At', 'required|xss_clean');
		}
		else if($scheduletype == "yearly")
		{
			$CI->form_validation->set_rules('yearlymonth', 'Execute Yearly', 'required|xss_clean');
			$CI->form_validation->set_rules('yearlyday', 'Execute On Day', 'required|xss_clean');
			$CI->form_validation->set_rules('yearlyhour', 'Execute At', 'required|xss_clean');
		}
	}
	function getActiveSheet()
	{
		return $this->_parent->getActiveSheet();
	}

	function getSelectedCells()
	{
		return $this->getActiveSheet()->getSelectedCells();
	}

	/**
	 * Get the currently active cell coordinate in currently active sheet.
	 * Only used for supervisor
	 *
	 * @return string E.g. 'A1'
	 */

	function getActiveCell()
	{
		return $this->getActiveSheet()->getActiveCell();
	}

	function formatcell($objPHPExcel,$col)
	{
		$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}


	function columnLetter($c)
	{
		$c = intval($c);
		if ($c <= 0)
			return '';
		$letter = '';
		while($c != 0){
			$p = ($c - 1) % 26;
			$c = intval(($c - $p) / 26);
			$letter = chr(65 + $p) . $letter;
		}
		return $letter;
	}

	function addComment($objPHPExcel,$col,$title="",$comment)
	{
		$title = $title == "" ? "Comment" : $title;
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getComment($col)->setAuthor('PHPExcel');
		$objCommentRichText = $sheet->getComment($col)->getText()->createTextRun($title.':');
		$objCommentRichText->getFont()->setBold(true);
		$sheet->getComment($col)->getText()->createTextRun("\r\n");
		$sheet->getComment($col)->getText()->createTextRun($comment);
		$sheet->getComment($col)->setWidth('300pt');
		$sheet->getComment($col)->setHeight('200pt');
		$sheet->getComment($col)->setMarginLeft('150pt');
		$sheet->getComment($col)->getFillColor()->setRGB('EEEEEE');
	}

	function apilog($msg)
	{
		$api_logs_path = config('app.api_log_path');
		$logs_enable = config('app.api_log_enable');
		if($logs_enable)
		{
			tracklog($api_logs_path,$msg);
		}
	}

	function tracklog($log_path,$msg)
	{
		if($log_path != '')
		{
			$log_file = $log_path;
			$logmsg = date('d/m/Y H:i:s')." : ".$msg."\n";
			@file_put_contents($log_file, $logmsg, FILE_APPEND);
		}

	}
	
	function limitoffset($limit="", $page="")
	{
		$limit = $limit != '' ? $limit : config('enconfig.def_limit');
		$page = $page != '' ? $page : config('enconfig.page');
		$offset = is_numeric($limit) ? $limit * $page : 0;
		return array("limit" => $limit, "page" => $page, "offset" => $offset);
	}
	function showerrormsg($msgs)
	{
		$msg_string = '';
		if(is_array($msgs) && count($msgs) > 0 )
		{
			$msg_string = array();
			foreach($msgs as $msg)
			{
				if(is_array($msg))
				{
					foreach($msg as $m)
					{
						$msg_string[] = $m;
					}
				}
				else
					$msg_string[] = $msg;
			}
			$msg_string = html_entity_decode(implode("<br/>",$msg_string));
		}
		else
		{
			$msg_string = $msgs;
		}
		return $msg_string;
	}	
	/*
		Here $pagetitle is translation label keyword
    */    
		function breadcrum($pagetitle)
		{
			$link = '/'.Request::path();

		//for url which contain uuid at last segment(for multiple uuid at last segment)
			$last_seg 	= substr($link,-36);
			$UUIDv4 	= '/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/';

			while(strlen($last_seg) == 36 && preg_match($UUIDv4, $last_seg)){
				if(strlen($last_seg) == 36 && preg_match($UUIDv4, $last_seg)){
					$link 		= substr($link, 0, -37);
					$last_seg 	= substr($link,-36);
				}
			}

		//$links = config('enmenu.menu');
			$links =  Lang::get('enmenu.menu');
			$pagetitle = Lang::get($pagetitle);

			if($link == "/assets" || strpos($link,"/assets/") === 0) $link = "/assets/";

			ob_start();
			array_key_stack($link, $links);
			$crum = ob_get_contents();
			ob_end_clean();
			$breadcrum = '<ol class="breadcrumb"><li class="crumb-active nounderline"><a class="nounderline">'.$pagetitle.'</a></li><li class="crumb-link"><a href="'.config('enconfig.iamapp_url').'"><span class="glyphicon glyphicon-home"></span></a></li>';
			$breadcrum .= $crum;
			$breadcrum .= '</ol>';
			echo $breadcrum;
		}	
		function search_in_array($srchvalue, $array)
		{
			if (is_array($array) && count($array) > 0)
			{
				$foundkey = array_search($srchvalue, $array);
				if ($foundkey === FALSE)
				{
					foreach ($array as $key => $value)
					{
						if (is_array($value) && count($value) > 0)
						{
							$foundkey = search_in_array($srchvalue, $value);
							if ($foundkey != FALSE)
								return $foundkey;
						}
					}
				}
				else
					return $foundkey;
			}
		}
		function array_key_stack($child, $stack) 
		{		
			foreach ($stack as $k => $v) 
			{
				if (is_array($v)) {
					$title = isset($v['title']) ? $v['title'] : '';
					$lastkey = search_in_array($child, $v);
					if (!isset($v['link']) && $lastkey == 'link')
					{					
						echo isset($title) && $title != '' ? ' <li class="crumb-link">'.$title.'</li> ' : '';
					}
				// If the current element of the array is an array, recurse it and capture the return
					$return = array_key_stack($child, $v);
				// If the return is an array, stack it and return it
					if (is_array($return)) {
						return array($k => $return);
					}
				} else {
				// Since we are not on an array, compare directly
					if ($v == $child) {

						if (strpos(url()->current(),'purchaseorders') !== false) {
							$changeUrl = config('app.site_url')."/purchaseorders";
						} 
						else
						{
							$changeUrl = url()->current();
						}
						echo isset($stack['title']) ? ' <li class="crumb-trail"><a href="'.$changeUrl.'">'.$stack['title'].'</a></li> ' : '';
						return array($k => $child);
					}
				}
			}
		// Return false since there was nothing found
			return false;
		}
		function enview($viewpath, $data = null)
		{
			$view = View::make($viewpath, $data);
			return $contents = $view->render();
		}
    	//Function to return settings submenu array which contains ensysconfig setting options
		function getconfigsettingmenu(){
		$ensyslist	= get_ensys_configlist(); //returns array
		$mnuarr		= array();

		if(isset($ensyslist) && is_array($ensyslist) && count($ensyslist) > 0){
			if(isset($ensyslist['content']) && is_array($ensyslist['content']) && count($ensyslist['content']) > 0){
				foreach($ensyslist['content'] as $k => $v){
					
					switch ($k) {
						case 'mail_server':
						$temp = ['mail_server' => [
							'title' => trans('label.lbl_mail_server'),
							'icon'	=> 'fa fa-envelope',
							'link'	=> '/config/mail_server',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'general_setting':
						$temp = ['general_setting' => [
							'title' => trans('label.lbl_general_setting'),
							'icon'	=> 'fa fa-cogs',
							'link'	=> '/config/general_setting',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'sso_setting':
						$temp = ['sso_setting' => [
							'title' => trans('label.lbl_sso_setting'),
							'icon'	=> 'fa fa-cube',
							'link'	=> '/config/sso_setting',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'rrd_setting':
						$temp = ['rrd_setting' => [
							'title' => trans('label.lbl_rrd_setting'),
							'icon'	=> 'fa fa-area-chart',
							'link'	=> '/config/rrd_setting',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'logging':
						$temp = ['logging' => [
							'title' => trans('label.lbl_logging'),
							'icon'	=> 'fa fa-list-alt',
							'link'	=> '/config/logging',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'system_setting':
						$temp = ['system_setting' => [
							'title' => trans('label.lbl_system_setting'),
							'icon'	=> 'fa fa-desktop',
							'link'	=> '/config/system_setting',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'sso_api':
						$temp = ['sso_api' => [
							'title' => trans('label.lbl_sso_api'),
							'icon'	=> 'fa fa-cubes',
							'link'	=> '/config/sso_api',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'billing_api':
						$temp = ['billing_api' => [
							'title' => trans('label.lbl_billing_api'),
							'icon'	=> 'fa fa-calculator',
							'link'	=> '/config/billing_api',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'emagic_api':
						$temp = ['emagic_api' => [
							'title' => trans('label.lbl_emagic_api'),
							'icon'	=> 'fa fa-cloud',
							'link'	=> '/config/emagic_api',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'debug_mode':
						$temp = ['debug_mode' => [
							'title' => trans('label.lbl_debug_mode'),
							'icon'	=> 'fa fa-bug',
							'link'	=> '/config/debug_mode',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'ad_setting':
						$temp = ['ad_setting' => [
							'title' => trans('label.lbl_ad_setting'),
							'icon'	=> 'fa fa-exchange',
							'link'	=> '/config/ad_setting',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						case 'password_policy':
						$temp = ['password_policy' => [
							'title' => trans('label.lbl_pass_policy_setting'),
							'icon'	=> 'fa fa-key',
							'link'	=> '/config/password_policy',
							'key'	=> 'CONFIG'
						]];
						array_push($mnuarr,$temp);
						break;
						default:
						if($k != ""){
							$temp = [$k => [
								'title' => $v,
								'icon'	=> 'fa fa-cog',
								'link'	=> '/config/'.$k,
								'key'	=> 'CONFIG'
							]];
							array_push($mnuarr,$temp);
						}
					}
				}
			}
		}
		return $mnuarr;
	}

	function setconfigsettingmenu($menu_array){
		$setting_menu = getconfigsettingmenu();	//returns array
		for($i=0;$i<count($setting_menu);$i++){
			
			$tmp = $setting_menu[$i];
			foreach($tmp as $a=>$b){
				$menu_array['menu']['iam']['links']['settings']['sublinks'][$a] = $b;
			}
		}
		return $menu_array;
	}
	function get_ensys_configlist($all='')
	{
		// Commentd - Namrata - to avoid multiple call to "load_config" 
		/*$ensysconfig = new Enconfig;
		return $ensysconfig->load_configlist($all);*/
		try {
			$from = 'api';
			if ($from == "api"){
				$path	 = config('app.en_sysconfig_api_url');
				$url	 = 'config/getlist';
				if($all == true) $url = 'config/getlist?all=true';
				
				$options = array();
				$restapi = new RemoteApi;
				$data	 = $restapi->apicall("GET", $path, $url, $options);
			}
		}
		catch (Exception $e)
		{
			$data['data'] = null;
			$data['message']['error'] = $e->getMessage();
			$data['status'] = 'error';
			
			$data = $restapi->sendoutput($data, $options);
		}
		catch (Error $e)
		{
			$data['data'] = null;
			$data['message']['error'] = $e->getMessage();
			$data['status'] = 'error';
			
			$data = $restapi->sendoutput($data, $options);
		}
		return $data;
	}
	function set_http_code_errmsg($data=array()){
		if(isset($data['http_code']) && is_array(config('enconfig.success_http_code')) && !in_array($data['http_code'], config('enconfig.success_http_code'))){
			$data['is_error'] = true;
			$data['msg']	  = trans('messages.msg_servererror', ['name' => $data['http_code']]);
		}
		return $data;
	}

	/**
    * Function to maintain Error Logs in Elastic Search
    * @author Darshan Chaure [11/02/2020]
    * @access public
    * @param function
    * @param functionality
    * @param parameters
    * @param errormsg
    * @param index
    */
	function save_errlog($function="",$functionality="",$parameters=array(),$errormsg="",$index="error")
	{
		//Maintain Error Log
		$enlog = new Enlog;
		
		$error_data['user_id'] 		 = showuserid();
		$error_data['username'] 	 = showname();
		$error_data['command']		 = "";
		$error_data['function']		 = $function;
		$error_data['functionality'] = $functionality;
		$error_data['error']		 = $errormsg;
		
		if(is_array($parameters) && count($parameters) > 0){
			$error_data['parameters'] = json_encode($parameters);
		}
		
		$enlog->$index($error_data);
	}

	/*Function for checking user abilities based on permission*/
	function canuser($ability='',$permission_key='')
	{
		if (session()->has('issuperadmin')) 
		{
			return true;
		}
		elseif (session()->has('accessrights')) 
		{
			$permissions 		= Session::get('accessrights');
			$permission_key     = strtoupper($permission_key);
			$permission_access  = array();
			foreach ($permissions as $permission) 
			{
				if (is_array($permission) && array_key_exists($permission_key,$permission)) 
				{
					$permission_access  = json_decode($permission[$permission_key],true);
				}
				else
				{
					$permission_access = array();
				}
			}
			if ($ability == 'view' && in_array('r', $permission_access)) 
			{
				return true;
			}
			if ($ability == 'create' && in_array('c', $permission_access)) 
			{
				return true;
			}
			else if ($ability == 'update' && in_array('u', $permission_access)) 
			{
				return true;
			} 
			else if ($ability == 'delete' && in_array('d', $permission_access)) 
			{
				return true;
			}
			else if ($ability == 'advance' && in_array('a', $permission_access)) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function check_accessrights($permission_key=null)
	{
		if (session()->has('accessrights') && !session()->has('issuperadmin')) 
		{
			$permissions 		= Session::get('accessrights');
			$permission_key     = strtoupper($permission_key);
			$permission_access  = array();
			foreach ($permissions as $permission) 
			{
				if (is_array($permission) && array_key_exists($permission_key,$permission)) 
				{
					$permission_access  = json_decode($permission[$permission_key],true);
					if(in_array('r', $permission_access) || in_array('a', $permission_access))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			if (session()->has('issuperadmin')) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

    /**
     * This controller function is used to edit email template data in database.
     * @author Snehal C
     * @access public
     * @package Email Template
     * @param string $template_key
     * @param string $to
     * @param array $inputdata
     * @return bool
     */

    function send_email_function($template_key,$to,$inputdata = array()){

    	$itam = new ItamService;
    	$emlib = new Emlib;
       //// $this->request = $request;
        //$this->request_params = $this->request->all();

    	$form_params['template_key'] = $template_key; 
    	$options = ['form_params' => $form_params];
    	$emailtemplate_resp = $itam->getemailtemplates($options);
    	$emailtemplates = _isset(_isset($emailtemplate_resp, 'content'), 'records');


    	foreach($inputdata as $key=> $value){

    		$emailtemplates[0]['email_body'] = str_replace($key, $value, $emailtemplates[0]['email_body']);
    	}
    	$phpmailer = new Maillib();
    	$to_emails = $to;
    	$subject = $emailtemplates[0]['subject'];
    	$email_body =  $emailtemplates[0]['email_body'];
    	$mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);
        //echo"<pre>";print_r($mailresponse);die;
    	if($mailresponse){
    		return $mailresponse;
    	}else{
    		return false;
    	}
       /* $response["html"] = $mailresponse;
        $response["is_error"] = $is_error = "";
        $response["msg"] = $msg = "Mail Send Successfully";
        echo json_encode($response);*/
    }
    function getlogo()
    {
    	$sysconfig = new SysconfigService;
    	$data = $sysconfig->getlogo();
    	$data = $data['content'];
    	return $data;

    }
    function rebranding()
    {
    	$sysconfig = new SysconfigService;
    	$data = $sysconfig->rebranding();
    	$data = $data['content'];
      // $uploadfilepath = $data['content'][0]['logo'];
       //$data['uploadfilepath'] = $uploadfilepath;
       //$data['includeView'] = view("emmegamenu", $data);  
       //return view('template', $data);
    	apilog("-----------------");
    	apilog("-----------------");
    	apilog("---------IN rebranding--------");
    	apilog(json_encode($data));
    	apilog("-----------------");
    	apilog("-----------------");
    	return  $data;
       //echo json_encode($data, true);
    }
    function displaywords($number){

    	$no = floor($number);
    	$point = round($number - $no, 2) * 100;
    	$hundred = null;
    	$digits_1 = strlen($no);
    	$i = 0;
    	$str = array();
    	$words = array('0' => '', '1' => 'One', '2' => 'Two',
    		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    		'13' => 'Thirteen', '14' => 'Fourteen',
    		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    		'60' => 'Sixty', '70' => 'Seventy',
    		'80' => 'Eighty', '90' => 'Ninety');
    	$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore', 'Arab', 'Kharab', 'Neel', 'Padma');
    	while ($i < $digits_1) {
    		$divider = ($i == 2) ? 10 : 100;
    		$number = floor($no % $divider);
    		$no = floor($no / $divider);
    		$i += ($divider == 10) ? 1 : 2;
    		if ($number) {
    			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
    			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
    			$str [] = ($number < 21) ? $words[$number] .
    			" " . $digits[$counter] . $plural . " " . $hundred
    			:
    			$words[floor($number / 10) * 10]
    			. " " . $words[$number % 10] . " "
    			. $digits[$counter] . $plural . " " . $hundred;
    		} else $str[] = null;
    	}
    	$str = array_reverse($str);
    	$result = implode('', $str);
    	$points = ($point) ?
    	"." . $words[$point / 10] . " " . 
    	$words[$point = $point % 10] : '';
    	$decimal_points = '';
    	if($points){
    		$decimal_points = " Rupees ".$points. " Paise";
    	}
    	echo $result . $decimal_points;
        /*$words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', '', 'Hundred', 'Thousand', 'Lakh', 'Crore');
       
        $number = explode(".", $number);
        $result = array("","");
        $j =0;
        foreach($number as $val){
            // loop each part of number, right and left of dot
            for($i=0;$i<strlen($val);$i++){
                // look at each part of the number separately  [1] [5] [4] [2]  and  [5] [8]
                
                $numberpart = str_pad($val[$i], strlen($val)-$i, "0", STR_PAD_RIGHT); // make 1 => 1000, 5 => 500, 4 => 40 etc.
                if($numberpart <= 20){
                    $numberpart = 1*substr($val, $i,2);
                    $i++;
                    $result[$j] .= $words[$numberpart] ." ";
                }else{
                    //echo $numberpart . "<br>\n"; //debug
                    if($numberpart > 90){  // more than 90 and it needs a $digit.
                        $result[$j] .= $words[$val[$i]] . " " . $digits[strlen($numberpart)-1] . " "; 
                    }else if($numberpart != 0){ // don't print zero
                        $result[$j] .= $words[str_pad($val[$i], strlen($val)-$i, "0", STR_PAD_RIGHT)] ." ";
                    }
                }
            }
            $j++;
        }
        if(trim($result[0]) != "") echo $result[0] . "Rupees ";
        if($result[1] != "") echo $result[1] . "Paise";
        echo " Only";*/
    }
    function generateponumber(){
    	$itam = new ItamService;

    	$options = array();
    	$po_number_arr = $itam->generateponumber($options);
    	$po_no_array = explode('/',$po_number_arr['content']);
    	$last_po_number = end($po_no_array);
		if (date('m') <= 3) { 	//Upto Mar 2014-2015
		    $financial_year = (date('Y')-1) . '-' . date('y');
		} else {	//After Mar 2015-2016
			$financial_year = date('Y') . '-' . (date('y') + 1);
		}
		

		// Nayana Change condition  


		// if($last_po_number != '1' && date('m') == '04'){
		// 	$nextnumber = (int) 0001;
		// }else{
		// 	$nextnumber = (int) $last_po_number + 1;
		// }


		if(!empty($po_no_array[3])){
			if($po_no_array[3] == 'April' && $last_po_number == '001'){
				$nextnumber = (int) $last_po_number + 1;
			}elseif($po_no_array[3] == 'March' && date('m') == '04'){
				$nextnumber = (int) 001;
			}else{
				$nextnumber = (int) $last_po_number + 1;
			}
		}else{
			$nextnumber = (int) 001;
		}






		//Ex: ESDS/2021-22/Sepember/0001
		$po_number = 'ESDS/';
		$po_number .= $financial_year.'/';
		$po_number .= date('F').'/';		
		$po_number .= str_pad($nextnumber, 3, '0', STR_PAD_LEFT);
		return $po_number;
    }
    function generateprnumber(){
    	$itam = new ItamService;
    	$options = array();
    	$po_number_arr = $itam->generateprnumber($options);
    	$po_no_array = explode('/',$po_number_arr['content']);
    	//ESDS/PR/2022-23/April/001
    	$last_po_number = end($po_no_array);    	
		if (date('m') <= 3) { 	//Upto Mar 2014-2015
		    $financial_year = (date('Y')-1) . '-' . date('y');
		} else {	//After Mar 2015-2016
			$financial_year = date('Y') . '-' . (date('y') + 1);
		}
		
		if(!empty($po_no_array[3])){
			if($po_no_array[3] == 'April' && $last_po_number == '001'){
				$nextnumber = (int) $last_po_number + 1;
			}elseif($po_no_array[3] == 'March' && date('m') == '04'){
				$nextnumber = (int) 001;
			}else{
				$nextnumber = (int) $last_po_number + 1;
			}
		}else{
			$nextnumber = (int) 001;
		}
		
		
		
		//Ex: ESDS/2021-22/Sepember/0001
		$po_number = 'ESDS/PR/';
		$po_number .= $financial_year.'/';
		$po_number .= date('F').'/';		
		$po_number .= str_pad($nextnumber, 3, '0', STR_PAD_LEFT);
		return $po_number;
    }
	// Nikhil
	function generatecrnumber(){
    	$itam = new ItamService;
    	$options = array();
    	$po_number_arr = $itam->generatecrnumber($options);
    	$po_no_array = explode('/',$po_number_arr['content']);
    	//ESDS/PR/2022-23/April/001
    	$last_po_number = end($po_no_array);    	
		if (date('m') <= 3) { 	//Upto Mar 2014-2015
		    $financial_year = (date('Y')-1) . '-' . date('y');
		} else {	//After Mar 2015-2016
			$financial_year = date('Y') . '-' . (date('y') + 1);
		}
		
		if(!empty($po_no_array[3])){
			if($po_no_array[3] == 'April' && $last_po_number == '001'){
				$nextnumber = (int) $last_po_number + 1;
			}elseif($po_no_array[3] == 'March' && date('m') == '04'){
				$nextnumber = (int) 001;
			}else{
				$nextnumber = (int) $last_po_number + 1;
			}
		}else{
			$nextnumber = (int) 001;
		}
		
		
		
		//Ex: ESDS/2021-22/Sepember/0001
		$po_number = 'ESDS/CR/';
		$po_number .= $financial_year.'/';
		$po_number .= date('F').'/';		
		$po_number .= str_pad($nextnumber, 3, '0', STR_PAD_LEFT);
		return $po_number;
    }
	// 

    function getvendorbyid($id){
    	$itam = new ItamService;

    	$options = ['form_params'=>array('pr_vendor_id'=>$id)];
    	$po_number_arr = $itam->getvendorbyid($options);
    	echo $po_number_arr['content'];
    }


	function user_notification() {
		
		$itam = new ItamService;
		$showuserid                        = showuserid();
    	$options = ['form_params'=>array('showuserid'=>$showuserid)];

		$resultdata = $itam->getusernotification($options); 
		
		$data=$resultdata['content'];
		
		return $data;
    }
    ?>
