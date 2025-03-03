<?php

namespace App\Services;

class Helper
{

    public static function get_variation_name(string $product_sku): string
    {
        if (str_ends_with($product_sku, '_RECOND'))
            return "Reconditionné";
        if (str_ends_with($product_sku, '_NEUF'))
            return "Neuf";

        return "??";
    }

}
