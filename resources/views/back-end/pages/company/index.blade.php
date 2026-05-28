<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    @include("$prefix.layout.head")
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body"
    data-kt-app-layout="dark-sidebar"
    data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true"
    data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true"
    data-kt-app-toolbar-enabled="true"
    class="app-default">

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">

        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            <!--begin::Header-->
            <div id="kt_app_header"
                class="app-header"
                data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}"
                data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}"
                data-kt-sticky-animation="false">

                @include("$prefix.layout.head-menu")

            </div>
            <!--end::Header-->


            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                <!--begin::Sidebar-->
                @include("$prefix.layout.side-menu")
                <!--end::Sidebar-->



                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">

                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">

                            <div id="kt_app_toolbar_container"
                                class="app-container container-xxl d-flex flex-stack">

                                @include("$prefix.layout.breadcrumbs")

                            </div>

                        </div>
                        <!--end::Toolbar-->



                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">

                            <!--begin::Content container-->
                            <div id="kt_app_content_container"
                                class="app-container container-xxl">

                                <div class="card card-flush">

                                    <!--begin::Card header-->
                                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">

                                        <div class="card-title">

                                            <form method="get">

                                                <div class="d-flex align-items-center position-relative my-1">

                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>

                                                    <input type="text"
                                                        class="form-control form-control-solid w-250px ps-12"
                                                        name="search"
                                                        value="{{ request()->get('search') }}"
                                                        placeholder="ค้นหาบริษัท">

                                                </div>

                                            </form>

                                        </div>


                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                                            <a href="{{ url("$segment/$folder/add") }}"
                                                class="btn btn-primary">

                                                <i class="ki-duotone ki-plus fs-2"></i>

                                                เพิ่มบริษัท

                                            </a>

                                        </div>
                                        <!--end::Card toolbar-->

                                    </div>
                                    <!--end::Card header-->



                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">

                                        <div class="hidden md:block mx-auto text-slate-500 mb-5">

                                            <b>

                                                Showing
                                                {{ $items->currentPage() }}
                                                to
                                                {{ $items->total() }}
                                                of
                                                {{ $items->total() }}
                                                entries

                                            </b>

                                        </div>



                                        <div class="table-responsive">

                                            <table class="table align-middle table-row-dashed fs-6 gy-5">

                                                <thead>

                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                                                        <th style="width:5%;"
                                                            class="text-center">

                                                            #

                                                        </th>

                                                        <th style="width:10%;"
                                                            class="text-center">

                                                            Logo

                                                        </th>

                                                        <th style="width:25%;">

                                                            ชื่อบริษัท

                                                        </th>

                                                        <th style="width:20%;">

                                                            จังหวัด

                                                        </th>

                                                        <th style="width:15%;">

                                                            Website

                                                        </th>

                                                        <!-- <th style="width:10%;"
                                                            class="text-center">

                                                            Status

                                                        </th> -->

                                                        <th style="width:15%;"
                                                            class="text-center">

                                                            Action

                                                        </th>

                                                    </tr>

                                                </thead>


                                                <tbody>

                                                    @foreach ($items as $index => $item)

                                                        <tr>

                                                            <!-- NUMBER -->
                                                            <td class="text-center">

                                                                {{ $items->pages->start + $index + 1 }}

                                                            </td>



                                                            <!-- LOGO -->
                                                            <td class="text-center">

                                                                @if($item->logo)

                                                                    <img src="{{ asset($item->logo) }}"
                                                                        class="w-60px h-60px rounded object-fit-cover border">

                                                                @else

                                                                    <div class="w-60px h-60px bg-light rounded d-flex align-items-center justify-content-center mx-auto">

                                                                        <i class="ki-duotone ki-picture fs-2x text-muted">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>

                                                                    </div>

                                                                @endif

                                                            </td>



                                                            <!-- NAME -->
                                                            <td>

                                                                <div class="d-flex flex-column">

                                                                    <span class="fw-bold fs-6">

                                                                        {{ $item->name_th }}

                                                                    </span>

                                                                    @if($item->name_en)

                                                                        <span class="text-muted fs-7">

                                                                            {{ $item->name_en }}

                                                                        </span>

                                                                    @endif

                                                                </div>

                                                            </td>



                                                            <!-- LOCATION -->
                                                            <td>

                                                                @if($item->province)

                                                                    {{ $item->province->name}}

                                                                @else

                                                                    -

                                                                @endif

                                                            </td>



                                                            <!-- WEBSITE -->
                                                            <td>

                                                                @if($item->website)

                                                                    <a href="{{ $item->website }}"
                                                                        target="_blank">

                                                                        {{ $item->website }}

                                                                    </a>

                                                                @else

                                                                    -

                                                                @endif

                                                            </td>



                                                            <!-- STATUS -->
                                                            <!-- <td class="text-center">

                                                                <label class="form-check form-switch form-check-custom form-check-solid"
                                                                    style="display: contents !important;">

                                                                    <input class="form-check-input update-status"
                                                                        type="checkbox"
                                                                        value="{{ $item->status }}"
                                                                        data-id="{{ $item->id }}"

                                                                        @if ($item->status == 'on')
                                                                            checked
                                                                        @endif>

                                                                </label>

                                                            </td> -->



                                                            <!-- ACTION -->
                                                            <td class="text-center">

                                                                <a href="{{ url("$segment/$folder/edit/$item->id") }}"
                                                                    class="btn btn-icon btn-light-warning btn-sm">

                                                                    <i class="ki-duotone ki-pencil fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>

                                                                </a>


                                                                <button type="button"
                                                                    onclick="deleteItem({{ $item->id }})"
                                                                    class="btn btn-icon btn-light-danger btn-sm">

                                                                    <i class="ki-duotone ki-trash fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                    </i>

                                                                </button>

                                                            </td>

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>



                                        <!-- PAGINATION -->
                                        <div class="table-footer mt-5">

                                            <div class="row">

                                                <div class="col-sm-12">

                                                    {!! $items->appends(request()->all())->links('back-end.layout.pagination') !!}

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <!--end::Card body-->

                                </div>

                            </div>
                            <!--end::Content container-->

                        </div>
                        <!--end::Content-->

                    </div>
                    <!--end::Content wrapper-->



                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">

                        @include("$prefix.layout.footer")

                    </div>
                    <!--End::Footer-->

                </div>
                <!--End::Main-->

            </div>

        </div>
        <!--end::Page-->

    </div>
    <!--end::App-->



    <!--begin::Scrolltop-->
    <div id="kt_scrolltop"
        class="scrolltop"
        data-kt-scrolltop="true">

        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>

    </div>
    <!--end::Scrolltop-->



    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <!--end::Javascript-->

