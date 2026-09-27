<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopOrderTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name'      => 'Juan Dela Cruz',
            'email'          => 'runner@example.com',
            'mobile_number'  => '09171234567',
            'size'           => 'L',
            'quantity'       => 2,
            'notify_consent' => true,
        ], $overrides);
    }

    public function test_shop_shows_every_product_with_its_order_box(): void
    {
        $this->get('/shop')
            ->assertOk()
            ->assertSee('TGC100 Trail Cap')
            ->assertSee('TGC100 Finisher Hoodie')
            ->assertSee('TGC100 Trail Tote')
            ->assertSee('id="finisher-hoodie"', false)
            ->assertSee('id="trail-cap-full_name"', false)
            ->assertSee('View sizing chart');
    }

    public function test_product_urls_redirect_to_their_shop_section(): void
    {
        $this->get('/shop/finisher-hoodie')->assertRedirect(route('shop.index') . '#finisher-hoodie');

        $this->get('/shop/not-a-product')->assertNotFound();
    }

    public function test_sized_order_is_saved_with_catalog_price_and_reference(): void
    {
        $response = $this->postJson('/shop/finisher-hoodie/order', $this->validPayload(['unit_price' => 1]))
            ->assertOk()
            ->assertJson(['status' => 'created']);

        $order = Order::sole();
        $this->assertSame($order->reference, $response->json('reference'));
        $this->assertMatchesRegularExpression('/^TGC-[A-HJKMNP-Z2-9]{6}$/', $order->reference);
        $this->assertSame('finisher-hoodie', $order->product);
        $this->assertSame('TGC100 Finisher Hoodie', $order->product_name);
        $this->assertSame('L', $order->size);
        $this->assertSame(2, $order->quantity);
        // Price comes from config, not the request
        $this->assertEquals(1399, $order->unit_price);
        $this->assertEquals(2798, $order->total);
    }

    public function test_one_size_order_needs_no_size(): void
    {
        $payload = $this->validPayload(['quantity' => 1]);
        unset($payload['size']);

        $this->postJson('/shop/trail-cap/order', $payload)->assertOk();

        $order = Order::sole();
        $this->assertNull($order->size);
        $this->assertEquals(650, $order->total);
    }

    public function test_sized_order_rejects_missing_or_unknown_size(): void
    {
        $payload = $this->validPayload();
        unset($payload['size']);

        $this->postJson('/shop/finisher-hoodie/order', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['size']);

        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload(['size' => '4XL']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['size']);

        $this->assertSame(0, Order::count());
    }

    public function test_order_requires_consent_and_quantity_limits(): void
    {
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload([
            'notify_consent' => false,
            'quantity'       => config('shop.max_quantity') + 1,
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['notify_consent', 'quantity']);
    }

    public function test_ordering_unknown_product_404s(): void
    {
        $this->postJson('/shop/not-a-product/order', $this->validPayload())->assertNotFound();
    }

    public function test_admin_sees_and_filters_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload());
        $capPayload = $this->validPayload(['full_name' => 'Maria Santos', 'quantity' => 1]);
        unset($capPayload['size']);
        $this->postJson('/shop/trail-cap/order', $capPayload);

        $this->actingAs($admin)
            ->get('/admin/orders')
            ->assertOk()
            ->assertSee('Juan Dela Cruz')
            ->assertSee('Maria Santos');

        $this->actingAs($admin)
            ->get('/admin/orders?product=trail-cap')
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertDontSee('Juan Dela Cruz');
    }

    public function test_new_orders_start_pending_and_admin_can_view_one(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload());
        $order = Order::sole();

        $this->assertSame('pending', $order->status);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('09171234567')
            ->assertSee('Mark as Fulfilled');
    }

    public function test_admin_can_fulfill_cancel_and_reopen_an_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload());
        $order = Order::sole();

        $this->actingAs($admin)
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'fulfilled'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('fulfilled', $order->status);
        $this->assertTrue($order->statusChangedBy->is($admin));
        $this->assertNotNull($order->status_changed_at);

        $this->actingAs($admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'pending']);
        $this->actingAs($admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'cancelled']);
        $this->assertSame('cancelled', $order->fresh()->status);

        $this->actingAs($admin)
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'shipped'])
            ->assertSessionHasErrors('status');
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_admin_filters_orders_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload());
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload(['full_name' => 'Maria Santos']));
        Order::where('full_name', 'Maria Santos')->sole()->markStatus('fulfilled', $admin);

        $this->actingAs($admin)
            ->get('/admin/orders?status=fulfilled')
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertDontSee('Juan Dela Cruz');
    }

    public function test_admin_export_downloads_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->postJson('/shop/finisher-hoodie/order', $this->validPayload());

        $response = $this->actingAs($admin)->get('/admin/orders/export');

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString(Order::sole()->reference, $response->streamedContent());
    }

    public function test_non_admins_cannot_view_orders(): void
    {
        $this->get('/admin/orders')->assertRedirect('/login');

        $this->postJson('/shop/trail-cap/order', array_diff_key($this->validPayload(), ['size' => 1]));
        $this->patch(route('admin.orders.updateStatus', Order::sole()), ['status' => 'fulfilled'])
            ->assertRedirect('/login');
        $this->assertSame('pending', Order::sole()->status);
    }
}
