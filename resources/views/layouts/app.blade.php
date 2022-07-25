<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('assets/img/brand/siaji-logo.svg') }}">

        <!-- Primary Meta Tags -->
        <meta name="title" content="{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? env('APP_NAME', 'SIABAS')) }}">
        <meta name="description" content="Basic Engine for Repeat Use">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? env('APP_NAME', 'SIABAS')) }}">
        <meta property="og:description" content="{{ isset($wmeta_desc) && !empty($wmeta_desc) ? $wmeta_desc : 'Basic Engine for Repeat Use' }}">
        <meta property="og:image" content="{{ isset($wmeta_image) ? $wmeta_image : asset('assets/img/brand/siaji-logo.svg') }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? env('APP_NAME', 'SIABAS')) }}">
        <meta property="twitter:description" content="{{ isset($wmeta_desc) && !empty($wmeta_desc) ? $wmeta_desc : 'Basic Engine for Repeat Use' }}">
        <meta property="twitter:image" content="{{ isset($wmeta_image) ? $wmeta_image : asset('assets/images/brand/siaji-logo.svg') }}">

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? env('APP_NAME', 'SIABAS')) }}</title>
        @if (\File::exists('manifest.webmanifest'))
            <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

        <!-- Fonts - Icons -->

        <!-- Plugins -->
        @yield('css_plugins')
        @yield('css_inline')

        {{-- Bootstrap --}}
        @include('layouts.plugins.bootstrap.css')
        <!-- Default Styles -->
        <link href="{{ mix('assets/css/tailwind.css') }}" rel="stylesheet">
        @if (isset($wdashboard) || isset($wauth))
            <link href="{{ mix('assets/css/dashboard-kit/siaji.css') }}" rel="stylesheet">
            <link href="{{ mix('assets/css/dashboard-kit/style.css') }}" rel="stylesheet">
        @endif

        @if (isset($provAds) && $provAds === true)
            <script data-ad-client="ca-pub-7931436749008016" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7931436749008016" crossorigin="anonymous"></script>
        @endif
    </head>
    <body class="{{ $wbodyClass ?? '' }} tw__min-h-screen siaji-dashboard-kit">
        {{-- Loader --}}
        @include('layouts.partials.loader')

        {{-- Partials --}}
        @if (isset($wdashboard) && $wdashboard)
            {{-- Header - Mobile --}}
            @include('layouts.partials.header-mobile')
            {{-- Sidebar --}}
            @include('layouts.partials.sidebar')
            {{-- Header --}}
            @include('layouts.partials.header')
        @endif

        {{-- Content --}}
        @if (isset($wdashboard) && $wdashboard)
            <!-- [ Main Content ] start -->
            <div class="pc-container">
                <div class="pcoded-content">
                    @if (isset($wheader['header_title']) || isset($wheader['header_breadcrumb']))
                        <!-- [ breadcrumb ] start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-12">
                                        <span id="breadcrumb">
                                            @if (isset($wheader['header_tour']) && $wheader['header_tour'] === true)
                                                <a href="javascript:void(0)" class=" tw__mr-1" id="tour-action">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </a>
                                            @endif

                                            @if (isset($wheader['header_title']) && !empty($wheader['header_title']))
                                                <div class="page-header-title">
                                                    <h5 class="m-b-10">{{ $wheader['header_title'] }}</h5>
                                                </div>
                                            @endif
    
                                            @if (isset($wheader['header_breadcrumb']) && !empty($wheader['header_breadcrumb']))
                                                <ul class="breadcrumb">
                                                    @foreach ($wheader['header_breadcrumb'] as $breadcrumb)
                                                        <li class="breadcrumb-item">
                                                            @if($breadcrumb['is_active'])
                                                                <span>{{ $breadcrumb['title'] }}</span>
                                                            @else
                                                            <a href="{{ $breadcrumb['url'] }}">
                                                                <span>{{ $breadcrumb['title'] }}</span>
                                                            </a>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </span>
                                    </div>

                                    @if (isset($wheader['extra_button']) && !empty($wheader['extra_button']))
                                        <div class="col-md-6 col-12 md:tw__text-right md:tw__mt-0 tw__text-left tw__mt-4">
                                            {!! $wheader['extra_button'] !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- [ breadcrumb ] end -->
                    @endif

                    <!-- [ Main Content ] start -->
                    <section id="content" class="tw__pt-[60px] tw__pb-[70px]">
                        @yield('content')
                    </section>
                    <!-- [ Main Content ] end -->
                </div>

                {{-- Footer --}}
                @include('layouts.partials.footer')
            </div>
            <!-- [ Main Content ] end -->
        @elseif (isset($wauth) && $wauth)
            <div class="auth-wrapper">
                <div class="auth-content">
                    @yield('content')
                </div>
            </div>
        @else
            <div class="main-content">
                @yield('content')
            </div>
        @endif

        @yield('content_modal')
        
        {{-- Logout Function --}}
        @if (auth()->check())
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @endif

        {{-- Bootstrap --}}
        @include('layouts.plugins.bootstrap.js')
        <!-- Scripts -->
        @if (isset($wdashboard) || isset($wauth))
            <script src="{{ mix('assets/js/dashboard-kit/plugins/feather.min.js') }}"></script>
            <script src="{{ mix('assets/js/dashboard-kit/vendor-all.min.js') }}"></script>
            <script src="{{ mix('assets/js/dashboard-kit/pcoded.js') }}"></script>

            <script src="{{ mix('assets/js/dashboard-kit/script.js') }}"></script>
            <script src="{{ mix('assets/js/dashboard-kit/siaji.js') }}"></script>
        @endif

        <!-- Axios -->
        @include('layouts.plugins.axios.js')

        <!-- Moment -->
        <script src="{{ mix('assets/plugins/moment/moment.js') }}"></script>

        <!-- Fontawesome -->
        <script src="{{ mix('assets/fonts/fontawesome/all.min.js') }}"></script>

        @yield('js_plugins')
        @yield('js_inline')
    </body>
</html>