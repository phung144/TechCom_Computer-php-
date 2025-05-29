<aside class="left-sidebar sidebar-dark" id="left-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="">
                <img src="{{ asset('client/assets/logo.png') }}" height="70%" alt="Mono">
                {{-- <span class="brand-name">TechCom</span> --}}
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

                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.home') }}">
                        <i class="mdi mdi-cube"></i>
                        <span class="nav-text">Thống kê</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.users.index') }}">
                          <i class="mdi mdi-account"></i>
                        <span class="nav-text">Tài khoản</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.products.index') }}">
                        <i class="mdi mdi-cube"></i>
                        <span class="nav-text">Sản phẩm</span>
                    </a>
                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.categories.index') }}">
                        <i class="mdi mdi-cube-outline"></i>
                        <span class="nav-text">Danh mục</span>
                    </a>
                </li>





                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.orders.index') }}">
                        <i class="mdi mdi-receipt"></i>
                        <span class="nav-text">Đơn hàng</span>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.comments.index') }}">
                        <i class="mdi mdi-comment"></i>
                        <span class="nav-text">Bình luận</span>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{route('admin.variants.index')}}">
                        <i class="mdi mdi-tag-multiple"></i>
                        <span class="nav-text">Biến thể</span></b>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{route('admin.vouchers.index')}}">
                        <i class="mdi mdi-ticket"></i>
                        <span class="nav-text">Mã giảm giá</span></b>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{route('admin.banners.index')}}">
                        <i class="mdi mdi-image"></i>
                        <span class="nav-text">Banner</span></b>
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
