<!-- Axios -->
<script src="{{ asset('assets/plugins/axios/axios.js') }}"></script>
<script>
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    window.axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
</script>