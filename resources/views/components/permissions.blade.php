@if($itemExists)
    <x-moonshine::layout.divider />

    <x-moonshine::title class="mb-6">
        {{ $label }}
    </x-moonshine::title>

    {!! $form !!}
@endif
