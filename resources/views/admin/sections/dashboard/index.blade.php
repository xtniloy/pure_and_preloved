@extends('admin.layout.main')
@section('page-title')
    Dashboard
@endsection

@push('css')
    <style>
        .icon-tile {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .5rem;
        }
        .bg-brand-soft { background: rgba(15, 118, 111, .12); color: #0f766f; }
        .text-brand { color: #0f766f !important; }
        .bg-brand-gradient { background: linear-gradient(45deg, #000000, #1f2937, #0f766f); }
        .product-thumb {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: .5rem;
            background: var(--cui-tertiary-bg);
        }
        .timeline-item { position: relative; padding-left: 1.5rem; padding-bottom: 1rem; }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 4px;
            top: 4px;
            bottom: 0;
            width: 2px;
            background: var(--cui-border-color);
        }
        .timeline-dot {
            position: absolute;
            left: 0;
            top: 3px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .timeline-item:last-child:before { display: none; }
    </style>
@endpush

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <div class="fs-2 fw-semibold">Jewellery Dashboard</div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><span>Dashboard</span></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                        <svg class="icon me-1"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use></svg>
                        Last 30 days
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Today</a>
                        <a class="dropdown-item" href="#">Last 7 days</a>
                        <a class="dropdown-item active" href="#">Last 30 days</a>
                        <a class="dropdown-item" href="#">This year</a>
                    </div>
                </div>
                @if(Route::has('admin.products.create'))
                    <a href="{{ route('admin.products.create') }}" class="btn bg-brand-gradient text-white">
                        <svg class="icon me-1"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use></svg>
                        Add Product
                    </a>
                @endif
            </div>
        </div>

        {{-- KPI stat cards --}}
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-brand-gradient">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">$284,540
                                <span class="fs-6 fw-normal">(12.4%
                                    <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-arrow-top') }}"></use></svg>)
                                </span>
                            </div>
                            <div>Total Revenue</div>
                        </div>
                    </div>
                    <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="spark-revenue" height="70"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-primary-gradient">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">1,284
                                <span class="fs-6 fw-normal">(8.1%
                                    <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-arrow-top') }}"></use></svg>)
                                </span>
                            </div>
                            <div>Orders</div>
                        </div>
                    </div>
                    <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="spark-orders" height="70"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-info-gradient">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">$221.60
                                <span class="fs-6 fw-normal">(3.6%
                                    <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-arrow-top') }}"></use></svg>)
                                </span>
                            </div>
                            <div>Avg. Order Value</div>
                        </div>
                    </div>
                    <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="spark-aov" height="70"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-success-gradient">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">348
                                <span class="fs-6 fw-normal">(18.2%
                                    <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-arrow-top') }}"></use></svg>)
                                </span>
                            </div>
                            <div>New Customers</div>
                        </div>
                    </div>
                    <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="spark-customers" height="70"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live metal prices strip (template — wire to a metals API later) --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <div class="row text-center g-3 align-items-center">
                    <div class="col-auto d-none d-md-block">
                        <span class="text-body-secondary small text-uppercase fw-bold">Live Metal Prices</span>
                        <div class="small text-body-secondary">per gram · indicative</div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <span class="icon-tile bg-brand-soft"><svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-diamond') }}"></use></svg></span>
                            <div class="text-start">
                                <div class="fw-semibold">Gold 24K <span class="text-success small">+0.8%</span></div>
                                <div class="text-body-secondary small">$74.20</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <span class="icon-tile bg-secondary bg-opacity-25 text-secondary"><svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-diamond') }}"></use></svg></span>
                            <div class="text-start">
                                <div class="fw-semibold">Silver <span class="text-danger small">-0.3%</span></div>
                                <div class="text-body-secondary small">$0.92</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <span class="icon-tile bg-info bg-opacity-25 text-info"><svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-diamond') }}"></use></svg></span>
                            <div class="text-start">
                                <div class="fw-semibold">Platinum <span class="text-success small">+1.1%</span></div>
                                <div class="text-body-secondary small">$31.50</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <span class="icon-tile bg-primary bg-opacity-25 text-primary"><svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-diamond') }}"></use></svg></span>
                            <div class="text-start">
                                <div class="fw-semibold">Diamond ct. <span class="text-success small">+0.2%</span></div>
                                <div class="text-body-secondary small">$5,400</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue trend + category breakdown --}}
        <div class="row">
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="card-title fs-4 fw-semibold">Revenue & Orders</div>
                                <div class="card-subtitle text-body-secondary">Performance over the last 12 months</div>
                            </div>
                            <div class="text-end">
                                <div class="fs-5 fw-semibold text-brand">$862,000</div>
                                <div class="small text-body-secondary">Year to date</div>
                            </div>
                        </div>
                        <div class="chart-wrapper mt-4" style="height:320px;">
                            <canvas id="revenue-trend-chart" height="320"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold">Sales by Category</div>
                        <div class="card-subtitle text-body-secondary mb-3">Share of revenue</div>
                        <div class="chart-wrapper" style="height:230px;">
                            <canvas id="category-doughnut-chart" height="230"></canvas>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between border-top py-2">
                                <span><span class="badge" style="background:#0f766f">&nbsp;</span> Rings</span>
                                <span class="fw-semibold">38%</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span><span class="badge" style="background:#14a89c">&nbsp;</span> Necklaces</span>
                                <span class="fw-semibold">24%</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span><span class="badge" style="background:#5eccc3">&nbsp;</span> Earrings</span>
                                <span class="fw-semibold">16%</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span><span class="badge" style="background:#0b5c56">&nbsp;</span> Bracelets</span>
                                <span class="fw-semibold">14%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent orders + Order status / quick stats --}}
        <div class="row">
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-title fs-4 fw-semibold mb-0">Recent Orders</div>
                            @if(Route::has('admin.orders.index'))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="fw-semibold text-body-secondary">
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sampleOrders = [
                                        ['id' => '#JW-10287', 'cust' => 'Olivia Bennett', 'item' => 'Diamond Solitaire Ring', 'total' => '$2,450', 'status' => 'Paid', 'cls' => 'success'],
                                        ['id' => '#JW-10286', 'cust' => 'Liam Carter', 'item' => 'Gold Cuban Chain', 'total' => '$1,180', 'status' => 'Processing', 'cls' => 'warning'],
                                        ['id' => '#JW-10285', 'cust' => 'Sophia Reyes', 'item' => 'Pearl Drop Earrings', 'total' => '$640', 'status' => 'Shipped', 'cls' => 'info'],
                                        ['id' => '#JW-10284', 'cust' => 'Noah Khan', 'item' => 'Sapphire Tennis Bracelet', 'total' => '$3,920', 'status' => 'Paid', 'cls' => 'success'],
                                        ['id' => '#JW-10283', 'cust' => 'Emma Watson', 'item' => 'Rose Gold Wristwatch', 'total' => '$5,300', 'status' => 'Pending', 'cls' => 'secondary'],
                                        ['id' => '#JW-10282', 'cust' => 'James Park', 'item' => 'Emerald Pendant', 'total' => '$1,760', 'status' => 'Refunded', 'cls' => 'danger'],
                                    ];
                                @endphp
                                @foreach($sampleOrders as $o)
                                    <tr>
                                        <td class="fw-semibold">{{ $o['id'] }}</td>
                                        <td>{{ $o['cust'] }}</td>
                                        <td class="text-body-secondary">{{ $o['item'] }}</td>
                                        <td class="text-end fw-semibold">{{ $o['total'] }}</td>
                                        <td class="text-center"><span class="badge bg-{{ $o['cls'] }}">{{ $o['status'] }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                {{-- Order status breakdown --}}
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold">Order Status</div>
                        <div class="card-subtitle text-body-secondary mb-4">Last 30 days</div>

                        <div class="progress-group mb-3">
                            <div class="progress-group-header">
                                <span>Delivered</span>
                                <div class="ms-auto fw-semibold">612</div>
                            </div>
                            <div class="progress-group-bars">
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-success-gradient" role="progressbar" style="width: 64%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-group mb-3">
                            <div class="progress-group-header">
                                <span>Processing</span>
                                <div class="ms-auto fw-semibold">248</div>
                            </div>
                            <div class="progress-group-bars">
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-warning-gradient" role="progressbar" style="width: 26%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-group mb-3">
                            <div class="progress-group-header">
                                <span>Shipped</span>
                                <div class="ms-auto fw-semibold">186</div>
                            </div>
                            <div class="progress-group-bars">
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-info-gradient" role="progressbar" style="width: 20%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-group mb-0">
                            <div class="progress-group-header">
                                <span>Cancelled / Refund</span>
                                <div class="ms-auto fw-semibold">38</div>
                            </div>
                            <div class="progress-group-bars">
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-danger-gradient" role="progressbar" style="width: 6%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold mb-3">Quick Actions</div>
                        <div class="d-grid gap-2">
                            @if(Route::has('admin.products.create'))
                                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-secondary text-start">
                                    <svg class="icon me-2 text-brand"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use></svg> Add new product
                                </a>
                            @endif
                            @if(Route::has('admin.categories.index'))
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary text-start">
                                    <svg class="icon me-2 text-brand"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use></svg> Manage categories
                                </a>
                            @endif
                            @if(Route::has('admin.orders.index'))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary text-start">
                                    <svg class="icon me-2 text-brand"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-cart') }}"></use></svg> Review orders
                                </a>
                            @endif
                            @if(Route::has('admin.shipping_methods.index'))
                                <a href="{{ route('admin.shipping_methods.index') }}" class="btn btn-outline-secondary text-start">
                                    <svg class="icon me-2 text-brand"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-truck') }}"></use></svg> Shipping methods
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top products + Low stock + Recent activity --}}
        <div class="row">
            {{-- Top selling products --}}
            <div class="col-xl-5">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-title fs-4 fw-semibold mb-0">Top Selling Products</div>
                            @if(Route::has('admin.products.index'))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">All</a>
                            @endif
                        </div>
                        @php
                            $topProducts = [
                                ['name' => 'Diamond Solitaire Ring', 'sku' => 'RNG-2201', 'sold' => 142, 'revenue' => '$348k'],
                                ['name' => '18K Gold Cuban Chain', 'sku' => 'NCK-1180', 'sold' => 118, 'revenue' => '$139k'],
                                ['name' => 'Pearl Drop Earrings', 'sku' => 'EAR-0640', 'sold' => 96, 'revenue' => '$61k'],
                                ['name' => 'Sapphire Tennis Bracelet', 'sku' => 'BRC-3920', 'sold' => 74, 'revenue' => '$290k'],
                                ['name' => 'Rose Gold Wristwatch', 'sku' => 'WCH-5300', 'sold' => 52, 'revenue' => '$275k'],
                            ];
                        @endphp
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <tbody>
                                @foreach($topProducts as $p)
                                    <tr>
                                        <td style="width:56px">
                                            <span class="product-thumb d-inline-flex align-items-center justify-content-center text-brand">
                                                <svg class="icon icon-lg"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-diamond') }}"></use></svg>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $p['name'] }}</div>
                                            <div class="small text-body-secondary">SKU: {{ $p['sku'] }} · {{ $p['sold'] }} sold</div>
                                        </td>
                                        <td class="text-end fw-semibold text-brand">{{ $p['revenue'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Low stock / inventory alerts --}}
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold mb-1">Low Stock Alerts</div>
                        <div class="card-subtitle text-body-secondary mb-3">Items running low on inventory</div>
                        @php
                            $lowStock = [
                                ['name' => 'Platinum Wedding Band', 'left' => 2, 'cls' => 'danger'],
                                ['name' => 'Ruby Stud Earrings', 'left' => 3, 'cls' => 'danger'],
                                ['name' => 'Silver Charm Bracelet', 'left' => 5, 'cls' => 'warning'],
                                ['name' => 'Gold Hoop Earrings', 'left' => 6, 'cls' => 'warning'],
                            ];
                        @endphp
                        <ul class="list-group list-group-flush">
                            @foreach($lowStock as $s)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <svg class="icon text-{{ $s['cls'] }}"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-warning') }}"></use></svg>
                                        <span>{{ $s['name'] }}</span>
                                    </div>
                                    <span class="badge bg-{{ $s['cls'] }} rounded-pill">{{ $s['left'] }} left</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-grid mt-3">
                            @if(Route::has('admin.products.index'))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Manage inventory</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent activity timeline --}}
            <div class="col-xl-3">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold mb-3">Recent Activity</div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-success"></span>
                            <div class="small fw-semibold">New order #JW-10287</div>
                            <div class="small text-body-secondary">2 min ago</div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-info"></span>
                            <div class="small fw-semibold">Product restocked</div>
                            <div class="small text-body-secondary">25 min ago</div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning"></span>
                            <div class="small fw-semibold">Low stock: Ruby Studs</div>
                            <div class="small text-body-secondary">1 hour ago</div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-primary"></span>
                            <div class="small fw-semibold">New customer signup</div>
                            <div class="small text-body-secondary">3 hours ago</div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-danger"></span>
                            <div class="small fw-semibold">Refund processed #JW-10282</div>
                            <div class="small text-body-secondary">5 hours ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Metal mix + Top customers --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold">Units Sold by Metal</div>
                        <div class="card-subtitle text-body-secondary mb-3">Material preference breakdown</div>
                        <div class="chart-wrapper" style="height:260px;">
                            <canvas id="metal-mix-chart" height="260"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="card-title fs-4 fw-semibold mb-3">Top Customers</div>
                        @php
                            $topCustomers = [
                                ['name' => 'Olivia Bennett', 'orders' => 14, 'spent' => '$24,800', 'tier' => 'VIP', 'cls' => 'warning'],
                                ['name' => 'Noah Khan', 'orders' => 11, 'spent' => '$19,300', 'tier' => 'Gold', 'cls' => 'success'],
                                ['name' => 'Emma Watson', 'orders' => 9, 'spent' => '$15,600', 'tier' => 'Gold', 'cls' => 'success'],
                                ['name' => 'James Park', 'orders' => 7, 'spent' => '$9,200', 'tier' => 'Silver', 'cls' => 'secondary'],
                            ];
                        @endphp
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="fw-semibold text-body-secondary">
                                <tr>
                                    <th>Customer</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Spent</th>
                                    <th class="text-center">Tier</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($topCustomers as $c)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar avatar-sm bg-brand-soft text-brand fw-semibold d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;border-radius:50%;">
                                                    {{ strtoupper(substr($c['name'], 0, 1)) }}
                                                </span>
                                                <span>{{ $c['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $c['orders'] }}</td>
                                        <td class="text-end fw-semibold">{{ $c['spent'] }}</td>
                                        <td class="text-center"><span class="badge bg-{{ $c['cls'] }}">{{ $c['tier'] }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{ asset('panel/assets/js/jewelry-dashboard-chart.js') }}"></script>
@endpush
