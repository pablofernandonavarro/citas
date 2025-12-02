<div class="flex items-center space-x-2">

@can('read_appointment')
    <a href="{{ route('admin.appointments.show', $appointment) }}">
        <x-wireui-button  type="button" blue xs>
            <icon class="fa-solid fa-eye" />
        </x-wireui-button>
    </a>
    @endcan



    @can('update_appointment')
    <a href="{{ route('admin.appointments.edit', $appointment) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    @endcan
    @can('update_appointment')
   <x-wireui-button
        href="{{ route('admin.appointments.consultation', $appointment) }}"
        green
        type="button"
        xs>
        <icon class="fa-solid fa-stethoscope" />
    </x-wireui-button>
    @endcan

</div>
