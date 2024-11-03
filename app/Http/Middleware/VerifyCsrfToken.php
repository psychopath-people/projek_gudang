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
        'login',  // Mengecualikan rute login dari pemeriksaan CSRF
        'users/*', // Mengecualikan semua rute yang diawali dengan 'users/', termasuk penghapusan
        'Administrator/*',
        'dashboard',
        'login.authenticate',
        'mutasi/*',
        'mutasi.store',
        'login.authenticate',
        'logout',

    ];
}
