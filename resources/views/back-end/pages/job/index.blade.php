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

                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">

                            <div id="kt_app_toolbar_container"
                                class="app-container container-xxl d-flex flex-stack">

                                @include("$prefix.layout.breadcrumbs")

                            </div>

                        </div>

                        <div id="kt_app_content"
                            class="app-content flex-column-fluid">

                            <!--begin::Content container-->
                            <div id="kt_app_content_container"
                                class="app-container container-xxl">

                                <div class="card card-flush">

                                    <!-- HEADER -->
                                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">

                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                                            <a href="{{ url("$segment/$folder/add") }}"
                                                class="btn btn-primary">

                                                Add Job

                                            </a>

                                        </div>

                                    </div>

                                    <div class="card-body pt-0">

                                        <!-- SEARCH -->
                                        <form method="get">

                                            <div class="row mb-5">

                                                <div class="col-md-4">

                                                    Keywords

                                                    <input type="text"
                                                        class="form-control form-control-solid"
                                                        name="search"
                                                        value="{{ Request::get('search') }}"
                                                        placeholder="ค้นหางาน">

                                                </div>

                                                <div class="col-md-3">

                                                    บริษัท

                                                    <select name="company_id"
                                                        class="form-select form-select-solid">

                                                        <option value="">

                                                            ทั้งหมด

                                                        </option>

                                                        @foreach($companies as $company)

                                                            <option value="{{ $company->id }}"
                                                                @if(Request::get('company_id') == $company->id)
                                                                    selected
                                                                @endif>

                                                                {{ $company->name_th }}

                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>

                                                <div class="col-md-3">

                                                    จังหวัด

                                                    <select name="province_id"
                                                        class="form-select form-select-solid">

                                                        <option value="">

                                                            ทั้งหมด

                                                        </option>

                                                        @foreach($provinces as $province)

                                                            <option value="{{ $province->id }}"
                                                                @if(Request::get('province_id') == $province->id)
                                                                    selected
                                                                @endif>

                                                                {{ $province->name }}

                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>

                                                <div class="col-md-2">

                                                    <button style="margin-top:25px;"
                                                        class="btn btn-success w-100">

                                                        Search

                                                    </button>

                                                </div>

                                            </div>

                                        </form>
                                        <!-- END SEARCH -->



                                        <!-- SHOWING -->
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



                                        <!-- TABLE -->
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

                                                            ตำแหน่งงาน

                                                        </th>

                                                        <th style="width:15%;">

                                                            บริษัท

                                                        </th>

                                                        <th style="width:15%;">

                                                            จังหวัด

                                                        </th>

                                                        <th style="width:10%;"
                                                            class="text-center">

                                                            เงินเดือน

                                                        </th>

                                                        <th style="width:10%;"
                                                            class="text-center">

                                                            Status

                                                        </th>

                                                        <th style="width:10%;"
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
                                                                        style="width:60px;height:60px;object-fit:cover;"
                                                                        class="rounded border">
                                                                @else

                                                                    -

                                                                @endif

                                                            </td>



                                                            <!-- TITLE -->
                                                            <td>

                                                                <div class="fw-bold">

                                                                    {{ $item->title_th }}
                                                                </div>

                                                                <div class="text-muted fs-7">

                                                                    {{ $item->job_type }}

                                                                </div>

                                                            </td>



                                                            <!-- COMPANY -->
                                                            <td>

                                                                {{ $item->company->name_th ?? '-' }}

                                                            </td>



                                                            <!-- PROVINCE -->
                                                            <td>

                                                                {{ $item->province->name ?? '-' }}

                                                            </td>



                                                            <!-- SALARY -->
                                                            <td class="text-center">

                                                                @if($item->salary_min || $item->salary_max)

                                                                    {{ number_format($item->salary_min) }}
                                                                    -
                                                                    {{ number_format($item->salary_max) }}

                                                                @else

                                                                    -

                                                                @endif

                                                            </td>



                                                            <!-- STATUS -->
                                                            <td class="text-center">

                                                                <label class="form-check form-switch form-check-custom form-check-solid"
                                                                    style="display: contents !important;">

                                                                    <input class="form-check-input update-status"
                                                                        type="checkbox"
                                                                        value="{{ $item->status }}"
                                                                        data-id="{{ $item->id }}"
                                                                        @if($item->status == 'on') checked @endif>

                                                                </label>

                                                            </td>



                                                            <!-- ACTION -->
                                                            <td class="text-center">

                                                                <!-- EDIT -->
                                                                <a href="{{ url("$segment/$folder/edit/$item->id") }}"
                                                                    class="btn btn-icon btn-light-warning btn-sm">

                                                                    <i class="ki-duotone ki-pencil fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>

                                                                </a>

                                                                <!-- DELETE -->
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
                                        <div class="table-footer mt-2">

                                            <div class="row">

                                                <div class="col-sm-5"></div>

                                                <div class="col-sm-7">

                                                    {!! $items->appends(request()->all())->links('back-end.layout.pagination') !!}

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!--end::Content container-->

                        </div>

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

    </div>

    <div id="kt_scrolltop"
        class="scrolltop"
        data-kt-scrolltop="true">

        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>

    </div>

    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <!--end::Javascript-->

</body>
<!--end::Body-->

<script>

    var fullUrl =
        window.location.origin
        + window.location.pathname;



    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */
$(document).ready(function() {

    $('.update-status').on('change', function() {

        var id = $(this).data('id');

        var status = $(this).is(':checked')
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

            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: 'อัปเดตสถานะเรียบร้อยแล้ว',
                    timer: 1500,
                    showConfirmButton: false
                });

            },

            error: function() {

                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถอัปเดตสถานะได้'
                });

            }

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

                return fetch(
                    fullUrl + '/destroy',
                    {

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

                    }
                )

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