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
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <form class="card" id="form" method="POST" action="{{ route('s.association.store') }}">
                @csrf
                @method('POST')

                <div class="card-header">
                    <h5 class="card-title">Form</h5>
                </div>
                <div class="card-body">
                    <div class="form-group tw__mb-0">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="input-name" placeholder="Association Name">
                    </div>
                </div>
                <div class="card-footer tw__text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-sm text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">List</h5>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        if(document.getElementById('form')){
            document.getElementById('form').addEventListener('submit', (e) => {
                e.preventDefault();
                console.log("Form is being submited");
            });
        }
    </script>
@endsection