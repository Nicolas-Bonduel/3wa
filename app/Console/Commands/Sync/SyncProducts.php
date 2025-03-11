<?php

namespace App\Console\Commands\Sync;

use App\Models\Dolibarr\ComplementproduitCat;
use App\Models\Dolibarr\ComplementproduitGam;
use App\Models\Dolibarr\ComplementproduitMarque;
use App\Models\Dolibarr\ComplementproduitSouscat;
use App\Models\DoliSync;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductRange;
use App\Models\ProductSubcategory;
use App\Models\ProductVariation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncProducts extends Command
{
    protected $signature = 'dolibarr:sync-products';

    protected $description = 'Syncs site products with Dolibarr';


    private function isMaster(string $product_sku): bool
    {
        if (
            str_ends_with($product_sku, "_NEUF") ||
            str_ends_with($product_sku, "_OCCASION") ||
            str_ends_with($product_sku, "_RECOND") ||
            str_ends_with($product_sku, "_ECHSTAND") ||
            str_ends_with($product_sku, "_ECHANGE_STAND")
        )
            return false;

        return true;
    }

    public function handle()
    {
        $this->output->title("Syncing products with Dolibarr...");


        // Create temp result tables
        $check_product_table = "doli_product_check";
        $check_product_extrafields_table = "doli_product_extrafields_check";
        $dolibarr_product_table = "llx_product";
        $dolibarr_product_extrafields_table = "llx_product_extrafields";
        DB::connection('dolibarr')->statement("DROP TABLE IF EXISTS `temp__$check_product_table`");
        DB::connection('dolibarr')->statement("CREATE TABLE `temp__$check_product_table` LIKE `$dolibarr_product_table`");
        DB::connection('dolibarr')->statement("INSERT INTO `temp__$check_product_table` SELECT * FROM `$dolibarr_product_table`");
        DB::connection('dolibarr')->statement("DROP TABLE IF EXISTS `temp__$check_product_extrafields_table`");
        DB::connection('dolibarr')->statement("CREATE TABLE `temp__$check_product_extrafields_table` LIKE `$dolibarr_product_extrafields_table`");
        DB::connection('dolibarr')->statement("INSERT INTO `temp__$check_product_extrafields_table` SELECT * FROM `$dolibarr_product_extrafields_table`");


        // Reset data
        $this->comment("Deleting tables content..");
        Product::query()->truncate();
        ProductVariation::query()->truncate();
        ProductCategory::query()->truncate();
        ProductSubcategory::query()->truncate();
        ProductBrand::query()->truncate();
        ProductRange::query()->truncate();

        // Import - categorizables
        $this->comment("Importing categorizables..");
        $dolibarr_categories = ComplementproduitCat::all();
        foreach ($dolibarr_categories as $llx_cat) {
            ProductCategory::query()->create([
                'ref' => htmlspecialchars($llx_cat->ref),
                'label' => htmlspecialchars($llx_cat->label),
                'fk_dolibarr_id' => $llx_cat->rowid,
            ])->save();
        }
        $dolibarr_subcategories = ComplementproduitSouscat::all();
        foreach ($dolibarr_subcategories as $llx_subcat) {
            ProductSubcategory::query()->create([
                'category_id' => ProductCategory::query()->where('fk_dolibarr_id', $llx_subcat->fk_cat)->first()->id,
                'ref' => htmlspecialchars($llx_subcat->ref),
                'label' => htmlspecialchars($llx_subcat->label),
                'fk_dolibarr_id' => $llx_subcat->rowid,
            ])->save();
        }
        $dolibarr_brands = ComplementproduitMarque::all();
        foreach ($dolibarr_brands as $llx_brand) {
            ProductBrand::query()->create([
                'ref' => htmlspecialchars($llx_brand->ref),
                'label' => htmlspecialchars($llx_brand->label),
                'fk_dolibarr_id' => $llx_brand->rowid,
            ])->save();
        }
        $dolibarr_ranges = ComplementproduitGam::all();
        foreach ($dolibarr_ranges as $llx_range) {
            ProductRange::query()->create([
                'ref' => htmlspecialchars($llx_range->ref),
                'label' => htmlspecialchars($llx_range->label),
                'fk_dolibarr_id' => $llx_range->rowid,
            ])->save();
        }

        // Import - master products
        $this->comment("Importing master products..");
        $this->output->newLine();
        $dolibarr_products = \App\Models\Dolibarr\Product::query()
            ->with('extrafields')
            ->whereHas('extrafields', fn ($q) => $q->where('siteweb', 1) ) // ignore disabled products
            ->get();
        $dolibarr_master_products = $dolibarr_products->filter( fn($p) => $this->isMaster($p->ref) );
        $bar = $this->output->createProgressBar($dolibarr_master_products->count());
        $bar->start();
        foreach ($dolibarr_master_products as $llx_product) {
//            UploadProductImagesJob::dispatch($product);

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

            $bar->advance();
        }
        $bar->finish();
        $this->output->newLine();

        // Import - variation products
        $this->comment("Importing variation products..");
        $this->output->newLine();
        $dolibarr_variation_products = $dolibarr_products->filter( fn($p) => ! $this->isMaster($p->ref) );
        $bar = $this->output->createProgressBar($dolibarr_variation_products->count());
        $bar->start();
        foreach ($dolibarr_variation_products as $llx_product_variation) {
            if (! str_ends_with($llx_product_variation->ref, '_NEUF') && ! str_ends_with($llx_product_variation->ref, '_RECOND'))
                continue; // ignore unwanted variations
            if (! $llx_product_variation->price)
                continue; // ignore missing prices

            $ref_store = htmlspecialchars($llx_product_variation->extrafields[0]['refstore']);
            $master_product = Product::query()->where('name', $ref_store)->first();
            if (! $master_product)
                continue; // ignore missing parents

            ProductVariation::query()->create([
                'parent_id' => $master_product->id,
                'name' => $ref_store,
                'sku' => htmlspecialchars($llx_product_variation->ref),
                'price' => $llx_product_variation->price,
                'fk_dolibarr_id' => $llx_product_variation->rowid
            ])->save();

            $bar->advance();
        }
        $bar->finish();
        $this->output->newLine();




        // Update buffer tables
        DB::connection('dolibarr')->statement("DROP TABLE `$check_product_table`");
        DB::connection('dolibarr')->statement("CREATE TABLE `$check_product_table` LIKE `temp__$check_product_table`");
        DB::connection('dolibarr')->statement("INSERT INTO `$check_product_table` SELECT * FROM `temp__$check_product_table`");
        DB::connection('dolibarr')->statement("DROP TABLE `temp__$check_product_table`");
        DB::connection('dolibarr')->statement("DROP TABLE `$check_product_extrafields_table`");
        DB::connection('dolibarr')->statement("CREATE TABLE `$check_product_extrafields_table` LIKE `temp__$check_product_extrafields_table`");
        DB::connection('dolibarr')->statement("INSERT INTO `$check_product_extrafields_table` SELECT * FROM `temp__$check_product_extrafields_table`");
        DB::connection('dolibarr')->statement("DROP TABLE `temp__$check_product_extrafields_table`");

        // log
        (new DoliSync([
            'logs' => 'creation',
        ]))->save();




        $this->output->newLine();
        $this->output->success('Done.');
        return Command::SUCCESS;
    }
}
