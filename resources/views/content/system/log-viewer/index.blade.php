@extends('layouts.app', [
    'wdashboard' => true,
    'wsecond_title' => 'Log Viewer',
    'wsidebar_menu' => 'log-viewer',
    'wsidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Dashboard',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'icon' => null,
                'is_active' => false,
                'url' => route('s.index')
            ], [
                'title' => 'Log Viewer',
                'icon' => null,
                'is_active' => true,
                'url' => null
            ], 
        ]
    ]
])

@section('content')
    <div id="browser" class=" tw__shadow">
        <div class=" tw__bg-[#343a40] tw__p-2 tw__rounded-t-xl" id="header">
            <div class=" tw__flex tw__items-center tw__gap-2">
                <a href="javascript:void(0)" class=" tw__text-white tw__ml-4" onclick="reloadIframe()"><i class="fa-solid fa-arrow-rotate-right"></i></a>
                <p class=" tw__mb-0 tw__mx-auto tw__px-8 tw__py-1 tw__bg-gray-200 tw__rounded tw__text-center lg:tw__max-w-[450px] tw__max-w-[250px] tw__overflow-hidden tw__whitespace-nowrap tw__text-ellipsis tw__block">
                    <span class=" href-url">{{ parse_url(env('APP_URL'))['host'] }}</span>
                </p>
                <a href="javascript:void(0)" class=" text-white tw__mr-4" onclick="newTabIframe()"><i class="fa-solid fa-up-right-from-square"></i></a>
            </div>
        </div>
        <div class=" tw__bg-white tw__rounded-b-xl tw__overflow-hidden" id="body">
            <iframe src="{{ route('log-viewer::dashboard') }}" class=" tw__w-full tw__min-h-[calc(100vh-175px)]" id="iframe" onload="frameLoaded(this)"></iframe>
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        const frameLoaded = (el) => {
            console.log("Frame is loaded");
            setTimeout(() => {
                if(document.querySelector('.href-url')){
                    document.querySelector('.href-url').innerHTML = el.contentWindow.location.href;
                }
            }, 0);
        }
        function reloadIframe(){
            if(document.getElementById('iframe')){
                document.getElementById('iframe').contentWindow.location.reload();
            }
        }
        function newTabIframe(){
            if(document.getElementById('iframe')){
                let url = document.getElementById('iframe').contentWindow.location.href;
                window.open(url, "_blank");
            }
        }
    </script>
@endsection