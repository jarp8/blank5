<?php

namespace App\Http\Middleware;

use App\Models\User;

use Closure;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Symfony\Component\HttpFoundation\Response;

class GatePermissions
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (DB::getSchemaBuilder()->hasTable('permi_permissions')) {
      $permissions = auth()->user()->allPermissions();

      foreach ($permissions as $permission) {
        $name = $permission->permiPermissions->name;

        Gate::define($name, function (User $user) {
          return true;
        });
      }
    }

    return $next($request);
  }
}
