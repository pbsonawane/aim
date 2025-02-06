<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $authUser = 'ProjectCosting';
        $authPassword = '7Bo{6z!&g8Wu?xC';

        $user = $request->getUser();
        $password = $request->getPassword();

        if ($user !== $authUser || $password !== $authPassword) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
	
    }
}
