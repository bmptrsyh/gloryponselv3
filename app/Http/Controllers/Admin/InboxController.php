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
    $customers = Customer::whereIn('id_customer', function($query) {
    $query->select('sender_id')
        ->from('chat')
        ->where('sender_type', 'customer');
})->withCount(['sentMessages as unread_messages_count' => function ($query) {
    $query->where('receiver_type', 'admin')
          ->where('dibaca', false);
}])->withCount(['sentMessages as total_messages_count' => function ($query) {
        $query->where('receiver_type', 'admin');
    }])->withCount(['sentMessages as total_answer_count' => function ($query) {
        $query->where('receiver_type', 'customer');
    }])->get();

$totalUnread = $customers->sum('unread_messages_count');
$totalMessages = $customers->sum('total_messages_count');
$totalAnswer = $customers->sum('total_answer_count');

    return view('admin.listInbox', compact('customers', 'totalUnread', 'totalMessages', 'totalAnswer'));
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
    
    Chat::where('receiver_type', 'admin')
    ->where('receiver_id', auth('admin')->id())
    ->where('sender_type', 'customer')
    ->where('sender_id', $customer->id_customer)
    ->update(['dibaca' => true]);

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
        'message' => $message,
        'dibaca' => false
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
        'message' => $message,
        'dibaca' => false
    ]);

    broadcast(new Inbox($message, $senderType, $receiverType, $receiverId, $senderId));

    return response()->json(['success' => true]);
}

}
