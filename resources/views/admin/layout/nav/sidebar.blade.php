<div class="sidebar sidebar-fixed sidebar-dark bg-dark-gradient border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <svg class="sidebar-brand-full" width="110" height="32" alt="CoreUI Logo">
                <use xlink:href="{{ asset('panel/assets/brand/logoui.svg#full') }}"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo">
                <use xlink:href="{{ asset('panel/assets/brand/logoui.svg#signet') }}"></use>
            </svg>
        </div>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">

        <li class="nav-item"><a class="nav-link" href="index.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg><span data-coreui-i18n="dashboard">Dashboard</span></a></li>

        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.users.index')}}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg><span data-coreui-i18n="dashboard">Users</span>
            </a>
        </li>
         @if(Route::has('admin.file.index'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.file.index')}}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-file') }}"></use>
                </svg><span data-coreui-i18n="file">Files</span>
            </a>
        </li>
         @endif

        <li class="nav-title" data-coreui-i18n="theme">Theme</li>
        <li class="nav-item"><a class="nav-link" href="colors.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-drop') }}"></use>
                </svg><span data-coreui-i18n="colors">Colors</span></a></li>
        <li class="nav-item"><a class="nav-link" href="typography.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                </svg><span data-coreui-i18n="typography">Typography</span></a></li>
        <li class="nav-title" data-coreui-i18n="components">Components</li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                </svg><span data-coreui-i18n="base">Base</span></a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="base/accordion.html"><span class="nav-icon"><span class="nav-icon-bullet"></span></span><span data-coreui-i18n="accordion">Accordion</span></a></li>
                <li class="nav-item"><a class="nav-link" href="base/breadcrumb.html"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Breadcrumb</a></li>
                <li class="nav-item"><a class="nav-link" href="base/calendar.html"><span class="nav-icon"><span class="nav-icon-bullet"></span></span><span data-coreui-i18n="calendar">Calendar</span></a></li>
            </ul>
        </li>



        <li class="nav-item mt-auto">

        </li>
        <li class="nav-title"><span data-coreui-i18n="systemUtilization">System Utilization</span></li>
        <li class="nav-item px-3 pb-2 d-narrow-none">
            <div class="text-uppercase small fw-bold mb-1" data-coreui-i18n="cpuUsage">CPU Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-info-gradient" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-body-secondary" data-coreui-i18n="cpuUsageDescription, { 'number_of_processes': 358, 'number_of_cores': '1/4' }">348 Processes. 1/4 Cores.</div>
        </li>
        <li class="nav-item px-3 pb-2 d-narrow-none">
            <div class="text-uppercase small fw-bold mb-1" data-coreui-i18n="memoryUsage">Memory Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-warning-gradient" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-body-secondary">11444MB/16384MB</div>
        </li>
        <li class="nav-item px-3 pb-2 mb-3 d-narrow-none">
            <div class="text-uppercase small fw-bold mb-1" data-coreui-i18n="ssdUsage">SSD Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-danger-gradient" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
            </div><small class="text-body-secondary-inverse">243GB/256GB</small>
        </li>
    </ul>
</div>


{{--toggle_sidebar--}}
@include('admin.layout.nav.toggle_sidebar')
