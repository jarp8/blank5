<div class="row">
  {{-- name --}}
  <div class="col-md-12">
    <div class="form-floating form-floating-outline mb-6">
      <input
        type="text"
        id="name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        placeholder="{{ __('Admin') }}"
        aria-describedby="validationName"
        value="{{ old('name', $role->name ?? '') }}"
        required />
      <label for="name">{{ __('Name') }}</label>

      @error('name')
        <div id="validationName" class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

    {{-- description --}}
    <div class="col-md-12">
      <div class="form-floating form-floating-outline mb-6">
        <input
          type="text"
          id="description"
          name="description"
          class="form-control @error('description') is-invalid @enderror"
          aria-describedby="validationDescription"
          value="{{ old('description', $role->description ?? '') }}" />
        <label for="description">{{ __('Description') }}</label>

        @error('description')
          <div id="validationDescription" class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

  <small class="text-light fw-medium mb-3">{{ __('The role can authenticate:') }}</small>

  {{-- login_web --}}
  <div class="col-md-12">
    <div class="form-check mb-3">
      <input type="hidden" name="login_web" value="0">
      <input
        type="checkbox"
        id="login_web"
        name="login_web"
        class="form-check-input @error('login_web') is-invalid @enderror"
        aria-describedby="validationLoginWeb"
        value="1"
        @checked(old('login_web', $role->login_web ?? false)) />
      <label class="form-check-label" for="login_web">
        {{ __('Web') }}
      </label>

      @error('login_web')
        <div id="validationLoginWeb" class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  {{-- login_app --}}
  <div class="col-md-12">
    <div class="form-check mb-3">
      <input type="hidden" name="login_app" value="0">
      <input
        type="checkbox"
        id="login_app"
        name="login_app"
        class="form-check-input @error('login_app') is-invalid @enderror"
        aria-describedby="validationLoginApp"
        value="1"
        @checked(old('login_app', $role->login_app ?? false)) />
      <label class="form-check-label" for="login_app">
        {{ __('App') }}
      </label>

      @error('login_app')
        <div id="validationLoginApp" class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
</div>
