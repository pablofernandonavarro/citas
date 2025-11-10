<div class="flex items-center space-x-2">
    @can('update_patient')
    <a href="{{ route('admin.patients.edit', $patient) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    @endcan

</div>
