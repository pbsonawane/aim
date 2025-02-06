<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnoppDetails extends Model
{
    protected $table = 'en_opportunity_details';
    //public $timestamps = false;
    protected $fillable = [
        'opportunity_id', 'basic_details', 'item_json',
    ];
    protected $primaryKey = 'id';
    //const CREATED_AT = 'created_at';
    //const UPDATED_AT = 'updated_at';
}