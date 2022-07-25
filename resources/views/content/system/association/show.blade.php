@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Association',
    'wsidebar_menu' => 'association',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Association',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Association',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.association.index')
            ], [
                'title' => 'Show',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])