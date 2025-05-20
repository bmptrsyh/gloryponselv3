<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chat;
use App\Events\Inbox;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{

public function listInbox()
{
    $customers = Customer::all(); // atau bisa ditambahkan pagination / search
    return view('admin.listInbox', compact('customers'));
}


public function adminInbox($id)
{
    $customer = Customer::findOrFail($id);

    $chats = Chat::where(function ($q) use ($customer) {
        $q->where('sender_id', $customer->id_customer)
          ->where('sender_type', 'customer');
    })->orWhere(function ($q) use ($customer) {
        $q->where('receiver_id', $customer->id_customer)
          ->where('receiver_type', 'customer');
    })->orderBy('created_at')->get();

    return view('admin.inbox', compact('customer', 'chats'));
}



    public function customerInbox()
{
    $customerId = auth()->id();

    $chats = Chat::where(function ($q) use ($customerId) {
        $q->where('sender_id', $customerId)
            ->where('sender_type', 'customer');
    })->orWhere(function ($q) use ($customerId) {
        $q->where('receiver_id', $customerId)
            ->where('receiver_type', 'customer');
    })->orderBy('created_at')->get();

    return view('customer.inbox', compact('chats'));
}

    public function send(Request $request)
{
    $request->validate([
        'message' => 'required'
    ]);

    $message = $request->message;
    $senderType = 'customer';
    $receiverType = 'admin';
    $senderId = auth()->id();
    $receiverId = 1;
    
    Chat::create([
        'sender_id' => $senderId,
        'sender_type' => $senderType,
        'receiver_id' => $receiverId,
        'receiver_type' => $receiverType,
        'message' => $message
    ]);

    broadcast(new Inbox($message, $senderType, $receiverType, $receiverId, $senderId));

    return response()->json(['success' => true]);
}

public function sendAdmin(Request $request)
{
    $request->validate([
        'message' => 'required',
        'customer_id' => 'required|integer',
    ]);

    $message = $request->message;
    $senderType = 'admin';
    $receiverType = 'customer';
    $senderId = auth('admin')->id();
    $receiverId = $request->customer_id;

    Chat::create([
        'sender_id' => $senderId,
        'sender_type' => $senderType,
        'receiver_id' => $receiverId,
        'receiver_type' => $receiverType,
        'message' => $message
    ]);

    broadcast(new Inbox($message, $senderType, $receiverType, $receiverId, $senderId));

    return response()->json(['success' => true]);
}

}
