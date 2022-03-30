<?php

namespace App\Http\Traits;


trait WithAlert
{
    public $message = '';

    public function alertMessage($message)
    {
        $this->render();
        session()->flash('message', $message);
    }
}
