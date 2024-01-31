<div class="side-wrapper text-black shadow-1 text-white overflow-y-scroll ">
    <nav class="text-black">
        <div class="container-fluid logo-content pt-3 position-sticky top-0">
            <a href="{{ route('dashboard') }}" class="nav-link  my-1">
                <span class="logo-text">
                    q-admin
                </span>
            </a>
        </div>
        <ul class="list-unstyled mx-2">
            <li class="nav-item my-1 {{ Request::segment(1) === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-chart-column"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item my-1 {{ Request::segment(1) === 'products' && !Request::segment(2) ? 'active' : '' }}">
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fa-solid fa-warehouse"></i>
                    Inventories
                </a>
            </li>
            <li
                class="nav-item my-1 {{ Request::segment(1) === 'products' && Request::segment(2) === 'create' ? 'active' : '' }}">
                <a href="{{ route('products.create') }}" class="nav-link">
                    <i class="fa-regular fa-square-plus"></i>
                    Add Product
                </a>
            </li>
            <li class="nav-item my-1 {{ Request::segment(1) === 'customers' ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-group"></i>
                    Workers Van
                </a>
            <li class="nav-item my-1 {{ Request::segment(1) === 'suppliers' ? 'active' : '' }}">
                <a href="{{ route('suppliers.index') }}" class="nav-link">
                    <i class="fa-solid fa-people-carry-box"></i>
                    Suppliers
                </a>
            </li>
            </li>
            <li class="nav-item my-1 {{ Request::segment(1) === 'create-order' ? 'open' : '' }}">
                <a href="#" class="nav-link dropdown" data-bs-toggle="collapse" data-bs-target="#create-order">
                    <span>
                        <i class="fa-solid fa-file-circle-plus"></i>
                        Create
                    </span>
                    <span class="float-right">
                        <i
                            class="fas {{ Request::segment(1) === 'create-order' ? 'fa-chevron-down' : 'fa-chevron-right' }}"></i>
                    </span>
                </a>
                <ul class="list-unstyled collapse dropdown-wrapper {{ Request::segment(1) === 'create-order' ? 'show' : '' }}"
                    id="create-order">
                    <li
                        class="nav-item ps-2 {{ Request::segment(1) === 'create-order' && Request::segment(2) === 'customer' ? 'active' : '' }}">
                        <a href="{{ route('customer.index') }}" target="_blank" class="nav-link">
                            <i class="fa fa-circle fa-sm nav-list-icon"></i>
                            Customer Order
                        </a>
                    </li>
                    <li
                        class="nav-item ps-2 {{ Request::segment(1) === 'create-order' && Request::segment(2) === 'supplier' ? 'active' : '' }}">
                        <a href="{{ route('supplier.index') }}" target="_blank" class="nav-link">
                            <i class="fa fa-circle fa-sm nav-list-icon"></i>
                            Supplier Order
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item my-1 {{ Request::segment(1) === 'orders' ? 'open' : '' }}">
                <a href="#" class="nav-link dropdown" data-bs-toggle="collapse" data-bs-target="#orders">
                    <span>
                        <i class="fa-solid fa-cart-plus"></i>
                        Orders
                    </span>
                    <span class="float-right">
                        <i
                            class="fas {{ Request::segment(1) === 'orders' ? 'fa-chevron-down' : 'fa-chevron-right' }}"></i>
                    </span>
                </a>
                <ul class="list-unstyled collapse dropdown-wrapper {{ Request::segment(1) === 'orders' ? 'show' : '' }}"
                    id="orders">
                    <li
                        class="nav-item ps-2 {{ Request::segment(1) === 'orders' && Request::segment(2) === 'customers' ? 'active' : '' }}">
                        <a href="{{ route('orders.customers') }}" class="nav-link">
                            <i class="fa fa-circle fa-sm nav-list-icon"></i>
                            Customers
                        </a>
                    </li>
                    <li
                        class="nav-item ps-2 {{ Request::segment(1) === 'orders' && Request::segment(2) === 'suppliers' ? 'active' : '' }}">
                        <a href="{{ route('orders.suppliers') }}" class="nav-link">
                            <i class="fa fa-circle fa-sm nav-list-icon"></i>
                            Suppliers
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item my-1 {{ Request::segment(1) === 'invoices' ? 'open' : '' }}">
                <a href="#" class="nav-link dropdown" data-bs-toggle="collapse" data-bs-target="#invoices">
                    <span>
                        <i class="fa-solid fa-file-invoice"></i>
                        Invoices
                    </span>
                    <span class="float-right">
                        <i
                            class="fas {{ Request::segment(1) === 'invoices' ? 'fa-chevron-down' : 'fa-chevron-right' }}"></i>
                    </span>
                </a>
                <ul class="list-unstyled collapse dropdown-wrapper {{ Request::segment(1) === 'invoices' ? 'show' : '' }}"
                    id="invoices">
                    <li
                        class="nav-item ps-2 {{ Request::segment(1) === 'invoices' && Request::segment(2) === 'customers' ? 'active' : '' }}">
                        <a href="{{ route('invoices.customers') }}" class="nav-link">
                            <i class="fa fa-circle fa-sm nav-list-icon"></i>
                            Customers
                        </a>
                    </li>
                    <li
                        class="nav-item ps-2 {{ Request::segment(1) === 'invoices' && Request::segment(2) === 'suppliers' ? 'active' : '' }}">
                        <a href="{{ route('invoices.suppliers') }}" class="nav-link">
                            <i class="fa fa-circle fa-sm nav-list-icon"></i>
                            Suppliers
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="nav-item my-1 {{ Request::segment(1) === 'order' && Request::segment(2) === 'returns' ? 'active' : '' }}">
                <a href="{{ route('order.returns') }}" class="nav-link">
                    <i class="fa-solid fa-cart-arrow-down"></i>
                    Returns
                </a>
            </li>
            <li class="nav-item my-1">
                <a href="#" class="nav-link">
                    <i class="fas fa-gear"></i>
                    Settings
                </a>
            </li>
        </ul>
    </nav>
</div>
