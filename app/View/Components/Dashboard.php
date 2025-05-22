<?php

namespace App\View\Components;

use Closure;
use App\Models\Chat;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Dashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $unreadCount = Chat::where('receiver_type', 'admin')
        ->where('receiver_id', auth('admin')->id())
        ->where('dibaca', false)
        ->count();

    return view('components.dashboard', [
        'unreadCount' => $unreadCount,
    ]);
    }
}
