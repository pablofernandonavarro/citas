<div class="flex items-center space-x-2">
    @can('update_role')
        <a href="{{ route('admin.roles.edit', $role) }}">
            <x-wireui-button primary type="button" blue xs>
                <icon class="fa-solid fa-pen-to-square" />
            </x-wireui-button>
        </a>
    @endcan

    @can('delete_role')
    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="delete-form">
        @csrf
        @method('DELETE')

        <x-wireui-button danger type="submit" red xs>
            <icon class="fa-solid fa-trash" />
        </x-wireui-button>
    </form>
    @endcan
</div>
