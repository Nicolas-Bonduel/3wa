<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Facades\Cart as Cart_;

class Cart extends Component
{

    /**
     * Remove product variation from cart
     * @param $id - product variation id
     * @return void
     */
    public function remove($id)
    {
        Cart_::remove($id);
        $this->dispatch('cart-update');
        $this->dispatch('notify', 'Panier mis à jour', 'success');
    }

    /**
     * Place order
     * @return void
     */
    public function placeOrder()
    {
        try {
            // sanity check
            $customer = auth('customer')->user();
            if (! $customer) {
                $this->dispatch('notify', 'Vous devez être connecté pour passer commande', 'info');
                return;
            }

            // order
            $subtotal = $amount = Cart_::total(true);
            $shipping_amount = 5; $subtotal += $shipping_amount;
            $tax_amount = $subtotal * 0.2;
            $total = $subtotal + $tax_amount;
            $new_order = Order::create([
                'customer_id' => $customer->id,
                'total' => $total,
                'amount' => $amount,
                'tax_amount' => $tax_amount,
                'shipping_amount' => $shipping_amount,
                'shipping_option' => 'standard',
                'fk_dolibarr_order_id' => -1,
            ]);

            // order addresses
            $billing_address = $shipping_address = $customer->addresses[0];
            $new_order_address__billing = OrderAddress::create([
                'order_id' => $new_order->id,
                'type' => 'billing',
                'name' => $billing_address->name,
                'country' => $billing_address->country,
                'address' => $billing_address->address,
                'zip' => $billing_address->zip,
                'city' => $billing_address->city,
            ]);
            $new_order_address__shipping = OrderAddress::create([
                'order_id' => $new_order->id,
                'type' => 'shipping',
                'name' => $shipping_address->name,
                'country' => $shipping_address->country,
                'address' => $shipping_address->address,
                'zip' => $shipping_address->zip,
                'city' => $shipping_address->city,
            ]);

            // order products
            $new_order_products = [];
            foreach (Cart_::content() as $cart_item) {
                $product = $cart_item['product'];
                $master = $product->product()->getResults();
                $new_order_products[] = OrderProduct::create([
                    'order_id' => $new_order->id,
                    'quantity' => $cart_item['quantity'],
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'description' => $master->description,
                    'content' => $master->content,
                    'price' => $cart_item['price'],
                    'category' => $master->category()->getResults()?->label,
                    'subcategory' => $master->subcategory()->getResults()?->label,
                    'brand' => $master->brand()->getResults()?->label,
                    'range' => $master->range()->getResults()?->label,
                ]);
            }

            // empty cart
            Cart_::clear();
            $this->dispatch('cart-update');

            // redirect with message
            session()->flash('notify', ['Commande passée avec succès', 'success']);
            $this->redirect(route('customer.orders'), navigate: true);

        }
        catch (\Throwable $e) {
            // remove any partial data
            if (isset($new_order_products))
                foreach ($new_order_products as $new_order_product)
                    $new_order_product->delete();
            if (isset($new_order_address__shipping))
                $new_order_address__shipping->delete();
            if (isset($new_order_address__billing))
                $new_order_address__billing->delete();
            if (isset($new_order))
                $new_order->delete();

            $this->dispatch('notify', 'Une erreur inattendue est survenue', 'error');
        }
    }


    #[Layout('layouts.app')]
    #[On('cart-update')]
    public function render()
    {
        $cart_items = Cart_::content();
        $total = Cart_::total();

        return view('livewire.pages.cart', compact('cart_items', 'total'));
    }
}
