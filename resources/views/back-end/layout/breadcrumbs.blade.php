<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
    <!--begin::Title-->
    @php
        $breadName = 'Dashboard';
        $nav_name = "Orange Technology Dashboard";
        $nav_last = end($navs);

        if(@$navs[0] != null){
            $nav_name = $nav_last['name'];
        }
        // if(@$bread_)
    @endphp

    
    <!--end::Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

        <!--begin::Item-->
        {{-- <li class="breadcrumb-item text-muted">
            <a href="{{ url('admin') }}" class="text-muted text-hover-primary">{{ @$breadName }}</a>
        </li> --}}
        <!--end::Item-->

        <!--begin::Item-->
        @if (@$navs)
            @php
            $nav_end = key($navs);
            @endphp
            @foreach ($navs as $index=>$nav)

                <li class="breadcrumb-item text-muted"><a href="{{ @$nav['url'] }}" class="text-muted text-hover-primary"><h3>{{ @$nav['name'] }}</h3></a></li>
                @if($index != $nav_end)
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                @endif
            @endforeach
        @endif
        <!--end::Item-->
    </ul>
    <h1 class="page-heading d-flex fw-bold fs-3 flex-column justify-content-center my-0" style="color:#A21D21;"> {{@$nav_name}}</h1>
    <!--end::Breadcrumb-->
</div>
