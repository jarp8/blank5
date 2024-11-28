@extends('layouts/layoutMaster')

@section('title', 'Edit role')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-header header-elements">
      <h5 class="mb-0 text-capitalize">{{ __('Edit role') }}</h5>
    </div>
    <div class="card-body">
      <form id="create-roles" action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        @include('content.roles.fields', ['isEdit' => true])
      </form>
    </div>
    <div class="card-footer">
      <div class="d-flex gap-4">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-primary" form="create-roles">{{ __('Save') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection
