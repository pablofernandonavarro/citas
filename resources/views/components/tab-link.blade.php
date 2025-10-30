@props(['tab'])


<li class="me-2">
    <a href="#" x-on:click.prevent="active = '{{ $tab }}'" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
        :class="{ 'border-red-500 text-red-600': active === '{{ $tab }}' }"
        :aria-current="active === '{{ $tab }}' ? 'page' : false">
        <i class="fas fa-user mr-2"></i>
        {{ $slot }}
    </a>
</li>
