@props(['active'])

@php
$isActive = (bool) ($active ?? false);
$classes = 'nav-link d-inline-flex align-items-center gap-2';

if ($isActive) {
    $classes .= ' active';
}
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($isActive) aria-current="page" @endif>
    {{ $slot }}
</a>
