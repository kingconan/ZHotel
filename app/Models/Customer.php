<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Customer extends Eloquent implements Authenticatable
{
    //
    /**
     * Customer
     * id name phone email password
     */
    use AuthenticableTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $collection = 'customer_collection';
}
