<div class="flex items-center space-x-2">
    @can('update_doctor')
    <a href="{{ route('admin.doctors.edit', $doctor) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    @endcan
     @can('update_doctor')
    <a href="{{ route('admin.doctors.schedules', $doctor) }}">
        <x-wireui-button green type="button"  xs>
            <icon class="fa-solid fa-clock" />
        </x-wireui-button>
    </a>
    @endcan
</div>
