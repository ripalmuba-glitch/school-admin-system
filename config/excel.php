<?php

use Maatwebsite\Excel\Excel;
// use Maatwebsite\Excel\Drivers\DomPDF\Driver as DomPDF; // Tidak kita perlukan lagi
use Maatwebsite\Excel\Drivers\mPDF\Driver as mPDF; // <-- Pastikan ini ada
use Maatwebsite\Excel\Drivers\TcPDF\Driver as TcPDF;

return [

    'exports' => [
        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas
        |--------------------------------------------------------------------------
        */
        'pre_calculate_formulas' => false,

        /*
        |--------------------------------------------------------------------------
        | CSV Settings
        |--------------------------------------------------------------------------
        */
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => "\r\n",
            'use_bom' => false,
            'include_separator_line' => false,
            'excel_compatibility' => false,
            'output_encoding' => '',
            'test_auto_detect' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Default Writer Type
        |--------------------------------------------------------------------------
        */
        'default_writer_type' => Excel::XLSX,
    ],

    'imports' => [
        /*
        |--------------------------------------------------------------------------
        | Read Only
        |--------------------------------------------------------------------------
        */
        'read_only' => true,

        /*
        |--------------------------------------------------------------------------
        | Default Import
        |--------------------------------------------------------------------------
        */
        'default_import_settings' => [
            'input_encoding' => 'UTF-8',
            'contiguous' => false,
            'dates' => [
                'format' => null,
                'columns' => [],
            ],
            'ignore_empty' => true,
            'heading_row' => [
                'formatter' => 'slug',
            ],
            'chunk_size' => 1000,
            'transaction' => [
                'handler' => 'db',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Heading Row
    |--------------------------------------------------------------------------
    */
    'heading_row' => [
        'formatter' => 'slug',
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction
    |--------------------------------------------------------------------------
    */
    'transactions' => [
        'handler' => 'db',
        'db' => [
            'connection' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Temporary Files
    |--------------------------------------------------------------------------
    */
    'temporary_files' => [
        'local_path' => storage_path('framework/cache/laravel-excel'),
        'remote_disk' => null,
        'remote_prefix' => null,
        'force_resync_remote' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Settings
    |--------------------------------------------------------------------------
    */
    'pdf' => [

        // --- PERUBAHAN UTAMA DI SINI ---
        // Ganti 'driver' dari DomPDF menjadi mPDF
        'driver' => Excel::DOMPDF,
        // ------------------------------------

        'mode'                  => 'utf-8',
        'format'                => 'A4',
        'orientation'           => 'P', // Portrait
        'creator'               => 'Laravel Excel',

        'drivers' => [

            'DomPDF' => [
                'driver' => \Maatwebsite\Excel\Drivers\DomPDF\Driver::class, // Tetap biarkan
                'path' => base_path('vendor/dompdf/dompdf/'),
            ],

            // --- Pastikan 'mPDF' ada ---
            'mPDF' => [
                'driver' => mPDF::class,
                'path' => base_path('vendor/mpdf/mpdf/'),
            ],

            'TcPDF' => [
                'driver' => TcPDF::class,
                'path' => base_path('vendor/tecnickcom/tcpdf/'),
            ],
        ],
    ],
];
