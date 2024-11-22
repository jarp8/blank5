<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];

    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function store(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (!Auth::attemptWhen($credentials, function (User $user) {
      $role = $user->roles()->where('login_web', 1)->first();

      return $role?->login_web === 1;
    }, $request->remember ?? false)) {
      return back()
        ->withInput()
        ->with('credentials', 'Invalid credentials. Please check your email and password and try again.');
    }

    $request->session()->regenerate();

    return redirect()->route('home.index');
  }
}
