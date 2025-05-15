<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function listInbox () {
        

        return view('admin.listInbox');
    }

    public function adminInbox () {
        return view('admin.inbox');
    }

    public function customerInbox () {
        return view('customer.inbox');
    }
}
