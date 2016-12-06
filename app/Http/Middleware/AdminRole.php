<?php

namespace App\Http\Middleware;

use Zizaco\Entrust\Middleware\EntrustRole;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Closure;

class AdminRole extends EntrustRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = 'admin')
    {
        try {
            return parent::handle($request, $next, $roles);
        } catch (HttpException $e) {
            return redirect('/');
        }
    }
}
