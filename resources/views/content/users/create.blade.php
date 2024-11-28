@extends('layouts/layoutMaster')

@section('title', 'Create user')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite([
  'resources/assets/js/forms-selects.js',
])
@endsection

@section('content')
<div class="container">
  <div class="card">
    <div class="card-header header-elements">
      <h5 class="mb-0 text-capitalize">{{ __('Create new user') }}</h5>
    </div>
    <div class="card-body">
      <form id="create-users" action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        @include('content.users.fields')
      </form>
    </div>
    <div class="card-footer">
      <div class="d-flex gap-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-primary" form="create-users">{{ __('Save') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection
