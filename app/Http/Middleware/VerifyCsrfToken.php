<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/uploader/image',
        '/api/parse/hotel',
        '/api/update/hotel',
        '/api/create/hotel',
//        '/api/hotel/*',
        '/chrome/test',
        '/ss/logout',
    ];
}
