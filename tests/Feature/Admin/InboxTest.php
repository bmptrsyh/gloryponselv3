<?php

namespace Tests\Feature\Admin;

use App\Events\Inbox;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class InboxTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Admin::factory()->create();

        return $this->actingAs($admin, 'admin');
    }

    private function actingAsCustomer()
    {
        /** @var \App\Models\Customer $customer */
        $customer = Customer::factory()->create();

        return $this->actingAs($customer, 'web');
    }

    /** ✅ TEST listInbox() */
    public function test_list_inbox_menampilkan_data_customer_dan_ringkasan()
    {
        $this->actingAsAdmin();

        $customer = Customer::factory()->create();

        Chat::factory()->create([
            'sender_id' => $customer->id_customer,
            'sender_type' => 'customer',
            'receiver_type' => 'admin',
            'dibaca' => false,
        ]);

        $response = $this->get(route('admin.listInbox'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.listInbox');
        $response->assertViewHas([
            'customers',
            'totalUnread',
            'totalMessages',
            'totalAnswer',
        ]);
    }

    /** ✅ TEST adminInbox() */
    public function test_admin_inbox_menampilkan_chat_dan_menandai_sudah_dibaca()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $customer = Customer::factory()->create();

        $chat = Chat::factory()->create([
            'sender_id' => $customer->id_customer,
            'sender_type' => 'customer',
            'receiver_id' => $admin->id,
            'receiver_type' => 'admin',
            'dibaca' => false,
        ]);

        $response = $this->get(route('admin.inbox', $customer->id_customer));

        $response->assertStatus(200);
        $response->assertViewIs('admin.inbox');
        $response->assertViewHas(['customer', 'chats']);

        // ✅ Pastikan dibaca jadi true
        $this->assertDatabaseHas('chat', [
            'id_chat' => $chat->id_chat,
            'dibaca' => true,
        ]);
    }

    /** ✅ TEST customerInbox() */
    public function test_customer_inbox_menampilkan_chat_customer()
    {
        /** @var \App\Models\Customer $customer */
        $customer = Customer::factory()->create();
        $this->actingAs($customer);

        Chat::factory()->create([
            'sender_id' => $customer->id_customer,
            'sender_type' => 'customer',
        ]);

        $response = $this->get(route('customer.inbox'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.inbox');
        $response->assertViewHas('chats');
    }

    /** ✅ TEST send() CUSTOMER → ADMIN + EVENT */
    public function test_customer_dapat_mengirim_pesan_ke_admin_dan_event_dikirim()
    {
        Event::fake();

        /** @var \App\Models\Customer $customer */
        $customer = Customer::factory()->create();
        $this->actingAs($customer);

        $response = $this->postJson(route('send.inbox'), [
            'message' => 'Halo Admin',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat', [
            'sender_type' => 'customer',
            'receiver_type' => 'admin',
            'message' => 'Halo Admin',
            'dibaca' => false,
        ]);

        Event::assertDispatched(Inbox::class);
    }

    /** ✅ TEST sendAdmin() ADMIN → CUSTOMER + EVENT */
    public function test_admin_dapat_mengirim_pesan_ke_customer_dan_event_dikirim()
    {
        Event::fake();

        /** @var \App\Models\Admin $admin */
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $customer = Customer::factory()->create();

        $response = $this->postJson(route('admin.send.inbox'), [
            'message' => 'Halo Customer',
            'customer_id' => $customer->id_customer,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat', [
            'sender_type' => 'admin',
            'receiver_type' => 'customer',
            'message' => 'Halo Customer',
            'dibaca' => false,
        ]);

        Event::assertDispatched(Inbox::class);
    }
}
