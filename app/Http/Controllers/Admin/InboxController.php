<?php

namespace App\Http\Controllers\Admin;

use App\Events\Inbox;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InboxController extends Controller
{
    public function listInbox () {
        
        return view('admin.listInbox');
    }

    public function adminInbox () {
        Inbox::dispatch('nyoba');
        return view('admin.inbox');
    }

    public function customerInbox () {
        return view('customer.inbox');
    }
}
