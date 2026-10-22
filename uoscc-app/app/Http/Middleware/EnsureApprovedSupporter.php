<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedSupporter
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->hasApprovedSupporterAccess()) {
            return redirect()->route('apoiadores.solicitacao');
        }

        return $next($request);
    }
}
