<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\PermiModule;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(UsersDataTable $dataTable)
  {
    return $dataTable->render('content.users.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $roles = Role::pluck('name', 'id');

    return view('content.users.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(UserRequest $request)
  {
    $user = User::create($request->all());

    $user->roles()->sync($request->roles);

    return redirect()->route('admin.users.permissions', $user)->with('status', ['message' => __('User created successfully')]);
  }

  /**
   * Display the specified resource.
   */
  public function show(User $user)
  {
    abort(501);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(User $user)
  {
    $user->load('roles');

    $roles = Role::pluck('name', 'id');

    return view('content.users.edit', compact('user', 'roles'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UserRequest $request, User $user)
  {
    $user->update($request->all());

    $user->roles()->sync($request->roles);

    return redirect()->route('admin.users.index')->with('status', ['message' => __('User updated successfully')]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $user)
  {
    $user->delete();

    return response()->json([
      'status' => true,
      'message' => __('User deleted successfully'),
    ], 200);
  }

  public function permissions(User $user)
  {
    $modules = PermiModule::with([
      'permiPermissions.permiFunction',
      'allSubModules',
    ])
      ->where('permi_module_id', null)
      ->where('is_active', true)
      ->get();

    $rolePermissions = $user->getRolesPermissions();
    $userPermissions = $user->getUserPermissions();

    $title = __('User permissions');
    $route = route('admin.users.storepermissions', $user);

    return view('content.permissions.create', compact(
      'modules',
      'rolePermissions',
      'route',
      'title',
      'user',
      'userPermissions',
    ));
  }

  public function storePermissions(Request $request, User $user)
  {
    $ids = array_keys($request->permiPermissions ?? []);

    $user->permiPermissions()->sync($ids, [
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s"),
    ]);

    return redirect()->route('admin.users.index')->with('status', ['message' => __('User permissions created successfully.')]);
  }
}
