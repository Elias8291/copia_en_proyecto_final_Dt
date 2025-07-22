<?php

namespace App\Helpers;

class ErrorImageHelper
{
    /**
     * Get a random error image from available options
     */
    public static function getRandomErrorImage(): string
    {
        $errorImages = self::getAvailableErrorImages();

        if (empty($errorImages)) {
            return asset('images/logoColor.png');
        }

        $randomImage = $errorImages[array_rand($errorImages)];

        return asset("images/{$randomImage}");
    }

    /**
     * Get list of available error images
     */
    private static function getAvailableErrorImages(): array
    {
        $errorImages = [];

        // Primary error images
        if (file_exists(public_path('images/error-hombre.png.png'))) {
            $errorImages[] = 'error-hombre.png.png';
        }

        if (file_exists(public_path('images/error-mujer.png'))) {
            $errorImages[] = 'error-mujer.png';
        }

        // Fallback images if primary ones don't exist
        if (empty($errorImages)) {
            $fallbackImages = [
                'mujer_bienvenida.png',
                'exito-elias.png',
                'logoColor.png',
            ];

            foreach ($fallbackImages as $image) {
                if (file_exists(public_path("images/{$image}"))) {
                    $errorImages[] = $image;
                }
            }
        }

        return $errorImages;
    }

    /**
     * Get specific error image by type
     */
    public static function getErrorImageByType(string $type): string
    {
        $imageMap = [
            'hombre' => 'error-hombre.png.png',
            'mujer' => 'error-mujer.png',
            'default' => 'logoColor.png',
        ];

        $imageName = $imageMap[$type] ?? $imageMap['default'];

        if (file_exists(public_path("images/{$imageName}"))) {
            return asset("images/{$imageName}");
        }

        return asset('images/logoColor.png');
    }
}
