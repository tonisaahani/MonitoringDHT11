<div>
    <x-modal wire:model="showeditModalStatus" title="Edit node" subtitle="Admin dashboard" separator>
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4 text-right">
                <x-input label="Code" wire:model="code" icon="o-user" hint="Code" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4 text-right">
                <x-input label="Name" wire:model="name" icon="o-user" hint="Name" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4 text-right">
                <x-button
                wire:click="updateNode"
                wire:loading.attr="disabled"
                wire:target="updateNode"
                spinner
                label="Update"
                icon="o-bookmark"
                class="btn-outline btn-sm btn-success"/>
            </div>
        </div>
    </x-modal>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
</div>
