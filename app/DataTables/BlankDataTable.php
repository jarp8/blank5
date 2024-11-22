<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

abstract class BlankDataTable extends DataTable
{
  protected string $table;
  protected string $title;
  protected bool $includeActions = true;

  /**
   * Build the DataTable class.
   */
  public function dataTable(QueryBuilder $query): EloquentDataTable
  {
    $dataTable = new EloquentDataTable($query);

    if ($this->includeActions) {
      $dataTable->addColumn('actions', fn($row) => $this->renderActions($row))
        ->rawColumns(['actions']);
    }

    return $dataTable->setRowId('id');
  }

  /**
   * Configure the HTML Builder.
   */
  public function html(): HtmlBuilder
  {
    return $this->builder()
      ->setTableId("{$this->table}-table")
      ->columns($this->getColumns())
      ->responsive(true)
      ->minifiedAjax()
      ->orderBy(0)
      ->selectStyleSingle()
      ->parameters($this->getTableParameters());
  }

  /**
   * Define columns, including default actions if enabled.
   * Child classes must implement `defineColumns`.
   */
  public function getColumns(): array
  {
    $columns = $this->defineColumns();

    if ($this->includeActions) {
      $columns[] = Column::computed('actions')
        ->exportable(false)
        ->printable(false)
        ->width(60)
        ->addClass('text-center');
    }

    return $columns;
  }

  /**
   * Define the columns specific to the child class.
   */
  abstract protected function defineColumns(): array;

  /**
   * Get actions based on user permissions.
   */
  protected function getActions($row): array
  {
    $actions = [];

    if (Gate::allows("{$this->table}.edit")) {
      $actions['buttons'][] = [
        'attributes' => [
          'href' => route("{$this->table}.edit", $row->id),
          'class' => 'update-action-datatable',
          'title' => __('Edit'),
        ],
        'slot' => '<i class="ri-edit-box-line"></i>',
      ];
    }

    if (Gate::allows("{$this->table}.destroy")) {
      $actions['buttons'][] = [
        'attributes' => [
          'href' => route("{$this->table}.destroy", $row->id),
          'class' => 'delete-action-datatable',
          'title' => __('Delete'),
        ],
        'slot' => '<i class="ri-delete-bin-line"></i>',
      ];
    }

    return $actions;
  }

  /**
   * Render action buttons.
   */
  protected function renderActions($row): string
  {
    return view('datatables.actions', [
      'actions' => $this->getActions($row),
    ])->render();
  }

  /**
   * Define custom table parameters.
   */
  protected function getTableParameters(): array
  {
    $translatedTitle = __($this->title);

    return [
      'initComplete' => "function() {
          $('div.{$this->table}-table-head-label').html('<h5 class=\"card-title mb-0\">{$translatedTitle}</h5>');
      }",
      'dom' => '<"card-header flex-column flex-md-row border-bottom"<"' . $this->table . '-table-head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6 mt-5 mt-md-0"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      'displayLength' => 7,
      'lengthMenu' => [7, 10, 25, 50, 75, 100],
      'language' => [
        'paginate' => [
          'next' => '<i class="ri-arrow-right-s-line"></i>',
          'previous' => '<i class="ri-arrow-left-s-line"></i>',
        ],
      ],
      'buttons' => $this->getTableButtons(),
    ];
  }

  /**
   * Define table buttons.
   */
  protected function getTableButtons(): array
  {
    $buttons = [
      [
        'extend' => 'collection',
        'className' => 'btn btn-label-primary dropdown-toggle me-4 waves-effect waves-light',
        'text' => '<i class="ri-external-link-line me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
        'buttons' => $this->getExportButtons(),
      ],
    ];

    if (Gate::allows("{$this->table}.create")) {
      $buttons[] = [
        'text' => '<i class="ri-add-line ri-16px me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Record</span>',
        'className' => 'create-new btn btn-primary waves-effect waves-light',
        'action' => "function () {
            window.location.href = '" . route("{$this->table}.create") . "';
        }",
      ];
    }

    return $buttons;
  }

  /**
   * Define export buttons.
   */
  protected function getExportButtons(): array
  {
    return [
      ['extend' => 'print', 'text' => '<i class="ri-printer-line me-1"></i>Print', 'className' => 'dropdown-item'],
      ['extend' => 'csv', 'text' => '<i class="ri-file-text-line me-1"></i>CSV', 'className' => 'dropdown-item'],
      ['extend' => 'excel', 'text' => '<i class="ri-file-excel-line me-1"></i>Excel', 'className' => 'dropdown-item'],
      ['extend' => 'pdf', 'text' => '<i class="ri-file-pdf-line me-1"></i>PDF', 'className' => 'dropdown-item'],
      ['extend' => 'copy', 'text' => '<i class="ri-file-copy-line me-1"></i>Copy', 'className' => 'dropdown-item'],
    ];
  }

  /**
   * Get the filename for export.
   */
  protected function filename(): string
  {
    return "{$this->table}_" . now()->format('YmdHis');
  }
}
