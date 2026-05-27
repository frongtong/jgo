<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
        @include("$prefix.layout.head")
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
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
		<!--end::Javascript-->

	</body>
	<!--end::Body-->
</html>