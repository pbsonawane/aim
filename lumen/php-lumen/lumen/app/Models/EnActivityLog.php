<?php

namespace App\Models;

use Basemkhirat\Elasticsearch\Model;
use Spatie\BinaryUuid\HasBinaryUuid;
use ES;

class EnActivityLog extends Model
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $index = '';

    protected $type = "activity";
	public function getKeyName()
    {
        return 'user_log_id';
    }
    public function __construct($index = "", $type = "")
    {
        $this->index = 'user_logs_'.date('dmY');
        //$this->type = $type;
    }
    protected function getuserlogs($inputdata = array(), $count = false)
    {
		$result = array();
        $searchkeyword = _isset($inputdata, 'searchkeyword');
		if ($count == true)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
			$query = ES::index("user_logs_*");
        }
		else
        {
			$query = ES::index("user_logs_*")->type("activity");
			if (isset($inputdata["limit"]) && $inputdata["limit"] > 0)
	            $query->take($inputdata["limit"])->skip($inputdata["offset"]);
        }
		if (trim($searchkeyword) != '')
        {
			/*
			$query->body([
				"_source" => ["content"]				
				"query" => [
					 "bool" => [
						 "must" => [
								[ "match" => [ "ip" => $searchkeyword ] ],
								[ "match" => [ "action" => $searchkeyword ] ],
								[ "match" => [ "method" => $searchkeyword ] ]								
						 ]
					 ]
				]				 
			]);
			*/
            //$query->where('ip', 'like', '%'.$searchkeyword.'%');
            $query->search($searchkeyword, function($searchkeyword){
                $searchkeyword->boost(4)->fields(["ip" => 1, "action" => 2, "fullname" => 3, "url" => 4]);
            });

            /*
            ->orWhere('action', 'like', '%' . $searchkeyword . '%')
                ->orWhere('method', 'like', '%' . $searchkeyword . '%');
            */

            /*
			$query->where('action', 'like', '%'.$searchkeyword.'%');
			$query->where('method', 'like', '%'.$searchkeyword.'%');      
			*/
        }
		if ($count == true)
		{
			return $query->count();
		}
		else
		{
			$data = $query->orderBy('@usertimestamp', 'DESC')->get();
			if ($data)
			{
				$i = 0;
				foreach ($data as $items)
				{
					if ($items)
					{
						$result[$i]['json_string'] = stripslashes($items->json_string);
						$result[$i]['url'] = $items->url;
						$result[$i]['method'] = $items->method;
						$result[$i]['ip'] = $items->ip;
						$result[$i]['agent'] = $items->agent;
						$result[$i]['action'] = $items->action;
                        $result[$i]['userid'] = $items->user_id;
                        $result[$i]['fullname'] = $items->fullname;
						$result[$i]['logtime'] = date("d/m/Y g:i a", $items->{'@usertimestamp'});
						$i++;
					}
				}
			}
			return $result;
		}       
    }
}
