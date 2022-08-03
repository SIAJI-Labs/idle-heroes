document.addEventListener('DOMContentLoaded', (e) => {
    generateTooltip();
    // let balanceHide_state = localStorage.getItem( 'SPTRA_hidebalance');
    // if(balanceHide_state !== null && balanceHide_state === 'true'){
    //     let hideBalanceEl = document.getElementById('control-hide_balance');
    //     if(hideBalanceEl){
    //         hideBalanceEl.checked = true;
    //     }
    // }
    
    setTimeout(() => {
        let datatableWrapper = document.querySelectorAll('.dataTables_wrapper');
        console.log(datatableWrapper);
        datatableWrapper.forEach((el) => {
            let pageLength = el.querySelector('.dataTables_length');
            let search = el.querySelector('.dataTables_filter');
            let info = el.querySelector('.dataTables_info');
            let paginate = el.querySelector('.dataTables_paginate');

            // Check if Search or pageLength feature is enabled
            if(pageLength || search){
                el.insertAdjacentHTML('afterbegin', `
                    <div class="sa-datatable-intro"></div>
                `);
                let saIntro = el.querySelector('.sa-datatable-intro');
                if(pageLength){
                    saIntro.appendChild(pageLength);
                }

                if(search){
                    let searchInput = search.querySelector('label input');
                    searchInput.setAttribute('placeholder', 'Search Keyword');
                    search.innerHTML = '<label></label>';
                    search.querySelector('label').appendChild(searchInput);

                    saIntro.appendChild(search);
                }
            }

            // Check if Info and Paginate feature is enabled
            if(info || paginate){
                el.insertAdjacentHTML('beforeend', `
                    <div class="sa-datatable-info"></div>
                `);
                let saInfo = el.querySelector('.sa-datatable-info');

                if(info){
                    saInfo.appendChild(info);
                }
                if(paginate){
                    // Check if Datatable is Paginate by Input
                    if(paginate.classList.contains('paging_input')){
                        (paginate.childNodes).forEach((e) => {
                            if(e.classList.contains('first') || e.classList.contains('last')){
                                e.remove();
                            }
                            setTimeout(() => {
                                if(e.innerHTML === '' && (e.tagName).toString().toLowerCase() !== 'input' && !(e.classList.contains('paginate_total'))){
                                    e.remove();
                                }
                            }, 50);
                        });
                    }

                    saInfo.appendChild(paginate);
                }
            }
        });
    }, 200);
});

const generateTooltip = () => {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

let hideBalanceEl = document.getElementById('control-hide_balance');
if(hideBalanceEl){
    hideBalanceEl.addEventListener('change', (e) => {
        let state = e.target.checked;
        console.log("Hide Balance is running");
        localStorage.setItem( 'SPTRA_hidebalance', state);

        let metaToken = document.querySelector('meta[name="csrf-token"]');
        let csrfToken = metaToken.getAttribute('content');
        axios.post(`${user_preference}`, {
            '_method': 'PUT',
            '_token': csrfToken,
            'key': 'hide-balance',
            'value': state
        }).then((response) => {
            console.log(response);

            Swal.fire({
                icon: 'success',
                title: 'Action: Success',
                text: 'Successfully update Hide Balance State. Please refresh page!',
            }).then((e) => {
                location.reload();
            });
        });
    });
}