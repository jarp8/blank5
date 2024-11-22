<div class="row">
  {{-- name --}}
  <div class="col-md-12">
    <div class="form-floating form-floating-outline mb-6">
      <input
        type="text"
        id="name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        placeholder="John Doe"
        aria-describedby="validationName"
        value="{{ old('name', $user->name ?? '') }}"
        required />
      <label for="name">{{ __('Full Name') }}</label>

      @error('name')
        <div id="validationName" class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  {{-- roles --}}
  <div class="col-md-12 mb-6">
    <div class="form-floating form-floating-outline">
      <select
        id="roles"
        name="roles[]"
        class="select2 form-select @error('roles') is-invalid @enderror"
        aria-describedby="validationRoles"
        multiple
        required>
        @foreach ($roles as $roleId => $role)
          <option
            value="{{ $roleId }}"
            @selected(in_array($roleId, old('roles', (isset($isEdit) ? $user->roles->pluck('id')->toArray() : []) ?? [])))
          >{{ $role }}</option>
        @endforeach
      </select>
      <label for="roles">{{ __('Roles') }}</label>

      @error('roles')
        <div id="validationRoles" class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  {{-- email --}}
  <div class="col-md-12">
    <div class="mb-6">
      <div class="input-group input-group-merge">
        <div class="form-floating form-floating-outline">
          <input
            type="email"
            id="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="john.doe@example.com"
            aria-label="john.doe@example.com"
            aria-describedby="validationEmail"
            value="{{ old('email', $user->email ?? '') }}"
            required />
          <label for="email">{{ __('Email') }}</label>

          @error('email')
            <div id="validationEmail" class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="form-text">{{ __('You can use letters, numbers & periods') }}</div>
    </div>
  </div>

  {{-- password --}}
  <div class="col-md-12">
    <div class="form-password-toggle mb-6">
      <div class="input-group input-group-merge">
        <div class="form-floating form-floating-outline">
          <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="validationPassword" />
          <label for="password">{{ __('Password') }}</label>

          @error('password')
            <div id="validationPassword" class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <span class="input-group-text cursor-pointer" id="validationPassword"><i class="ri-eye-off-line"></i></span>
      </div>
    </div>
  </div>
</div>
