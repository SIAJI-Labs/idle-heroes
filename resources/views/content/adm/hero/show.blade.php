@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Hero: Detail',
    'wsidebar_menu' => 'hero',
    'wsidebar_submenu' => 'list',
    'wheader' => [
        'header_title' => 'Hero: Detail',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Hero',
                'icon' => null,
                'is_active' => false,
                'url' => route('adm.hero.index')
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

@section('css_plugins')
    {{-- Sweetalert2 --}}
    @include('layouts.plugins.sweetalert2.css')
    {{-- Choices --}}
    @include('layouts.plugins.choices.css')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Hero: Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered tw__mb-0">
                <tbody>
                    <tr>
                        <th>Faction</th>
                        <td>
                            <span class=" tw__flex tw__items-center tw__gap-2">{!! $data->heroFaction()->exists() ? ($data->heroFaction->icon ? '<img src="'.asset($data->heroFaction->icon).'" alt="'.$data->heroFaction->name.'" class="tw__h-5">' : '').$data->heroFaction->name : '-' !!}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Class</th>
                        <td>
                            <span class=" tw__flex tw__items-center tw__gap-2">{!! $data->heroClass()->exists() ? ($data->heroClass->icon ? '<img src="'.asset($data->heroClass->icon).'" alt="'.$data->heroClass->name.'" class="tw__h-5">' : '').$data->heroClass->name : '-' !!}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $data->name }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="card tw__mt-4 tw__mb-0">
                <div class="card-header">
                    <div class=" tw__flex tw__items-center">
                        <h5 class="card-title">Homeowner</h5>

                        <div class=" tw__ml-auto tw__flex tw__items-center tw__gap-1">
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm tw__flex tw__items-center tw__gap-1" data-bs-toggle="modal" data-bs-target="#modal-tenant"><i class="fa-solid fa-plus"></i>Add Tenant</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary" role="alert">
                        <div class=" tw__flex tw__items-center tw__gap-1">
                            <i class="fa-solid fa-circle-info"></i>
                            <div class="tw__block tw__font-bold tw__uppercase">
                                Information!
                            </div>
                        </div>
                        <span class="tw__block">Hero below are house tenant for <strong>{{ $data->name }}</strong>, when <strong>{{ $data->name }}</strong> is homeowner.</span>
                    </div>
                    
                    <div class="row">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card lg:tw__mb-0">
                                    <div class="card-header">
                                        <h5 class="card-title">Slot {{ $i }}</h5>
                                    </div>
                                    <div class="card-body tw__max-h-[250px] tw__overflow-y-scroll" id="slot_{{ $i }}-container">
                                        <span>No available data</span>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1 btn-load_more" data-slot="slot_{{ $i }}" data-page="1" onclick="fetchData(1, 'slot_{{ $i }}')"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="card tw__mt-4 tw__mb-0">
                <div class="card-header">
                    <div class=" tw__flex tw__items-center">
                        <h5 class="card-title">Tenant</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary" role="alert">
                        <div class=" tw__flex tw__items-center tw__gap-1">
                            <i class="fa-solid fa-circle-info"></i>
                            <div class="tw__block tw__font-bold tw__uppercase">
                                Information!
                            </div>
                        </div>
                        <span class="tw__block">Hero below are homeowner where <strong>{{ $data->name }}</strong> is tenant.</span>
                    </div>

                    <div class="row">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card lg:tw__mb-0">
                                    <div class="card-header">
                                        <h5 class="card-title">Slot {{ $i }}</h5>
                                    </div>
                                    <div class="card-body tw__max-h-[250px] tw__overflow-y-scroll" id="slot_ho_{{ $i }}-container">
                                        <span>No available data</span>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1 btn-load_more" data-slot="slot_ho_{{ $i }}" data-page="1" onclick="fetchHomeownerData(1, 'slot_ho_{{ $i }}')"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_modal')
    <form action="{{ route('adm.hero.update', $data->uuid) }}" method="POST" class="modal fade" id="modal-tenant" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal-tenant" aria-hidden="true" style="display: none;">
        @csrf
        @method('PUT')

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">
                        Add Hero to Tenant
                    </h2>
                    <button type="button" class="btn-close tw__text-black" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="input-slot">Slot</label>
                        <select class="form-control" id="input-slot" name="slot" placeholder="Search for Tenant Slot">
                            <option value="">Search for Tenant Slot</option>
                            @for ($i = 1; $i <= 4; $i++)
                                <option value="slot_{{ $i }}">Slot {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="input-hero_id">Hero</label>
                        <select class="form-control" id="input-hero_id" name="hero_id" placeholder="Search for Hero as Tenant">
                            <option value="">Search for Hero as Tenant</option>
                        </select>
                    </div>

                    <div class="form-group tw__mb-0">
                        <div class="form-check tw__text-sm" id="form-add_more">
                            <input class="form-check-input" type="checkbox" name="create" value="" id="input-add_and_create">
                            <label class="form-check-label" for="input-add_and_create">Add more?</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Submit</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js_plugins')
    {{-- Sweetalert2 --}}
    @include('layouts.plugins.sweetalert2.js')
    {{-- Choices --}}
    @include('layouts.plugins.choices.js')
@endsection

@section('js_inline')
    {{-- Choices --}}
    <script>
        // Tenant Slot
        var slotChoice = null;
        if(document.getElementById('input-slot')){
            const slotEl = document.getElementById('input-slot');
            slotChoice = new Choices(slotEl, {
                allowHTML: true,
                searchEnabled: false,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Tenant Slot",
                placeholder: true,
                placeholderValue: 'Search for Tenant Slot',
                shouldSort: false
            });
        }

        // Hero
        var heroChoice = null;
        if(document.getElementById('input-hero_id')){
            const heroEl = document.getElementById('input-hero_id');
            heroChoice = new Choices(heroEl, {
                allowHTML: true,
                removeItemButton: true,
                searchPlaceholderValue: "Search for Hero as Tenant",
                placeholder: true,
                placeholderValue: 'Search for Hero as Tenant',
                shouldSort: false
            });
            heroEl.addEventListener('showDropdown', (e) => {
                let placeholder = [
                    heroChoice.setChoiceByValue('').getValue()
                ];
                heroChoice.clearChoices();
                heroChoice.setChoices(placeholder);
                heroChoice.setChoices(() => {
                    // console.log(e);
                    let url = new URL(`{{ route('adm.json.hero.list') }}`);
                    url.searchParams.append('force_order_column', 'name');
                    url.searchParams.append('force_order', 'asc');
                    url.searchParams.append('action', 'tenant');
                    url.searchParams.append('hero_id', '{{ $data->uuid }}');
                    return fetch(url)
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
            });
        }
    </script>

    <script>
        const fetchData = (page = 1, slot = []) => {
            if(Array.isArray(slot)){
                slot.forEach((val) => {
                    fetchData(page, val);
                });
            } else {
                // Fetch tenant
                let container = document.getElementById(`${slot}-container`);
                if(container){
                    // console.log(`Fetch data for: ${slot}`);

                    let button = null;
                    if(document.querySelector(`.btn-load_more[data-slot="${slot}"]`)){
                        button = document.querySelector(`.btn-load_more[data-slot="${slot}"]`);
                        let currentPage = button.dataset.page;
                        button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                    }

                    if(page === parseInt(1)){
                        container.innerHTML = 'Loading...';
                    }
                    let url = new URL(`{{ route('adm.json.hero.list') }}`);
                    url.searchParams.append('page', page);
                    url.searchParams.append('limit', 5);
                    url.searchParams.append('force_order_column', 'name');
                    url.searchParams.append('force_order', 'asc');
                    url.searchParams.append('action', 'tenant');
                    url.searchParams.append('hero_id', '{{ $data->uuid }}');
                    url.searchParams.append('slot', slot);

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
                                            <div class=" tw__flex tw__items-center tw__gap-1">
                                                ${val.hero_faction.icon ? `<img src="{{ asset('') }}/${val.hero_faction.icon}" alt="${val.hero_faction.name}" class="tw__h-3">` : ''}
                                                <span class="tw__leading-none">${val.hero_faction.name}</span>
                                            </div>
                                        `);
                                    }
                                    if(val.hero_class){
                                        smallInformation.push(`
                                            <div class=" tw__flex tw__items-center tw__gap-1">
                                                ${val.hero_class.icon ? `<img src="{{ asset('') }}/${val.hero_class.icon}" alt="${val.hero_class.name}" class="tw__h-3.5">` : ''}
                                                <span class="tw__leading-none">${val.hero_class.name}</span>
                                            </div>
                                        `);
                                    }

                                    content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex');
                                    content.innerHTML = `
                                        <div class=" tw__flex tw__gap-2 tw__flex-col">
                                            <div class="tw__flex tw__items-center tw__gap-1">
                                                <span class="tw__text-base tw__font-bold tw__flex tw__items-center tw__gap-1">${val.tenant && val.tenant.length > 0 ? '<i class="fa-solid fa-crown" alt="Homeowner"></i>' : ''}${val.name}</span>
                                            </div>
                                            <small class="tw__flex tw__items-center tw__gap-1 tw__flex-wrap">${smallInformation.join('')}</small>
                                        </div>

                                        <div class="tw__ml-auto dropdown dropstart tw__leading-none tw__flex tw__items-baseline">
                                            <button class="dropdown-toggle arrow-none" type="button" data-bs-auto-close="outside" id="dropdown-${index}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdown-${index}">
                                                <li>
                                                    <a class="dropdown-item tw__text-red-400" href="javascript:void(0)" onclick="removeData('${val.uuid}', '${slot}')">
                                                        <span class=" tw__flex tw__items-center"><i class="fa-solid fa-xmark"></i>Remove</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('adm.hero.index') }}/${val.uuid}">
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
                                content.innerHTML = `
                                    <span class="tw__block tw__italic">No available data</span>
                                `;

                                container.appendChild(content);
                            }

                            // Update Pagination
                            if(document.querySelector(`.btn-load_more[data-slot="${slot}"]`)){
                                let nextPage = parseInt(page) + 1;
                                if(page === response.last_page){
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).setAttribute('disabled', true);
                                } else {
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).removeAttribute('disabled');
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).setAttribute('onclick', `fetchData(${nextPage}, '${slot}')`);
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).dataset.page = nextPage;
                                }
                            }
                        });
                }
            }
        }
        const fetchHomeownerData = (page = 1, slot = []) => {
            if(Array.isArray(slot)){
                slot.forEach((val) => {
                    fetchHomeownerData(page, val);
                });
            } else {
                // Fetch tenant
                let container = document.getElementById(`${slot}-container`);
                if(container){
                    // console.log(`Fetch data for: ${slot}`);

                    let button = null;
                    if(document.querySelector(`.btn-load_more[data-slot="${slot}"]`)){
                        button = document.querySelector(`.btn-load_more[data-slot="${slot}"]`);
                        let currentPage = button.dataset.page;
                        button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                    }

                    if(page === parseInt(1)){
                        container.innerHTML = 'Loading...';
                    }
                    let url = new URL(`{{ route('adm.json.hero.list') }}`);
                    url.searchParams.append('page', page);
                    url.searchParams.append('limit', 5);
                    url.searchParams.append('force_order_column', 'name');
                    url.searchParams.append('force_order', 'asc');
                    url.searchParams.append('action', 'homeowner');
                    url.searchParams.append('hero_id', '{{ $data->uuid }}');
                    url.searchParams.append('slot', slot.replace('slot_ho_', 'slot_'));

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
                                            <div class=" tw__flex tw__items-center tw__gap-1">
                                                ${val.hero_faction.icon ? `<img src="{{ asset('') }}/${val.hero_faction.icon}" alt="${val.hero_faction.name}" class="tw__h-3">` : ''}
                                                <span class="tw__leading-none">${val.hero_faction.name}</span>
                                            </div>
                                        `);
                                    }
                                    if(val.hero_class){
                                        smallInformation.push(`
                                            <div class=" tw__flex tw__items-center tw__gap-1">
                                                ${val.hero_class.icon ? `<img src="{{ asset('') }}/${val.hero_class.icon}" alt="${val.hero_class.name}" class="tw__h-3.5">` : ''}
                                                <span class="tw__leading-none">${val.hero_class.name}</span>
                                            </div>
                                        `);
                                    }

                                    content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex');
                                    content.innerHTML = `
                                        <div class=" tw__flex tw__gap-2 tw__flex-col">
                                            <div class="tw__flex tw__items-center tw__gap-1">
                                                <span class="tw__text-base tw__font-bold tw__flex tw__items-center tw__gap-1">${val.tenant && val.tenant.length > 0 ? '<i class="fa-solid fa-crown" alt="Homeowner"></i>' : ''}${val.name}</span>
                                            </div>
                                            <small class="tw__flex tw__items-center tw__gap-1 tw__flex-wrap">${smallInformation.join('')}</small>
                                        </div>

                                        <div class="tw__ml-auto dropdown dropstart tw__leading-none tw__flex tw__items-baseline">
                                            <button class="dropdown-toggle arrow-none" type="button" data-bs-auto-close="outside" id="dropdown-${index}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdown-${index}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('adm.hero.index') }}/${val.uuid}">
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
                                content.innerHTML = `
                                    <span class="tw__block tw__italic">No available data</span>
                                `;

                                container.appendChild(content);
                            }

                            // Update Pagination
                            if(document.querySelector(`.btn-load_more[data-slot="${slot}"]`)){
                                let nextPage = parseInt(page) + 1;
                                if(page === response.last_page){
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).setAttribute('disabled', true);
                                } else {
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).removeAttribute('disabled');
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).setAttribute('onclick', `fetchHomeownerData(${nextPage}, '${slot}')`);
                                    document.querySelector(`.btn-load_more[data-slot="${slot}"]`).dataset.page = nextPage;
                                }
                            }
                        });
                }
            }
        }
        const removeData = (uuid, slot) => {
            console.log(`Remove Data for Tenant ${uuid}`);
            Swal.fire({
                title: 'Warning',
                icon: 'warning',
                text: `Remove this hero from tenant list!`,
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Remove',
                showLoaderOnConfirm: true,
                backdrop: true,
                preConfirm: (login) => {
                    return axios.post(`{{ route('adm.hero.index') }}/${uuid}`, {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                        'action': 'tenant',
                        'hero_id': '{{ $data->uuid }}',
                        'slot': slot
                    })
                    .then(function (response) {
                        return response;
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                console.log(result);

                fetchData(1, slot);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchData(1, ['slot_1', 'slot_2', 'slot_3', 'slot_4']);
            fetchHomeownerData(1, ['slot_ho_1', 'slot_ho_2', 'slot_ho_3', 'slot_ho_4']);
        });

        if(document.getElementById('modal-tenant')){
            let form = document.getElementById('modal-tenant');

            form.addEventListener('submit', (e) => {
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
                let formData = rawData;
                axios.post(link, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data;'
                        }
                    })
                    .then(function (response) {
                        let result = response.data;

                        if(!(document.getElementById('input-add_and_create').checked)){
                            const modalEl = (document.getElementById('modal-tenant'));
                            const myModal = bootstrap.Modal.getInstance(modalEl);
                            myModal.hide()
                        } else {
                            // Reset Faction
                            if(slotChoice){
                                slotChoice.setChoiceByValue('');
                            }
                            // Reset Class
                            if(heroChoice){
                                heroChoice.setChoiceByValue('');
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
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
            form.addEventListener('hidden.bs.modal', (e) => {
                let slot = slotChoice.getValue().value;
                if(slot){
                    fetchData(1, slot);
                }

                // Reset Faction
                if(slotChoice){
                    slotChoice.setChoiceByValue('');
                }
                // Reset Class
                if(heroChoice){
                    heroChoice.setChoiceByValue('');
                }
            });
        }
    </script>
@endsection