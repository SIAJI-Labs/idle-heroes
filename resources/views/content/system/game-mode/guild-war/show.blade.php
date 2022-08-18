@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Guild War: Detail',
    'wsidebar_menu' => 'guild-war',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Game Mode: Guild War - Detail',
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
                'title' => 'Guild War',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.game-mode.guild-war.index')
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

@section('css_plugins')
    {{-- Datatable --}}
    @include('layouts.plugins.datatable.css')
    {{-- Choices --}}
    @include('layouts.plugins.choices.css')
    {{-- Sweetalert --}}
    @include('layouts.plugins.sweetalert2.css')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="tw__grid tw__grid-flow-col tw__grid-cols-2 tw__gap-4 tw__items-center">
                <h5 class="card-title">Guild War: Detail</h5>
                <div class="tw__text-right">
                    <a href="javascript:void(0)" class="btn btn-sm btn-secondary tw__inline-flex tw__items-center tw__gap-1" data-bs-toggle="modal" data-bs-target="#modal-participant"><i class="fas fa-plus"></i>Add Participant</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>Guild</th>
                    <td>{{ $data->guild->name }}</td>
                </tr>
                <tr>
                    <th>Period</th>
                    <td>
                        <span id="game_mode-period" data-start="{{ $data->period->datetime }}" data-end="{{ date("Y-m-d H:i:s", strtotime($data->period->datetime.'+'.$data->period->length.' days')) }}" data-length="{{ $data->period->length }}">{{ date('d F, Y / H:i:s', strtotime($data->period->datetime)) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>State</th>
                    <td>
                        <span id="game_mode-state">-</span>
                    </td>
                </tr>
            </table>

            <div class="card">
                <div class="card-header">
                    <div class="tw__grid tw__grid-flow-col tw__grid-cols-2 tw__gap-4 tw__items-center">
                        <h5 class="card-title">Point Form</h5>
                        <div class="tw__text-right">
                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary tw__inline-flex tw__items-center tw__gap-1" onclick="resetPointAction(true)">Reset Form</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="row" id="form-point" action="{{ route('s.game-mode.guild-war.participation.store') }}">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="action" value="participation_point" readonly>
                        <input type="hidden" name="guild_war_id" value="{{ $data->uuid }}" readonly>

                        <div class="col-12 col-lg-4 tw__order-1">
                            <div class="form-group lg:tw__mb-0">
                                <label>Member</label>
                                <select class="form-control" id="input-point_member_id" name="point_member_id" placeholder="Search for Registered Guild Member">
                                    <option value="">Search for Registered Guild Member</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 tw__order-2">
                            <div class="form-group tw__mb-2 lg:tw__mb-0">
                                <label>Progress</label>
                                <select class="form-control" id="input-progress" name="progress" placeholder="Search for Progress">
                                    <option value="">Search for Progress</option>
                                    <option value="day_1">Day 1</option>
                                    <option value="day_2">Day 2</option>
                                    <option value="day_3">Day 3</option>
                                    <option value="day_4">Day 4</option>
                                    <option value="day_5">Day 5</option>
                                    <option value="day_6">Day 6</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-7 col-lg-2 tw__order-4 lg:tw__order-3">
                            <div class="form-group tw__mb-0">
                                <label>Point</label>
                                <input type="text" inputmode="numeric" class="form-control" name="point" id="input-point" placeholder="Point">
                            </div>
                        </div>
                        <div class="col-5 col-md-2 tw__order-5 lg:tw__order-4">
                            <div class="form-group tw__mb-0">
                                <label class=" tw__block tw__text-white">Submit</label>
                                <button type="submit" class="btn btn-primary tw__flex tw__items-center tw__gap-1">Submit</button>
                            </div>
                        </div>
                        <div class="col-12 tw__order-3 lg:tw__order-5">
                            <div class="form-group lg:tw__mb-0 lg:tw__mt-4">
                                <div class="form-check tw__text-sm" id="form-add_more">
                                    <input class="form-check-input" type="checkbox" name="add_more" value="" id="input-add_more">
                                    <label class="form-check-label" for="input-add_more">Keep selected progress</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered tw__mb-0">
                    <thead>
                        <tr id="table-header">
                            <th class=" tw__cursor-pointer">#</th>
                            <th data-key="guildMember.player.name" class=" tw__cursor-pointer">Member</th>
                            <th data-key="sum_progress" class=" tw__text-center tw__cursor-pointer">Sum</th>
                            <th data-key="day_1" class=" tw__text-center tw__cursor-pointer">Day 1</th>
                            <th data-key="day_2" class=" tw__text-center tw__cursor-pointer">Day 2</th>
                            <th data-key="day_3" class=" tw__text-center tw__cursor-pointer">Day 3</th>
                            <th data-key="day_4" class=" tw__text-center tw__cursor-pointer">Day 4</th>
                            <th data-key="day_5" class=" tw__text-center tw__cursor-pointer">Day 5</th>
                            <th data-key="day_6" class=" tw__text-center tw__cursor-pointer">Day 6</th>
                        </tr>
                    </thead>
                    <tbody id="guild_war-container">
                        <tr>
                            <td class=" tw__text-center" colspan="9">No available data</td>
                        </tr>
                    </tbody>
                    <tfoot id="table-footer">
                        <tr>
                            <th colspan="2">Missing</th>
                            <th data-key="sum_progress">-</th>
                            <th data-key="day_1">-</th>
                            <th data-key="day_2">-</th>
                            <th data-key="day_3">-</th>
                            <th data-key="day_4">-</th>
                            <th data-key="day_5">-</th>
                            <th data-key="day_6">-</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('content_modal')
    @include('content.system.game-mode.guild-war.partials.modal-participant')
@endsection

@section('js_plugins')
    {{-- jQuery --}}
    @include('layouts.plugins.jquery.js')
    {{-- Datatable --}}
    @include('layouts.plugins.datatable.js')
    {{-- Choices --}}
    @include('layouts.plugins.choices.js')
    {{-- iMask --}}
    @include('layouts.plugins.iMask.js')
    {{-- Sweetalert --}}
    @include('layouts.plugins.sweetalert2.js')
@endsection

@section('js_inline')
    <script>
        var timer;
        document.addEventListener('DOMContentLoaded', () => {
            if(document.getElementById('game_mode-period')){
                let element = document.getElementById('game_mode-period');
                let start = momentDateTime(moment(element.dataset.start), null, null, false);
                let ends = momentDateTime(moment(element.dataset.end), null, null, false);
                let length = element.dataset.length;

                element.innerHTML = `
                    <span class="tw__block">${momentDateTime(element.dataset.start, 'DD MMM, YYYY / HH:mm', true)}</span>
                    <small class="text-muted tw__italic">Length: ${length} day${length > 1 ? 's' : ''}, ends in ${momentDateTime(moment(element.dataset.start).add(length, 'days'), 'DD MMM, YYYY / HH:mm', true)}</small>
                `;
                element.dataset.start = start;
                element.dataset.end = ends;
            }
            setTimeout(() => {
                if(document.getElementById('game_mode-state')){
                    let element = document.getElementById('game_mode-state');
                    if(document.getElementById('game_mode-period')){
                        let periodEl = document.getElementById('game_mode-period');
                        let date = new Date(periodEl.dataset.end);
                        let length = periodEl.dataset.length;

                        if(moment(date) > moment()){
                            timer = setInterval(() => {
                                countDowntimer(periodEl);
                            }, 1000);
                        } else {
                            element.innerHTML = `Event completed!`;
                        }
                    } else {
                        element.innerHTML = '-';
                    }

                    const addDays = (date, days) => {
                        let res = new Date(date);
                        res.setDate(res.getDate() + days);
                        return res;
                    }
                    const countDowntimer = (el, index) => {
                        let text = '-';
                        let now = new Date();
                        let start = new Date(el.dataset.start);
                        let finish = new Date(el.dataset.end);
                        let ends = addDays(finish, parseInt(el.dataset.length));

                        let preparationText = '-';
                        let endText = '-';
                        let timeType = ['preparation', 'ends'];
                        timeType.forEach((val) => {
                            let date = start;
                            if(val === 'preparation'){
                                date = start;
                            } else if(val === 'ends'){
                                date = finish;
                            }

                            let diff = date.getTime() - now.getTime();
                            let days = Math.floor(diff / (1000*60*60*24));
                            let hours = (days * 24) + Math.floor(diff % (1000*60*60*24) / (1000*60*60));
                            let parsedHours = String(hours).padStart(2, '0');
                            let minutes = Math.floor(diff % (1000*60*60)/ (1000*60));
                            let parsedMinutes = String(minutes).padStart(2, '0');
                            let seconds = Math.floor(diff % (1000*60) / 1000);
                            let parsedSeconds = String(seconds).padStart(2, '0');

                            let text = '-';
                            if(now < date){
                                text = `${parsedHours}:${parsedMinutes}:${parsedSeconds}`;
                            }

                            if(val === 'preparation'){
                                preparationText = text;
                            } else if(val === 'ends'){
                                endText = text;
                            }
                        });

                        // Validate Time remaning
                        if(now >= finish){
                            // Time is up
                            clearInterval(timer[index]);
                        } else {
                            text = `Preparation ends in ${preparationText} / Season Ends in ${endText}`;
                        }

                        document.getElementById('game_mode-state').innerHTML = text;
                    }
                }
            }, 100);
        });
    </script>
    <script>
        // IMask
        var pointMask = null;
        document.addEventListener('DOMContentLoaded', () => {
            // iMask
            pointMask = IMask(document.getElementById('input-point'), {
                mask: Number,
                thousandsSeparator: ',',
                scale: 2,  // digits after point, 0 for integers
                signed: false,  // disallow negative
                radix: '.',  // fractional delimiter
                min: 0,
            });
        });

        var guildMemberChoice = null;
        if(document.getElementById('input-guild_member_id')){
            const guildMemberEl = document.getElementById('input-guild_member_id');
            guildMemberChoice = new Choices(guildMemberEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Available Guild Member",
                placeholder: true,
                placeholderValue: 'Search for Available Guild Member',
                shouldSort: false
            });
            guildMemberChoice.setChoices(() => {
                // console.log(e);
                let url = new URL(`{{ route('s.json.guild.member.list') }}`);
                url.searchParams.append('guild_id', '{{ $data->guild->uuid }}');
                return fetch(url)
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    return data.data.map(function(result) {
                        return {
                            value: result.uuid,
                            label: `${result.player.name}${result.player.player_identifier ? ` (#${result.player.player_identifier})` : ''}`
                        };
                    });
                });
            });
        }

        var pointProgressChoice = null;
        if(document.getElementById('input-progress')){
            const pointProgressEl = document.getElementById('input-progress');
            pointProgressChoice = new Choices(pointProgressEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Progress",
                placeholder: true,
                placeholderValue: 'Search for Progress',
                shouldSort: false
            });
        }

        var memberProgressChoice = null;
        if(document.getElementById('input-point_member_id')){
            const memberProgressEl = document.getElementById('input-point_member_id');
            memberProgressChoice = new Choices(memberProgressEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Registered Guild Member",
                placeholder: true,
                placeholderValue: 'Search for Registered Guild Member',
                shouldSort: false
            });
            function memberProgressChoiceFetch(memberProgressChoice){
                let placeholder = [
                    memberProgressChoice.setChoiceByValue('').getValue()
                ];
                memberProgressChoice.clearChoices();
                memberProgressChoice.setChoices(placeholder);
                memberProgressChoice.setChoices(() => {
                    // console.log(e);
                    let url = new URL(`{{ route('s.json.game-mode.guild-war.participant.list') }}`);
                    url.searchParams.append('guild_war_id', '{{ $data->uuid }}');
                    return fetch(url)
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            return data.data.map(function(result) {
                                return {
                                    value: result.uuid,
                                    label: `${result.guild_member.player.name}${result.guild_member.player.player_identifier ? `(#${result.guild_member.player.player_identifier})` : ''}`
                                };
                            });
                        });
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                memberProgressChoiceFetch(memberProgressChoice);
            });
        }
    </script>
    <script>
        const progressSet = (el) => {
            console.log(el);
            let parentRow = el.closest('tr');
            if(parentRow && parentRow.dataset.uuid && memberProgressChoice){
                memberProgressChoice.setChoiceByValue(parentRow.dataset.uuid);
            }
            if(el.dataset.progress && pointProgressChoice){
                pointProgressChoice.setChoiceByValue(el.dataset.progress);
            }
            if(el.dataset.value && pointMask){
                pointMask.value = (el.dataset.value).toString();
                document.getElementById('input-point').focus();
            }
        }

        const fetchOrder = () => {
            console.log("Fetch Order");
            let tableHeader = document.getElementById('table-header');
            if(tableHeader){
                let firstChild = tableHeader.querySelector('[data-key]');
                let key = firstChild.dataset.key;
                let sort = 'asc';
                let el = firstChild;

                if(firstChild && !(localStorage.getItem('tableGuildWarParticipation-{{ $data->uuid }}'))){
                    // Add to Storage
                    localStorage.setItem( 'tableGuildWarParticipation-{{ $data->uuid }}', JSON.stringify({
                        'key': key,
                        'sort': sort
                    }));
                } else if((localStorage.getItem('tableGuildWarParticipation-{{ $data->uuid }}'))){
                    let selectedSort = JSON.parse(localStorage.getItem('tableGuildWarParticipation-{{ $data->uuid }}'));
                    if(tableHeader.querySelector(`th[data-key="${selectedSort.key}"]`)){
                        el = tableHeader.querySelector(`th[data-key="${selectedSort.key}"]`);
                        sort = selectedSort.sort;
                    }
                }
                el.classList.add('sort', `sort_${sort}`);

                // Add Event Listener
                if((tableHeader.querySelectorAll('th[data-key]')).length > 0){
                    tableHeader.querySelectorAll('th[data-key]').forEach((el) => {
                        el.addEventListener('click', (thEl) => {
                            let target = thEl.target;
                            console.log(target);
                            // Check if current target has sort class
                            if(target.classList.contains('sort')){
                                // Has sort, toggle sort
                                target.classList.toggle('sort_asc');
                                target.classList.toggle('sort_desc');
                            } else {
                                // Didn't have sort
                                if(tableHeader.querySelector('.sort')){
                                    tableHeader.querySelector('.sort').classList.remove('sort', 'sort_asc', 'sort_desc');
                                }

                                // Add sort
                                target.classList.add('sort', 'sort_asc');
                            }

                            let key = target.dataset.key;
                            let sort = 'asc';
                            if(target.classList.contains('sort_desc')){
                                sort = 'desc';
                            }
                            // Update storage
                            localStorage.setItem( 'tableGuildWarParticipation-{{ $data->uuid }}', JSON.stringify({
                                'key': key,
                                'sort': sort
                            }));

                            setTimeout(() => {
                                fetchData(1);
                            }, 0);
                        });
                    });
                }
            }

            setTimeout(() => {
                fetchData(1);
            }, 0);
        }
        const fetchData = (page = 1) => {
            console.log("Fetch Data");
            if(document.getElementById('guild_war-container')){
                let button = null;
                let container = document.getElementById('guild_war-container');
                if(page === parseInt(1)){
                    container.innerHTML = 'Loading...';
                }
                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let sortKey = null;
                let sortOrder = null;
                let tableHeader = document.getElementById('table-header');
                if(tableHeader){
                    if(tableHeader.querySelector('.sort')){
                        sortKey = tableHeader.querySelector('.sort').dataset.key;
                        sortOrder = 'asc';
                        if(tableHeader.querySelector('.sort').classList.contains('sort_desc')){
                            sortOrder = 'desc';
                        }
                    }
                }

                let url = new URL(`{{ route('s.json.game-mode.guild-war.participant.list') }}`);
                url.searchParams.append('guild_war_id', '{{ $data->uuid }}');
                url.searchParams.append('page', page);
                url.searchParams.append('limit', 30);
                url.searchParams.append('sort', sortOrder);
                url.searchParams.append('sort_key', sortKey);
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
                            let missing = {'sum_progress': 0, 'day_1': 0, 'day_2': 0, 'day_3': 0, 'day_4': 0, 'day_5': 0, 'day_6': 0, };
                            data.forEach((val, index) => {
                                content.setAttribute('data-uuid', val.uuid);

                                let progressList = [];
                                let progressIndex = ['1', '2', '3', '4', '5', '6'];
                                progressIndex.forEach((ci, ciindex) => {
                                    let progress = val[`day_${ci}`];
                                    progressList.push(`
                                        <td onclick="progressSet(this)" data-progress="day_${ci}" data-value="${progress}">
                                            <span>${numberFormat(progress, null)}</span>
                                        </td>
                                    `);

                                    if(progress <= 0){
                                        missing[`day_${ci}`] += 1;
                                    }
                                });
                                content.innerHTML = `
                                    <td>${index+1}</td>
                                    <td>
                                        <div class=" tw__flex tw__gap-1 tw__flex-col">
                                            <div class="tw__flex tw__items-center tw__gap-1">
                                                <button type="button" class="tw__px-2 tw__py-1 tw__leading-none tw__rounded tw__bg-red-400 hover:tw__bg-red-600" onclick="removeParticipant('${val.uuid}')"><i class="fa-solid fa-xmark"></i></button>
                                                <span class="tw__text-base tw__font-bold tw__flex tw__items-center tw__gap-1">${val.guild_member.player.name}</span>
                                            </div>
                                            ${val.guild_member.player.player_identifier ? `<small class="">(#${val.guild_member.player.player_identifier})</small>` : ''}
                                        </div>    
                                    </td>
                                    <td>${numberFormat(val.sum_progress, null)}</td>
                                    ${progressList.join('')}
                                `;
                                container.appendChild(content);
                                content = document.createElement('tr');

                                if(val.sum_progress <= 0){
                                    missing[`sum_progress`] += 1;
                                }
                            });

                            // console.log(missing);
                            let tableFooter = document.getElementById('table-footer');
                            if(tableFooter){
                                if(tableFooter.querySelector('th[data-key="sum_progress"]')){
                                    tableFooter.querySelector('th[data-key="sum_progress"]').innerHTML = numberFormat(missing['sum_progress'], null);
                                }
                                let progressIndex = ['1', '2', '3', '4', '5', '6'];
                                progressIndex.forEach((ci, ciindex) => {
                                    if(tableFooter.querySelector(`th[data-key="day_${ci}"]`)){
                                        tableFooter.querySelector(`th[data-key="day_${ci}"]`).innerHTML = numberFormat(missing[`day_${ci}`], null);
                                    }
                                });
                            }
                        } else {
                            content.innerHTML = `
                                <td colspan="9" class="tw__text-center">No available data</td>
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
            fetchOrder(1);
        });

        if(document.getElementById('modal-participant')){
            const resetAction = () => {
                let form = document.getElementById('modal-participant');

                // Reset Title
                if(form.querySelector('.card-title')){
                    form.querySelector('.card-title').innerHTML = 'Add new Participant';
                }
                // Reset Action URL
                form.setAttribute('action', '{{ route('s.game-mode.guild-war.participation.store') }}');
                // Set Method
                if(form.querySelector('input[name="_method"]')){
                    form.querySelector('input[name="_method"]').value = 'POST';
                }
                // Reset Selected Guild
                if(guildMemberChoice){
                    guildMemberChoice.setChoiceByValue('');
                }
            }

            document.getElementById('modal-participant').addEventListener('hidden.bs.modal', (e) => {
                if(document.getElementById('input-add_and_create')){
                    document.getElementById('input-add_and_create').checked = false;
                }
                memberProgressChoiceFetch(memberProgressChoice);
                resetAction();
                fetchData(1);
            });

            document.getElementById('modal-participant').addEventListener('submit', (e) => {
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

                        if(!(document.getElementById('input-add_and_create').checked)){
                            const recordModalEl = (document.getElementById('modal-participant'));
                            const myModal = bootstrap.Modal.getInstance(recordModalEl);
                            myModal.hide()
                        } else {
                            resetAction();
                        }
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
        function removeParticipant(uuid){
            // Remove Participant
            Swal.fire({
                title: 'Are you sure removing this participant?',
                text: 'Removing participant will also delete this participant point (if exists)',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Remove',
                showLoaderOnConfirm: true,
                reverseButtons: true,
                preConfirm: (login) => {
                    return axios.post(`{{ route('s.game-mode.guild-war.participation.store') }}/${uuid}`, {'_token': "{{ csrf_token() }}", '_method': 'DELETE'})
                    .then(function (response) {
                        console.log(response);

                        return response.data;
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    Swal.fire({
                        title: `Action Success`,
                        icon: 'success',
                        text: 'Participant removed'
                    }).then((e) => {
                        fetchData();
                        memberProgressChoiceFetch(memberProgressChoice);
                    });
                }
            });
        }

        const resetPointAction = (force = null) => {
            let form = document.getElementById('form-point');

            // Reset Title
            if(form.querySelector('.card-title')){
                form.querySelector('.card-title').innerHTML = 'Point Form';
            }
            // Reset Action URL
            form.setAttribute('action', '{{ route('s.game-mode.guild-war.participation.store') }}');
            // Set Method
            if(form.querySelector('input[name="_method"]')){
                form.querySelector('input[name="_method"]').value = 'POST';
            }
            // Reset Selected Member
            if(memberProgressChoice){
                memberProgressChoice.setChoiceByValue('');
            }
            if(force){
                // Reset add more
                if(form.querySelector('input[name="add_more"]')){
                    form.querySelector('input[name="add_more"]').checked = false;
                }
            }
            // Reset Selected Progress Key
            if(form.querySelector('input[name="add_more"]')){
                if(pointProgressChoice && !(form.querySelector('input[name="add_more"]').checked)){
                    pointProgressChoice.setChoiceByValue('');
                }
            } else {
                if(pointProgressChoice){
                    pointProgressChoice.setChoiceByValue('');
                }
            }
            // Reset Point
            if(pointMask){
                pointMask.value = '';
            }
            memberProgressChoiceFetch(memberProgressChoice);
        }
        if(document.getElementById('form-point')){
            document.getElementById('form-point').addEventListener('submit', (e) => {
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
                let rawData = new FormData(e.target);
                rawData.append('point', pointMask.unmaskedValue);
                let formData = Object.fromEntries(rawData);
                axios.post(link, formData)
                    .then(function (response) {
                        let result = response.data;
                        console.log(result);

                        resetPointAction();
                        fetchData();
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
    </script>
@endsection