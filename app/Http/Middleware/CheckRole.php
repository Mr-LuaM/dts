<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
    
        try {
            // Get the authenticated user
            $user = Auth::user();
    
            // Alternatively, get the token from the request and parse it
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
    
            // Retrieve the role from the token payload
            $userRole = $payload->get('role');
    
            if ($userRole !== $role) {
                // The user role does not match the required role
                return response()->json(['error' => "You don't have permission to access this resource."], 403);
            }
    
        } catch (\Exception $e) {
            // Handle error (token invalid, expired, etc.)
            return response()->json(['error' => 'Could not parse token'], 401);
        }
    
    
        return $next($request);
    }
}
