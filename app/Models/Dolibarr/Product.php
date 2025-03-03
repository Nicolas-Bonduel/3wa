<?php

namespace App\Models\Dolibarr;

use Botble\Ecommerce\Models\ProductFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'llx_product';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [
    'rowid',
    'ref',
    'entity',
    'ref_ext',
    'datec',
    'tms',
    'fk_parent',
    'label',
    'description',
    'note_public',
    'note',
    'customcode',
    'fk_country',
    'fk_state',
    'price',
    'price_ttc',
    'price_min',
    'price_min_ttc',
    'price_base_type',
    'cost_price',
    'default_vat_code',
    'tva_tx',
    'recuperableonly',
    'localtax1_tx',
    'localtax1_type',
    'localtax2_tx',
    'localtax2_type',
    'fk_user_author',
    'fk_user_modif',
    'tosell',
    'tobuy',
    'onportal',
    'tobatch',
    'sell_or_eat_by_mandatory',
    'batch_mask',
    'fk_product_type',
    'duration',
    'seuil_stock_alerte',
    'url',
    'barcode',
    'fk_barcode_type',
    'accountancy_code_sell',
    'accountancy_code_sell_intra',
    'accountancy_code_sell_export',
    'accountancy_code_buy',
    'accountancy_code_buy_intra',
    'accountancy_code_buy_export',
    'partnumber',
    'net_measure',
    'net_measure_units',
    'weight',
    'weight_units',
    'length',
    'length_units',
    'width',
    'width_units',
    'height',
    'height_units',
    'surface',
    'surface_units',
    'volume',
    'volume_units',
    'stock',
    'pmp',
    'fifo',
    'lifo',
    'fk_default_warehouse',
    'canvas',
    'finished',
    'lifetime',
    'qc_frequency',
    'hidden',
    'import_key',
    'model_pdf',
    'fk_unit',
    'price_autogen',
    'fk_project',
    'mandatory_period',
    'fk_default_bom',
    'fk_default_workstation',
    ];

    public $timestamps = false;

    public function extrafields(){
        return $this->hasMany(ProductExtrafields::class,  'fk_object', 'rowid');
    }

    public function files(){
        return $this->hasMany(EcmFiles::class,  'fk_object', 'rowid');
    }

}
