@extends('../layout/main')

@section('head')
    @yield('subhead')
@endsection

@section('content')
    @include('../layout/components/mobile-menu')
    <div class="flex">
        <!-- BEGIN: Side Menu -->
        <nav class="side-nav">
            <a href="" class="intro-x flex items-center pl-5 pt-4">
                <img alt="Midone - HTML Admin Template" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="hidden xl:block text-white text-lg ml-3">
                    UPR
                </span>
            </a>
            <div class="side-nav__devider my-6"></div>
            <ul>
                <li>
                    <a href="{{ route('manage-users.index') }}" class="side-menu">
                        <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                          </svg></div>
                        <div class="side-menu__title">ระบบจัดการSuper Users</div>
                    </a>
                </li>

                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"><i data-lucide="settings"></i></div>
                        <div class="side-menu__title">
                            การตั้งค่าพื้นฐานระบบ
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="{{ url('webpanel/product') }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="plus-circle"></i> </div>
                                <div class="side-menu__title"> Add </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('webpanel/type_rate') }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="plus-circle"></i> </div>
                                <div class="side-menu__title">Price Rate</div>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('webpanel/size') }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="plus-circle"></i> </div>
                                <div class="side-menu__title"> Size </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('webpanel/type') }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="plus-circle"></i> </div>
                                <div class="side-menu__title"> Type </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('webpanel/type_animal') }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="plus-circle"></i> </div>
                                <div class="side-menu__title"> Animal Type </div>
                            </a>
                        </li>


                    </ul>
                </li>
                @foreach ($side_menu as $menuKey => $menu)
                    @if ($menu == 'devider')
                        <li class="side-nav__devider my-6"></li>
                    @else
                        <li>
                            <a href="{{ isset($menu['route_name']) ? route($menu['route_name'], $menu['params']) : 'javascript:;' }}"
                                class="{{ $first_level_active_index == $menuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                <div class="side-menu__icon">
                                    <x-icon name="{{ $menu['icon'] }}" />
                                </div>
                                <div class="side-menu__title">
                                    {{ $menu['title'] }}
                                    @if (isset($menu['sub_menu']))
                                        <div
                                            class="side-menu__sub-icon {{ $first_level_active_index == $menuKey ? 'transform rotate-180' : '' }}">

                                            <x-icon name="chevron-down" />
                                        </div>
                                    @endif
                                </div>
                            </a>
                            @if (isset($menu['sub_menu']))
                                <ul class="{{ $first_level_active_index == $menuKey ? 'side-menu__sub-open' : '' }}">
                                    @foreach ($menu['sub_menu'] as $subMenuKey => $subMenu)
                                        <li>
                                            <a href="{{ isset($subMenu['route_name']) ? route($subMenu['route_name'], $subMenu['params']) : 'javascript:;' }}"
                                                class="{{ $second_level_active_index == $subMenuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                                <div class="side-menu__icon">
                                                    <x-icon name="activity" />
                                                </div>
                                                <div class="side-menu__title">
                                                    {{ $subMenu['title'] }}
                                                    @if (isset($subMenu['sub_menu']))
                                                        <div
                                                            class="side-menu__sub-icon {{ $second_level_active_index == $subMenuKey ? 'transform rotate-180' : '' }}">
                                                            <x-icon name="chevron-down" />
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                            @if (isset($subMenu['sub_menu']))
                                                <ul
                                                    class="{{ $second_level_active_index == $subMenuKey ? 'side-menu__sub-open' : '' }}">
                                                    @foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu)
                                                        <li>
                                                            <a href="{{ isset($lastSubMenu['route_name']) ? route($lastSubMenu['route_name'], $lastSubMenu['params']) : 'javascript:;' }}"
                                                                class="{{ $third_level_active_index == $lastSubMenuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                                                <div class="side-menu__icon">
                                                                    <x-icon name="zap" />
                                                                </div>
                                                                <div class="side-menu__title">{{ $lastSubMenu['title'] }}
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <!-- END: Side Menu -->
        <!-- BEGIN: Content -->
        <div class="content">
            @include('../layout/components/top-bar')
            @yield('subcontent')
        </div>
        <!-- END: Content -->
    </div>
@endsection
