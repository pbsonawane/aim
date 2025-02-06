<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnUserNotification extends Model 
{
    public $incrementing    = true;
    public $timestamps = true;  
	protected $table        = 'en_user_notification';	
    protected $fillable     = [
        'id','type','message','store_user','show_user','notification_read','notification_read   ','read_at','status'
    ];
	protected $primaryKey   = 'id';
}