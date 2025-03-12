<?php

namespace App\Livewire\Pages;

use App\Models\ProductBrand;
use App\Models\ProductRange;
use App\Models\ProductSubcategory;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    #[Url]
    public string $filters = '';                // dynamic url query param
    public array $filters_data = [];            // decoded data from url query param

    public bool $mobile_menu_visible = false;   // to toggle mobile menu visibility

    public function toggleMobileFilters(): void
    {
        $this->mobile_menu_visible = ! $this->mobile_menu_visible;
    }

    /**
     * filters checkboxes toggle
     * @param string $key - categorizable, namely 'subcategory', 'brand' or 'range'
     * @param string $value - id in said categorizable
     * @return void
     */
    public function toggleFilterData(string $key, string $value): void
    {
        // update data
        parse_str($this->filters, $this->filters_data);
        if (isset($this->filters_data[$key][$value]))
            unset($this->filters_data[$key][$value]);
        else
            $this->filters_data[$key][$value] = true;

        // reconstruct url from data
        $this->filters = http_build_query($this->filters_data);
    }


    #[Layout('layouts.app')]
    public function render()
    {
        /* decode url param */
        parse_str($this->filters, $this->filters_data);

        /* get products based on filters */
        $products = ProductService::get_products($this->filters_data, 24);


        /* display only relevant toggles */
        // subcategories
        $subcategories_query = ProductSubcategory::query()->has('products'); // that have products
        if (isset($this->filters_data['brand']) && ! empty($this->filters_data['brand'])) {
            $subcategories_query = $subcategories_query // and said products belong to selected brands
                ->whereHas('products', fn($q) => $q->whereIn('brand_id', array_keys($this->filters_data['brand'])));
        }
        if (isset($this->filters_data['range']) && ! empty($this->filters_data['range'])) {
            $subcategories_query = $subcategories_query // and said products belong to selected ranges
                ->whereHas('products', fn($q) => $q->whereIn('range_id', array_keys($this->filters_data['range'])));
        }
        $available_subcategories = $subcategories_query->get();

        // brands
        $brands_query = ProductBrand::query()->has('products'); // that have products
        if (isset($this->filters_data['subcategory']) && ! empty($this->filters_data['subcategory'])) {
            $brands_query = $brands_query // and said products belong to selected subcategories
                ->whereHas('products', fn($q) => $q->whereIn('subcategory_id', array_keys($this->filters_data['subcategory'])));
        }
        if (isset($this->filters_data['range']) && ! empty($this->filters_data['range'])) {
            $brands_query = $brands_query // and said products belong to selected ranges
                ->whereHas('products', fn($q) => $q->whereIn('range_id', array_keys($this->filters_data['range'])));
        }
        $available_brands = $brands_query->get();

        // ranges
        $ranges_query = ProductRange::query()->has('products'); // that have products
        if (isset($this->filters_data['subcategory']) && ! empty($this->filters_data['subcategory'])) {
            $ranges_query = $ranges_query // and said products belong to selected subcategories
                ->whereHas('products', fn($q) => $q->whereIn('subcategory_id', array_keys($this->filters_data['subcategory'])));
        }
        if (isset($this->filters_data['brand']) && ! empty($this->filters_data['brand'])) {
            $ranges_query = $ranges_query // and said products belong to selected brands
                ->whereHas('products', fn($q) => $q->whereIn('brand_id', array_keys($this->filters_data['brand'])));
        }
        $available_ranges = $ranges_query->get();


        // only show subcategory toggles in result
        $available_subcategories_ids = $available_subcategories->pluck('id')->toArray();
        if (isset($this->filters_data['subcategory']) && ! empty($this->filters_data['subcategory']))
            $this->filters_data['subcategory'] = array_filter($this->filters_data['subcategory'], fn($key) =>
                in_array($key, $available_subcategories_ids)
            , ARRAY_FILTER_USE_KEY);

        // only show brand toggles in result
        $available_brands_ids = $available_brands->pluck('id')->toArray();
        if (isset($this->filters_data['brand']) && ! empty($this->filters_data['brand']))
            $this->filters_data['brand'] = array_filter($this->filters_data['brand'], fn($key) =>
                in_array($key, $available_brands_ids)
            , ARRAY_FILTER_USE_KEY);

        // only show range toggles in result
        $available_ranges_ids = $available_ranges->pluck('id')->toArray();
        if (isset($this->filters_data['range']) && ! empty($this->filters_data['range']))
            $this->filters_data['range'] = array_filter($this->filters_data['range'], fn($key) =>
                in_array($key, $available_ranges_ids)
            , ARRAY_FILTER_USE_KEY);


        /* reconstruct url from data */
        $this->filters = http_build_query($this->filters_data);


        return view('livewire.pages.products', compact(
            'products',
            'available_subcategories',
            'available_brands',
            'available_ranges'
        ));
    }
}
