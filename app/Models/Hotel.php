<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Hotel extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $collection = 'hotel_collection';
}
