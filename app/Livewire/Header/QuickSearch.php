<?php

namespace App\Livewire\Header;

use App\Models\Product;
use Livewire\Component;

class QuickSearch extends Component
{

    public string $input = "";  // input text
    public $products = [];      // result (products)
    public int $max_results = 28; // max results


    public function render()
    {
        $total_products = Product::count();

        if ($this->input) {
            $search_properties = ['name', 'sku', 'description', 'content']; // properties to compare

            // input cleaning
            $search = str_replace(' ', '', $this->input);
            $search = str_replace('-', '', $search);
            $search = str_replace('_', '', $search);
            $search = str_replace('/', '', $search);
            $search = str_replace('.', '', $search);
            $search = strtolower($search);

            $query = Product::query();
            $has_where = false;
            if (in_array('name', $search_properties)) {
                $query->{$has_where ? 'orWhereRaw' : 'whereRaw'}("LOWER(REPLACE(REPLACE(REPLACE(name, ' ', ''), '_', ''), '-', '')) like ?", ['%' . $search . '%']);
                $has_where = true;
            }
            if (in_array('sku', $search_properties)) {
                $query->{$has_where ? 'orWhereRaw' : 'whereRaw'}("LOWER(REPLACE(REPLACE(REPLACE(sku, ' ', ''), '_', ''), '-', '')) like ?", ['%' . $search . '%']);
                $has_where = true;
            }
            if (in_array('description', $search_properties)) {
                $query->{$has_where ? 'orWhereRaw' : 'whereRaw'}("LOWER(description) like ?", ['%' . $search . '%']);
                $has_where = true;
            }
            if (in_array('content', $search_properties)) {
                $query->{$has_where ? 'orWhereRaw' : 'whereRaw'}("LOWER(content) like ?", ['%' . $search . '%']);
                $has_where = true;
            }

            $this->products = $query->limit($this->max_results)->get();
        }
        else
            $this->products = null;


        return view('livewire.header.quick-search', compact('total_products'));
    }
}
