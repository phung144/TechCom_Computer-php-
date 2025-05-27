<aside class="left-sidebar sidebar-dark" id="left-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="">
                <img src="{{ asset('dist/assets/images/logo.png') }}" alt="Mono">
                <span class="brand-name">MONO</span>
            </a>
        </div>
        <!-- begin sidebar scrollbar -->
        <div class="sidebar-left" data-simplebar style="height: 100%;">
            <!-- sidebar menu -->
            <ul class="nav sidebar-inner" id="sidebar-menu">



                {{-- <li class="active">
                    <a class="sidenav-item-link" href="index.html">
                        <i class="mdi mdi-briefcase-account-outline"></i>
                        <span class="nav-text">Business Dashboard</span>
                    </a>
                </li>





                <li>
                    <a class="sidenav-item-link" href="analytics.html">
                        <i class="mdi mdi-chart-line"></i>
                        <span class="nav-text">Analytics Dashboard</span>
                    </a>
                </li> --}}





                <li class="section-title">
                    Apps
                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.home') }}">
                        <i class="mdi mdi-cube"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.users.index') }}">
                          <i class="mdi mdi-account"></i>
                        <span class="nav-text">User</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.products.index') }}">
                        <i class="mdi mdi-cube"></i>
                        <span class="nav-text">Product</span>
                    </a>
                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.categories.index') }}">
                        <i class="mdi mdi-cube-outline"></i>
                        <span class="nav-text">Category</span>
                    </a>
                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.carts.index') }}">
                        <i class="mdi mdi-cart"></i>
                        <span class="nav-text">Cart</span>
                    </a>
                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.orders.index') }}">
                        <i class="mdi mdi-receipt"></i>
                        <span class="nav-text">Order</span>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.comments.index') }}">
                        <i class="mdi mdi-receipt"></i>
                        <span class="nav-text">Comment</span>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{route('admin.variants.index')}}">
                        <i class="mdi mdi-tag-multiple"></i>
                        <span class="nav-text">Variant</span></b>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{route('admin.vouchers.index')}}">
                        <i class="mdi mdi-tag-multiple"></i>
                        <span class="nav-text">Voucher</span></b>
                    </a>
                </li>


















            </ul>

        </div>

        <div class="sidebar-footer">
            <div class="sidebar-footer-content">
                <ul class="d-flex">
                    <li>
                        <a href="user-account-settings.html" data-toggle="tooltip" title="Profile settings"><i
                                class="mdi mdi-settings"></i></a>
                    </li>
                    <li>
                        <a href="#" data-toggle="tooltip" title="No chat messages"><i
                                class="mdi mdi-chat-processing"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>
