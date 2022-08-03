@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Guild: Detail',
    'wsidebar_menu' => 'guild',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Guild: Detail',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Guild',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.guild.index')
            ], [
                'title' => 'Detail',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ],
        'extra_button' => '
            <a href="'.($provPreviousMenu).'" class="btn btn-sm bg-secondary d-inline-flex align-items-center tw__text-white tw__gap-1">
                <i class="fa-solid fa-angle-left"></i>
                Back
            </a>
        '
    ]
])

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Guild: Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <tbody>
                    <tr>
                        <th>Association</th>
                        <td><a href="{{ route('s.association.show', $data->association->uuid) }}">{{ $data->association->name }}</a></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $data->name }}</td>
                    </tr>
                    <tr>
                        <th>Identifier</th>
                        <td>{{ $data->guild_id ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Available Player</h5>
                        </div>
                        <div class="card-body" id="available-player"></div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1" id="btn-load_more" data-page="1" onclick="fetchData(1)"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Guild member</h5>
                        </div>
                        <div class="card-body" id="guild-member"></div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1" id="btn_member-load_more" data-page="1" onclick="fetchData(1)"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        const fetchAvailablePlayer = (page = 1) => {
            if(document.getElementById('available-player')){
                let button = null;
                let container = document.getElementById('available-player');
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
                url.searchParams.append('limit', 10);
                url.searchParams.append('force_order_column', 'name');
                url.searchParams.append('force_order', 'asc');
                url.searchParams.append('action', 'guild-member');
                url.searchParams.append('status', 'non-member');
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
                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex', 'tw__items-center');
                                content.innerHTML = `
                                    <div class="tw__mr-auto">
                                        <span class="tw__font-bold tw__block">${val.name}${val.player_identifier ? `<small class="tw__ml-1 tw__text-gray-400">(#${val.player_identifier})</small>` : ''}</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary tw__flex tw__items-center tw__gap-1" onclick="transferPlayer('${val.uuid}', 'join')"><i class="fa-solid fa-check"></i>Add</button>
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
                            content = document.createElement('div');
                        }

                        // Update Pagination
                        if(document.getElementById('btn_member-load_more')){
                            let nextPage = parseInt(page) + 1;
                            if(page === response.last_page){
                                document.getElementById('btn_member-load_more').setAttribute('disabled', true);
                            } else {
                                document.getElementById('btn_member-load_more').removeAttribute('disabled');
                                document.getElementById('btn_member-load_more').setAttribute('onclick', `fetchData(${nextPage})`);
                                document.getElementById('btn_member-load_more').dataset.page = nextPage;
                            }
                        }
                    });
            }
        };
        const fetchGuildMember = (page = 1) => {
            if(document.getElementById('guild-member')){
                let button = null;
                let container = document.getElementById('guild-member');
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
                url.searchParams.append('limit', 10);
                url.searchParams.append('force_order_column', 'name');
                url.searchParams.append('force_order', 'asc');
                url.searchParams.append('action', 'guild-member');
                url.searchParams.append('status', 'member');
                url.searchParams.append('guild_id', '{{ $data->uuid }}');
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
                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex', 'tw__items-center');
                                content.innerHTML = `
                                    <div class="tw__mr-auto">
                                        <span class="tw__font-bold tw__block">${val.name}${val.player_identifier ? `<small class="tw__ml-1 tw__text-gray-400">(#${val.player_identifier})</small>` : ''}</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger tw__flex tw__items-center tw__gap-1" onclick="transferPlayer('${val.uuid}', 'expel')"><i class="fa-solid fa-xmark"></i>Remove</button>
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
        };

        document.addEventListener('DOMContentLoaded', () => {
            fetchAvailablePlayer(1);
            fetchGuildMember(1);
        });

        function transferPlayer(uuid, action){
            console.log(`Transfer ${uuid} player with action ${action}`);

            let rawData = new FormData;
            rawData.append('_method', 'POST');
            rawData.append('_token', '{{ csrf_token() }}');
            rawData.append('action', action);
            rawData.append('guild_id', '{{ $data->uuid }}');
            rawData.append('uuid', uuid);
            let formData = Object.fromEntries(rawData);

            axios.post(`{{ route('s.guild.player.store') }}`, formData)
                .then(function (response) {
                    console.log(response);
                    fetchAvailablePlayer(1);
                    fetchGuildMember(1);
                });
        }
    </script>
@endsection