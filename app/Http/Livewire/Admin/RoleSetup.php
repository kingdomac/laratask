<?php

namespace App\Http\Livewire\Admin;

use App\Models\Role;
use Livewire\Component;

class RoleSetup extends Component
{
    public $role;

    public $name;
    public $color;
    public $icon;
    public bool $message;

    public function mount()
    {
        $role = Role::findOrFail($this->role->id);
        $this->name = $role->name;
        $this->color = $role->color;
        $this->icon = $role->icon;
    }
    public function update()
    {
        if ($this->name && $this->color) {
            Role::where('id', $this->role->id)->update(['name' => $this->name, 'color' => $this->color, 'icon' => $this->icon]);
            $this->message = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.role-setup');
    }
}
