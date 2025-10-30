@props(["tab"])
<div x-show="active === '{{ $tab }}'">
    {{ $slot }}
</div>