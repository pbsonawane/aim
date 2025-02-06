<?php

namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class EnSessionTokens extends Model
{
    protected $table = 'en_sessiontokens';
	protected $fillable = [
        'session_token_id', 'session_id', 'accesstoken', 'domainkey', 'url', 'method', 'ip', 'agent', 'auth', 'authtime'
    ];
    protected $primaryKey = 'session_token_id';
}
