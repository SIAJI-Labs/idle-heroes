@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Star Expedition: Map Clearance',
    'wsidebar_menu' => 'star-expedition',
    'wsidebar_submenu' => 'map',
    'wheader' => [
        'header_title' => 'Game Mode: SE Map Clearance',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Game Mode',
                'icon' => null,
                'is_active' => false,
                'url' => null
            ], [
                'title' => 'SE Map Clearance',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])