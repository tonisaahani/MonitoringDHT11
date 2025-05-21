<?php

namespace App\Livewire\Admin\Node;

use App\Models\Node;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;



class Edit extends Component
{
    use Toast;
    public $showeditModalStatus = false;
    public $nodeId = null;
    public $code;
    public $name;

    #[On('AdminNodeCreate_refresh')]
    public function render()
    {
        $this->dispatch('refreshNodeTable');

        if (!is_null($this->nodeId)) {
            $node = Node::find($this->nodeId);
            $this->code = $node->code;
            $this->name = $node->name;
        }
        return view('livewire.admin.node.edit');
    }


    #[On('AdminNodeEdit_editModal')]
    public function nodeEdit($id)
    {
        $this->showeditModalStatus = true;
        $this->nodeId = $id;
    }

    public function updateNode()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required',
        ]);
        Node::find($this->nodeId)->update([
            'code' => $this->code,
            'name' => $this->name,
        ]);

        $this->showeditModalStatus = false;

        $this->dispatch('AdminNodeIdx_refresh');

        $this->toast(
            type: 'success',
            title: 'Node has beeen updated!',
            description: null,                  // optional (text)
            position: 'toast-top toast-center',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
