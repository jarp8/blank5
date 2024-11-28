@extends('layouts.layoutMaster')

@section('title', 'Assign permissions')

<!-- Page Scripts -->
@section('page-script')
@vite([
  'resources/js/permissions.js',
])
@endsection

@section('content')
<div class="container">
  <x-alert :status="session('status')" />

  <div class="card">
    <div class="card-header header-elements">
      <h5 class="mb-0 text-capitalize">{{ __($title) ?? __('Permissions by module') }}</h5>
    </div>
    <div class="card-body">
      <form id="create-permissions" action="{{ $route }}" method="POST">
        @csrf
        @include('content.permissions.submodule_permissions')
      </form>
    </div>
    <div class="card-footer">
      <div class="d-flex gap-4">
        <button class="btn btn-secondary" onclick="history.back()">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary" form="create-permissions">{{ __('Save') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection
