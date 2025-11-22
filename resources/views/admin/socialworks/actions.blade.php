<div class="flex items-center space-x-2">
    @can('update_socialwork')
    <a href="{{ route('admin.socialworks.edit', $socialwork) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    @endcan
    @can('delete_socialwork')
    <form method="POST" action="{{ route('admin.socialworks.destroy', $socialwork) }}"
        class="delete-form">
        @csrf
        @method('DELETE')

        <x-wireui-button danger type="submit" red xs>
            <icon class="fa-solid fa-trash" />
        </x-wireui-button>
    </form>
    @endcan
</div>
