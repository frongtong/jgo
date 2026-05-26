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

                                                    <div class="card-toolbar">
                                                        <div class="rounded-circle bg-success w-15px h-15px"
                                                            id="kt_ecommerce_add_category_status"></div>
                                                    </div>
                                                </div>

                                                <div class="card-body pt-0">
                                                    <label class="required form-label">Status</label>
                                                    <select name="isActive" id="isActive" class="form-select">
                                                        <option value="Y">Active</option>
                                                        <option value="N">Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <label class="required form-label">Role</label>
                                                    <select name="role" id="role" class="form-select" required>
                                                        <option value="">Role</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">
                                                                {{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9 mb-5">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>General</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="required form-label">Name </label>
                                                            <input type="text" id="name" name="name"
                                                                class="form-control mb-2" placeholder="name">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="required form-label">Email </label>
                                                            <input type="text" id="email" name="email"
                                                                class="form-control mb-2" placeholder="email">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-5">
                                                            <label class="required form-label">Password </label>
                                                            <div class="input-group col-mb-6">
                                                                <input type="password" id="password"
                                                                    class="form-control" name="password"
                                                                    placeholder="Password" autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <span class="card-link show_pass disabled"><i
                                                                                class="far fa-eye"
                                                                                data-id="password"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-5">
                                                            <label class="required form-label">Confirm password </label>
                                                            <div class="input-group col-mb-6">
                                                                <input type="password" id="confirm_password"
                                                                    class="form-control" name="confirm_password"
                                                                    placeholder="Confirm password" autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <span class="card-link show_pass_confirm disabled"><i
                                                                                class="far fa-eye"
                                                                                data-id="confirm_password"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
       $('#resetpassword').change(function() {
            if ($(this).prop("checked") == true) {
                $('#password').attr('disabled', false);
                $('#confirm_password').attr('disabled', false);
            } else if ($(this).prop("checked") == false) {
                $('#password').attr('disabled', true);
                $('#confirm_password').attr('disabled', true);
                $('#password').val(null);
                $('#confirm_password').val(null);
            }
            updateShowPasswordVisibility();
        });

        // Function to update show password icon visibility
        function updateShowPasswordVisibility() {
            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();
            
            if (password && confirmPassword) {
                $('.show_pass, .show_pass_confirm').removeClass('disabled').css('cursor', 'pointer');
            } else {
                $('.show_pass, .show_pass_confirm').addClass('disabled').css('cursor', 'not-allowed');
            }
        }

        // Event listeners for password inputs
        $('#password, #confirm_password').on('input', updateShowPasswordVisibility);

        // Show/hide password functionality
        $('.show_pass, .show_pass_confirm').click(function() {
            if ($(this).hasClass('disabled')) return;

            var passwordField = $(this).hasClass('show_pass') ? $('#password') : $('#confirm_password');
            var passwordFieldType = passwordField.attr('type');
            
            if (passwordFieldType == 'password') {
                passwordField.attr('type', 'text');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Initial visibility update
        updateShowPasswordVisibility();
    </script>
    <script>
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
                        url: "{{ "$segment/$folder/add" }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
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
                            } else if (data.status == 500) {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message,
                                    text: data.desc,
                                    showCancelButton: false,
                                    confirmButtonText: 'Close',
                                });
                            }
                        }
                    });
                } else {
                    return false;
                }
            });

            return false;
        }
        
    </script> 

</body> 

</html>
