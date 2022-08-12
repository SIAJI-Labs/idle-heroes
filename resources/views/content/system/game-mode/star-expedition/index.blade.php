@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Star Expedition',
    'wsidebar_menu' => 'star-expedition',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Game Mode: Star Expedition',
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
                'title' => 'Star Expedition',
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
            <form class="card tw__sticky tw__top-40" id="form" method="POST" action="{{ route('s.game-mode.star-expedition.store') }}">
                @csrf
                @method('POST')

                <div class="card-header">
                    <h5 class="card-title">Form (insert)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Guild</label>
                        <select class="form-control" id="input-guild_id" name="guild_id" placeholder="Search for Guild">
                            <option value="">Search for Guild</option>
                        </select>
                    </div>
                    <div class="form-group tw__mb-0">
                        <label>Period</label>
                        <select class="form-control" id="input-period_id" name="period_id" placeholder="Search for Period">
                            <option value="">Search for Period</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer tw__text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-sm text-gray-600 ms-auto" onclick="resetAction()">Reset</button>
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
                <div class="card-body" id="star_expedition-container"></div>
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
        // Guild Choices
        var guildChoice = null;
        if(document.getElementById('input-guild_id')){
            const guildEl = document.getElementById('input-guild_id');
            guildChoice = new Choices(guildEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Guild",
                placeholder: true,
                placeholderValue: 'Search for Guild',
                shouldSort: false
            });
            guildChoice.setChoices(() => {
                // console.log(e);
                return fetch(
                    `{{ route('s.json.guild.list') }}`
                )
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    return data.data.map(function(result) {
                        return {
                            value: result.uuid,
                            label: `${result.association.name} - ${result.name}`
                        };
                    });
                });
            });
        }

        // Period List
        var periodChoice = null;
        if(document.getElementById('input-period_id')){
            const periodEl = document.getElementById('input-period_id');
            periodChoice = new Choices(periodEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Period",
                placeholder: true,
                placeholderValue: 'Search for Period',
                shouldSort: false
            });
            periodChoice.setChoices(() => {
                // console.log(e);
                return fetch(
                    `{{ route('s.json.game-mode.period.list') }}`
                )
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    return data.data.map(function(result) {
                        return {
                            value: result.uuid,
                            label: `${momentDateTime(result.datetime, 'DD MMM, YYYY', true)}`
                        };
                    });
                });
            });
        }
    </script>
    <script>
        var timer = [];
        const addDays = (date, days) => {
            let res = new Date(date);
            res.setDate(res.getDate() + days);
            return res;
        }
        const calculateTimeRemaning = () => {
            let el = document.querySelectorAll('span[data-finish]');
            if(el.length > 0){
                el.forEach((el, index) => {
                    let date = new Date(el.dataset.finish);
                    let length = el.dataset.length;

                    // Check if finish date is greater than today
                    if(moment(date) > moment()){
                        timer[index] = setInterval(() => {
                            countDowntimer(el);
                        }, 1000);
                    } else {
                        el.parentNode.innerHTML = `Event completed!`;
                    }
                });
            }
        }
        const countDowntimer = (el, index) => {
            let text = '-';
            let now = new Date();
            let start = new Date(el.dataset.start);
            let finish = new Date(el.dataset.finish);
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
                text = `${preparationText} / Season Ends in ${endText}`;
            }

            el.innerHTML = text;
        }

        const editData = (uuid) => {
            axios.get(`{{ route('s.game-mode.star-expedition.index') }}/${uuid}`)
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
                    form.setAttribute('action', `{{ route('s.game-mode.star-expedition.index') }}/${data.uuid}`);
                    // Set Method
                    if(form.querySelector('input[name="_method"]')){
                        form.querySelector('input[name="_method"]').value = 'PUT';
                    }
                    // Reset Guild
                    if(guildChoice){
                        guildChoice.setChoiceByValue(data.guild.uuid);
                    }
                    // Reset Guild
                    if(periodChoice){
                        periodChoice.setChoiceByValue(data.period.uuid);
                    }
                });
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
                form.setAttribute('action', '{{ route('s.game-mode.star-expedition.store') }}');
                // Set Method
                if(form.querySelector('input[name="_method"]')){
                    form.querySelector('input[name="_method"]').value = 'POST';
                }
                // Reset Selected Guild
                if(guildChoice){
                    guildChoice.setChoiceByValue('');
                }
                // Reset Selected Period
                if(periodChoice){
                    periodChoice.setChoiceByValue('');
                }
            }
        }
        if(document.getElementById('form')){
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

                        resetAction();
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

        const fetchData = (page = 1) => {
            if(document.getElementById('star_expedition-container')){
                let button = null;
                let container = document.getElementById('star_expedition-container');
                if(page === parseInt(1)){
                    container.innerHTML = 'Loading...';
                }
                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let url = new URL(`{{ route('s.json.game-mode.star-expedition.list') }}`);
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

                        console.log(data);
                        if(data.length > 0){
                            data.forEach((val, index) => {
                                let extra = '';
                                if(moment(val.period.datetime) <= moment() && moment(val.period.datetime).add(val.period.length, 'days') >= moment()){
                                    extra = `<i class="fa-solid fa-khanda"></i>`;
                                }

                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex', 'tw__flex-col');
                                content.innerHTML = `
                                    <div class=" tw__mb-2">
                                        <div class="tw__flex tw__items-center">
                                            <span class="tw__text-base tw__flex tw__items-center tw__gap-1">${val.guild.association.name} - ${val.guild.name}</span>
                                        
                                            <div class="dropdown dropstart tw__leading-none tw__flex tw__ml-auto">
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
                                                        <a class="dropdown-item" href="{{ route('s.game-mode.star-expedition.index') }}/${val.uuid}">
                                                            <span class=" tw__flex tw__items-center"><i class="fa-solid fa-eye"></i>Show</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <span class="tw__text-base tw__font-bold tw__flex tw__items-center tw__gap-1">${extra}${momentDateTime(val.period.datetime, 'DD MMM, YYYY / HH:mm', true)}</span>
                                        <small class="text-muted tw__italic">Length: ${val.period.length} day${val.period.length > 1 ? 's' : ''}, ends in ${momentDateTime(moment(val.period.datetime).add(val.period.length, 'days'), 'DD MMM, YYYY / HH:mm', true)}</small>
                                    </div>
                                    
                                    <small class="text-muted tw__italic tw__text-gray-300">Preparations end in <span data-start="${momentDateTime(moment(val.period.datetime), null, null, false)}" data-finish="${momentDateTime(moment(val.period.datetime).add(val.period.length, 'days'), null, null, false)}" data-length="${val.length}">-</span></small>
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

                        // Calculate time remaning
                        calculateTimeRemaning();
                    });
            }
        }
        document.addEventListener('DOMContentLoaded', (e) => {
            fetchData();
        });
    </script>
@endsection