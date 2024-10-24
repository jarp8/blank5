<?php

namespace App\Permissions\Traits;

trait HasPermission
{
  public function getUserPermissions()
  {
    $user = $this->with([
      'permiPermissionUser.permiPermissions' => function ($query) {
        $query->whereHas('permiModule', function ($q) {
          $q->where('is_active', 1);
        });
      },
    ])->first();

    return $user->permiPermissionUser;
  }

  public function getRolesPermissions()
  {
    $user = $this->with([
      'roles.permiPermissionRole.permiPermissions' => function ($query) {
        $query->whereHas('permiModule', function ($q) {
          $q->where('is_active', 1);
        });
      },
    ])->first();

    return $user->roles->pluck('permiPermissionRole')->flatten(1);
  }

  public function allPermissions()
  {
    // Combinar las dos colecciones de permisos
    $userPermissions = $this->getUserPermissions();
    $rolesPermissions = $this->getRolesPermissions();

    // Combinar ambas colecciones
    $allPermissions = $userPermissions->concat($rolesPermissions);

    // Eliminar duplicados basados en 'permi_permission_id'
    $uniquePermissions = $allPermissions->unique('permi_permission_id');

    return $uniquePermissions;
  }

  // $permission = 'home.index'
  public function hasPermission($permission)
  {
    return $this->allPermissions()->pluck('permiPermissions')->contains('name', $permission);
  }
}
