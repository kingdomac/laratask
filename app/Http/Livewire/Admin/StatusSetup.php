<?php

namespace App\Http\Livewire\Admin;

use App\Models\Status;
use Livewire\Component;

class StatusSetup extends Component
{
    public $status;

    public $name;
    public $color;
    public bool $message = false;

    public function mount()
    {
        $status = Status::findOrFail($this->status->id);
        $this->name = $status->name;
        $this->color = $status->color;
    }

    public function updatedName()
    {
        $this->message = false;
    }

    public function update()
    {
        if ($this->name && $this->color) {
            status::where('id', $this->status->id)->update(['name' => $this->name, 'color' => $this->color]);
            $this->message = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.status-setup');
    }
}
