<!-- [ navigation menu ] start -->
<nav class="pc-sidebar ">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('s.index') }}" class="b-brand">
                <h3 class=" tw__uppercase tw__mb-0 text-white">{{ env('APP_NAME') }}</h3>
                <small>by SIAJI</small>
                {{-- <img src="{{ asset('assets/img/dashboard-kit/logo.svg') }}" alt="" class="logo logo-lg"> --}}
            </a>
        </div>
        <div class="navbar-content tw__max-h-[calc(100vh-70px)] tw__overflow-y-scroll">
            <ul class="pc-navbar">
                @if (isset($provMenu) && is_array($provMenu) && count($provMenu) > 0)
                    @foreach ($provMenu as $menuGroup)
                        @if (isset($menuGroup['name']) && !empty($menuGroup['name']))
                            <li class="pc-item pc-caption {{ isset($menuGroup['disabled']) && $menuGroup['disabled'] === true ? 'disabled' : '' }}">
                                <label>{{ $menuGroup['name'] }}</label>
                                @if (isset($menuGroup['description']) && !empty($menuGroup['description']))
                                    <span>{{ $menuGroup['description'] }}</span>
                                @endif
                            </li>
                        @endif

                        @if (isset($menuGroup['menu']) && is_array($menuGroup['menu']) && count($menuGroup['menu']) > 0)
                            @foreach ($menuGroup['menu'] as $menu)
                                <li class="pc-item {{ isset($menu['submenu']) && !empty($menu['submenu']) ? ('pc-hasmenu '.($menu['active_key'] ? ($wsidebar_menu == $menu['active_key'] ? 'pc-trigger' : '') : '')) : '' }} {{ $menu['active_key'] ? ($wsidebar_menu == $menu['active_key'] ? 'active' : '') : '' }} {{ isset($menu['disabled']) && $menu['disabled'] === true ? 'disabled' : '' }}">
                                    <a href="{{ $menu['route'] ?? 'javascript:void(0)' }}" class="pc-link tw__flex tw__items-center">
                                        @if (isset($menu['icon']) && !empty($menu['icon']))
                                            <span class="pc-micon tw__flex tw__items-center">
                                                {!! $menu['icon'] !!}
                                            </span>
                                        @endif
                                        <span class="pc-mtext tw__mr-auto">{{ $menu['name'] }}</span>

                                        {{-- Submenu Arrow --}}
                                        @if (isset($menu['submenu']) && !empty($menu['submenu']))
                                            <span class="pc-arrow">
                                                <i class="fa-solid fa-angle-right"></i>
                                            </span>
                                        @endif
                                    </a>

                                    {{-- Submenu --}}
                                    @if (isset($menu['submenu']) && !empty($menu['submenu']))
                                        <ul class="pc-submenu">
                                            @foreach ($menu['submenu'] as $submenu)
                                                <li class="pc-item {{ $menu['active_key'] ? ($wsidebar_menu == $menu['active_key'] && $wsidebar_submenu == $submenu['active_sub'] ? 'active' : '') : '' }} {{ isset($submenu['disabled']) && $submenu['disabled'] === true ? 'disabled' : '' }}">
                                                    <a class="pc-link" href="{{ $submenu['route'] ?? 'javascript:void(0)' }}">{{ $submenu['name'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <li class="pc-item pc-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('s.index') }}" class="pc-link ">
                            <span class="pc-micon">
                                <i class="fa-solid fa-house"></i>
                            </span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->