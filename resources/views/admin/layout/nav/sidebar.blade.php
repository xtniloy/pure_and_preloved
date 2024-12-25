<div class="sidebar sidebar-fixed sidebar-dark bg-dark-gradient border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <svg class="sidebar-brand-full" width="110" height="32" alt="CoreUI Logo">
                <use xlink:href="{{ asset('assets/brand/logoui.svg#full') }}"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo">
                <use xlink:href="{{ asset('assets/brand/logoui.svg#signet') }}"></use>
            </svg>
        </div>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item"><a class="nav-link" href="index.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg><span data-coreui-i18n="dashboard">Dashboard</span></a></li>
        <li class="nav-title" data-coreui-i18n="theme">Theme</li>
        <li class="nav-item"><a class="nav-link" href="colors.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-drop') }}"></use>
                </svg><span data-coreui-i18n="colors">Colors</span></a></li>
        <li class="nav-item"><a class="nav-link" href="typography.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                </svg><span data-coreui-i18n="typography">Typography</span></a></li>
        <li class="nav-title" data-coreui-i18n="components">Components</li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
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




<div class="sidebar sidebar-light sidebar-lg sidebar-end sidebar-overlaid border-start" id="aside">
    <div class="sidebar-header p-0 position-relative">
        <ul class="nav nav-underline-border w-100" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-coreui-toggle="tab" href="#timeline" role="tab">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                    </svg></a></li>
            <li class="nav-item"><a class="nav-link" data-coreui-toggle="tab" href="#messages" role="tab">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-speech') }}"></use>
                    </svg></a></li>
            <li class="nav-item"><a class="nav-link" data-coreui-toggle="tab" href="#settings" role="tab">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-settings') }}"></use>
                    </svg></a></li>
        </ul>
        <button class="btn-close position-absolute top-50 end-0 translate-middle my-0" type="button" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#aside&quot;)).toggle()"></button>
    </div>
    <!-- Tab panes-->
    <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
            <div class="list-group list-group-flush">
                <div class="list-group-item border-start-4 border-start-secondary bg-body-tertiary text-center fw-bold text-body-secondary text-uppercase small" data-coreui-i18n="today">Today</div>
                <div class="list-group-item border-start-4 border-start-warning list-group-item-divider">
                    <div class="avatar avatar-lg float-end"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"></div>
                    <div>Meeting with <strong>Lucas</strong></div><small class="text-body-secondary me-3">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                        </svg> 1 - 3pm</small><small class="text-body-secondary">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-location-pin') }}"></use>
                        </svg> Palo Alto, CA</small>
                </div>
                <div class="list-group-item border-start-4 border-start-info">
                    <div class="avatar avatar-lg float-end"><img class="avatar-img" src="{{ asset('assets/img/avatars/4.jpg')}}" alt="user@email.com"></div>
                    <div>Skype with <strong>Megan</strong></div><small class="text-body-secondary me-3">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                        </svg> 4 - 5pm</small><small class="text-body-secondary">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/brand.svg#cib-skype') }}"></use>
                        </svg> On-line</small>
                </div>
                <div class="list-group-item border-start-4 border-start-secondary bg-body-tertiary text-center fw-bold text-body-secondary text-uppercase small" data-coreui-i18n="tomorrow">Tomorrow</div>
                <div class="list-group-item border-start-4 border-start-danger list-group-item-divider">
                    <div>New UI Project - <strong>deadline</strong></div><small class="text-body-secondary me-3">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                        </svg> 10 - 11pm</small><small class="text-body-secondary">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-home') }}"></use>
                        </svg> creativeLabs HQ</small>
                    <div class="avatars-stack mt-2">
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/2.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/3.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/4.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/5.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/6.jpg')}}" alt="user@email.com"></div>
                    </div>
                </div>
                <div class="list-group-item border-start-4 border-start-success list-group-item-divider">
                    <div><strong>#10 Startups.Garden</strong> Meetup</div><small class="text-body-secondary me-3">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                        </svg> 1 - 3pm</small><small class="text-body-secondary">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-location-pin') }}"></use>
                        </svg> Palo Alto, CA</small>
                </div>
                <div class="list-group-item border-start-4 border-start-primary list-group-item-divider">
                    <div><strong>Team meeting</strong></div><small class="text-body-secondary me-3">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                        </svg> 4 - 6pm</small><small class="text-body-secondary">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-home') }}"></use>
                        </svg> creativeLabs HQ</small>
                    <div class="avatars-stack mt-2">
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/2.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/3.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/4.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/5.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/6.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"></div>
                        <div class="avatar avatar-xs"><img class="avatar-img" src="{{ asset('assets/img/avatars/8.jpg')}}" alt="user@email.com"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane p-3" id="messages" role="tabpanel">
            <div class="message">
                <div class="py-3 pb-5 me-3 float-start">
                    <div class="avatar"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                </div>
                <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
                <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
            <hr>
            <div class="message">
                <div class="py-3 pb-5 me-3 float-start">
                    <div class="avatar"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                </div>
                <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
                <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
            <hr>
            <div class="message">
                <div class="py-3 pb-5 me-3 float-start">
                    <div class="avatar"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                </div>
                <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
                <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
            <hr>
            <div class="message">
                <div class="py-3 pb-5 me-3 float-start">
                    <div class="avatar"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                </div>
                <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
                <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
            <hr>
            <div class="message">
                <div class="py-3 pb-5 me-3 float-start">
                    <div class="avatar"><img class="avatar-img" src="{{ asset('assets/img/avatars/7.jpg')}}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                </div>
                <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
                <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
        </div>
        <div class="tab-pane p-3" id="settings" role="tabpanel">
            <h6 data-coreui-i18n="settings">Settings</h6>
            <div class="aside-options">
                <div class="clearfix mt-4">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox" checked="">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option 1</label>
                    </div>
                </div>
                <div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option 2</label>
                    </div>
                </div>
                <div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option 3</label>
                    </div>
                </div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox" checked="">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option 4</label>
                    </div>
                </div>
            </div>
            <hr>
            <h6 data-coreui-i18n="systemUtilization">System Utilization</h6>
            <div class="small text-uppercase fw-semibold mb-1 mt-4" data-coreui-i18n="cpuUsage">CPU Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-body-secondary" data-coreui-i18n="cpuUsageDescription, { 'number_of_processes': 358, 'number_of_cores': '1/4' }">348 Processes. 1/4 Cores.</div>
            <div class="small text-uppercase fw-semibold mb-1 mt-2" data-coreui-i18n="memoryUsage">Memory Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-body-secondary">11444GB/16384MB</div>
            <div class="small text-uppercase fw-semibold mb-1 mt-2" data-coreui-i18n="ssdUsage">SSD Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-body-secondary">243GB/256GB</div>
            <div class="small text-uppercase fw-semibold mb-1 mt-2" data-coreui-i18n="ssdUsage">SSD Usage</div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-body-secondary">25GB/256GB</div>
        </div>
    </div>
</div>
