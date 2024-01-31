<nav class="navbar nav-wrapper p-2 bg-light">
    <div class="container-fluid">
        <button id="nav-toggler" data-mdb-ripple-color="dark" class="btn bg-white shadow d-lg-none d-sm-block"
            type="button" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-item me-4 d-lg-block d-none" data-date-time>Sat, 06 Jan 2024 16:12:54 GMT</div>
        <form action="" method="get" class="py-2 m-0 d-none d-md-block">
            <input class="form-control" type="search" name="q" placeholder="Search..." aria-label="Search" />
        </form>
        <div class="dropdown me-2">
            <a href="javascript:void(0)" class="nav-link" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ auth()->user()->fullname ?? 'username' }}
                <i class="fas fa-chevron-down"></i>
                <img src="{{ auth()->user()->photo ?? '' }}" class="user-image rounded-circle" alt="" />
            </a>
            <ul class="dropdown-menu dropdown-menu-start shadow border-0">
                <li>
                    <h6 class="dropdown-header h6 text-uppercase ">username</h6>
                </li>
                <li><a class="dropdown-item" href="#">Notifications</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a type="button" class="dropdown-item" href="#logout" data-bs-toggle="modal">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
