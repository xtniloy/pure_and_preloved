<li class="nav-item py-1">
    <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
</li>
<li class="nav-item dropdown">
    <button class="btn btn-link nav-link" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
        <svg class="icon icon-lg theme-icon-active">
            <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-contrast') }}"></use>
        </svg>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
        <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                <svg class="icon icon-lg me-3">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-sun') }}"></use>
                </svg><span data-coreui-i18n="light">Light</span>
            </button>
        </li>
        <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                <svg class="icon icon-lg me-3">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-moon') }}"></use>
                </svg><span data-coreui-i18n="dark"> Dark</span>
            </button>
        </li>
        <li>
            <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="auto">
                <svg class="icon icon-lg me-3">
                    <use xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-contrast') }}"></use>
                </svg>Auto
            </button>
        </li>
    </ul>
</li>
