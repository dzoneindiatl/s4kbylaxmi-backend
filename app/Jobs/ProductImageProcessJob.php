<?php

namespace App\Jobs;

use App\Models\ProductGraphics;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProductImageProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productId;
    private $graphics;
    private $folderPath;
    public $colorValueId;

    public function __construct($productId, array $graphics, string $folderPath, ?string $colorValueId = null)
    {
        $this->productId = $productId;
        $this->graphics = $graphics;
        $this->folderPath = $folderPath;
        $this->colorValueId = $colorValueId;
    }

    public function handle()
    {
        // 1. Map each unique URL to all image types using it
        $uniqueUrls = [];
        $coreTypes = ['front', 'back', 'variant'];

        foreach ($this->graphics as $type => $url) {
            if (empty($url) || $type === 'same_image' || !is_string($url)) {
                continue;
            }
            $url = $this->sanitizeDriveUrl($url);
            // Group types by URL
            $uniqueUrls[$url][] = $type;
        }
        

        foreach ($uniqueUrls as $url => $types) {
            $fileName = $this->storeImageFromGoogleDrive($url, $this->folderPath);

            if (!$fileName) {
                continue;
            }

            // 3. Set flags to 1 if the corresponding key exists in the $types array
            ProductGraphics::create([
                'product_id'      => $this->productId,
                'variant_id'      => $this->colorValueId,
                'image_id'        => $this->generateImageId(),
                'product_type'    => 'variant_group',
                'graphic'         => $fileName,
                'graphic_type'    => 'image',
                'status'          => 1,
                'is_front'        => in_array('front', $types, true) ? 1 : 0,
                'is_back'         => in_array('back', $types, true) ? 1 : 0,
                'is_variant_icon' => in_array('variant', $types, true) ? 1 : 0,
            ]);
        }


        // foreach ($this->graphics as $type => $url) {
        //     if (empty($url)) {
        //         continue;
        //     }

        //     $fileName = $this->storeImageFromGoogleDrive($url, $this->folderPath);

        //     if (!$fileName) {
        //         continue;
        //     }

        //     $ss = ProductGraphics::create([
        //         'product_id'       => $this->productId,
        //         'variant_id'       => $this->colorValueId,
        //         'image_id'         => $this->generateImageId(),
        //         'product_type'     => 'variant_group',
        //         'graphic'          => $fileName,
        //         'graphic_type'     => 'image',
        //         'status'           => 1,
        //         'is_front'         => $type === 'front' ? 1 : 0,
        //         'is_back'          => $type === 'back' ? 1 : 0,
        //         'is_variant_icon'  => $type === 'variant' ? 1 : 0,
        //     ]);
        // }
    }

    private function storeImageFromGoogleDrive(string $url, string $folderPath)
    {
        if (empty($url)) {
            return null;
        }

        if (preg_match('/\/d\/([^\/]+)\//', $url, $matches)) {
            $fileId = $matches[1];
        } elseif (preg_match('/id=([^&]+)/', $url, $matches)) {
            $fileId = $matches[1];
        } else {
            return null;
        }

        $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}&confirm=t";
        
        $response = Http::withOptions([
            'verify' => false,
        ])
        ->timeout(30)
        ->get($downloadUrl);

        if (!$response->successful()) {
            return null;
        }

        $extension = 'jpg';
        $contentType = $response->header('Content-Type');

        if (str_contains($contentType, 'png')) {
            $extension = 'png';
        } elseif (str_contains($contentType, 'webp')) {
            $extension = 'webp';
        }

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $fileName = 'variant_' . $this->productId . '_' . uniqid() . '.' . $extension;
        $fullPath = rtrim($folderPath, '/') . '/' . $fileName;

        file_put_contents($fullPath, $response->body());

        return $fileName;
    }

    private function generateImageId(): string
    {
        return 'img_' .
            round(microtime(true) * 1000) .
            '_' .
            substr(bin2hex(random_bytes(8)), 0, 13);
    }

    private function sanitizeDriveUrl(string $url): string
    {
        return strtok($url, '?');
    }
}
