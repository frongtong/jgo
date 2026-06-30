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
									<div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
										<div>
											<h1 class="fs-2hx fw-bold mb-1">Dashboard</h1>
											<div class="text-muted">สรุปภาพรวมการสมัครงาน</div>
										</div>
										<a href="{{ url("$segment/jobapplication/export") }}" class="btn btn-success">
											Export Excel
										</a>
									</div>

									<div class="row g-5 g-xl-8">
										@foreach($applicationSummary as $summary)
											<div class="col-sm-6 col-xl-4">
												<a href="{{ $summary['url'] }}" class="card card-flush h-100 {{ $summary['class'] }}">
													<div class="card-body">
														<div class="text-gray-700 fw-semibold mb-3">{{ $summary['label'] }}</div>
														<div class="fs-2hx fw-bold text-gray-900">{{ number_format($summary['count']) }}</div>
														<div class="text-muted mt-2">คลิกเพื่อดูรายการที่เกี่ยวข้อง</div>
													</div>
												</a>
											</div>
										@endforeach
									</div>

									<div class="card card-flush mt-8">
										<div class="card-header">
											<div class="card-title">
												<h2>ทางลัด</h2>
											</div>
										</div>
										<div class="card-body">
											<a href="{{ url("$segment/jobapplication") }}" class="btn btn-light-primary me-3">
												ไปหน้ารายการใบสมัคร
											</a>
											<a href="{{ url("$segment/jobapplication?status=interview") }}" class="btn btn-light-info">
												ดูรายการนัดสัมภาษณ์
											</a>
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
