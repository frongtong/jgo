<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<base href="{{url("/th")}}" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>JGO  - Backend</title>
	<meta charset="utf-8" />
	<meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
	<meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
	<meta property="og:url" content="https://keenthemes.com/metronic" />
	<meta property="og:site_name" content="Keenthemes | Metronic" />
	<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
	<link rel="shortcut icon" href="{{ asset('dist/images/logo.png') }}" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<link href="{{asset("backend/assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css" />
	<link href="{{asset("backend/assets/css/style.bundle.css")}}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="{{ asset('backend/cusmike/toastr/toastr.min.css') }}">
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
	<div class="d-flex flex-column flex-root" id="kt_app_root">
		<style>
			body {
				background-image: url('backend/assets/media/auth/bg10.jpeg');
			}

			[data-bs-theme="dark"] body {
				background-image: url('backend/assets/media/auth/bg10-dark.jpeg');
			}
		</style>
		<div class="d-flex flex-column flex-lg-row flex-column-fluid">
			<div class="d-flex flex-lg-row-fluid">
				<div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">

					<img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
						src="backend/assets/media/auth/logo.png" alt="" />

					<img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
						src="backend/assets/media/auth/logo.png" alt="" />

					<h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">
						Welcome to the Website Management System
					</h1>

					<div class="text-gray-600 fs-base text-center fw-semibold">
						Manage your website quickly, efficiently, and securely<br>
						through the backend administration panel.<br><br>

					</div>

				</div>
			</div>
			<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
				<div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
					<div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
						<!--begin::Wrapper-->
						<div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
							<form id="frm_cus" class="form w-100" action="" method="post" onsubmit="return login_submit();">
								<div class="text-center mb-11">
									<h1 class="text-dark fw-bolder mb-3">Sign In</h1>
								</div>
								<div class="separator separator-content my-14">
									<span class="w-125px text-gray-500 fw-semibold fs-7">JGO Backend</span>
								</div>
								<div class="fv-row mb-8">
									<input type="text" placeholder="Username & Email" id="username" name="username" value="admin" autocomplete="off" class="form-control bg-transparent" />
								</div>
								<div class="fv-row mb-3">
									<input type="password" placeholder="Password" id="password" name="password" value="1234" autocomplete="off" class="form-control bg-transparent" />
								</div>
								<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
									<div></div>
									{{-- <a href="javascript:void(0);" class="link-primary">Forgot Password ?</a> --}}
								</div>
								<div class="d-grid mb-10">
									<button type="button" onclick="login_submit();" id="kt_sign_in_submit" class="btn btn-primary">
										<span class="indicator-label">Sign In</span>
										<span class="indicator-progress">Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
								</div>
								{{-- <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
									<a href="../../demo1/dist/authentication/layouts/overlay/sign-up.html" class="link-primary">Sign up</a></div> --}}
							</form>
						</div>
						<!--end::Wrapper-->

						<div class="d-flex flex-stack">
							<div class="me-10">
							</div>
							<div class="d-flex fw-semibold text-primary fs-base gap-5">
								{{-- <a href="javascript:void(0);">Terms</a>
									<a href="javascript:void(0);">Plans</a>
									<a href="javascript:void(0);">Contact Us</a> --}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--begin::Javascript-->
	<script>
		var hostUrl = "backend/assets/";
	</script>
	<script src="{{asset("backend/assets/plugins/global/plugins.bundle.js")}}"></script>
	<script src="{{asset("backend/assets/js/scripts.bundle.js")}}"></script>
	<script src="{{asset("backend/assets/js/custom/authentication/sign-in/general.js")}}"></script>
	<script src="{{ asset('backend/cusmike/toastr/toastr.js') }}"></script>
	<script>
		var fullUrl = window.location.origin + window.location.pathname;

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		function contact() {
			// alert(1)
			toastr.error("The system is not activated yet.");
			return false;
		}

		function login_submit() {
			var formData = new FormData($("#frm_cus")[0]);
			var username = $('#username').val();
			var password = $('#password').val();
			if (username == "" || password == "") {
				toastr.error("Sorry, please complete the information.");
				return false;
			}

			$.ajax({
				type: 'POST',
				url: fullUrl,
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(data) {
					if (data.result == "success") {
						location.reload();
					} else {
						Swal.fire({
							title: "" + data.title,
							text: "" + data.text,
							icon: 'error',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Close',
						});
					}
				}
			});
		}
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->

</html>