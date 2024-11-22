@extends('layouts/layoutMaster')

@section('title', 'Create role')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-header header-elements">
      <h5 class="mb-0 text-capitalize">{{ __('Create new role') }}</h5>
    </div>
    <div class="card-body">
      <form id="create-roles" action="{{ route('roles.store') }}" method="POST">
        @csrf
        @include('content.roles.fields')
      </form>
    </div>
    <div class="card-footer">
      <div class="d-flex gap-4">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-primary" form="create-roles">{{ __('Save') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection
