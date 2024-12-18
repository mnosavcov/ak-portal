<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class EmailLayout extends Component
{
    public $unsubscribeUrl;

    public function __construct($unsubscribeUrl = null)
    {
        $this->unsubscribeUrl = $unsubscribeUrl;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.email');
    }
}
