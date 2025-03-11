<?php

namespace App\Console\Commands\Sync;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductRange;
use App\Models\ProductSubcategory;
use App\Models\ProductVariation;
use Illuminate\Console\Command;

class RefreshProduct extends Command
{


    protected $dolibarr_product_rowid = null;


    protected $signature = 'sync:refresh-product {--rowid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes a product from Dolibarr';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->output->title("Refreshing product from Dolibarr");
        $this->output->newLine();

        $this->dolibarr_product_rowid = intval($this->option("rowid"));

        $dolibarr_product = \App\Models\Dolibarr\Product::with('extrafields')->find($this->dolibarr_product_rowid);

        if ($dolibarr_product == null)
            return Command::FAILURE;
        else if($dolibarr_product->extrafields[0]['siteweb'] != 1)
            return Command::INVALID;
        else {
            $product = Product::query()->with('variations')->where('name', $dolibarr_product->ref)->first();

            // delete variations + product
            if ($product) {
                $product['variations']->each(fn($variation) => $variation->delete());
                $product->delete();
            }


            // Import - master product
            $llx_product = \App\Models\Dolibarr\Product::with('extrafields')
                ->where('rowid', $this->dolibarr_product_rowid)
                ->first();

            Product::query()->create([
                'name' => htmlspecialchars($llx_product->extrafields[0]['refstore']),
                'sku' => htmlspecialchars($llx_product->ref),
                'description' => htmlspecialchars($llx_product->label),
                'content' => htmlspecialchars($llx_product->dscription),
                'images' => '',
                'is_featured' => is_numeric($llx_product->extrafields[0]['produitmisenavant']) && ((boolean)$llx_product->extrafields[0]['produitmisenavant']),
                'category_id' => ProductCategory::query()->where('fk_dolibarr_id', $llx_product->extrafields[0]['categorie'])->first()?->id,
                'subcategory_id' => ProductSubcategory::query()->where('fk_dolibarr_id', $llx_product->extrafields[0]['souscategorie'])->first()?->id,
                'brand_id' => ProductBrand::query()->where('fk_dolibarr_id', $llx_product->extrafields[0]['marqueprincipale'])->first()?->id,
                'range_id' => ProductRange::query()->where('fk_dolibarr_id', $llx_product->extrafields[0]['gamme'])->first()?->id,
                'related' => htmlspecialchars($llx_product->extrafields[0]['equiv']),
                'fk_dolibarr_id' => $llx_product->rowid
            ])->save();

            // Import - variation products
            $dolibarr_variation_products = \App\Models\Dolibarr\Product::query()
                ->with('extrafields')
                ->where('ref', $llx_product->ref . '_RECOND')
                ->orWhere('ref', $llx_product->ref . '_NEUF')
                ->get()
            ;
            $master_product = Product::query()->where('name', $llx_product->ref)->first();
            foreach ($dolibarr_variation_products as $llx_product_variation) {
                if (! $llx_product_variation->price)
                    continue; // ignore missing prices

                $ref_store = htmlspecialchars($llx_product_variation->extrafields[0]['refstore']);

                ProductVariation::query()->create([
                    'parent_id' => $master_product->id,
                    'name' => $ref_store,
                    'sku' => htmlspecialchars($llx_product_variation->ref),
                    'price' => $llx_product_variation->price,
                    'fk_dolibarr_id' => $llx_product_variation->rowid
                ])->save();
            }


            return Command::SUCCESS;

        }

    }

}
