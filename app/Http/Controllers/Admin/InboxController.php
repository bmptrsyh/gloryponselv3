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
        
        return view('admin.inbox');
    }

    public function customerInbox () {
        return view('customer.inbox');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $message = $request->message;
        event(new Inbox($message));

        return response()->json(['success' => true]);
    }

}
