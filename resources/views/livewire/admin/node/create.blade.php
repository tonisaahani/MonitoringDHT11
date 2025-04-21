namespace App\Http\Livewire\Admin\Node;

use Livewire\Component;

class Create extends Component
{
public $addNodeState = false; // Menambahkan properti $addNodeState

public function render()
{
return view('livewire.admin.node.create');
}

public function addNode()
{
$this->addNodeState = true; // Ubah state ketika add node dipanggil
}
}
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
                <!-- Form untuk menambah node di sini -->
            </div>
        </div>
    @endif
</div>
