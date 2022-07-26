@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Hero',
    'wsidebar_menu' => 'hero',
    'wsidebar_submenu' => 'list',
    'wheader' => [
        'header_title' => 'Hero',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Hero',
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
            <form class="card" id="form" method="POST" action="{{ route('adm.hero.store') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="card-header">
                    <h5 class="card-title">Form (insert)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label for="input-faction_id">Faction</label>
                        <select class="form-control" id="input-faction_id" name="faction_id" placeholder="Search for Faction">
                            <option value="">Search for Faction</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="input-class_id">Class</label>
                        <select class="form-control" id="input-class_id" name="class_id" placeholder="Search for Class">
                            <option value="">Search for Class</option>
                        </select>
                    </div>
                    <div class="form-group last:tw__mb-0">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="input-name" placeholder="Hero Name">
                    </div>
                    <div class="form-group last:tw__mb-0">
                        <label>Avatar</label>
                        <input type="file" class="form-control" id="input-avatar" name="avatar" accept=".jpg,.jpeg,.png,.svg,.gif,.webp">
                        <small class="text-muted tw__italic">*Max 200kb; Only accept .jpg, .jpeg, .png, .svg, .gif, .webp</small>
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
                <div class="card-body" id="hero-container"></div>
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
    {{-- Choices --}}
    <script>
        // Faction Choices
        var factionChoice = null;
        if(document.getElementById('input-faction_id')){
            const factionEl = document.getElementById('input-faction_id');
            factionChoice = new Choices(factionEl, {
                allowHTML: true,
                searchEnabled: false,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Faction",
                placeholder: true,
                placeholderValue: 'Search for Faction',
                shouldSort: false
            });
            factionChoice.setChoices(() => {
                // console.log(e);
                return fetch(
                    `{{ route('adm.json.hero.faction.list') }}`
                )
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    return data.data.map(function(result) {
                        return {
                            value: result.uuid,
                            label: `<span class=" tw__flex tw__items-center tw__gap-1">${result.icon ? `<img src="{{ asset('') }}/${result.icon}" class="tw__h-4">` : ''}${result.name}</span>`
                        };
                    });
                });
            });
        }
        // Class Choices
        var classChoice = null;
        if(document.getElementById('input-class_id')){
            const classEl = document.getElementById('input-class_id');
            classChoice = new Choices(classEl, {
                allowHTML: true,
                searchEnabled: false,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Class",
                placeholder: true,
                placeholderValue: 'Search for Class',
                shouldSort: false
            });
            classChoice.setChoices(() => {
                // console.log(e);
                return fetch(
                    `{{ route('adm.json.hero.class.list') }}`
                )
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    return data.data.map(function(result) {
                        return {
                            value: result.uuid,
                            label: `<span class=" tw__flex tw__items-center tw__gap-1">${result.icon ? `<img src="{{ asset('') }}/${result.icon}" class="tw__h-4">` : ''}${result.name}</span>`
                        };
                    });
                });
            });
        }
    </script>

    <script>
        const fetchData = (page = 1) => {
            if(document.getElementById('hero-container')){
                let button = null;
                let container = document.getElementById('hero-container');
                if(page === parseInt(1)){
                    container.innerHTML = 'Loading...';
                }
                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let url = new URL(`{{ route('adm.json.hero.list') }}`);
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
                                let smallInformation = [];
                                if(val.hero_faction){
                                    smallInformation.push(`
                                        <div class=" tw__flex tw__items-center tw__gap-2">
                                            ${val.hero_faction.icon ? `<img src="{{ asset('') }}/${val.hero_faction.icon}" alt="${val.hero_faction.name}" class="tw__h-3">` : ''}
                                            <span>${val.hero_faction.name}</span>
                                        </div>
                                    `);
                                }
                                if(val.hero_class){
                                    smallInformation.push(`
                                        <div class=" tw__flex tw__items-center tw__gap-2">
                                            ${val.hero_class.icon ? `<img src="{{ asset('') }}/${val.hero_class.icon}" alt="${val.hero_class.name}" class="tw__h-3">` : ''}
                                            <span>${val.hero_class.name}</span>
                                        </div>
                                    `);
                                }

                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex');
                                content.innerHTML = `
                                    <div class=" tw__flex tw__gap-2 tw__flex-col">
                                        ${val.avatar ? `<img src="{{ asset('') }}/${val.avatar}" alt="${val.name}" class="tw__h-5">` : ''}
                                        <span class="tw__text-base tw__font-bold">${val.name}</span>
                                        <small class="tw__flex tw__items-center tw__gap-1">${smallInformation.join('<span>/</span>')}</small>
                                    </div>

                                    <div class="tw__ml-auto dropdown dropstart tw__leading-none tw__flex tw__items-baseline">
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
                                                <a class="dropdown-item" href="{{ route('adm.hero.faction.index') }}/${val.uuid}">
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

                if(form.querySelectorAll('.old-file')){
                    form.querySelectorAll('.old-file').forEach((el) => {
                        el.remove();
                    });
                }

                // Reset Title
                if(form.querySelector('.card-title')){
                    form.querySelector('.card-title').innerHTML = 'Form (Insert)';
                }
                // Reset Action URL
                form.setAttribute('action', '{{ route('adm.hero.store') }}');
                // Set Method
                if(form.querySelector('input[name="_method"]')){
                    form.querySelector('input[name="_method"]').value = 'POST';
                }
                // Reset Faction
                if(factionChoice){
                    factionChoice.setChoiceByValue('');
                }
                // Reset Class
                if(classChoice){
                    classChoice.setChoiceByValue('');
                }
                // Reset Avatar
                if(form.querySelector('input[name="avatar"]')){
                    form.querySelector('input[name="avatar"]').value = '';
                }
                // Reset Name
                if(form.querySelector('input[name="name"]')){
                    form.querySelector('input[name="name"]').value = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', (e) => {
            fetchData();
        });

        if(document.getElementById('form')){
            function editData(uuid) {
                axios.get(`{{ route('adm.hero.index') }}/${uuid}`)
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
                        form.setAttribute('action', `{{ route('adm.hero.index') }}/${data.uuid}`);
                        // Set Method
                        if(form.querySelector('input[name="_method"]')){
                            form.querySelector('input[name="_method"]').value = 'PUT';
                        }
                        // Reset Faction
                        if(factionChoice && data.hero_faction){
                            factionChoice.setChoiceByValue(data.hero_faction.uuid);
                        }
                        // Reset Class
                        if(classChoice && data.hero_class){
                            classChoice.setChoiceByValue(data.hero_class.uuid);
                        }
                        // Reset Avatar
                        if(form.querySelector('input[name="avatar"]')){
                            form.querySelector('input[name="avatar"]').value = '';
                            form.querySelector('input[name="avatar"]').closest('.form-group').insertAdjacentHTML('beforeend', `<small class="tw__mt-1 old-file text-muted tw__italic tw__block">**Leave it empty to keep old file!<a data-fslightbox href="{{ asset('') }}/${data.avatar}" class="tw__ml-1 tw__text-blue-400 hover:tw__text-blue-700 hover:tw__underline">(Preview old file)</a></small>`);
                        }
                        // Reset Name
                        if(form.querySelector('input[name="name"]')){
                            form.querySelector('input[name="name"]').value = data.name;
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
                let rawData = new FormData(e.target);
                let file = document.getElementById('input-avatar').files;
                if(file.length > 0){
                    rawData.append('avatar', file[0]);
                }
                let formData = rawData;
                axios.post(link, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data;'
                        }
                    })
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
    </script>
@endsection