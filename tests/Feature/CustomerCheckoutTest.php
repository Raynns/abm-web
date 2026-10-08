<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(int $stock = 10, int $price = 25000): Product
    {
        $category = Category::create(['name' => 'Fungisida', 'slug' => 'fungisida']);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Uji Pertanian',
            'slug' => 'produk-uji-pertanian',
            'description' => 'Produk untuk tes.',
            'price' => $price,
            'stock' => $stock,
            'is_active' => true,
        ]);
    }

    private function contact(): array
    {
        return [
            'customer_name' => 'Pembeli Contoh',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jalan Contoh No 12, Kabupaten Kuningan',
        ];
    }

    public function test_guest_can_add_update_remove_cart_items(): void
    {
        $product = $this->makeProduct();

        $this->post(route('cart.store', $product), ['quantity' => 2])->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertOk()->assertSee('Rp 50.000');
        $this->patch(route('cart.update', $product->id), ['quantity' => 3])->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertSee('Rp 75.000');
        $this->delete(route('cart.destroy', $product->id))->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertSee('Keranjang Anda masih kosong');
    }

    public function test_guest_checkout_stores_order_and_decreases_stock(): void
    {
        $product = $this->makeProduct();
        $this->post(route('cart.store', $product), ['quantity' => 2]);
        $this->get(route('checkout.show'))->assertOk();

        $this->post(route('checkout.store'), $this->contact() + ['expected_total' => 50000])
            ->assertRedirect(route('checkout.success'));

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Pembeli Contoh',
            'total' => 50000,
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id, 'quantity' => 2, 'price' => 25000, 'subtotal' => 50000,
        ]);
        $this->assertSame(8, $product->fresh()->stock);
        $this->get(route('checkout.success'))->assertOk()->assertSee('Pesanan berhasil dibuat');
        $this->get(route('cart.index'))->assertSee('Keranjang Anda masih kosong');
    }

    public function test_checkout_rejects_insufficient_stock_and_does_not_create_order(): void
    {
        $product = $this->makeProduct(stock: 2);
        $this->post(route('cart.store', $product), ['quantity' => 2]);
        $product->update(['stock' => 1]);

        $this->post(route('checkout.store'), $this->contact() + ['expected_total' => 50000])
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->fresh()->stock);
    }

    public function test_checkout_rejects_stale_price(): void
    {
        $product = $this->makeProduct();
        $this->post(route('cart.store', $product), ['quantity' => 1]);
        $product->update(['price' => 27000]);

        $this->post(route('checkout.store'), $this->contact() + ['expected_total' => 25000])
            ->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_admin_can_cancel_pending_order_and_restore_stock(): void
    {
        $product = $this->makeProduct();
        $this->post(route('cart.store', $product), ['quantity' => 2]);
        $this->post(route('checkout.store'), $this->contact() + ['expected_total' => 50000]);
        $order = Order::firstOrFail();

        // Guest tidak dapat mengubah status pesanan.
        $this->patch(route('admin.orders.update-status', $order), ['status' => 'canceled'])->assertRedirect(route('login'));
        $this->assertSame(8, $product->fresh()->stock);

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), ['status' => 'canceled'])
            ->assertRedirect(route('admin.orders.show', $order));
        $this->assertSame('canceled', $order->fresh()->status);
        $this->assertSame(10, $product->fresh()->stock);
        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), ['status' => 'canceled'])
            ->assertSessionHasErrors('status');
        $this->assertSame(10, $product->fresh()->stock);
    }
}
