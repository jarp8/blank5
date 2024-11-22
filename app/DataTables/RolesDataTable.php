<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class RolesDataTable extends BlankDataTable
{
  protected string $table = 'roles';
  protected string $title = 'Roles';

  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    return parent::dataTable($query)
      ->editColumn('login_web', fn(Role $item) => $this->formatStatusColumn($item, 'login_web', 'login_web_status'))
      ->editColumn('login_app', fn(Role $item) => $this->formatStatusColumn($item, 'login_app', 'login_app_status'))
      ->rawColumns(['login_web', 'login_app', 'actions']);
  }

  public function formatStatusColumn(Role $item, string $attribute, string $statusAttribute): string
  {
    return view('datatables.badge', [
      'type' => $item->{$attribute} ? 'success' : 'danger',
      'message' => $item->{$statusAttribute},
    ])->render();
  }

  /**
   * Get the query source of dataTable.
   */
  public function query(Role $model): QueryBuilder
  {
    return $model->newQuery();
  }

  protected function getActions($row): array
  {
    $actions = parent::getActions($row);

    if (Gate::allows("{$this->table}.permissions")) {
      $actions['buttons'][] = [
        'attributes' => [
          'href' => route("{$this->table}.permissions", $row->id),
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
      Column::make('login_web'),
      Column::make('login_app'),
      Column::make('created_at'),
      Column::make('updated_at'),
    ];
  }
}
