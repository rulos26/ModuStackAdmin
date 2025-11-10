@props(['align' => 'end', 'menuClass' => '', 'containerClass' => ''])

@php
$alignmentClasses = match ($align) {
    'start' => 'dropdown-menu-start',
    'center' => '',
    default => 'dropdown-menu-end',
};
@endphp

<div {{ $attributes->merge(['class' => trim('dropdown position-relative '.$containerClass)]) }}
     x-data="{ open: false }"
     @click.outside="open = false"
     @close.stop="open = false">
    <div @click="open = !open" role="button">
        {{ $trigger }}
    </div>

    <div x-show="open"
         :class="{ 'show': open }"
         class="dropdown-menu {{ $alignmentClasses }} {{ $menuClass }}"
         @click="open = false">
        {{ $content }}
    </div>
</div>
