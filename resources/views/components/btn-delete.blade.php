@props([
    'size' => 'sm', // 'sm', 'md', 'lg'
    'tooltip' => 'Delete',
    'iconOnly' => true,
])

<x-button 
    variant="danger" 
    :size="$size"
    icon="fas fa-trash-alt"
    title="{{ $tooltip }}"
    {{ $attributes }}
>
    @if(!$iconOnly)
        {{ $slot->isEmpty() ? 'Delete' : $slot }}
    @endif
</x-button>
