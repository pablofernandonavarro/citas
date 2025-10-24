<div class="flex items-center space-x-2">
    <a href="{{ route('admin.roles.edit', $role) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol?');">
        @csrf
        @method('DELETE')
        <x-wireui-button danger type="submit" red xs>
            <icon class="fa-solid fa-trash" />
        </x-wireui-button>
</div>
