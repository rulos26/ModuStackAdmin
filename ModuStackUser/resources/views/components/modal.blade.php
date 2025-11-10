@props([
    'name',
    'show' => false,
    'maxWidth' => 'lg',
])

@php
$dialogClass = match ($maxWidth) {
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
    default => '',
};

$isVisible = (bool) $show;
@endphp

<div {{ $attributes->merge([
        'id' => $name,
        'class' => trim('modal fade'.($isVisible ? ' show' : '')),
    ]) }}
    tabindex="-1"
    aria-hidden="{{ $isVisible ? 'false' : 'true' }}"
    @if($isVisible) style="display: block;" @endif>
    <div class="modal-dialog {{ $dialogClass }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@if ($isVisible)
    <div class="modal-backdrop fade show"></div>
@endif
