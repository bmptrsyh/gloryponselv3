<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarLink extends Component
{
    public $route;

    public function __construct($route = null)
    {
        $this->route = $route;
    }
    

    public function render()
    {
        return view('components.sidebar-link');
    }
}
