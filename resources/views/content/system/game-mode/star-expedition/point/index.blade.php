@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Star Expedition: Point',
    'wsidebar_menu' => 'star-expedition',
    'wsidebar_submenu' => 'point',
    'wheader' => [
        'header_title' => 'Game Mode: SE Point',
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
                'title' => 'SE Point',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])