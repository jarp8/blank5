@props(['status'])

<div class="alert-container">
  @if ($status)
    @php
     $type =  $status['type'] ?? 'success';
    @endphp

    <div {{ $attributes->merge(['class' => "alert alert-$type  alert-dismissible alert-alt fade show"]) }}>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        {{ $status['message'] }}
    </div>
  @endif
</div>
