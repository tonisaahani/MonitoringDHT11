<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <x-card title="Admin | Node Management" shadow separator>
        <livewire:admin.node.create/>

        <x-table :headers="$headers" :rows="$nodes" with-pagination>
            @scope('cell_action', $node)
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4 text-right">
                    <x-button icon="o-pencil" label="Edit" color="primary" size="xs" wire:click="$dispatch('AdminNodeEdit_editModal', { id: {{$node->id}} })" />
                    <x-button icon="o-trash" label="Delete" color="error" size="xs" wire:click="delete({{ $node->id }})" />
                </div>
            </div>
            @endscope
        </x-table>
    </x-card>
    <livewire:admin.node.edit/>
</div>