</body>
<!--end::Body-->



<script>

    var fullUrl =
        window.location.origin +
        window.location.pathname;



    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {

        $('.update-status').on('change', function () {

            var id = $(this).data('id');

            var status =
                $(this).is(':checked')
                ? "on"
                : "off";

            $.ajax({

                url: fullUrl + "/update-status",

                type: 'POST',

                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },

            });

        });

    });



    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    function deleteItem(id)
    {

        Swal.fire({

            title: "ลบข้อมูล",

            text: "คุณต้องการลบข้อมูลใช่หรือไม่?",

            icon: "warning",

            showCancelButton: true,

            confirmButtonColor: "#DD6B55",

            showLoaderOnConfirm: true,

            preConfirm: () => {

                return fetch(fullUrl + '/destroy', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).getAttribute('content')

                    },

                    body: JSON.stringify({
                        id: id
                    })

                })

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            response.statusText
                        );

                    }

                    return response.json();

                })

                .then(data => {

                    if (data.success) {

                        Swal.fire(
                            "ลบแล้ว!",
                            "ข้อมูลของคุณถูกลบแล้ว",
                            "success"
                        ).then(() => {

                            location.reload();

                        });

                    } else {

                        Swal.fire(
                            "ล้มเหลว!",
                            "ไม่สามารถลบข้อมูลได้",
                            "error"
                        );

                    }

                })

                .catch(error => {

                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    );

                });

            }

        });

    }

</script>

</html>