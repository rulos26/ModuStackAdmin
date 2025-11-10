@props(['active'])

@php
$isActive = (bool) ($active ?? false);
$classes = 'list-group-item list-group-item-action';

if ($isActive) {
    $classes .= ' active';
}
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($isActive) aria-current="page" @endif>
    {{ $slot }}
</a>
