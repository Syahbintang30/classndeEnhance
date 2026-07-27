<?php

$viewsCachePath = storage_path('framework/views');
if (! is_dir($viewsCachePath)) {
    @mkdir($viewsCachePath, 0755, true);
}

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath($viewsCachePath) ?: $viewsCachePath
    ),

];
