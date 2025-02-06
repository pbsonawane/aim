<?php

namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class EnSystemSettings extends Model
{
    protected $table = 'en_system_settings';
	protected $fillable = [
        'configuration', 'setting_id', 'status', 'type'
    ];
    protected $primaryKey = null;
	public $incrementing = false;
	protected function getSystemSetting()
	{
		$query = DB::table($this->table)
                ->select('configuration', 'setting_id', 'status', 'type');
        $data =  $query->get();
		return $data;	
	}
}
