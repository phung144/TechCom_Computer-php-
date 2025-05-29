<header class="main-header" id="header">
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>

        <span class="page-title">Bảng điều khiển</span>

        <div class="navbar-right ">

            <!-- search form -->
            <div class="search-form">


            </div>

            <ul class="nav navbar-nav">

                <!-- User Account -->
                <li class="dropdown user-menu">
                    <button class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : asset('images/user/user-xs-01.jpg') }}" class="user-image rounded-circle" alt="User Image" />
                        <span class="d-none d-lg-inline-block">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">

                        <li class="dropdown-footer">
                            <a class="dropdown-link-item" href="{{route('logout.admin')}}"> <i class="mdi mdi-logout"></i> Log Out
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>


</header>
