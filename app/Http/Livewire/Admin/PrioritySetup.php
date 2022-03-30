<?php

namespace App\Http\Livewire\Admin;

use App\Models\Priority;
use Livewire\Component;

class PrioritySetup extends Component
{
    public $priority;

    public $name;
    public $color;
    public bool $message = false;

    public function mount()
    {
        $priority = Priority::findOrFail($this->priority->id);
        $this->name = $priority->name;
        $this->color = $priority->color;
    }

    public function updatedName()
    {
        $this->message = false;
    }

    public function update()
    {
        if ($this->name && $this->color) {
            Priority::where('id', $this->priority->id)->update(['name' => $this->name, 'color' => $this->color]);
            $this->message = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.priority-setup');
    }
}
