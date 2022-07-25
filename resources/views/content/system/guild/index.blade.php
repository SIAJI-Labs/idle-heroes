@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Guild',
    'wsidebar_menu' => 'guild',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Guild',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Guild',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <form class="card" id="form" method="POST" action="{{ route('s.guild.store') }}">
                @csrf
                @method('POST')

                <div class="card-header">
                    <h5 class="card-title">Form (insert)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="input-name" placeholder="Guild Name">
                    </div>
                    <div class="form-group tw__mb-0">
                        <label>Guild ID</label>
                        <input type="text" class="form-control" name="guild_id" id="input-guild_id" placeholder="Guild Identifier">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection