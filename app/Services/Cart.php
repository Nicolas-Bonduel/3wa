<?php

namespace App\Services;

use App\Models\ProductVariation;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;

class Cart {
    const DEFAULT_INSTANCE = 'cart';

    protected $session;
    protected $instance;

    /**
     * Constructs a new cart object.
     *
     * @param Illuminate\Session\SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
        $this->instance = self::DEFAULT_INSTANCE;
    }

    /**
     * Adds a new item to the cart.
     *
     * @param ProductVariation $product
     * @param int $quantity
     * @param float|null $price
     * @param array $options
     * @return void
     */
    public function add(ProductVariation $product, int $quantity = 1, float $price = null, array $options = []): void
    {
        $cartItem = $this->createCartItem($product, $quantity, $price, $options);
        $id = $cartItem['product']->id;

        $content = $this->getContent();

        if ($content->has($id)) {
            $cartItem->put('quantity', $content->get($id)->get('quantity') + $quantity);
        }

        $content->put($id, $cartItem);

        $this->session->put($this->instance, $content);
    }

    /**
     * Updates the quantity of a cart item.
     *
     * @param string $id
     * @param string $action
     * @return void
     */
    public function update(string $id, int $qty): void
    {
        if ($qty <= 0 || $qty > 999)
            throw new \Error("[Update cart] invalid quantity '$qty' given");

        $content = $this->getContent();

        if ($content->has($id)) {
            $cartItem = $content->get($id);

            $cartItem->put('quantity', $qty);

            $content->put($id, $cartItem);

            $this->session->put($this->instance, $content);
        }
    }

    /**
     * Removes an item from the cart.
     *
     * @param string $id
     * @return void
     */
    public function remove(string $id): void
    {
        $content = $this->getContent();

        if ($content->has($id)) {
            $this->session->put($this->instance, $content->except($id));
        }
    }

    /**
     * Clears the cart.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->session->forget($this->instance);
    }

    /**
     * Returns the content of the cart.
     *
     * @return Illuminate\Support\Collection
     */
    public function content(): Collection
    {
        return is_null($this->session->get($this->instance)) ? collect([]) : $this->session->get($this->instance);
    }

    /**
     * Returns total price of the items in the cart.
     *
     * @return string
     */
    public function total(): string
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, $item) {
            return $total += $item->get('price') * $item->get('quantity');
        });

        return number_format($total, 2);
    }

    /**
     * Returns the content of the cart.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getContent(): Collection
    {
        return $this->session->has($this->instance) ? $this->session->get($this->instance) : collect([]);
    }

    /**
     * Creates a new cart item from given inputs.
     *
     * @param ProductVariation $product
     * @param int $quantity
     * @param float|null $price
     * @param array $options
     * @return Collection
     */
    protected function createCartItem(ProductVariation $product, int $quantity = 1, float $price = null, array $options = []): Collection
    {
        // sanity checks
        if ($quantity > 999)
            throw new \Error("[Add to cart] invalid quantity '$quantity' given");
        $price = $price ?? $product->price;
        if (! $price || $price <= 0)
            throw new \Error("[Add to cart] invalid price '$price' given");

        return collect([
            'product' => $product,
            'quantity' => $quantity,
            'price' => $price,
            'options' => $options,
        ]);
    }
}
