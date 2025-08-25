<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid px-4">
        <button class="header-toggler d-lg-none" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <form class="d-none d-sm-flex" role="search">
            <div class="input-group"><span class="input-group-text bg-body-secondary border-0 px-1" id="search-addon">
                <svg class="icon icon-lg my-1 mx-2 text-body-secondary">
                  <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-search') }}"></use>
                </svg></span>
                <input class="form-control bg-body-secondary border-0" type="text" placeholder="Search..." aria-label="Search" aria-describedby="search-addon" data-coreui-i18n="[placeholder]search">
            </div>
        </form>
        <ul class="header-nav d-none d-md-flex ms-auto">
{{--            Notification--}}
            @include('admin.layout.nav.header._notification')

{{--            Task--}}
            @include('admin.layout.nav.header._task')

{{--            Message--}}
            @include('admin.layout.nav.header._message')

        </ul>
        <ul class="header-nav ms-auto ms-md-0">
{{--            Dark--}}
            @include('admin.layout.nav.header._dark')
{{--            User--}}
            @include('admin.layout.nav.header._user')


        </ul>

{{--        toggle_sidebar--}}
        <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#aside')).show()" style="margin-inline-end: -12px">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-applications-settings') }}"></use>
            </svg>
        </button>
    </div>
</header>
