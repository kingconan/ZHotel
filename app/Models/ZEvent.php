<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ZEvent extends Eloquent
{
    //
    protected $connection = 'zevent';

    protected $collection = 'event_collection';


    public static function log($role, $action, $what ,$obj){
        $log = new ZEvent();
        $log->who = $role;
        $log->action = $action;
        $log->what = $what;
        $log->obj = $obj;
        $log->save();
    }
}
