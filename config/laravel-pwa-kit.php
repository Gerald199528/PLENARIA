<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable PWA
    |--------------------------------------------------------------------------
    | Globally enable or disable Progressive Web App functionality.
    */
    'enable_pwa' => true,

    /*
    |--------------------------------------------------------------------------
    | Show Install Toast on First Load
    |--------------------------------------------------------------------------
    |
    | Determines whether the PWA install toast should be displayed when a user
    | first visits the site. Once the toast is shown or dismissed, it will not
    | reappear for that user on the same day, preventing repeated interruptions
    | and improving user experience.
    |
    | Type: `bool`
    | Default: true
    |
    */
    'install-toast-show' => true,


    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Configuration
    |--------------------------------------------------------------------------
    | Defines metadata for your Progressive Web App.
    | This configuration is used to generate the manifest.json file.
    | Reference: https://developer.mozilla.org/en-US/docs/Web/Manifest
    */
    'manifest' => [
        'appName' => 'PLENARIA',
        'name' => 'PLENARIA - Tu Concejo Municipal Digital',
        'shortName' => 'PLENARIA',
        'short_name' => 'PLENARIA',
        'startUrl' => '/',
        'start_url' => '/',
        'scope' => '/',
        'author' => 'Tu Municipio',
        'version' => '1.0',
        'description' => 'Plataforma Digital para Concejos Municipales - Solución Digital para la Gestión de Concejos Municipales',
        'orientation' => 'portrait-primary',
        'dir' => 'auto',
        'lang' => 'es',
        'display' => 'standalone',
        'themeColor' => '#1d4ed8',
        'theme_color' => '#1d4ed8',
        'backgroundColor' => '#ffffff',
        'background_color' => '#ffffff',
        'icons' => [
            [
                'src' => 'logo.png',
                'sizes' => '512x512',
                'type' => 'image/png',
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    | Enables verbose logging for service worker events and cache information.
    */
    'debug' => env('LARAVEL_PWA_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Toast Content
    |--------------------------------------------------------------------------
    | Title and description text for the install prompt toast.
    */
    'title' => '¡Bienvenido a PLENARIA!',
    'description' => 'Haz clic en <strong>Instalar Ahora</strong> & disfrútalo como una aplicación nativa.',

    /*
    |--------------------------------------------------------------------------
    | Mobile View Position
    |--------------------------------------------------------------------------
    | Position of the PWA install toast on small devices.
    | Supported values: "top", "bottom".
    | RTL mode is supported and respects <html dir="rtl">.
    */
    'small_device_position' => 'bottom',

    /*
    |--------------------------------------------------------------------------
    | Install Now Button Text
    |--------------------------------------------------------------------------
    | Defines the text shown on the "Install Now" button inside the PWA
    | installation toast. This can be customized for localization.
    |
    | Example: 'install_now_button_text' => 'অ্যাপ ইন্সটল করুন'
    */
    'install_now_button_text' => 'Instalar Ahora',

    /*
    |--------------------------------------------------------------------------
    | Livewire Integration
    |--------------------------------------------------------------------------
    | Optimize PWA functionality for applications using Laravel Livewire.
    */
    'livewire-app' => false,
];
