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
            
            <div id="guild_war_participation-container"></div>
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
@endsection

@section('js_inline')
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
            memberProgressChoice.passedElement.element.addEventListener('showDropdown', (e) => {
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
            });
        }
    </script>
    <script>
        const fetchData = (page = 1) => {
            if(document.getElementById('guild_war_participation-container')){
                let button = null;
                let container = document.getElementById('guild_war_participation-container');
                if(page === parseInt(1)){
                    container.innerHTML = 'Loading...';
                }
                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let url = new URL(`{{ route('s.json.game-mode.guild-war.participant.list') }}`);
                url.searchParams.append('guild_war_id', '{{ $data->uuid }}');
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
                        let content = document.createElement('div');

                        if(data.length > 0){
                            data.forEach((val, index) => {
                                console.log(val.day_2);

                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex', 'tw__flex-col');
                                content.innerHTML = `
                                    <div class=" tw__flex tw__items-center tw__gap-1">
                                        <span class="tw__text-base tw__font-bold tw__flex tw__items-center tw__gap-1">${val.guild_member.player.name}</span>
                                        ${val.guild_member.player.player_identifier ? `<span class="">(#${val.guild_member.player.player_identifier})</span>` : ''}
                                    </div>

                                    <div class=" tw__mt-3 tw__grid tw__grid-cols-2 md:tw__grid-cols-3 lg:tw__grid-cols-6 tw__gap-1 md:tw__gap-2 lg:tw__gap-4">
                                        <div class=" tw__flex tw__flex-col">
                                            <span class="tw__font-bold">Day 1</span>
                                            <span>${val.day_1 ? numberFormat(val.day_1, '') : '-'}</span>
                                        </div>
                                        <div class=" tw__flex tw__flex-col">
                                            <span class="tw__font-bold">Day 2</span>
                                            <span>${val.day_2 ? numberFormat(val.day_2, '') : '-'}</span>
                                        </div>
                                        <div class=" tw__flex tw__flex-col">
                                            <span class="tw__font-bold">Day 3</span>
                                            <span>${val.day_3 ? numberFormat(val.day_3, '') : '-'}</span>
                                        </div>
                                        <div class=" tw__flex tw__flex-col">
                                            <span class="tw__font-bold">Day 4</span>
                                            <span>${val.day_4 ? numberFormat(val.day_4, '') : '-'}</span>
                                        </div>
                                        <div class=" tw__flex tw__flex-col">
                                            <span class="tw__font-bold">Day 5</span>
                                            <span>${val.day_5 ? numberFormat(val.day_5, '') : '-'}</span>
                                        </div>
                                        <div class=" tw__flex tw__flex-col">
                                            <span class="tw__font-bold">Day 6</span>
                                            <span>${val.day_6 ? numberFormat(val.day_6, '') : '-'}</span>
                                        </div>
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
        document.addEventListener('DOMContentLoaded', () => {
            fetchData(1);
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