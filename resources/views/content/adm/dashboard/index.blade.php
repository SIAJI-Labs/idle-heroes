@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Dashboard',
    'wsidebar_menu' => 'dashboard',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Dashboard',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])