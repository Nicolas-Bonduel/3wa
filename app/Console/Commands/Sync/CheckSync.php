<?php

namespace App\Console\Commands\Sync;

use App\Models\DoliSync;
use App\Models\DoliSyncDetail;
use App\Models\DoliSyncDiff;
use App\Models\DoliSyncRun;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CheckSync extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:check {--debug} {--ignore-max-parse}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Incremental Sync Laravel <--> Dolibarr';

    protected const MAX_PARSE = 250;

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle()
    {
        $run_log = DoliSyncRun::first();
        $now = new \DateTime('now');
        if (! $run_log) {
            $run_log = new DoliSyncRun(['last' => $now, 'is_running' => true]);
        }
        else {
            if ($run_log->is_running) {
                $this->output->success('An instance is already running. Aborting..');
                return Command::SUCCESS;
            }

            $run_log->update(['last' => $now, 'is_running' => true]);
        }
        $run_log->save();


        $dolibarr_product_table = "llx_product";
        $dolibarr_product_extrafields_table = "llx_product_extrafields";

        $check_product_table = "doli_product_check";
        $check_product_extrafields_table = "doli_product_extrafields_check";

        $this->output->title("Sync check Dolibar <-> Laravel");
        $logs = [];

        try {

            if (!Schema::connection('dolibarr')->hasTable($check_product_table)) {
                $this->comment("Buffer tables don't exist, creating...");

                DB::connection('dolibarr')->statement("CREATE TABLE `$check_product_table` LIKE `$dolibarr_product_table`");
                DB::connection('dolibarr')->statement("INSERT INTO `$check_product_table` SELECT * FROM `$dolibarr_product_table`");
                $this->comment("1/2");
                DB::connection('dolibarr')->statement("CREATE TABLE `$check_product_extrafields_table` LIKE `$dolibarr_product_extrafields_table`");
                DB::connection('dolibarr')->statement("INSERT INTO `$check_product_extrafields_table` SELECT * FROM `$dolibarr_product_extrafields_table`");

                // log
                $doli_sync = new DoliSync([
                    'logs' => 'creation',
                ]);
                $doli_sync->save();

                $this->output->success('Done.');
                $run_log->update(['is_running' => false]);
                $run_log->save();
                return Command::SUCCESS;

            }

            // `llx_product` <-> `doli_product_check` for columns 'ref', 'label', 'tms', 'description', 'price'
            [
                "product_ids_to_update" => $product_ids_to_update__product,
                "product_ids_to_add" => $product_ids_to_add__product,
                "product_ids_to_delete" => $product_ids_to_delete__product,
                "details" => $details
            ] =
                $this->get_diff($dolibarr_product_table, $check_product_table,
                    ['ref', 'label', 'tms', 'description', 'price']);

            // `llx_product_extrafields` <-> `doli_product_extrafields_check` for columns 'tms', 'marqueprincipale', 'categorie', 'souscategorie', 'gamme', 'equiv', 'associe', 'grade', 'produitmisenavant', 'refstore', 'cof7', 'cof8', 'siteweb'
            [
                "product_ids_to_update" => $product_ids_to_update__product_extrafields,
                "product_ids_to_add" => $product_ids_to_add__product_extrafields,
                "product_ids_to_delete" => $product_ids_to_delete__product_extrafields,
                "details" => $details_extrafields
            ] =
                $this->get_diff($dolibarr_product_extrafields_table, $check_product_extrafields_table,
                    ['tms', 'marqueprincipale', 'categorie', 'souscategorie', 'gamme', 'equiv', 'associe', 'grade', 'produitmisenavant', 'refstore', 'cof7', 'cof8', 'siteweb'],
                    "fk_object");

            // create temp result tables
            DB::connection('dolibarr')->statement("DROP TABLE IF EXISTS `temp_$check_product_table`");
            DB::connection('dolibarr')->statement("CREATE TABLE `temp_$check_product_table` LIKE `$dolibarr_product_table`");
            DB::connection('dolibarr')->statement("INSERT INTO `temp_$check_product_table` SELECT * FROM `$dolibarr_product_table`");
            DB::connection('dolibarr')->statement("DROP TABLE IF EXISTS `temp_$check_product_extrafields_table`");
            DB::connection('dolibarr')->statement("CREATE TABLE `temp_$check_product_extrafields_table` LIKE `$dolibarr_product_extrafields_table`");
            DB::connection('dolibarr')->statement("INSERT INTO `temp_$check_product_extrafields_table` SELECT * FROM `$dolibarr_product_extrafields_table`");


            // merge
            $product_ids_to_update = [];
            foreach ($product_ids_to_update__product as $rowid) {
                $res = DB::connection('dolibarr')->table($dolibarr_product_table)->select('ref')->where('rowid', $rowid)->first();
                $product_ids_to_update[$rowid] = $res->ref;
            }
            foreach ($product_ids_to_update__product_extrafields as $rowid) {
                if (!array_key_exists($rowid, $product_ids_to_update)) {
                    $res = DB::connection('dolibarr')->table($dolibarr_product_table)->select('ref')->where('rowid', $rowid)->first();
                    $product_ids_to_update[$rowid] = $res->ref;
                }
            }
            $product_ids_to_add = [];
            foreach ($product_ids_to_add__product as $rowid) {
                $res = DB::connection('dolibarr')->table($dolibarr_product_table)->select('ref')->where('rowid', $rowid)->first();
                $product_ids_to_add[$rowid] = $res->ref;
                if (!in_array($rowid, $product_ids_to_add__product_extrafields)) // ok
                    Throw new \Error("Anomaly. New product (Id: $rowid, Ref: $res->ref) was found in `$dolibarr_product_table` table but not in `$dolibarr_product_extrafields_table` table. Aborting.");
            }
            foreach ($product_ids_to_add__product_extrafields as $rowid) {
                if (!array_key_exists($rowid, $product_ids_to_add)) {
                    $res = DB::connection('dolibarr')->table($dolibarr_product_table)->select('ref')->where('rowid', $rowid)->first();
                    if (!$res) // ok
                        Throw new \Error("Anomaly. New product (Id: $rowid) was found in `$dolibarr_product_extrafields_table` table but not in `$dolibarr_product_table` table. Aborting.");
                    else
                        Throw new \Error("UNEXPECTED. New product (Id: $rowid, Ref: $res->ref) was found in `$dolibarr_product_extrafields_table` table and in `$dolibarr_product_table` table but was not listed before?? wtf??");
                }
            }
            $product_ids_to_delete = [];
            foreach ($product_ids_to_delete__product as $rowid) {
                $res = DB::connection('dolibarr')->table($check_product_table)->select('ref')->where('rowid', $rowid)->first();
                $product_ids_to_delete[$rowid] = $res->ref;
                // no further checks, consider that if a product is deleted in the main table, dependencies in other tables should be deleted as well
            }
            foreach ($product_ids_to_delete__product_extrafields as $rowid) {
                if (!array_key_exists($rowid, $product_ids_to_delete)) {
                    $res = DB::connection('dolibarr')->table($check_product_table)->select('ref')->where('rowid', $rowid)->first();
                    if ($res) // ok
                        Throw new \Error("Anomaly. To-Delete product (Id: $rowid, Ref: $res->ref) was found in `$check_product_extrafields_table` table but not in `$check_product_table` table. Aborting.");
                    else
                        Throw new \Error("UNEXPECTED. To-Delete product (Id: $rowid) was found in `$check_product_extrafields_table` table and in `$check_product_table` table but was not listed before?? wtf??");

                }
            }

            // sanity check
            $count = count($product_ids_to_update) + count($product_ids_to_add) + count($product_ids_to_delete);
            if (!$this->option('ignore-max-parse') && $count > $this::MAX_PARSE) {

                $this->comment("Detected $count changes, maximum allowed are set to " . $this::MAX_PARSE . ".");
                $this->newLine();
                $this->comment("Please run a global update for such a significant amount of changes.");

                $last_entry = DoliSync::query()->select('logs')->latest('id')->first();
                if ($last_entry == null || !str_starts_with($last_entry->logs, 'exceeded max load')) {
                    $doli_sync = new DoliSync([
                        'logs' => 'exceeded max load: ' . $count . ' > ' . $this::MAX_PARSE,
                    ]);
                    $doli_sync->save();
                }

                $this->output->success('Done.');
                return Command::SUCCESS;

            }

            // products to update
            $this->newLine();
            $msg = "Products to update (" . count($product_ids_to_update) . ") :";
            $this->comment($msg);
            $logs[] = $msg;
            $this->table(['Id', 'Ref'],
                array_map( fn($val, $key) => [$key, $val],
                    $product_ids_to_update, array_keys($product_ids_to_update) ),
            );
            $logs[] = "Id: Ref";
            foreach ($product_ids_to_update as $id => $ref)
                $logs[] = "$id: $ref";
            $this->newLine();
            $logs[] = "";
            // products to add
            $msg = "Products to add (" . count($product_ids_to_add) . ") :";
            $this->comment($msg);
            $logs[] = $msg;
            $this->table(['Id', 'Ref'],
                array_map( fn($val, $key) => [$key, $val],
                    $product_ids_to_add, array_keys($product_ids_to_add) ),
            );
            $logs[] = "Id: Ref";
            foreach ($product_ids_to_add as $id => $ref)
                $logs[] = "$id: $ref";
            $this->newLine();
            $logs[] = "";
            // products to delete
            $msg = "Products to delete (" . count($product_ids_to_delete) . ") :";
            $this->comment($msg);
            $logs[] = $msg;
            $this->table(['Id', 'Ref'],
                array_map( fn($val, $key) => [$key, $val],
                    $product_ids_to_delete, array_keys($product_ids_to_delete) ),
            );
            $logs[] = "Id: Ref";
            foreach ($product_ids_to_delete as $id => $ref)
                $logs[] = "$id: $ref";
            $this->newLine();
            $logs[] = "";

            // debug
            if ($this->option('debug')) {
                $this->comment(" -- DEBUG -- ");
                $this->newLine();

                $this->comment("`$dolibarr_product_table`");
                $this->table(['Id', 'Type', 'Diff'],
                    array_map(function($d, $key) use($product_ids_to_update, $product_ids_to_add, $product_ids_to_delete) {
                        $type = "unknown?";
                        if (array_key_exists($key, $product_ids_to_update))
                            $type = "update";
                        else if (array_key_exists($key, $product_ids_to_add))
                            $type = "add";
                        else if (array_key_exists($key, $product_ids_to_delete))
                            $type = "delete";

                        return [$key, $type, implode(",", $d)];
                    }, $details, array_keys($details)),
                );

                $this->comment("`$dolibarr_product_extrafields_table`");
                $this->table(['Id', 'Type', 'Diff'],
                    array_map(function($d, $key) use($product_ids_to_update, $product_ids_to_add, $product_ids_to_delete) {
                        $type = "unknown?";
                        if (array_key_exists($key, $product_ids_to_update))
                            $type = "update";
                        else if (array_key_exists($key, $product_ids_to_add))
                            $type = "add";
                        else if (array_key_exists($key, $product_ids_to_delete))
                            $type = "delete";

                        return [$key, $type, implode(",", $d)];
                    }, $details_extrafields, array_keys($details_extrafields)),
                );
            }

            // filter products to update ==> retrieve only masters
            $master_product_ids_to_update = [];
            $variants = ['_NEUF', '_OCCASION', '_RECOND', '_ECHSTAND'];
            foreach (array_keys($product_ids_to_update) as $rowid) {
                $res = DB::connection('dolibarr')->table($dolibarr_product_table)->select('ref')->where('rowid', $rowid)->first();
                if (!$res)
                    Throw new \Error("UNEXPECTED 1x01. rowid: $rowid");
                $ref = $res->ref;
                $is_variant = false;
                foreach ($variants as $variant) {
                    if (str_ends_with($ref, $variant)) {
                        $master_ref = substr($ref, 0, strlen($ref) - strlen($variant));
                        $res2 = DB::connection('dolibarr')->table($dolibarr_product_table)->select('rowid', 'ref')->where('ref', $master_ref)->first();
                        if (!$res2)
                            Throw new \Error("Anomaly. To-Update product (Id: $rowid, Ref: $ref) does not have a master.");
                        $master_rowid = $res2->rowid;
                        if (!array_key_exists($master_rowid, $master_product_ids_to_update)) {
                            $master_product_ids_to_update[$master_rowid] = $master_ref;
                        }
                        $is_variant = true;
                        break;
                    }
                }
                if (!$is_variant) {
                    $res3 = DB::connection('dolibarr')->table($dolibarr_product_table)->select('rowid')->where('ref', $ref)->first();
                    if (!$res3)
                        Throw new \Error("UNEXPECTED 1x02. rowid: $rowid");
                    $master_rowid = $res3->rowid;
                    if (!array_key_exists($master_rowid, $master_product_ids_to_update)) {
                        $master_product_ids_to_update[$master_rowid] = $ref;
                    }
                }
            }
            $this->newLine();
            $logs[] = "";
            $msg = "Products to update (" . count($master_product_ids_to_update) . ") :";
            $this->comment($msg);
            $logs[] = $msg;
            $this->table(['Id', 'Ref'],
                array_map( fn($val, $key) => [$key, $val],
                    $master_product_ids_to_update, array_keys($master_product_ids_to_update) ),
            );
            $logs[] = "Id: Ref";
            foreach ($master_product_ids_to_update as $id => $ref)
                $logs[] = "$id: $ref";
            $this->newLine();
            $logs[] = "";

            // filter products to add ==> retrieve only masters
            $master_product_ids_to_add = [];
            foreach (array_keys($product_ids_to_add) as $rowid) {
                $res = DB::connection('dolibarr')->table($dolibarr_product_table)->select('ref')->where('rowid', $rowid)->first();
                if (!$res)
                    Throw new \Error("UNEXPECTED 2x01. rowid: $rowid");
                $ref = $res->ref;
                $is_variant = false;
                foreach ($variants as $variant) {
                    if (str_ends_with($ref, $variant)) {
                        $master_ref = substr($ref, 0, strlen($ref) - strlen($variant));
                        $res2 = DB::connection('dolibarr')->table($dolibarr_product_table)->select('rowid')->where('ref', $master_ref)->first();
                        if (!$res2)
                            Throw new \Error("Anomaly. To-Update product (Id: $rowid, Ref: $ref) does not have a master.");
                        $master_rowid = $res2->rowid;
                        if (!array_key_exists($master_rowid, $master_product_ids_to_add)) {
                            $master_product_ids_to_add[$master_rowid] = $master_ref;
                        }
                        $is_variant = true;
                        break;
                    }
                }
                if (!$is_variant) {
                    $res3 = DB::connection('dolibarr')->table($dolibarr_product_table)->select('rowid')->where('ref', $ref)->first();
                    if (!$res3)
                        Throw new \Error("UNEXPECTED 2x02. rowid: $rowid");
                    $master_rowid = $res3->rowid;
                    if (!array_key_exists($master_rowid, $master_product_ids_to_add)) {
                        $master_product_ids_to_add[$master_rowid] = $ref;
                    }
                }
            }
            $this->newLine();
            $logs[] = "";
            $msg = "Products to add (" . count($master_product_ids_to_add) . ") :";
            $this->comment($msg);
            $logs[] = $msg;
            $this->table(['Id', 'Ref'],
                array_map( fn($val, $key) => [$key, $val],
                    $master_product_ids_to_add, array_keys($master_product_ids_to_add) ),
            );
            $logs[] = "Id: Ref";
            foreach ($master_product_ids_to_add as $id => $ref)
                $logs[] = "$id: $ref";
            $this->newLine();
            $logs[] = "";


            /* --- Update Products --- */
            $warnings = [];

            // products to update
            if (count($master_product_ids_to_update)) {
                $this->newLine();
                $logs[] = "";
                $msg = "Updating products...";
                $this->comment($msg);
                $logs[] = $msg;
                $bar = $this->output->createProgressBar(count($master_product_ids_to_update));
                $i = 0;
                foreach (array_keys($master_product_ids_to_update) as $rowid) { $i++;
                    $bar->advance();
                    $success = Artisan::call('sync:refresh-product', ["--rowid" => $rowid]);
                    if ($success == Command::FAILURE)
                        Throw new \Error("Update failed for product id $rowid");
                    if ($success >= 4)
                        $warnings[$rowid] = $success;
                    $logs[] = "" . $i . "/" . count($master_product_ids_to_update);
                }
                $bar->finish();
                $this->newLine();
                $logs[] = "";
            }

            // todo products to delete


            // products to add
            if (count($master_product_ids_to_add)) {
                $this->newLine();
                $logs[] = "";
                $msg = "Adding products...";
                $this->comment($msg);
                $logs[] = $msg;
                $bar = $this->output->createProgressBar(count($master_product_ids_to_add));
                $i = 0;
                foreach (array_keys($master_product_ids_to_add) as $rowid) { $i++;
                    $bar->advance();
                    $success = Artisan::call('sync:refresh-product', ["--rowid" => $rowid]);
                    if ($success == Command::FAILURE)
                        Throw new \Error("Adding failed for product id $rowid");
                    if ($success >= 4)
                        $warnings[$rowid] = $success;
                    $logs[] = "" . $i . "/" . count($master_product_ids_to_add);
                }
                $bar->finish();
                $this->newLine();
                $logs[] = "";
            }

            /* --- TODO Update Products Relations --- */


            /* --- Refresh Tables & Log --- */
            // todo products to delete

            if (count($master_product_ids_to_update) + count($master_product_ids_to_add)) {

                // refresh tables
                $this->newLine();
                $logs[] = "";
                $msg = "refreshing tables...";
                $this->comment($msg);
                $logs[] = $msg;
                $bar = $this->output->createProgressBar(2);
                DB::connection('dolibarr')->statement("DROP TABLE `$check_product_table`");
                DB::connection('dolibarr')->statement("CREATE TABLE `$check_product_table` LIKE `temp_$check_product_table`");
                DB::connection('dolibarr')->statement("INSERT INTO `$check_product_table` SELECT * FROM `temp_$check_product_table`");
                DB::connection('dolibarr')->statement("DROP TABLE `temp_$check_product_table`");
                $bar->advance();
                DB::connection('dolibarr')->statement("DROP TABLE `$check_product_extrafields_table`");
                DB::connection('dolibarr')->statement("CREATE TABLE `$check_product_extrafields_table` LIKE `temp_$check_product_extrafields_table`");
                DB::connection('dolibarr')->statement("INSERT INTO `$check_product_extrafields_table` SELECT * FROM `temp_$check_product_extrafields_table`");
                DB::connection('dolibarr')->statement("DROP TABLE `temp_$check_product_extrafields_table`");
                $bar->finish();
                $this->newLine();
                $logs[] = "";

                // log
                $this->newline();
                $this->comment("Logging results..");
                // todo products to delete
                $logs_ = implode('\n', $logs);
                if (strlen($logs_) > 65535)
                    $logs = substr($logs_, -65535);
                if (count($warnings)) {
                    $warnings_log = "";
                    foreach ($warnings as $id => $warning)
                        $warnings_log .= '' . $id . ': ' . $warning . ' ;';
                }
                $doli_sync = new DoliSync([
                    'warnings' => count($warnings) ? $warnings_log : null,
                    'warnings_relations' => null,
                    'up_amount' => count($master_product_ids_to_update),
                    'up_row_ids' => implode(',', array_keys($master_product_ids_to_update)),
                    'add_amount' => count($master_product_ids_to_add),
                    'add_row_ids' => implode(',', array_keys($master_product_ids_to_add)),
                    'logs' => $logs_,
                ]);
                $doli_sync->save();

                foreach ($master_product_ids_to_update as $rowid => $ref) {
                    $doli_sync_detail = new DoliSyncDetail([
                        'sync_id' => $doli_sync->id,
                        'rowid' => $rowid,
                        'ref' => $ref,
                        'type' => 'update',
                    ]);
                    $doli_sync_detail->save();
                }
                foreach ($master_product_ids_to_add as $rowid => $ref) {
                    $doli_sync_detail = new DoliSyncDetail([
                        'sync_id' => $doli_sync->id,
                        'rowid' => $rowid,
                        'ref' => $ref,
                        'type' => 'add',
                    ]);
                    $doli_sync_detail->save();
                }

                foreach ($details as $rowid => $detail) {
                    $ref = "unknown?";
                    $type = "unknown?";
                    if (array_key_exists($rowid, $product_ids_to_update)) {
                        $ref = $product_ids_to_update[$rowid];
                        $type = "update";
                    }
                    else if (array_key_exists($rowid, $product_ids_to_add)) {
                        $ref = $product_ids_to_add[$rowid];
                        $type = "add";
                    }
                    else if (array_key_exists($rowid, $product_ids_to_delete)) {
                        $ref = $product_ids_to_delete[$rowid];
                        $type = "delete";
                    }

                    $doli_sync_diff = new DoliSyncDiff([
                        'sync_id' => $doli_sync->id,
                        "rowid" => $rowid,
                        "ref" => $ref,
                        "type" => $type,
                        "tables" => $dolibarr_product_table . ' <-> ' . $check_product_table,
                        "changes" => implode(",", $detail),
                        'value_changes' => null,
                    ]);
                    $doli_sync_diff->save();
                }
                foreach ($details_extrafields as $rowid => $detail) {
                    $ref = "unknown?";
                    $type = "unknown?";
                    if (array_key_exists($rowid, $product_ids_to_update)) {
                        $ref = $product_ids_to_update[$rowid];
                        $type = "update";
                    }
                    else if (array_key_exists($rowid, $product_ids_to_add)) {
                        $ref = $product_ids_to_add[$rowid];
                        $type = "add";
                    }
                    else if (array_key_exists($rowid, $product_ids_to_delete)) {
                        $ref = $product_ids_to_delete[$rowid];
                        $type = "delete";
                    }

                    $doli_sync_diff = new DoliSyncDiff([
                        'sync_id' => $doli_sync->id,
                        "rowid" => $rowid,
                        "ref" => $ref,
                        "type" => $type,
                        "tables" => $dolibarr_product_extrafields_table . ' <-> ' . $check_product_extrafields_table,
                        "changes" => implode(",", $detail),
                        'value_changes' => null,
                    ]);
                    $doli_sync_diff->save();
                }


                $this->newLine(); $this->newLine();
                $this->comment("Logged results to `doli_syncs` , `doli_sync_details` & `doli_sync_diffs` tables");
                $this->newLine();

            }

            $this->output->success('Done.');
            $run_log->update(['is_running' => false]);
            $run_log->save();
            return Command::SUCCESS;

        }
        catch (Throwable $e) {

            $last_entry = DoliSync::query()->select('error')->latest('id')->first();
            if ($last_entry == null || $last_entry->error != $e->getMessage()) {
                $doli_sync = new DoliSync([
                    'error' => $e->getMessage(),
                    'logs' => implode('\n', $logs),
                ]);
                $doli_sync->save();
            }

            $run_log->update(['is_running' => false]);
            $run_log->save();

            throw $e;
        }

    }

    private function get_diff($table, $table_check, $columns, $foreign_id_name = "")
    {
        $this->comment("Checking sync `$table` <-> `$table_check` for columns : " . implode(' , ', $columns));

        $product_ids_to_update = [];
        $details = [];
        $product_ids_to_add = [];
        $product_ids_to_delete = [];
        $product_ids = [];

        $bar = $this->output->createProgressBar(count($columns));
        foreach ($columns as $column) { $bar->advance(1); $this->newLine();

            $diff = $this->diff($table, $table_check, $column, $foreign_id_name);

            for ($i = 0; $i < count($diff); $i++) {
                $id = $foreign_id_name ? $diff[$i]->{$foreign_id_name} : $diff[$i]->rowid;

                if (!in_array($id, $product_ids))
                    $product_ids[] = $id;

                if (!array_key_exists($id, $details))
                    $details[$id][] = $column;
                else if (!in_array($column, $details[$id]))
                    $details[$id][] = $column;
            }
        }
        $bar->finish();
        $this->newLine();

        foreach ($product_ids as $rowid) {
            $is_in_doli = DB::connection('dolibarr')->table($table)->select($foreign_id_name ?: 'rowid')->where($foreign_id_name ?: 'rowid', $rowid)->first();
            $is_in_check = DB::connection('dolibarr')->table($table_check)->select($foreign_id_name ?: 'rowid')->where($foreign_id_name ?: 'rowid', $rowid)->first();

            if ($is_in_doli && $is_in_check)
                $product_ids_to_update[] = $rowid;
            else if ($is_in_doli && !$is_in_check)
                $product_ids_to_add[] = $rowid;
            else if (!$is_in_doli && $is_in_check)
                $product_ids_to_delete[] = $rowid;
            else
                throw new \Error("wtf?? rowid " . ($foreign_id_name ? "(foreign)" : "") . ": " . $foreign_id_name ?: $rowid);

        }

        return [
            "product_ids_to_update" => $product_ids_to_update,
            "product_ids_to_add" => $product_ids_to_add,
            "product_ids_to_delete" => $product_ids_to_delete,
            "details" => $details
        ];
    }

    private function diff($table1, $table2, $field, $foreign_id_name = "")
    {
        $foreign = $foreign_id_name ? $foreign_id_name . ',' : "";
        $query =
            "SELECT
              rowid,
              $foreign
               $field
            FROM
            (
                SELECT
                rowid,
                $foreign
                $field

                FROM
                  `$table1`
                UNION ALL
                SELECT
                rowid,
                $foreign
                $field
                FROM
                  `$table2`
              ) tbl
            GROUP BY
                rowid,
                $foreign
                $field
            HAVING
              count(*) = 1
            ORDER BY
              rowid;

            ";

        $res = DB::connection('dolibarr')->select($query);

        return $res;
    }


}
