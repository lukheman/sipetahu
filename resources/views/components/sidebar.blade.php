@props([
    'brandName' => '',
    'brandIcon' => 'fas fa-layer-group'
])

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="height: 32px; width: 32px; border-radius: 8px; object-fit: cover;">
        <span style="font-family: var(--font-display, 'Syne', sans-serif); font-weight: 800; font-size: 1.25rem; color: var(--text-primary); letter-spacing: -0.02em; line-height: 1;">{{ $brandName }}</span>
    </div>
    <div class="sidebar-menu">
        {{ $slot }}
    </div>
</div>
