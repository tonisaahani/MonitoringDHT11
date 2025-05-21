<?php

namespace App\Livewire\Admin\Node;

use App\Models\Node;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public $addNodeState = false;
    public $code;
    public $name;
    #[On('AdminNodeCreate_refresh')]
    public function render()
    {
        return view('livewire.admin.node.create');
    }

    public function addNode()
    {
        $this->addNodeState = !$this->addNodeState;
    }

    public function saveNode()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        Node::create([
            'code' => $this->code,
            'name' => $this->name,
        ]);

        $this->toast(
            type: 'success',
            title: 'Node has been saved!',
            description: null,
            position: 'toast-top toast-center',
            icon: 'o-information-circle',
            css: 'alert-info',
            timeout: 3000,
            redirectTo: null
        );

        $this->dispatch('refreshNodeTable');

        $this->reset();
    }
}
