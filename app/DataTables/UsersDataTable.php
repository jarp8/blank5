<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UsersDataTable extends BlankDataTable
{
  protected string $module = 'admin.users';
  protected string $title = 'Users';

  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    return parent::dataTable($query)
      ->editColumn('roles.name', function (User $item) {
        return $item->roles->pluck('name')->join(', ');
      });
  }

  public function query(User $model): QueryBuilder
  {
    return $model->with('roles');
  }

  protected function getActions($row): array
  {
    $actions = parent::getActions($row);

    if (Gate::allows("{$this->module}.permissions")) {
      $actions['buttons'][] = [
        'attributes' => [
          'href' => route("{$this->module}.permissions", $row->id),
          'title' => __('Permissions'),
        ],
        'slot' => '<i class="ri-key-line"></i>',
      ];
    }

    return $actions;
  }

  protected function defineColumns(): array
  {
    return [
      Column::make('id'),
      Column::make('name'),
      Column::make('email'),
      Column::make('roles.name'),
      Column::make('created_at'),
      Column::make('updated_at'),
    ];
  }
}
