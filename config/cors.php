<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // អនុញ្ញាតឱ្យ API គ្រប់ Route អាច Access បាន
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // អនុញ្ញាតឱ្យប្រើគ្រប់ Methods (GET, POST, PUT, DELETE)
    'allowed_methods' => ['*'],

    // អនុញ្ញាតឱ្យគ្រប់ប្រភព (Origins) អាចហៅមកកាន់ API នេះបាន (សំខាន់សម្រាប់ Flutter)
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // អនុញ្ញាតឱ្យផ្ញើគ្រប់ Headers (ដូចជា Authorization Token ជាដើម)
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
