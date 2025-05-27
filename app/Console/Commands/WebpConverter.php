<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class WebpConverter extends Command
{
    protected $signature = 'images:to-webp';

    protected $description = 'Converts product images to webp';


    public function handle()
    {
        $this->output->title("Converting product images to webp...");


        $disk = Storage::disk('public');
        $files = $disk->allFiles('produit');
        $allowedExtensions = ['png', 'jpg', 'jpeg'];


        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions))
                continue;

            $imagePath = storage_path("app/public/{$file}");
            if (! str_ends_with($imagePath, '_1.jpg') && ! str_ends_with($imagePath, '_1.jpeg') && ! str_ends_with($imagePath, '_1.png'))
                continue;

            $explode = explode('.', $imagePath);
            array_pop($explode);
            $without_extension = implode('.', $explode);
            if (file_exists($without_extension . '.webp'))
                continue;


            $webpPath = preg_replace('/\.(png|jpe?g)$/i', '.webp', $imagePath);

            switch ($extension) {
                case 'png':
                    $img = imagecreatefrompng($imagePath);
                    break;
                case 'jpg':
                case 'jpeg':
                    $img = imagecreatefromjpeg($imagePath);
                    break;
                default:
                    $img = false;
            }

            if ($img === false) {
                $this->error("Could not load image: $file");
                continue;
            }

            // Save as WebP
            if (imagewebp($img, $webpPath))
                $this->info("Converted: $file → " . basename($webpPath));
            else
                $this->error("Failed to convert: $file");
        }


        $this->output->newLine();
        $this->output->success('Done.');
        return Command::SUCCESS;
    }
}
