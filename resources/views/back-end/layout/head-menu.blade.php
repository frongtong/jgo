<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
    <!--begin::Sidebar mobile toggle-->
    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
        <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
            <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>
    <!--end::Sidebar mobile toggle-->
    <!--begin::Mobile logo-->
    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
        <a href="javascript:void(0);" class="d-lg-none">
            <img alt="Logo" src="{{ asset('dist/images/logo.png') }}" class="h-30px" />
            {{-- <img alt="Logo" src="backend/assets/media/logos/default-small.svg" class="h-30px" /> --}}
        </a>
    </div>
    <!--end::Mobile logo-->
    <!--begin::Header wrapper-->
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
        <!--begin::Menu wrapper-->
        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
        </div>
        <!--end::Menu wrapper-->

        <!--begin::Navbar-->
        <div class="app-navbar flex-shrink-0">

            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <img src="{{ asset('dist/images/logo.png') }}" class="rounded-3" alt="user" />
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ asset('dist/images/logo.png') }}" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5"> {{ Auth::guard('admin')->user()->name }}
                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Admin</span></div>
                                <p class="fw-semibold text-muted fs-7">{{ Auth::guard('admin')->user()->email }}</p>
                                {{-- <p class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::guard('admin')->user()->email }}</p> --}}
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5">
                        <a href="{{ url('webpanel/administrator/user/edit/' . Auth::guard('admin')->user()->id) }}" class="menu-link px-5">My Profile</a>
                    </div>
                    <!--end::Menu item-->
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">Mode
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="ki-duotone ki-night-day theme-light-show fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                    <span class="path7"></span>
                                    <span class="path8"></span>
                                    <span class="path9"></span>
                                    <span class="path10"></span>
                                </i>
                                <i class="ki-duotone ki-moon theme-dark-show fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span></span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-night-day fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                            <span class="path6"></span>
                                            <span class="path7"></span>
                                            <span class="path8"></span>
                                            <span class="path9"></span>
                                            <span class="path10"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-moon fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-screen fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5">
                         <a href="{{url("webpanel/logout")}}" class="menu-link px-5">Sign Out</a>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header wrapper-->
</div>
