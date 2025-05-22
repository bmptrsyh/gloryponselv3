<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chat';
    protected $primaryKey = 'id_chat';
    protected $fillable = ['sender_id', 'sender_type', 'receiver_id', 'receiver_type', 'message', 'dibaca'];



    // public function sender()
    // {
    //     return $this->belongsTo(Customer::class, 'sender_id');
    // }

    // // Relationship to get receiver profile
    // public function receiver()
    // {
    //     return $this->belongsTo(Customer::class, 'receiver_id');
    // }

    // public function receiverProfilee()
    // {
    //     return $this->belongsTo(Admin::class, 'receiver_id', 'id')->select(['id', 'name', 'picture', 'bio']);
    // }

    // public function senderProfilee()
    // {
    //     return $this->belongsTo(Admin::class, 'sender_id', 'id')->select(['id', 'name', 'picture', 'bio']);
    // }
     
    // public function receiverSellerProfile()
    // {
    //     return $this->belongsTo(Customer::class, 'receiver_id', 'id')->select(['id', 'name', 'picture', 'bio']);
    // }

    // public function senderSellerProfile()
    // {
    //     return $this->belongsTo(Customer::class, 'sender_id', 'id')->select(['id', 'name', 'picture', 'bio']);
    // }

}