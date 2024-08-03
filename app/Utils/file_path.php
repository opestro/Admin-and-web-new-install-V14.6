<?php

use App\Enums\GlobalConstant;
use Illuminate\Support\Facades\Storage;

if (!function_exists('productImagePath')) {
    function productImagePath(string $type): string
    {
        return asset(GlobalConstant::FILE_PATH['product'][$type]);
    }
}

if (!function_exists('getStorageImages')) {
    function getStorageImages($path, $type = null, $source = null): string
    {
        if ($source && base_path($source)) {
            return (!empty($path) && $path['status'] == 200) ? $path['path'] : dynamicAsset($source);
        }
        if ($source && file_exists($source)) {
            return (!empty($path) && $path['status'] == 200) ? $path['path'] : $source;
        }
        $placeholderMap = [
            'backend-basic' => 'back-end/img/placeholder/placeholder-1-1.png',
            'backend-brand' => 'back-end/img/placeholder/brand.png',
            'backend-banner' => 'back-end/img/placeholder/placeholder-4-1.png',
            'backend-category' => 'back-end/img/placeholder/category.png',
            'backend-logo' => 'back-end/img/placeholder/placeholder-4-1.png',
            'backend-product' => 'back-end/img/placeholder/product.png',
            'backend-profile' => 'back-end/img/placeholder/user.png',
            'backend-payment' => 'back-end/img/placeholder/placeholder-4-1.png',
            'product' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-1-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-1-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-1-1.png',
            ],
            'avatar' => [
                'theme_aster' => 'assets/img/placeholder/user.png',
                'theme_fashion' => 'assets/img/placeholder/user.png',
                'default' => 'public/assets/front-end/img/placeholder/user.png',
            ],
            'banner' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-2-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-2-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-2-1.png',
            ],
            'wide-banner' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-4-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-4-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-4-1.png',
            ],
            'brand' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-1-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-2-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-1-1.png',
            ],
            'category' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-1-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-1-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-1-1.png',
            ],
            'logo' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-4-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-4-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-4-1.png',
            ],
            'shop' => [
                'theme_aster' => 'assets/img/placeholder/shop.png',
                'theme_fashion' => 'assets/img/placeholder/shop.png',
                'default' => 'public/assets/front-end/img/placeholder/shop.png',
            ],
            'shop-banner' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-4-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-4-1.png',
                'default' => 'public/assets/front-end/img/placeholder/seller-banner.png',
            ],
        ];

        if (isset($placeholderMap[$type])) {
            if (is_array($placeholderMap[$type])) {
                $theme = theme_root_path();
                $placeholderPath = theme_asset(path: $placeholderMap[$type][$theme]);
                if ($theme == 'default') {
                    $placeholderPath = theme_asset(path: $placeholderMap[$type][$theme]);
                }
                return (!empty($path) && $path['status'] == 200) ? $path['path'] : $placeholderPath;
            } else {
                return (!empty($path) && $path['status'] == 200) ? $path['path'] : dynamicAsset(path: 'public/assets/' . $placeholderMap[$type]);
            }
        }

        return (!empty($path) && $path['status'] == 200) ? $path['path'] : dynamicStorage(path: 'public/assets/front-end/img/placeholder/placeholder-2-1.png');
    }
}

if (!function_exists('dynamicAsset')) {
    function dynamicAsset(string $path): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $position = strpos($path, 'public/');
            $result = $path;
            if ($position === 0) {
                $result = preg_replace('/public/', '', $path, 1);
            }
        } else {
            $result = $path;
        }
        return asset($result);
    }
}

if (!function_exists('dynamicStorage')) {
    function dynamicStorage(string $path): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('storage/app/public', 'storage', $path);
        } else {
            $result = $path;
        }
        return asset($result);
    }
}

if (!function_exists('getValidImage')) {
    function getValidImage($path, $type = null, $source = null): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $path = str_replace('storage/app/public', 'storage', $path);
        }

        $givenPath = dynamicStorage($path);

        if ($source) {
            return is_file($path) ? $givenPath : $source;
        }

        $placeholderMap = [
            'backend-basic' => 'back-end/img/placeholder/placeholder-1-1.png',
            'backend-brand' => 'back-end/img/placeholder/brand.png',
            'backend-banner' => 'back-end/img/placeholder/placeholder-4-1.png',
            'backend-category' => 'back-end/img/placeholder/category.png',
            'backend-logo' => 'back-end/img/placeholder/placeholder-4-1.png',
            'backend-product' => 'back-end/img/placeholder/product.png',
            'backend-profile' => 'back-end/img/placeholder/user.png',
            'backend-payment' => 'back-end/img/placeholder/placeholder-4-1.png',
            'product' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-1-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-1-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-1-1.png',
            ],
            'avatar' => [
                'theme_aster' => 'assets/img/placeholder/user.png',
                'theme_fashion' => 'assets/img/placeholder/user.png',
                'default' => 'public/assets/front-end/img/placeholder/user.png',
            ],
            'banner' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-2-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-2-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-2-1.png',
            ],
            'wide-banner' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-4-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-4-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-4-1.png',
            ],
            'brand' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-2-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-2-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-1-1.png',
            ],
            'category' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-1-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-1-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-1-1.png',
            ],
            'logo' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-4-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-4-1.png',
                'default' => 'public/assets/front-end/img/placeholder/placeholder-4-1.png',
            ],
            'shop' => [
                'theme_aster' => 'assets/img/placeholder/shop.png',
                'theme_fashion' => 'assets/img/placeholder/shop.png',
                'default' => 'public/assets/front-end/img/placeholder/shop.png',
            ],
            'shop-banner' => [
                'theme_aster' => 'assets/img/placeholder/placeholder-4-1.png',
                'theme_fashion' => 'assets/img/placeholder/placeholder-4-1.png',
                'default' => 'public/assets/front-end/img/placeholder/seller-banner.png',
            ],
        ];

        if (isset($placeholderMap[$type])) {
            if (is_array($placeholderMap[$type])) {
                $theme = theme_root_path();
                $placeholderPath = theme_asset(path: $placeholderMap[$type][$theme]);
                if ($theme == 'default') {
                    $placeholderPath = theme_asset(path: $placeholderMap[$type][$theme]);
                }

                return is_file($path) ? $givenPath : $placeholderPath;
            } else {
                return is_file($path) ? $givenPath : dynamicAsset(path: 'public/assets/' . $placeholderMap[$type]);
            }
        }

        return is_file($path) ? $givenPath : dynamicStorage(path: 'public/assets/front-end/img/placeholder/placeholder-2-1.png');
    }
}

if (!function_exists('validFileCheck')) {
    function validFileCheck($path)
    {
        $headers = get_headers($path);
        if (stripos($headers[0], "200 OK")) {
            return $path;
        } else {
            return null;
        }
    }
}

if (!function_exists('getTemporaryImageForExport')) {
    function getTemporaryImageForExport($imagePath)
    {
        $imageData = file_get_contents($imagePath);
        return imagecreatefromstring($imageData);
    }
}

if (!function_exists('getImageForExport')) {
    function getImageForExport($imagePath)
    {
        $temporaryImage = getTemporaryImageForExport($imagePath);
        $pngImage = imagecreatetruecolor(imagesx($temporaryImage), imagesy($temporaryImage));
        imagealphablending($pngImage, false);
        imagesavealpha($pngImage, true);
        imagecopy($pngImage, $temporaryImage, 0, 0, 0, 0, imagesx($temporaryImage), imagesy($temporaryImage));
        return $pngImage;
    }
}
