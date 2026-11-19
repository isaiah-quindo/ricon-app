<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmed;
use App\Mail\OrderPaymentRejected;
use App\Mail\OrderShipped;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The launch catalog (trail cap + tee) is seeded by migration with zero stock.
 */
class ShopOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Mail::fake();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function variant(string $slug, string $color, ?string $size = null): ProductVariant
    {
        return ProductVariant::whereHas('product', fn($q) => $q->where('slug', $slug))
            ->whereHas('color', fn($q) => $q->where('label', $color))
            ->where('size', $size)
            ->sole();
    }

    private function stocked(string $slug, string $color, ?string $size = null, int $stock = 10): ProductVariant
    {
        $variant = $this->variant($slug, $color, $size);
        $variant->update(['stock' => $stock]);

        return $variant;
    }

    private function checkout(array $items, array $overrides = [])
    {
        return $this->post(route('shop.store'), array_merge([
            'items'            => $items,
            'full_name'        => 'Juan Dela Cruz',
            'email'            => 'runner@example.com',
            'mobile_number'    => '09171234567',
            'shipping_area'    => 'ncr',
            'address_line'     => '12 Session Rd, Brgy. Malcolm',
            'city'             => 'Quezon City',
            'province'         => 'Metro Manila',
            'postal_code'      => '1100',
            'proof_of_payment' => UploadedFile::fake()->image('receipt.jpg'),
            'payment_consent'  => '1',
        ], $overrides));
    }

    private function placeOrder(): Order
    {
        $cap = $this->stocked('trail-cap', 'Khaki', null, 5);
        $this->checkout([['variant_id' => $cap->id, 'quantity' => 2]])->assertRedirect(route('shop.success'));

        return Order::sole();
    }

    // ---------------------------------------------------------------- public

    public function test_shop_and_checkout_pages_render_the_catalog(): void
    {
        $this->stocked('running-in-constant-tee', 'Army Green', 'L', 3);

        $this->get('/shop')
            ->assertOk()
            ->assertSee('TGC100 Trail Cap')
            ->assertSee('Running in Constant Tee')
            ->assertSee('Add to Cart')
            ->assertSee('tee-army-back.png', false);

        $this->get('/shop/checkout')
            ->assertOk()
            ->assertSee('Complete Your Order')
            ->assertSee('7-591-41115-4')
            ->assertSee('Mindanao');
    }

    public function test_cart_checkout_creates_one_order_with_every_item_and_takes_stock(): void
    {
        $cap = $this->stocked('trail-cap', 'Sage Green', null, 5);
        $tee = $this->stocked('running-in-constant-tee', 'Black', 'M', 4);

        $this->checkout([
            ['variant_id' => $cap->id, 'quantity' => 2],
            ['variant_id' => $tee->id, 'quantity' => 1],
            ['variant_id' => $cap->id, 'quantity' => 1], // same item twice merges
        ], ['shipping_area' => 'visayas'])->assertRedirect(route('shop.success'));

        $order = Order::with('items')->sole();
        $this->assertSame('payment_submitted', $order->status);
        $this->assertMatchesRegularExpression('/^TGC-[A-HJKMNP-Z2-9]{6}$/', $order->reference);
        $this->assertCount(2, $order->items);
        $this->assertEquals(650 * 4, $order->subtotal);
        $this->assertEquals(220, $order->shipping_fee);
        $this->assertEquals(650 * 4 + 220, $order->total);
        Storage::disk('s3')->assertExists($order->payment_proof_path);

        $teeLine = $order->items->firstWhere('product_variant_id', $tee->id);
        $this->assertSame('Black', $teeLine->color);
        $this->assertSame('M', $teeLine->size);

        $this->assertSame(2, $cap->fresh()->stock);
        $this->assertSame(3, $tee->fresh()->stock);

        $this->get(route('shop.success'))->assertOk()->assertSee($order->reference);
    }

    public function test_success_page_without_an_order_goes_back_to_the_shop(): void
    {
        $this->get(route('shop.success'))->assertRedirect(route('shop.index'));
    }

    public function test_pickup_needs_no_address_but_delivery_does(): void
    {
        $cap = $this->stocked('trail-cap', 'Black');

        $this->checkout([['variant_id' => $cap->id, 'quantity' => 1]], [
            'shipping_area' => 'luzon', 'address_line' => '', 'city' => '', 'province' => '', 'postal_code' => '',
        ])->assertSessionHasErrors(['address_line', 'city', 'province', 'postal_code']);
        $this->assertSame(0, Order::count());

        $this->checkout([['variant_id' => $cap->id, 'quantity' => 1]], [
            'shipping_area' => 'pickup', 'address_line' => '', 'city' => '', 'province' => '', 'postal_code' => '',
        ])->assertRedirect(route('shop.success'));

        $order = Order::sole();
        $this->assertTrue($order->isPickup());
        $this->assertEquals(0, $order->shipping_fee);
        $this->assertEquals(650, $order->total);
    }

    public function test_checkout_refuses_more_than_is_in_stock(): void
    {
        $tee = $this->stocked('running-in-constant-tee', 'Khaki', 'S', 1);

        $this->checkout([['variant_id' => $tee->id, 'quantity' => 2]])
            ->assertSessionHasErrors('items');

        $this->assertSame(0, Order::count());
        $this->assertSame(1, $tee->fresh()->stock);
        $this->assertEmpty(Storage::disk('s3')->allFiles());
    }

    public function test_checkout_requires_proof_consent_and_known_items(): void
    {
        $this->checkout([['variant_id' => '9d6f6c1e-0000-4000-8000-000000000000', 'quantity' => 1]], [
            'proof_of_payment' => null,
            'payment_consent'  => null,
        ])->assertSessionHasErrors(['proof_of_payment', 'payment_consent']);

        $this->checkout([['variant_id' => '9d6f6c1e-0000-4000-8000-000000000000', 'quantity' => 1]])
            ->assertSessionHasErrors('items');

        $this->checkout([])->assertSessionHasErrors('items');
    }

    public function test_hidden_products_cannot_be_bought(): void
    {
        $cap = $this->stocked('trail-cap', 'Khaki');
        Product::where('slug', 'trail-cap')->update(['is_active' => false]);

        $this->checkout([['variant_id' => $cap->id, 'quantity' => 1]])->assertSessionHasErrors('items');
        $this->assertSame(0, Order::count());
        $this->get('/shop')->assertDontSee('TGC100 Trail Cap');
    }

    // ----------------------------------------------------------------- admin

    public function test_admin_confirms_payment_and_emails_the_buyer(): void
    {
        $order = $this->placeOrder();

        $this->actingAs($this->admin)->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Confirm Payment')
            ->assertSee('TGC100 Trail Cap')
            ->assertSee('12 Session Rd');

        $this->actingAs($this->admin)
            ->post(route('admin.orders.confirm', $order), ['send_email' => '1'])
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('confirmed', $order->status);
        $this->assertTrue($order->paymentVerifiedBy->is($this->admin));
        $this->assertNotNull($order->confirmation_emailed_at);
        Mail::assertSent(OrderConfirmed::class, fn($m) => $m->hasTo('runner@example.com') && $m->order->is($order));
    }

    public function test_admin_can_confirm_without_emailing_then_send_it_later(): void
    {
        $order = $this->placeOrder();

        $this->actingAs($this->admin)->post(route('admin.orders.confirm', $order));
        $this->assertSame('confirmed', $order->fresh()->status);
        Mail::assertNothingSent();

        $this->actingAs($this->admin)->post(route('admin.orders.resendConfirmation', $order))->assertSessionHas('success');
        Mail::assertSent(OrderConfirmed::class, 1);
    }

    public function test_rejecting_a_payment_restocks_and_emails_the_reason(): void
    {
        $order = $this->placeOrder();
        $cap = $this->variant('trail-cap', 'Khaki');
        $this->assertSame(3, $cap->stock);

        $this->actingAs($this->admin)->patch(route('admin.orders.updateNotes', $order), ['admin_notes' => 'Internal only']);
        $this->actingAs($this->admin)
            ->post(route('admin.orders.reject', $order), ['reason' => 'Receipt amount does not match.'])
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('rejected', $order->status);
        $this->assertSame('Receipt amount does not match.', $order->rejection_reason);
        $this->assertSame(5, $cap->fresh()->stock);

        Mail::assertSent(OrderPaymentRejected::class, function ($mail) {
            $html = $mail->render();
            return str_contains($html, 'Receipt amount does not match.') && ! str_contains($html, 'Internal only');
        });
    }

    public function test_shipping_records_tracking_and_emails_it(): void
    {
        $order = $this->placeOrder();

        // Can't ship before payment is confirmed
        $this->actingAs($this->admin)->post(route('admin.orders.ship', $order), ['tracking_number' => '123456789012'])
            ->assertSessionHas('error');
        Mail::assertNothingSent();

        $this->actingAs($this->admin)->post(route('admin.orders.confirm', $order));
        $this->actingAs($this->admin)
            ->post(route('admin.orders.ship', $order), ['tracking_number' => ' 123456789012 '])
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('shipped', $order->status);
        $this->assertSame('123456789012', $order->tracking_number);
        $this->assertNotNull($order->shipped_at);
        $this->assertStringEndsWith('123456789012', $order->tracking_url);

        Mail::assertSent(OrderShipped::class, fn($m) => str_contains($m->render(), '123456789012'));

        // Correcting the number re-sends it
        $this->actingAs($this->admin)->post(route('admin.orders.ship', $order), ['tracking_number' => '999']);
        $this->assertSame('999', $order->fresh()->tracking_number);
        Mail::assertSent(OrderShipped::class, 2);
    }

    public function test_cancelling_restocks_and_reopening_takes_stock_back_when_available(): void
    {
        $order = $this->placeOrder();
        $cap = $this->variant('trail-cap', 'Khaki');

        $this->actingAs($this->admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'cancelled']);
        $this->assertSame(5, $cap->fresh()->stock);

        $this->actingAs($this->admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'confirmed']);
        $this->assertSame('confirmed', $order->fresh()->status);
        $this->assertSame(3, $cap->fresh()->stock);

        // Cancel again, sell out, then reopening fails and leaves everything as it was
        $this->actingAs($this->admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'cancelled']);
        $cap->update(['stock' => 1]);

        $this->actingAs($this->admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'payment_submitted'])
            ->assertSessionHasErrors('items');
        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(1, $cap->fresh()->stock);

        $this->actingAs($this->admin)->patch(route('admin.orders.updateStatus', $order), ['status' => 'bogus'])
            ->assertSessionHasErrors('status');
    }

    public function test_admin_lists_filters_and_exports_orders(): void
    {
        $order = $this->placeOrder();
        $tee = $this->stocked('running-in-constant-tee', 'Black', 'XL');
        $this->checkout([['variant_id' => $tee->id, 'quantity' => 1]], ['full_name' => 'Maria Santos', 'shipping_area' => 'pickup']);

        $this->actingAs($this->admin)->get('/admin/orders')
            ->assertOk()
            ->assertSee('Juan Dela Cruz')
            ->assertSee('Maria Santos');

        $this->actingAs($this->admin)->get('/admin/orders?product=' . urlencode('Running in Constant Tee'))
            ->assertSee('Maria Santos')
            ->assertDontSee('Juan Dela Cruz');

        $this->actingAs($this->admin)->get('/admin/orders?shipping_area=ncr')
            ->assertSee('Juan Dela Cruz')
            ->assertDontSee('Maria Santos');

        $csv = $this->actingAs($this->admin)->get('/admin/orders/export')->streamedContent();
        $this->assertStringContainsString($order->reference, $csv);
        $this->assertStringContainsString('"Running in Constant Tee",Black,XL', $csv);
    }

    public function test_stock_edits_apply_as_a_change_so_concurrent_sales_are_kept(): void
    {
        $cap = $this->stocked('trail-cap', 'Black', null, 10);
        $sage = $this->variant('trail-cap', 'Sage Green');
        $product = Product::firstWhere('slug', 'trail-cap');

        // Admin's page showed 10 and 0; a sale of 2 lands before they save
        $cap->update(['stock' => 8]);

        $this->actingAs($this->admin)->patch(route('admin.products.updateStock', $product), [
            'original' => [$cap->id => 10, $sage->id => 0],
            'stock'    => [$cap->id => 15, $sage->id => 0],
        ])->assertSessionHas('success');

        $this->assertSame(13, $cap->fresh()->stock);
        $this->assertSame(0, $sage->fresh()->stock);
    }

    public function test_admin_creates_a_product_and_variants_follow_colors_and_sizes(): void
    {
        $this->actingAs($this->admin)->get(route('admin.products.index'))->assertOk()->assertSee('Running in Constant Tee');

        $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name'       => 'Trail Tote',
            'price'      => 399,
            'specs_text' => "Fabric: Canvas\nnot a spec line",
            'sizes_text' => '',
            'is_active'  => '1',
            'colors'     => [
                'c0' => ['label' => 'Forest', 'hex' => '#1b4332', 'front_image' => UploadedFile::fake()->image('tote.png')],
            ],
        ])->assertRedirect();

        $tote = Product::firstWhere('slug', 'trail-tote');
        $this->assertSame(['Fabric' => 'Canvas'], $tote->specs);
        $this->assertNull($tote->sizes);
        $this->assertCount(1, $tote->variants);
        Storage::disk('s3')->assertExists($tote->colors->first()->image_front);

        $color = $tote->colors->first();
        $this->actingAs($this->admin)->put(route('admin.products.update', $tote), [
            'name'       => 'Trail Tote',
            'slug'       => 'trail-tote',
            'price'      => 399,
            'sizes_text' => 's, l',
            'is_active'  => '1',
            'colors'     => [
                'c0' => ['id' => $color->id, 'label' => 'Forest', 'hex' => '#1B4332'],
                'c1' => ['label' => 'Sand', 'hex' => '#c9b68a'],
            ],
        ])->assertSessionHasNoErrors();

        $tote->refresh();
        $this->assertSame(['S', 'L'], $tote->sizes);
        $this->assertCount(2, $tote->colors);
        $this->assertCount(4, $tote->variants);
        $this->assertSame($color->image_front, $tote->colors->first()->image_front);

        $this->actingAs($this->admin)->get(route('admin.products.edit', $tote))->assertOk()->assertSee('Save Stock');
    }

    public function test_non_admins_cannot_manage_orders_or_inventory(): void
    {
        $order = $this->placeOrder();
        auth()->logout();

        $this->get('/admin/orders')->assertRedirect('/login');
        $this->get('/admin/products')->assertRedirect('/login');
        $this->post(route('admin.orders.confirm', $order))->assertRedirect('/login');
        $this->assertSame('payment_submitted', $order->fresh()->status);

        $runner = User::factory()->create(['role' => 'user']);
        $this->actingAs($runner)->get('/admin/products')->assertForbidden();
    }
}
