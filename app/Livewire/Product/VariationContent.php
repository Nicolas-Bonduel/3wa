<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductVariation;
use Livewire\Component;

class VariationContent extends Component
{
    public Product $product_master;                 // master product
    public ProductVariation $product_variation;     // current product variation

    private bool $mounted = false;

    public function mount(Product $product_master, ProductVariation $product_variation)
    {
        $this->product_master = $product_master;
        $this->product_variation = $product_variation;
        $this->mounted = true;
    }

    /**
     * Swaps to another product variation
     * @param int $variation_id: product variation id
     * @return void
     */
    public function swap(int $variation_id)
    {
        if ($variation_id !== $this->product_variation->id)
            $this->product_variation = $this->product_master->variation($variation_id);
    }


    public function render()
    {
        // available product variations (sorted by : default variation first)
        $product_variations = $this->product_master->variations()->getResults()
            ->sortBy(fn($v) => $v->id != $this->product_master->default_variation()->id);

        // product variations stocks
        $product_stocks_ = $this->product_master->stocks();
        // reformat stocks to get a shallow link to variations
        $product_stocks = [];
        foreach ($product_stocks_ as $key => $stock)
            $product_stocks[$product_variations->first(fn($v) => str_ends_with($v->sku, $key))?->id ?? '??'] = $stock;

        // change current variation if out of stock (only on page load)
        if ($this->mounted &&  $product_stocks[$this->product_variation->id] < 1) {
            foreach ($product_variations as $variation) {
                if ($product_stocks[$variation->id] > 0) { // only if another variation actually has stock, otherwise it's pointless
                    $this->product_variation = $variation;
                    break;
                }
            }
        }


        return view('livewire.product.variation-content', compact('product_variations','product_stocks'));
    }
}
