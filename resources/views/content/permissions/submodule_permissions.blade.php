@foreach ($modules as $module)
  @php
    $name = $module->name;
  @endphp
  @if ($module->permi_module_id == null || $module->permi_module_type_id == 1)
    <h6 class="text-capitalize mb-1">{{ __($name) }}</h6>
  @endif

  @if ($module->allSubModules->isNotEmpty())
    @include('content.permissions.submodule_permissions', [
      'modules' => $module->allSubModules
    ])
  @else
    <div class="accordion mb-3" id="{{ $name }}collapsibleSection">
      <div class="accordion-item">
        <div class="accordion-header" id="{{ $name }}headingDeliveryOptions" style="position: relative">
          <input
            type="checkbox"
            id="{{ $name }}CheckAll"
            class="form-check-input checkAll"
            style="position: absolute; z-index: 9; left: 8px; top: 50%; transform: translateY(-50%);"
            data-me="{{ $module->id }}"
            @if ($module->permi_module_id != null) {{ "data-parent={$module->permi_module_id}" }} @endif >
          <button
            type="button"
            class="accordion-button collapsed"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $name }}collapseDeliveryOptions"
            aria-expanded="false"
            aria-controls="{{ $name }}collapseDeliveryOptions">
            <span class="ps-3">{{ __($name) }}</span>
          </button>
        </div>

        <div
          id="{{ $name }}collapseDeliveryOptions"
          class="accordion-collapse collapse"
          aria-labelledby="{{ $name }}headingDeliveryOptions"
          data-bs-parent="#{{ $name }}collapsibleSection">
          <div class="accordion-body">
            @foreach ($module->permiPermissions as $permission)
              <div class="form-check mb-3">
                <input
                  type="checkbox"
                  id="{{ $name . '-' . $permission->id }}"
                  name="permiPermissions[{{ $permission->id }}]"
                  class="form-check-input checkChild"
                  data-parent="{{ $module->id }}"
                  @if ($rolePermissions->contains('permi_permission_id', $permission->id))
                    checked
                  @elseif (isset($userPermissions) && $userPermissions->contains('permi_permission_id', $permission->id))
                    checked
                  @endif
                  @if ($rolePermissions->contains('permi_permission_id', $permission->id) && isset($user))
                    disabled
                  @endif
                  />
                <label class="form-check-label" for="{{ $name . '-' . $permission->id }}">
                  {{ $permission->name }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endif
@endforeach
