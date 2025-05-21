<?php

namespace App\Livewire\Admin\Node;

use App\Models\Node;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;


class Idx extends Component
{
    use Toast;
    use WithPagination;
    public $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'code', 'label' => 'Code'],
        ['key' => 'name', 'label' => 'Node name'],
        ['key' => 'action', 'label' => 'Action'],
    ];

    #[On('AdminNodeIdx_refresh')]
    public function render()
    {
        $this->dispatch('AdminNodeIdx_refresh');

        $nodes = Node::paginate(5);
        return view('livewire.admin.node.idx', ['nodes' => $nodes]);
    }
    public function delete($id)
    {
        Node::find($id)->delete();
        $this->toast(
            type: 'success',
            title: 'Node has beeen deleted!',
            description: null,                  // optional (text)
            position: 'toast-top toast-center',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );

        $this->dispatch('AdminNodeIdx_refresh');
    }
}
