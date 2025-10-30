@props(['active'])

<div class="p-6" x-data="{
    active: '{{ $active }}'
}">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            {{ $header }}
        </ul>

    </div>
    <div class="px-4 mt-4">
        {{ $slot }}
    </div>
</div>
