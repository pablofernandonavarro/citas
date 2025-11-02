<div class="flex items-center space-x-2">
    <a href="{{ route('admin.doctors.edit', $doctor) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    <a href="{{ route('admin.doctors.schedules', $doctor) }}">
        <x-wireui-button primary type="button" green xs>
            <icon class="fa-solid fa-clock" />
        </x-wireui-button>
   
</div>