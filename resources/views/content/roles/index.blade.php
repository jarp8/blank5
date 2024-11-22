@extends('layouts/layoutMaster')

@section('title', 'Roles')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-select-bs5/select.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
<script type="module" src="/vendor/datatables/buttons.server-side.js"></script>
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@vite([
  'resources/js/datatable/delete.js',
])
@endsection

@section('content')
<div class="container">
  <x-alert :status="session('status')" />

  <div class="card">
      <div class="card-body">
          {{ $dataTable->table() }}
      </div>
  </div>
</div>
@endsection
