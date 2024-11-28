<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\PermiModule;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(RolesDataTable $dataTable)
  {
    return $dataTable->render('content.roles.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('content.roles.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(RoleRequest $request)
  {
    $role = Role::create($request->all());

    return redirect()->route('admin.roles.permissions', $role)->with('status', ['message' => __('Role created successfully')]);
  }

  /**
   * Display the specified resource.
   */
  public function show(Role $role)
  {
    abort(501);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Role $role)
  {
    return view('content.roles.edit', compact('role'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(RoleRequest $request, Role $role)
  {
    $role->update($request->all());

    return redirect()->route('admin.roles.index')->with('status', ['message' => __('Role updated successfully')]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Role $role)
  {
    $role->delete();

    return response()->json([
      'status' => true,
      'message' => __('Role deleted successfully'),
    ], 200);
  }

  public function permissions(Role $role)
  {
    $modules = PermiModule::with([
      'permiPermissions.permiFunction',
      'allSubModules',
    ])
      ->where('permi_module_id', null)
      ->where('is_active', true)
      ->get();

    $rolePermissions = $role->permiPermissionRole;

    $title = __('Role permissions');
    $route = route('admin.roles.storepermissions', $role);

    return view('content.permissions.create', compact(
      'modules',
      'role',
      'rolePermissions',
      'route',
      'title',
    ));
  }

  public function storePermissions(Request $request, Role $role)
  {
    $ids = array_keys($request->permiPermissions ?? []);

    $role->permiPermissions()->sync($ids, [
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s"),
    ]);

    return redirect()->route('admin.roles.index')->with('status', ['message' => __('Role permissions created successfully.')]);
  }
}
