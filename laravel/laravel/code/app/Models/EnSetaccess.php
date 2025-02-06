<?php

namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class EnSetaccess extends Model
{
    protected $table = 'en_setaccess';
	protected $fillable = [
        'session_access_id', 'session_id', 'username', 'accesstoken', 'domainkey', 'url', 'method', 'ip', 'created_at', 'updated_at', 'agent'
    ];
    protected $primaryKey = 'session_access_id';
}
