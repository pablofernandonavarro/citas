<div class="flex items-center space-x-2">
@can('update_user')
    <a href="{{ route('admin.users.edit', $user) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>

@endcan
@can('delete_user')
    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
        class="delete-form">
        @csrf
        @method('DELETE')

        <x-wireui-button danger type="submit" red xs>
            <icon class="fa-solid fa-trash" />
        </x-wireui-button>
    </form>
    @endcan
</div>
