@props([
    'size' => 'sm', // 'sm', 'md', 'lg'
    'tooltip' => 'Edit',
    'iconOnly' => true,
])

<x-button
    variant="primary"
    :size="$size"
    icon="fas fa-edit"
    title="{{ $tooltip }}"
    {{ $attributes }}
>
    @if(!$iconOnly)
        {{ $slot->isEmpty() ? 'Edit' : $slot }}
    @endif
</x-button>
