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
                    <h5 class="card-title">Form (insert)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group tw__mb-0">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="input-name" placeholder="Association Name">
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
                <div class="card-body" id="association-container"></div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-sm page-control tw__flex tw__items-center tw__gap-1" id="btn-load_more" data-page="1" onclick="fetchData(1)"><i class="fa-solid fa-arrows-rotate"></i> Load More</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        const fetchData = (page = 1) => {
            if(document.getElementById('association-container')){
                let button = null;
                let container = document.getElementById('association-container');
                if(page === parseInt(1)){
                    container.innerHTML = 'Loading...';
                }
                if(document.getElementById('btn-load_more')){
                    button = document.getElementById('btn-load_more');
                    let currentPage = button.dataset.page;
                    button.innerHTML = `<i class="fa-solid fa-spinner" data-animate="spin"></i> Loading`;
                }

                let url = new URL(`{{ route('s.json.association.list') }}`);
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
                                let guild = `0 guild`;
                                let player = `0 player`;
                                content.classList.add('tw__p-4', 'tw__my-4', 'first:tw__mt-0', 'last:tw__mb-0', 'tw__bg-gray-100', 'tw__rounded-lg', 'tw__w-full', 'tw__flex');
                                content.innerHTML = `
                                    <div class="tw__mr-auto">
                                        <span class="tw__font-bold tw__block">${val.name}</span>
                                        <small>Consist of ${guild} and ${player}</small>
                                    </div>

                                    <div class="dropdown tw__leading-none tw__flex tw__items-baseline">
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
                                                <a class="dropdown-item" href="{{ route('s.association.index') }}/${val.uuid}">
                                                    <span class=" tw__flex tw__items-center"><i class="fa-solid fa-eye"></i>Show</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `;
                                container.appendChild(content);
                            });

                            // Update Pagination
                            if(document.getElementById('btn-load_more')){
                                let nextPage = parseInt(page) + 1;
                                if(page === response.last_page){
                                    document.getElementById('btn-load_more').setAttribute('disabled', true);
                                } else {
                                    document.getElementById('record-load_more').removeAttribute('disabled');
                                    document.getElementById('btn-load_more').setAttribute('onclick', `fetchData(${nextPage})`);
                                    document.getElementById('btn-load_more').dataset.page = nextPage;
                                }
                            }
                        } else {
                            content.classList.add('alert', 'alert-primary', 'tw__mb-0');
                            content.setAttribute('role', 'alert');
                            content.innerHTML = `
                                <div class=" tw__flex tw__items-center">
                                    <i class="bi bi-info-circle-fill tw__mr-2"></i>
                                    <div class="tw__block tw__font-bold tw__uppercase">
                                        Attention!
                                    </div>
                                </div>
                                <span class="tw__block tw__italic">No data found</span>
                            `;

                            container.appendChild(recordContent);
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
                form.setAttribute('action', '{{ route('s.association.store') }}');
                // Set Method
                if(form.querySelector('input[name="_token"]')){
                    form.querySelector('input[name="_token"]').value = 'POST';
                }
                // Reset Name
                if(form.querySelector('input[name="name"]')){
                    form.querySelector('input[name="name"]').value = '';
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
                    })
                    .catch(function (error) {
                        let error_data = error.response.data;
                        let errors = error_data.errors;
                        
                        for (const [key, value] of Object.entries(errors)) {
                            let el = document.getElementById(`input-${key}`);
                            el.closest('.form-control').classList.add('is-invalid');
                            el.closest('.form-group').classList.add('has-invalid');
                            el.closest('.form-group').insertAdjacentHTML('beforeend', `<small class="invalid-feedback tw__text-xs">*${value}</small>`);
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