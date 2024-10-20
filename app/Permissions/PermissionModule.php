<?php

namespace App\Permissions;

use App\Models\PermiModule;
use App\Permissions\Enums\ModuleType;

class PermissionModule
{
  public MainStructure $structure; // La estructura principal
  public MainStructure $insertedViewStructure; // Las vistas que tendrán los permisos
  public MainStructure $insertedDBStructure; // La estructura insertada en la DB

  // El constructor solicita la estructura de los módulos en el constructor, ejemplo:
  // $structure = [
  //   'name' => 'Users', 'icon' => null, 'sub_modules' => [
  //     ['name' => 'roles', 'icon' => null],
  //     ['name' => 'users', 'icon' => null]
  //   ]
  // ];
  public function __construct(MainStructure $structure)
  {
    $this->structure = $structure;
    $this->insertedViewsStructure = new MainStructure();
    $this->insertedStructure = new MainStructure();
  }

  // Insertar los módulos
  public function insertModules()
  {
    // Inserta los módulos conforme a la estructura solicitada
    $this->insertedStructure->structure = $this->createModules(
      $this->structure->structure
    );

    // Desactivar módulos inactivos
    $this->softDeleteUnused();
  }

  // Crea los módulos utilizando recursión con la función "insertSubModules"
  // $modules = Es la estructura de los módulos
  // $mainModuleId = Parámetro para insertar el id del padre al hijo
  private function createModules(array $modules, int $mainModuleId = null)
  {
    $insertedStructure = [];

    // Recorre la estrucura que hay dentro del arreglo de módulos
    foreach ($modules as $index => $module) {
      // Si es elemento del arreglo es de tipo MODULE
      // quiere decir que tiene submódulos y utilizamos la función de insertSubModules
      if ($module->permi_module_type_id == ModuleType::module->value) {
        $permiModule = PermiModule::updateOrCreate([
          'name' => $module->name,
          'permi_module_id' => $mainModuleId,
          'permi_module_type_id' => $module->permi_module_type_id,
        ], [
          'name' => $module->name,
          'is_active' => $module->is_active,
        ]);

        $mergedPermiModule = $this->mergePermiModule($permiModule, $module);

        if ($mainModuleId == null) {
          $insertedStructure[] = $mergedPermiModule;

          $insertedStructure[$index]['sub_modules'] = $this->insertSubModules(
            $module->sub_modules,
            $permiModule
          );
        } else {
          $insertedStructure = $mergedPermiModule;

          $insertedStructure['sub_modules'] = $this->insertSubModules(
            $module->sub_modules,
            $permiModule
          );
        }
      } else {
        // Caso contrario es una vista, insertamos el elemento en la base de datos
        $permiModuleView = PermiModule::updateOrCreate([
          'name' => $module->name,
          'permi_module_type_id' => $module->permi_module_type_id,
        ], [
          'name' => $module->name,
          'is_active' => $module->is_active,
        ]);

        $mergedPermiModule = $this->mergePermiModule($permiModuleView, $module);

        // Añadimos al arreglo de moduleViews, las vistas para poder agregarle los
        // permisos posteriormente
        $this->insertedViewsStructure->structure[] = $mergedPermiModule;

        $insertedStructure[] = $mergedPermiModule;
      }
    }

    return $insertedStructure;
  }

  // Inserta los submódulos y si este tiene submódulos utiliza la función createModules
  // $sub_modules = Estructura de los submódulos
  // $module = El objeto module padre
  private function insertSubModules(array $sub_modules, PermiModule $module = null)
  {
    $insertedSubStructure = [];

    // Si el arreglo sub_modules es diferente de vacío
    if (!empty($sub_modules)) {
      foreach ($sub_modules as $subModule) {
        // Si el elemento del arreglo es de tipo VIEW insertará
        // en la base de datos el elemento
        if ($subModule->permi_module_type_id == ModuleType::view->value) {
          $permiModuleView = PermiModule::updateOrCreate([
            'name' => $subModule->name,
            'permi_module_id' => $module->id,
            'permi_module_type_id' => $subModule->permi_module_type_id
          ], [
            'name' => $subModule->name,
            'is_active' => $module->is_active == 0
              ? $module->is_active
              : $subModule->is_active,
          ]);

          $mergedPermiModule = $this->mergePermiModule($permiModuleView, $subModule);

          $this->insertedViewsStructure->structure[] =  $mergedPermiModule;

          $insertedSubStructure[] =  $mergedPermiModule;
        } else {
          // Caso contrario es de tipo MODULE y si este elemento contiene sub_modules
          // utilizaremos la función createModules
          if (isset($subModule->sub_modules)) {
            $insertedSubStructure[] = $this->createModules(
              array($subModule),
              $module->id
            );
          }
        }
      }
    }

    return $insertedSubStructure;
  }

  private function mergePermiModule($permiModule, $array)
  {
    return $permiModule->toArray() + (array) $array;
  }

  // Desactiva los módulos que son inactivos con is_active = 0
  private function softDeleteUnused()
  {
    $this->insertedStructure = $this->insertedStructure
      ->filterStructure(function ($item) {
        return $item['is_active'] === 1;
      });

    $ids = $this->insertedStructure->getStructureIds();

    $modules = PermiModule::whereNotIn('id', $ids)->get();

    foreach ($modules as $module) {
      $module->update([
        'is_active' => 0
      ]);
    }
  }
}
