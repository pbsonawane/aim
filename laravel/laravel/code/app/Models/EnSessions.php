<?php

namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class EnSessions extends Model
{
    protected $table = 'en_sessions';
	protected $fillable = [
        'session_id', 'username', 'token', 'url', 'method', 'ip', 'created_at', 'updated_at', 'agent'
    ];
    protected $primaryKey = 'session_id';
}
