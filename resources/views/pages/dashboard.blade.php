@extends('app.index')
@section('content')
    <div>
        <h6 class="h3 text-uppercase">dashboard</h6>
    </div>
    <div class="row">
        <h6 class="h6 text-lead mt-2">Orders Statistics</h6>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Today Orders">
                <div class="card-statistic-3 p-4">
                    {{-- <div class="card-icon card-icon-large"><i class=""></i></div> --}}
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Today Orders</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $today_orders }}
                            </h2>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('orders.customers', ['date' => 'today']) }}" class="btn btn-light"
                                title="Go to">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Total Orders">
                <div class="card-statistic-3 p-4">
                    {{-- <div class="card-icon card-icon-large"><i class=""></i></div> --}}
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Orders</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $orders }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="{{ route('orders.customers') }}" class="btn btn-light"
                                    title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Total Suppliers">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Suppliers</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">

                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="#" class="btn btn-light" title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Total Workers">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Workers Van</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">

                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="#" class="btn btn-light" title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="row">
        <h6 class="h6 text-lead mt-2">Products Statistics</h6>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Total Products In stock">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Products</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $products }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-light" title="Go to">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Products out of stock">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 text-truncate ">Products Out-of-Stock</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $out_of_stock }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="{{ route('products.index', ['query' => 'out-of-stock']) }}" class="btn btn-light"
                                    title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Products with low stock">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 text-truncate ">Products Low-Stock</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $low_stock }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="{{ route('products.index', ['query' => 'low-stock']) }}" class="btn btn-light" title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title='Expired Products'>
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Products Expired</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $expired_products }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="{{ route('products.index', ['query' => 'expired']) }}" class="btn btn-light" title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h6 class="h6 text-lead mt-2">Other Statistics</h6>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Total Workers">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Workers</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $workers }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <a href="{{ route('customers.index') }}" class="btn btn-light" title="Go to">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Total Suppliers">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 text-truncate ">Total Suppliers</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $suppliers }}
                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="{{ route('suppliers.index') }}" class="btn btn-light"
                                    title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title="Products with low stock">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 text-truncate ">Products Low-Stock</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">

                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="#" class="btn btn-light" title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 mb-2" title='Expired Products'>
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Products Expired</h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="d-flex align-items-center mb-0">

                            </h2>
                        </div>
                        <div class="col-6 d text-end">
                            <span><a href="#" class="btn btn-light" title="Go to">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
