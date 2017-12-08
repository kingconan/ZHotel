<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Master extends Eloquent implements Authenticatable
{
    //
    use AuthenticableTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $collection = 'master_collection';
}