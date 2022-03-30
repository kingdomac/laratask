<?php

namespace App\Http\Livewire\Admin;

use App\Models\Label;
use Livewire\Component;

class LabelSetup extends Component
{
    public $label;

    public $name;
    public $color;
    public $icon;
    public bool $message = false;

    public function mount()
    {
        $label = Label::findOrFail($this->label->id);
        $this->name = $label->name;
        $this->color = $label->color;
        $this->icon = $label->icon;
    }

    public function updatedName()
    {
        $this->message = false;
    }

    public function update()
    {
        if ($this->name && $this->color) {
            Label::where('id', $this->label->id)->update(['name' => $this->name, 'color' => $this->color, 'icon' => $this->icon]);
            $this->message = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.label-setup');
    }
}
