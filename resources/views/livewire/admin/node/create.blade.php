<div>
    @if ($addNodeState == false)
        <div class="text-left">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4 text-right">
                    <x-button wire:click="addNode" icon="o-plus-circle" label="Add node"
                        class="btn-outline btn-success btn-sm" />
                </div>
            </div>
        </div>
    @else
        <div class="text-left">
            <h1>
                <b>Add Node</b>
            </h1>
            <br />
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4 text-right">
                    <x-button wire:click="addNode" icon="o-x-mark" class="btn-outline btn-circle btn-error btn-xs" />
                </div>
            </div>
            <br />
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/4 sm:flex-none xl:mb-0 xl:w-1/4 text-left">
                    <x-input wire:model="code" label="Code" icon="o-at-symbol"
                        placeholder="Please Add" />
                </div>
                <div class="w-full max-w-full px-3 mb-6 sm:w-2/4 sm:flex-none xl:mb-0 xl:w-2/4 text-left">
                    <x-input wire:model="name" label="Node Name" icon="o-at-symbol"
                        placeholder="Please Add" />
                </div>
            </div>
            <br />
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/4 sm:flex-none xl:mb-0 xl:w-1/4 text-left">

                    <x-button
                    wire:click="saveNode"
                    wire:loading.attr="disabled"
                    wire:target="saveNode"
                    spinner
                    label="Save"
                    icon="o-bookmark"
                    class="btn-sm btn-success"
                />
            </div>
            </div>
        </div>
    @endif
</div>
