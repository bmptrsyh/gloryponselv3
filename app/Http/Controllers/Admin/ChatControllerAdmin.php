<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatControllerAdmin extends Controller
{
    public function index () {
        return view ('admin.listInbox');
    }

    public function chat($id) {
        return view ('admin.inbox');
    }
}
