<?php

use App\Http\Middleware\AlwaysAcceptJson;
use App\Http\Middleware\GatePermissions;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\ValidatePermissionRoute;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo(function (Request $request) {
      if ($request->is('admin/*')) {
        return route('admin.login');
      }

      return route('/');
    });

    $middleware->web(LocaleMiddleware::class);

    $middleware->alias([
      'permission' => ValidatePermissionRoute::class,
      'permission.gate' => GatePermissions::class,
    ]);

    $middleware->api(append: [
      AlwaysAcceptJson::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (ValidationException $e, Request $request) {
      if ($request->expectsJson()) {
        return response()->json([
          'status' => false,
          'message' => $e->getMessage(),
          'errors' => $e->errors(),
        ], $e->status);
      }
    });

    $exceptions->render(function (QueryException $e, Request $request) {
      if ($request->ajax()) {
        return response()->json([
          'error' => $e->getMessage()
        ], 422);
      }
    });

    $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
      if ($request->is('api/*')) {
        return response()->json([
          'error' => $e->getMessage()
        ], 405);
      }
    });

    $exceptions->render(function (HttpException $e, Request $request) {
      $statusCode = $e->getStatusCode();

      if ($request->is('admin/*')) {
        return response()->view("admin-errors.{$statusCode}", status: $statusCode);
      }
    });
  })->create();
