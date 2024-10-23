<?php

namespace App\Providers;

// use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades;
use Illuminate\View\View;
use Illuminate\Routing\Route;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    Facades\View::composer('*', function (View $view) {
      // $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
      $verticalMenu['menu'] = auth()->user()?->authMenu();
      // dd($verticalMenu);
      $verticalMenuJson = json_encode($verticalMenu);
      $verticalMenuData = json_decode($verticalMenuJson);

      $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
      $horizontalMenuData = json_decode($horizontalMenuJson);

      $view->with('menuData', [$verticalMenuData, $horizontalMenuData]);
    });

    // Share all menuData to all the views
    // $this->app->make('view')->share('menuData', [$verticalMenuData, $horizontalMenuData]);
  }
}
