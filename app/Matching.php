<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Matching as MatchingEloquent;

class Matching extends Model
{
    protected $table = 'website';
    protected $fillable   = [
        'website_name','description','keyword'
    ];
}
