<?php

namespace App\Traits;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait FormattableModelTrait
{

  // Función para obtener el nombre completo del creador
  public function getCreatedByFullName(): string
  {
    return $this->createdBy ? $this->createdBy->fullName : 'Desconocido';
  }

  // Función para obtener el nombre completo del actualizador
  public function getUpdatedByFullName(): string
  {
    return $this->updatedBy ? $this->updatedBy->fullName : 'Desconocido';
  }

  // Función para obtener la fecha de creación
  public function getCreatedAtDate(): DateTime
  {
    return new DateTime($this->created_at->format('Y-m-d'));
  }

  // Función para obtener la hora de creación
  public function getCreatedAtTime(): DateTime
  {
    return new DateTime($this->created_at->format('H:i:s'));
  }

  // Función para obtener la fecha de actualización
  public function getUpdatedAtDate(): DateTime
  {
    return new DateTime($this->updated_at->format('Y-m-d'));
  }

  // Función para obtener la hora de actualización
  public function getUpdatedAtTime(): DateTime
  {
    return new DateTime($this->updated_at->format('H:i:s'));
  }

  // Global scope para filtrar por is_active
  protected static function bootFormattableModelTrait()
  {

    static::addGlobalScope('is_active', function (Builder $builder) {
      // Obtener el nombre de la tabla asociada al modelo
      $table = (new static)->getTable();
      $builder->where("{$table}.is_active", true);
    });

    // Hook para la creación
    static::creating(function (Model $model) {
      $model->created_by = Auth::id(); // Añadir el ID del usuario autenticado
      $model->updated_by = Auth::id();
      $model->created_at = now(); // Añadir fecha actual
      $model->updated_at = now();
    });

    // Hook para la actualización
    static::updating(function (Model $model) {
      $model->updated_by = Auth::id(); // Actualizar el ID del usuario
      $model->updated_at = now(); // Actualizar fecha actual
    });
  }

  // Método para obtener los atributos numéricos
  protected function getNumericAttributes(): array
  {
    // Obtener los nombres de las columnas de la tabla
    return array_filter(Schema::getColumnListing($this->getTable()), function ($column) {
      // Verificar el tipo de columna
      $columnType = Schema::getColumnType($this->getTable(), $column);
      return in_array($columnType, ['int', 'integer', 'bigint', 'float', 'double', 'decimal']);
    });
  }

  // Método para agregar los atributos a la propiedad $casts
  protected function addDynamicCasts()
  {
    foreach ($this->getNumericAttributes() as $attribute) {
      $this->casts[$attribute] = 'float'; // O usa 'decimal:2' si es necesario
    }
  }

  // Llamar a addDynamicCasts en el método boot
  protected static function booted()
  {
    (new static())->addDynamicCasts();
  }

  public function getTableName(): string
  {
    return $this->getTable(); // Esto utiliza el método getTable() de la clase Model
  }
}
