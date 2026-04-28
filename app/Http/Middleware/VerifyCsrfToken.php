<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/sslcommerz-payment-success/*',
        'webhook/meta/*',   // Meta webhook POST — verified via X-Hub-Signature-256 instead
    ];
}
