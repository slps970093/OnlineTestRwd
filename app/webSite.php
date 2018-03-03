<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\webSite as webSiteEloquent;
class webSite extends Model
{
    //
    public $timestamps = false;
    protected $table = 'website';
    protected $fillable = [
        'website_name','description','keyword'
    ];
}
