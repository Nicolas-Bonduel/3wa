<?php

namespace App\Services;


use App\Models\Dolibarr\Categorie;
use App\Models\Dolibarr\EcmFiles;
use App\Models\Dolibarr\Product;
use App\Models\Dolibarr\ProductExtrafields;
use App\Models\Dolibarr\ProductStock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class DolibarrProductService
{

    /**
     * Returns the list of stocks for all variants of a master product
     * Looks like this :
     * [
     *      'RECOND' => -quantity-,
     *      'NEUF' => -quantity-
     * ]
     * @param \App\Models\Product $product - master product
     * @return array
     */
    public static function getProductStocks(\App\Models\Product $product)
    {

        $allowed_variations = ['RECOND', 'NEUF'];

        $refs = [];
        foreach ($allowed_variations as $suffix)
            $refs[] = $product->sku . '_' . $suffix;


        $llx_products = Product::query()
            ->select('rowid', 'ref')
            ->with('extrafields', fn($q) => $q->select('fk_object', 'stockvirtuel'))
            ->whereIn('ref', $refs)
            ->get();
        $llx_stocks = ProductStock::query()
            ->select('fk_product', 'reel')
            ->whereIn('fk_product', $llx_products->pluck('rowid'))
            ->where('fk_entrepot', 3)
            ->get();


        $return = [];
        foreach ($llx_products as $llx_product) {
            $key = substr($llx_product->ref, strlen($product->sku) + 1);
            $virtual_stock = $llx_product->extrafields[0]['stockvirtuel'];
            if ($virtual_stock !== null)    // always take the virtual stock when available
                $return[$key] = intval($virtual_stock);
            else                            // if not, take the static stock
                $return[$key] = intval($llx_stocks->first(fn($s) => $s->fk_product == $llx_product->rowid)?->reel ?? 0);
        }

        return $return;
    }

    /**
     * Returns the list of stocks for all variants of all given master products
     * Looks like this :
     * [
     *      -master product id- => [
     *          'RECOND' => -quantity-,
     *          'NEUF' => -quantity-
     *      ],
     *      -master product id- => [
     *          'RECOND' => -quantity-,
     *          'NEUF' => -quantity-
     *      ],
     *      ...
     * ]
     * @param $products - collection of master products
     * @return array
     */
    public static function getProductsStocks($products): array
    {

        $allowed_variations = ['RECOND', 'NEUF'];

        $product_variations = $products->pluck('variations')->collapse();
        $llx_ids = $product_variations->pluck('fk_dolibarr_id');


        $llx_products = Product::query()
            ->select('rowid', 'ref')
            ->with('extrafields', fn($q) => $q->select('fk_object', 'stockvirtuel'))
            ->whereIn('rowid', $llx_ids)
            ->get();
        $llx_stocks = ProductStock::query()
            ->select('fk_product', 'reel')
            ->whereIn('fk_product', $llx_ids)
            ->where('fk_entrepot', 3)
            ->get();


        $stocks = [];
        foreach ($product_variations as $product_variation) {
            $stock =
                $llx_products->find($product_variation->fk_dolibarr_id)->extrafields[0]['stockvirtuel']         // always take the virtual stock when available
                ??
                $llx_stocks->first(fn($s) => $s->fk_product == $product_variation->fk_dolibarr_id)?->reel ?? 0  // if not, take the static stock
            ;

            // only takes the allowed variations (I know, I'm getting the stock needlessly before knowing if I'll need it, it's late..)
            foreach ($allowed_variations as $allowed_variation) {
                if (str_ends_with($product_variation->sku, '_' . $allowed_variation))
                    $stocks[$product_variation->parent_id][$allowed_variation] = $stock;
            }
        }


        return $stocks;
    }

    /**
     * Don't use it with a distant connection please...
     */
    public static function getProductStock(\App\Models\ProductVariation $product): int
    {

        $llx_product = Product::query()
            ->select('rowid', 'ref')
            ->with('extrafields', fn($q) => $q->select('fk_object', 'stockvirtuel'))
            ->where('ref', $product->sku)
            ->first();
        $llx_stock = ProductStock::query()
            ->select('fk_product', 'reel')
            ->where('fk_product', $llx_product->rowid)
            ->where('fk_entrepot', 3)
            ->first();

        $virtual_stock = $llx_product->extrafields[0]['stockvirtuel'];

        return $virtual_stock ?: $llx_stock?->reel ?? 0;
    }

}
