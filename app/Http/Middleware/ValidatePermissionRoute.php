<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePermissionRoute
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!auth()->user()->hasPermission($request->route()->getName())) {
      if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
          'status' => false,
          'message' => __('This action is unauthorized'),
        ], 403);
      } else {
        abort(403);
      }
    }

    return $next($request);
  }
}
