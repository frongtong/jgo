<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    @include("$prefix.layout.head")
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    class="app-default">
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header" data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
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
                            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                                @include("$prefix.layout.breadcrumbs")
                            </div>
                        </div>

                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                <form id="form_submit" method="POST" enctype="multipart/form-data" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 mb-5">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>Status</h2>
                                                    </div>

                                                    <div class="card-toolbar">
                                                        <div class="rounded-circle {{ Helper::Status($row->isActive) }} w-15px h-15px"
                                                            id="kt_ecommerce_add_category_status"></div>
                                                    </div>
                                                </div>

                                                <div class="card-body pt-0">
                                                    <select name="isActive" id="isActive" class="form-select">
                                                        <option value="Y"
                                                            @if (@$row->isActive == 'Y') selected @endif>Active
                                                        </option>
                                                        <option value="N"
                                                            @if (@$row->isActive == 'N') selected @endif>Inactive
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>General</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="required form-label">Role </label>
                                                            <input type="text" id="name" name="name"
                                                                class="form-control mb-2" placeholder="Role name"
                                                                value="{{ @$row->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="required form-label">Detail </label>
                                                            <input type="text" id="detail" name="detail"
                                                                class="form-control mb-2" placeholder="Detail - Role"
                                                                value="{{ @$row->detail }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2 class="font-medium text-base mr-auto">
                                                            <i class="fa fa-cog text-danger"></i> Permission Setting
                                                        </h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                        id="kt_ecommerce_products_table">
                                                        <thead>
                                                            <tr
                                                                class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                                                                <th style="width:20%;" class="text-center">
                                                                    ลำดับ
                                                                </th>
                                                                <th style="width:20%;" class="text-center">
                                                                    <div
                                                                        class="form-check form-check-sm form-check-custom form-check-solid">
                                                                        <input type="checkbox" id="checkAll"
                                                                            class="form-check-input">
                                                                    </div>
                                                                </th>
                                                                <th style="width:20%;" class="text-left">
                                                                    รายการที่สามารถเข้าถึงได้</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (@$menus)
                                                                @foreach (@$menus as $index => $m)
                                                                    @php
                                                                        $second = \App\Models\Backend\MenuModel::where(
                                                                            '_id',
                                                                            $m->id,
                                                                        )
                                                                            ->where('status', 'on')
                                                                            ->get();
                                                                        $role_main = \App\Models\Backend\Role_listModel::where(
                                                                            [
                                                                                'role_id' => $row->id,
                                                                                'menu_id' => $m->id,
                                                                            ],
                                                                        )->first();
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            {{ $index + 1 }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <input name="menu_id[]"
                                                                                value="{{ $m->id }}" hidden>
                                                                            <div
                                                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                                                <input class="form-check-input view"
                                                                                    type="checkbox"
                                                                                    @if (@$role_main->read == 'on') checked @endif
                                                                                    name="read_{{ $m->id }}" />
                                                                            </div>
                                                                        </td>
                                                                        @if (count(@$second) == 0)
                                                                            <td class="text-left">
                                                                                <div
                                                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                                                    <input
                                                                                        class="form-check-input view"
                                                                                        type="checkbox"
                                                                                        @if (@$role_main->read == 'on') checked @endif
                                                                                        name="read_{{ $m->id }}" />
                                                                                </div>
                                                                            </td>
                                                                        @endif
                                                                        <td class="text-left">
                                                                            {{ $m->name }}
                                                                            <input name="menu_id[]"
                                                                                value="{{ $m->id }}" hidden>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-end mt-5">
                                                <a href="{{ url("$segment/$folder") }}" id=""
                                                    class="btn btn-light me-2">Cancel</a>
                                                <button type="button" id="" onclick="check_add();"
                                                    class="btn btn-primary" style="background: #1C2842;"><span
                                                        class="indicator-label">Save Changes</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>

    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the checkAll checkbox element
            const checkAll = document.getElementById('checkAll');

            // Get all the individual checkboxes in the table
            const checkboxes = document.querySelectorAll('.form-check-input.view');

            // Add event listener to checkAll checkbox
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = checkAll.checked;
                });
            });
        });

        function check_add() {
            var formData = new FormData($("#form_submit")[0]);
            Swal.fire({
                icon: 'warning',
                title: 'Please press confirm to complete the transaction.',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST', 
                        url: "{{ "$segment/$folder/edit/$row->id" }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    text: data.desc,
                                    showCancelButton: false,
                                    confirmButtonText: 'Close',
                                }).then((result) => {
                                    location.href = "{{ "$segment/$folder" }}";
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error); // Log any errors for debugging
                            Swal.fire({
                                icon: 'error',
                                title: 'An error occurred!',
                                text: 'Please try again later.',
                                showCancelButton: false,
                                confirmButtonText: 'Close',
                            });
                        }
                    });
                } else {
                    return false;
                }
            });

            return false;
        }
    </script>
    <!--end::Javascript-->

</body>
<!--end::Body-->

</html>
