@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Player',
    'wsidebar_menu' => 'player',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Player',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Player',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])

@section('css_plugins')
    {{-- Choices --}}
    @include('layouts.plugins.choices.css')
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <form class="card" id="form" method="POST" action="{{ route('s.player.store') }}">
                @csrf
                @method('POST')

                <div class="card-header">
                    <h5 class="card-title">Form (insert)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label for="input-wallet_id">Association</label>
                        <select class="form-control" id="input-association_id" name="association_id" placeholder="Search for Association">
                            <option value="">Search for Association</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="input-name" placeholder="Player Name">
                    </div>
                    <div class="form-group tw__mb-0">
                        <label>Player Identifier</label>
                        <input type="text" class="form-control" name="player_id" id="input-player_id" placeholder="Player Identifier">
                    </div>
                </div>
                <div class="card-footer tw__text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-sm text-gray-600 ms-auto" onclick="resetAction()">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tw__flex tw__items-center tw__gap-1">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">List</h5>
                </div>
                <div class="card-body" id="player-container"></div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1" id="btn-load_more" data-page="1" onclick="fetchData(1)"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_plugins')
    {{-- Choices --}}
    @include('layouts.plugins.choices.js')
@endsection

@section('js_inline')
    <script>
        // Association Choices
        let associationChoice = null;
        if(document.getElementById('input-association_id')){
            const associationEl = document.getElementById('input-association_id');
            associationChoice = new Choices(associationEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Association",
                placeholder: true,
                placeholderValue: 'Search for Association',
                shouldSort: false
            });
            associationChoice.setChoices(() => {
                // console.log(e);
                return fetch(
                    `{{ route('s.json.association.list') }}`
                )
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    return data.data.map(function(result) {
                        return {
                            value: result.uuid,
                            label: `${result.name}`
                        };
                    });
                });
            });
        }

        const fetchData = (page = 1) => {
            if(document.getElementById('player-container')){
                let button = null;
                let container = document.getElementById('player-container');
                if(page === parseInt(1)){
                    container.innerHTML = 'Loading...';
                }
                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let url = new URL(`{{ route('s.json.player.list') }}`);
                url.searchParams.append('page', page);
                url.searchParams.append('limit', 5);
                url.searchParams.append('force_order_column', 'name');
                url.searchParams.append('force_order', 'asc');
                fetch(url)
                    .then((response) => {
                        if(page === parseInt(1)){
                            container.innerHTML = '';
                        }
                        if(button !== null){
                            button.innerHTML = `<i class="fa-solid fa-arrows-rotate"></i> Load More`;
                        }
                        return response.json();
                    }).then((response) => {
                        let data = response.data;
                        let content = document.createElement('div');

                        if(data.length > 0){
                            data.forEach((val, index) => {
                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex');
                                content.innerHTML = `
                                    <div class="tw__mr-auto">
                                        <span class="tw__font-bold tw__block">${val.name}${val.player_identifier ? `<small class="tw__ml-1 tw__text-gray-400">(#${val.player_identifier})</small>` : ''}</span>
                                        <small>${val.guild_member ? '' : 'Non-'}Member${val.guild_member && val.guild_member.guild ? ` - <a href="{{ route('s.guild.index') }}/${val.guild_member.guild.uuid}}">${val.guild_member.guild.name}</a>` : ''}</small>
                                    </div>

                                    <div class="dropdown dropstart tw__leading-none tw__flex tw__items-baseline">
                                        <button class="dropdown-toggle arrow-none" type="button" data-bs-auto-close="outside" id="dropdown-${index}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdown-${index}">
                                            <li>
                                                <a class="dropdown-item tw__text-yellow-400" href="javascript:void(0)" onclick="editData('${val.uuid}')">
                                                    <span class=" tw__flex tw__items-center"><i class="fa-solid fa-pen-to-square"></i>Edit</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('s.player.index') }}/${val.uuid}">
                                                    <span class=" tw__flex tw__items-center"><i class="fa-solid fa-eye"></i>Show</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `;
                                container.appendChild(content);
                                content = document.createElement('div');
                            });
                        } else {
                            content.classList.add('alert', 'alert-primary', 'tw__mb-0');
                            content.setAttribute('role', 'alert');
                            content.innerHTML = `
                                <div class=" tw__flex tw__items-center">
                                    <i class="fa-solid fa-triangle-exclamation tw__mr-2"></i>
                                    <div class="tw__block tw__font-bold tw__uppercase">
                                        Attention!
                                    </div>
                                </div>
                                <span class="tw__block tw__italic">No data found</span>
                            `;

                            container.appendChild(content);
                        }

                        // Update Pagination
                        if(document.getElementById('btn-load_more')){
                            let nextPage = parseInt(page) + 1;
                            if(page === response.last_page){
                                document.getElementById('btn-load_more').setAttribute('disabled', true);
                            } else {
                                document.getElementById('btn-load_more').removeAttribute('disabled');
                                document.getElementById('btn-load_more').setAttribute('onclick', `fetchData(${nextPage})`);
                                document.getElementById('btn-load_more').dataset.page = nextPage;
                            }
                        }
                    });
            }
        }

        const resetAction = () => {
            let elId = 'form';
            if(document.getElementById(elId)){
                let form = document.getElementById(elId);

                // Reset Title
                if(form.querySelector('.card-title')){
                    form.querySelector('.card-title').innerHTML = 'Form (Insert)';
                }
                // Reset Action URL
                form.setAttribute('action', '{{ route('s.player.store') }}');
                // Set Method
                if(form.querySelector('input[name="_method"]')){
                    form.querySelector('input[name="_method"]').value = 'POST';
                }
                // Reset Association
                if(associationChoice){
                    associationChoice.setChoiceByValue('');
                }
                // Reset Name
                if(form.querySelector('input[name="name"]')){
                    form.querySelector('input[name="name"]').value = '';
                }
                // Reset Identifier
                if(form.querySelector('input[name="player_id"]')){
                    form.querySelector('input[name="player_id"]').value = '';
                }
            }
        }

        if(document.getElementById('form')){
            function editData(uuid){
                axios.get(`{{ route('s.player.index') }}/${uuid}`)
                    .then(function (response) {
                        let result = response.data;
                        let data = result.result.data;

                        console.log(data);
                        let form = document.getElementById("form");

                        // Reset Title
                        if(form.querySelector('.card-title')){
                            form.querySelector('.card-title').innerHTML = 'Form (Update)';
                        }
                        // Reset Action URL
                        form.setAttribute('action', `{{ route('s.player.index') }}/${data.uuid}`);
                        // Set Method
                        if(form.querySelector('input[name="_method"]')){
                            form.querySelector('input[name="_method"]').value = 'PUT';
                        }
                        // Reset Association
                        if(associationChoice){
                            associationChoice.setChoiceByValue(data.association.uuid);
                        }
                        // Reset Name
                        if(form.querySelector('input[name="name"]')){
                            form.querySelector('input[name="name"]').value = data.name;
                        }
                        // Reset Identifier
                        if(form.querySelector('input[name="player_id"]')){
                            form.querySelector('input[name="player_id"]').value = data.player_identifier;
                        }
                    });
            }
            document.getElementById('form').addEventListener('submit', (e) => {
                e.preventDefault();
                console.log("Form is being submited");

                let submitBtn = e.submitter;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                // Empty Validation
                let invalidFeedback = document.querySelectorAll('.invalid-feedback');
                let formGroup = document.querySelectorAll('.form-group');
                if(invalidFeedback){
                    invalidFeedback.forEach((e) => {
                        e.parentNode.removeChild(e);
                    });
                }
                if(formGroup){
                    formGroup.forEach((e) => {
                        e.classList.remove('has-invalid');
                        e.querySelector('.form-control') ? e.querySelector('.form-control').classList.remove('is-invalid') : null;
                    })
                }

                // Create Request
                let link = e.target.getAttribute('action');
                let formData = Object.fromEntries(new FormData(e.target));
                axios.post(link, formData)
                    .then(function (response) {
                        let result = response.data;
                        console.log(result);

                        fetchData(1);
                        resetAction();
                    })
                    .catch(function (error) {
                        let error_data = error.response.data;
                        let errors = error_data.errors;
                        
                        for (const [key, value] of Object.entries(errors)) {
                            let el = document.getElementById(`input-${key}`);
                            el.closest('.form-control').classList.add('is-invalid');
                            el.closest('.form-group').classList.add('has-invalid');
                            el.closest('.form-group').insertAdjacentHTML('beforeend', `<small class="invalid-feedback tw__text-xs tw__block">*${value}</small>`);
                        }
                    })
                    .then(function () {
                        // always executed
                        submitBtn.innerHTML = `Submit`;
                        submitBtn.disabled = false;
                    });
            });
        }
        document.addEventListener('DOMContentLoaded', (e) => {
            fetchData();
        });
    </script>
@endsection