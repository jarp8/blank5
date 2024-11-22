<div class="d-flex justify-content-around">
  @foreach ($actions['buttons'] ?? [] as $button)
    <x-datatables.anchor :attributes="new Illuminate\View\ComponentAttributeBag($button['attributes'])">
      {!! $button['slot'] !!}
    </x-datatables.anchor>
  @endforeach

  @if (isset($actions['dropdown']) && count($actions['dropdown']) > 0)
    <x-datatables.dropdown>
      @foreach ($actions['dropdown'] as $dropdown)
        <x-datatables.dropdown-item :attributes="new Illuminate\View\ComponentAttributeBag($dropdown['attributes'])">
          {!! $dropdown['slot'] !!}
        </x-datatables.dropdown-item>
      @endforeach
    </x-datatables.dropdown>
  @endif
</div>
