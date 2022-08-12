@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Player: Detail',
    'wsidebar_menu' => 'player',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Player - Detail',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Player',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.player.index')
            ], [
                'title' => 'Detail',
                'icon' => null,
                'is_active' => false,
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
            <div class="tw__grid tw__grid-flow-col tw__grid-cols-2 tw__gap-4 tw__items-center">
                <h5 class="card-title">Player: Detail</h5>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>Name</th>
                    <td>{{ $data->name }}</td>
                </tr>
                <tr>
                    <th>Player Identifier</th>
                    <td>{{ $data->player_identifier ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Current Guild</th>
                    <td>
                        <span class=" tw__block">{{ $data->guildMember()->exists() ? $data->guildMember->guild->name : '-' }}</span>
                        @if ($data->guildMember()->exists())
                            <small class="text-muted tw__italic" data-join="{{ $data->guildMember->join }}">-</small>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Guild History</h5>
                </div>
                <div class="card-body tw__p-0 table-responsive">
                    <table class="table table-hover table-bordered table-striped tw__mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Period</th>
                            </tr>
                        </thead>
                        <tbody id="guild_history-table">
                            <tr>
                                <td colspan="3" class=" tw__text-center">No available data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1" id="btn-load_more" data-page="1" onclick="fetchData(1)"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        if(document.querySelector('small[data-join]')){
            let dateEl = document.querySelector('small[data-join]');
            let joinDate = dateEl.dataset.join;

            dateEl.innerHTML = momentDateTime(moment(dateEl.dataset.join), 'DD MMM, YYYY / HH:mm', true);
        }
        
        const fetchData = (page = 1) => {
            if(document.getElementById('guild_history-table')){
                let button = null;
                let container = document.getElementById('guild_history-table');
                if(page === parseInt(1)){
                    container.innerHTML = `
                        <tr>
                            <td class=" tw__text-center" colspan="3">Loading...</td>
                        </tr>
                    `;
                }

                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let url = new URL(`{{ route('s.json.player.guild-history.list', $data->uuid) }}`);
                url.searchParams.append('page', page);
                url.searchParams.append('limit', 5);
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
                        let content = document.createElement('tr');

                        if(data.length > 0){
                            data.forEach((val, index) => {
                                let period = [];
                                let join = momentDateTime(moment(val.join), 'DD MMM, YYYY / HH:mm', true);
                                let end = '<strong>Now</strong>';
                                if(val.out){
                                    end = momentDateTime(moment(val.out), 'DD MMM, YYYY / HH:mm', true);
                                }
                                period.push(join);
                                period.push(end);

                                content.innerHTML = `
                                    <td>${(parseInt(index) + 1)}</td>
                                    <td>${val.guild.name}</td>
                                    <td>
                                        <span>${period.join(' - ')}</span>
                                    </td>
                                `;
                                
                                container.appendChild(content);
                                content = document.createElement('tr');
                            });
                        } else {
                            content.innerHTML = `
                                <td class=" tw__text-center" colspan="3">No available data</td>
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
        document.addEventListener('DOMContentLoaded', () => {
            fetchData(1);
        });
    </script>
@endsection