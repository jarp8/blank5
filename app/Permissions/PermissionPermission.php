<?php

namespace App\Permissions;

use App\Models\PermiFunction;
use App\Models\PermiPermission;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use ReflectionMethod;
use RegexIterator;

class PermissionPermission
{
  private $permiModules; // Los módulos que se les asignará permisos

  public function __construct($moduleViews)
  {
    $this->permiModules = $moduleViews;
  }

  // Inserta los permisos
  // $functions = ['index']
  public function insertPermissions($functions)
  {
    // Verifica, crea y obtiene las funciones/permisos en la base de datos
    $functions = $this->verifyCreateFunctions($functions);

    // Inserta los permisos en la base de datos
    $this->insertLogic($functions);
  }

  // Inserta los permisos resource
  // Permisos que agrega resource = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']
  // $functions = Existe la posibilidad de agregarle más funciones/permisos además del resource
  public function insertResourcePermissions($functions = [])
  {
    // Verifica, crea y obtiene las funciones/permisos en la base de datos y se hace un merge con las funciones resource
    $functions = $this->verifyCreateFunctions(array_merge($functions, PermiFunction::$resourceControllerFunctions));

    // Inserta los permisos en la base de datos
    $this->insertLogic($functions);
  }

  // Inserta los permisos/funciones del controlador
  // $functions = Existe la posibilidad de agregarle más funciones/permisos además del controlador
  public function insertControllerPermissions($functions = [])
  {
    // Recorre la estructura de los permiModules
    foreach ($this->permiModules as $permiModule) {
      // Verifica, crea y obtiene las funciones/permisos en la base de datos
      // y se hace un merge con las funciones que tiene el controlador
      $dbFunctions = $this->verifyCreateFunctions(
        array_merge($functions, $this->getFunctionsFromClass($permiModule->name))
      );

      // Inserta los permisos en la base de datos
      // NOTA: Como la función insertLogic(1, [2]) acepta en el segundo
      // parámetro un arreglo y en este caso permiModule es un objeto,
      // para solucionar esto se utiliza la sintaxis [$permiModule]
      $this->insertLogic($dbFunctions, [$permiModule]);
    }
  }

  // Inserta las funciones/permisos del respectivo listado de módulos
  // $functions = ['index']
  // $modules = [], para que insertControllerPermissions pudiera utilizar esta función
  private function insertLogic($functions, $modules = [])
  {
    //Si $modules tiene elementos los asignaremos a $localModules,
    //caso contrario utilizaremos los de la varible global $this->permiModules
    $localModules = (count($modules) > 0)
      ? $modules
      : $this->permiModules;

    //Recorremos los módulos
    foreach ($localModules as $permiModule) {
      //Recorremos las funciones/permisos para la inserción
      foreach ($functions as $function) {
        Log::channel('stderr')->info("\t PermiPermission: " . "{$permiModule->name}.{$function['name']}");

        //Insertamos el permiso con su respectivo nombre, módulo y permiso/función
        PermiPermission::firstOrCreate([
          'name' => "{$permiModule->name}.{$function['name']}",
          'permi_module_id' => $permiModule->id,
          'permi_function_id' => $function['id']
        ]);
      }
    }
  }

  // Verifica, crea y retorna el arreglo de las funciones
  // $functions = ['index']
  private function verifyCreateFunctions($functions)
  {
    // Crea la funciones si no existen
    $this->createPermiFunctionIfNotExist($functions);

    // Retorna arreglo de funciones/permisos en arreglo
    return $this->getPermiFunctionsByBame($functions);
  }

  // Crea los permisos/funciones si no existen
  // $functions = ['index']
  public function createPermiFunctionIfNotExist($functions)
  {
    foreach ($functions as $functionName) {
      $permiFunctions[] = PermiFunction::firstOrCreate(['name' => $functionName]);
    }

    return $permiFunctions ?? [];
  }

  // Obtiene los permisos/funciones de la base de datos y los retorna en un arreglo
  // $functions = ['index']
  public function getPermiFunctionsByBame($functions)
  {
    return PermiFunction::whereIn('name', $functions)->get()->toArray();
  }

  // Transforma el nombre del módulo para buscar el controlador
  // $string = 'users'
  // return 'UserController'
  private function transformClassName($string)
  {
    $pascalCase = str_replace(['.'], ' ', $string);
    $pascalCase = str_replace(' ', '\\', ucwords($pascalCase));

    //Convierte el nombre de la clase en singular
    $pascalCase = Str::singular($pascalCase);

    //Retorna el nombre de la clase transformada
    return "{$pascalCase}Controller";
  }

  // Obtiene la instancia del controlador, buscando entre archivos dentro de la carpeta Controllers
  // $name = 'UserController'
  private function getControllerInstance($name)
  {
    // Se utiliza para buscar resursivamente el directorio
    $directories = new RecursiveDirectoryIterator(app_path("Http/Controllers"));
    // Se utiliza para iterar sobre los archivos y subdirectorios
    // encontrados en el directorio de controladores.
    $iterator = new RecursiveIteratorIterator($directories);
    // Se utiliza para filtrar los archivos encontrados y
    // eleccionar solo aquellos que tienen una extensión .php.
    $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

    // Itera sobre los archivos
    foreach ($regex as $file) {
      // Remover todo hasta y incluyendo 'Controllers/'
      $relativePath = preg_replace('/^.*Controllers[\/\\\\]/', '', $file[0]);
      // Remover la extensión .php
      $relativePathWithoutExtension = preg_replace('/\.php$/', '', $relativePath);

      // Se verifica si el nombre del controlador $name
      // coincide con el nombre del archivo sin la extensión.
      // Si coinciden, significa que se ha encontrado el controlador que se está buscando.
      if ($name === $relativePathWithoutExtension) {
        // Obtiene la posición donde se encuenra /app/
        $startPosition = strpos($file[0], '\\app\\');
        // Remueve el string apartir de la posición de $startPosition
        $stringFromApp = substr($file[0], $startPosition);
        // Reemplaza /app/ por App/ dentro de $stringFromApp
        $replaceApp = str_replace('\\app\\', 'App/', $stringFromApp);
        // Remueve el .php
        $removeDotPhp = preg_replace('/\.php$/', '', $replaceApp);
        // Remueves los / por \\ para poder instanciar la clase con el path
        $controllerClassName = str_replace('/', '\\', $removeDotPhp);

        // Se instancia el controlador
        $controller = new $controllerClassName();

        Log::channel('stderr')->info('Controller: ' . $controllerClassName);

        // Se retorna el controlador
        return $controller;
      }
    }
    //En caso de que no exista el controlador, retorna una excepción
    throw new Exception("Controller not found: {$name}");
  }

  // Obtiene las funciones por el nombre de la clase
  // $name = 'users'
  private function getFunctionsFromClass($name)
  {
    // Transforma el nombre $name = 'users', $name = 'UserController'
    $name = $this->transformClassName($name);

    // Obtiene la instancia del controlador
    $class = new ReflectionClass($this->getControllerInstance($name)::class);
    // Obtiene los mêtodos public
    $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

    // Obtiene todos los métodos public excepto por el contructor
    foreach ($methods as $method) {
      if ($method->class == $class->getName() && $method->getName() != '__construct') {
        $functions[] = strtolower($method->getName());
      }
    }

    // Retorna el nombre de las funciones o métodos
    return $functions;
  }
}
