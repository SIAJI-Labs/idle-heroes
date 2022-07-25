<!-- [ Header ] start -->
<header class="pc-header ">
    <div class="header-wrapper">
        <div class="mr-auto pc-mob-drp">
            {{-- Shown on Larger Resolution --}}
            <ul class="list-unstyled tw__hidden xl:tw__inline-flex">
                {{-- <li class="dropdown pc-h-item">
                    <a class="pc-head-link active dropdown-toggle arrow-none mr-0 tw__bg-transparent general-tour" id="tour-icon-a" href="javascript:void(0)">
                        <i class="bi bi-info-circle"></i>
                    </a>
                </li>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link active dropdown-toggle arrow-none mr-0 tw__bg-transparent notification-icon" id="notif-icon-a" href="javascript:void(0)" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                    </a>
                </li> --}}
                <li class="dropdown pc-h-item tw__hidden">
                    <a class="pc-head-link tw__hidden" id="headerdrp-collapse"></a>
                </li>
            </ul>
        </div>
        <div class="ml-auto">
            <ul class="list-unstyled">
                {{-- <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none mr-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="bi bi-search"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pc-h-dropdown drp-search">
                        <form class="px-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <i data-feather="search"></i>
                                <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                            </div>
                        </form>
                    </div>
                </li> --}}
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none mr-0" data-bs-toggle="dropdown" data-bs-auto-close="outside" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ getAvatar(auth()->check() ? auth()->user()->name : env("APP_NAME")) }}" alt="user-image" class="user-avtar">
                        <span>
                            <span class="user-name">{{ auth()->check() ? auth()->user()->name : env("APP_NAME") }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pc-h-dropdown">
                        @if (isset($provTopMenu) && is_array($provTopMenu) && count($provTopMenu) > 0)
                            @foreach ($provTopMenu as $menuGroup)
                                <a href="{{ $menuGroup['route'] ?? 'javascript:void(0)' }}" class="dropdown-item">
                                    @if (isset($menuGroup['icon']) && !empty($menuGroup['icon']))
                                        {!! $menuGroup['icon'] !!}
                                    @endif
                                    <span>{{ $menuGroup['name'] }}</span>
                                </a>
                            @endforeach
                        @endif

                        <a href="javascript:void(0)" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</header>
<!-- [ Header ] end -->