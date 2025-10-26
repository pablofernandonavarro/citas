<div class="flex items-center space-x-2">
    <a href="{{ route('admin.patients.edit', $patient) }}">
        <x-wireui-button primary type="button" blue xs>
            <icon class="fa-solid fa-pen-to-square" />
        </x-wireui-button>
    </a>
    <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" 
        class="delete-form">
        @csrf
        @method('DELETE')
    
        <x-wireui-button danger type="submit" red xs>
            <icon class="fa-solid fa-trash" />
        </x-wireui-button>
    </form>
</div>
