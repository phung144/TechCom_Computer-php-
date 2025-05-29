<header id="header" class="u-header u-header-left-aligned-nav">
    <div class="u-header__section">
        <!-- Topbar -->
        <div class="u-header-topbar py-2 d-none d-xl-block">
            <div class="container">
                <div class="d-flex align-items-center">
                    <div class="topbar-left">
                        <a href="#" class="text-gray-110 font-size-13 u-header-topbar__nav-link">Chào mừng bạn đến với Cửa hàng Máy tính TechCom</a>
                    </div>
                    <div class="topbar-right ml-auto">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item mr-0 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                                <!-- Account Sidebar Toggle Button -->
                                <a id="sidebarNavToggler" href="javascript:;" role="button" class="u-header-topbar__nav-link"
                                    aria-controls="sidebarContent"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    data-unfold-event="click"
                                    data-unfold-hide-on-scroll="false"
                                    data-unfold-target="#sidebarContent"
                                    data-unfold-type="css-animation"
                                    data-unfold-animation-in="fadeInRight"
                                    data-unfold-animation-out="fadeOutRight"
                                    data-unfold-duration="500">
                                    @if(auth()->check())
                                    <i class="ec ec-user mr-1"></i>
                                    <a href="{{ route('profile.show') }}" class="font-weight-bold text-primary">{{ auth()->user()->name }}</a>
                                    @else
                                    <i class="ec ec-user mr-1"></i>
                                    <a href="{{ route('register') }}">Đăng ký</a>
                                    <span class="text-gray-50">or</span>
                                    <a href="{{ route('login') }}">Đăng nhập</a>
                                    @endif
                                </a>
                                <!-- End Account Sidebar Toggle Button -->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->

        <!-- Logo-Search-header-icons -->
        <div class="py-2 py-xl-5 bg-primary-down-lg">
            <div class="container my-0dot5 my-xl-0">
                <div class="row align-items-center">
                    <!-- Logo-offcanvas-menu -->
                    <div class="col-auto">
                        <!-- Nav -->
                        <nav class="navbar navbar-expand u-header__navbar py-0 justify-content-xl-between max-width-270 min-width-270">
                            <!-- Logo -->
                            <a href="{{ route('client-home') }}"><img src="{{ asset('client/assets/logo.png') }}" alt="" class="header-logo" style="max-height: 80px; margin-left: 12px;"></a>
                            <!-- End Logo -->

                            <!-- Fullscreen Toggle Button -->
                            <button id="sidebarHeaderInvokerMenu" type="button" class="navbar-toggler d-block btn u-hamburger mr-3 mr-xl-0"
                                aria-controls="sidebarHeader"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-unfold-event="click"
                                data-unfold-hide-on-scroll="false"
                                data-unfold-target="#sidebarHeader1"
                                data-unfold-type="css-animation"
                                data-unfold-animation-in="fadeInLeft"
                                data-unfold-animation-out="fadeOutLeft"
                                data-unfold-duration="500">
                                <span id="hamburgerTriggerMenu" class="u-hamburger__box">
                                    <span class="u-hamburger__inner"></span>
                                </span>
                            </button>
                            <!-- End Fullscreen Toggle Button -->
                        </nav>
                        <!-- End Nav -->
                    </div>
                    <!-- End Logo-offcanvas-menu -->
                    <!-- Search Bar -->
                    <div class="col d-none d-xl-block">
                        <form class="js-focus-state" method="GET" action="{{ route('shop.search') }}">
                            <label class="sr-only" for="searchproduct">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control py-2 pl-5 font-size-15 border-right-0 height-40 border-width-2 rounded-left-pill border-primary" name="query" id="searchproduct-item" placeholder="Search for Products" aria-label="Search for Products" aria-describedby="searchProduct1" required>
                                <div class="input-group-append">
                                    <!-- End Select -->
                                    <button class="btn btn-primary height-40 py-2 px-3 rounded-right-pill" type="submit" id="searchProduct1">
                                        <span class="ec ec-search font-size-24"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Search Bar -->
                    <!-- Header Icons -->
                    <div class="col col-xl-auto text-right text-xl-left pl-0 pl-xl-3 position-static">
                        <div class="d-inline-flex">
                            <ul class="d-flex list-unstyled mb-0 align-items-center">
                                <!-- Search -->
                                <li class="col d-xl-none px-2 px-sm-3 position-static">
                                    <a id="searchClassicInvoker" class="font-size-22 text-gray-90 text-lh-1 btn-text-secondary" href="javascript:;" role="button"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Search"
                                        aria-controls="searchClassic"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        data-unfold-target="#searchClassic"
                                        data-unfold-type="css-animation"
                                        data-unfold-duration="300"
                                        data-unfold-delay="300"
                                        data-unfold-hide-on-scroll="true"
                                        data-unfold-animation-in="slideInUp"
                                        data-unfold-animation-out="fadeOut">
                                        <span class="ec ec-search"></span>
                                    </a>

                                    <!-- Input -->
                                    <div id="searchClassic" class="dropdown-menu dropdown-unfold dropdown-menu-right left-0 mx-2" aria-labelledby="searchClassicInvoker">
                                        <form class="js-focus-state input-group px-3">
                                            <input class="form-control" type="search" placeholder="Search Product">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary px-3" type="button"><i class="font-size-18 ec ec-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End Input -->
                                </li>
                                <!-- End Search -->

                                <li class="col d-none d-xl-block">
                                    <a href="@auth {{ route('wishlist.index') }} @else {{ route('login') }} @endauth"
                                           class="text-gray-90 position-relative d-flex"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="Wishlist">
                                            <i class="fas fa-heart font-size-22"></i> <!-- icon mới -->
                                            <span class="bg-lg-down-black width-22 height-22 bg-primary position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12">
                                                @auth
                                                    {{ \App\Models\Wishlist::where('user_id', auth()->id())->count() }}
                                                @else
                                                    0
                                                @endauth
                                            </span>
                                        </a>
                                    </li>
                                <li class="col d-none d-xl-block">
                                    <a href="@auth {{ route('orders.index') }} @else {{ route('login') }} @endauth"
                                       class="text-gray-90 position-relative d-flex"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Orders">
                                        <i class="fas fa-receipt font-size-22"></i> <!-- icon mới -->
                                        <span class="bg-lg-down-black width-22 height-22 bg-primary position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12">
                                            @auth
                                                {{ \App\Models\Order::where('user_id', auth()->id())->count() }}
                                            @else
                                                0
                                            @endauth
                                        </span>
                                    </a>
                                </li>

                                {{-- <li class="col d-none d-xl-block"><a href="" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Favorites"><i class="font-size-22 e#c ec-favorites"></i></a></li>
                                <li class="col d-xl-none px-2 px-sm-3"><a href="https://transvelo.github.io/electro-html/2.0/html/shop/my-account.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="My Account"><i class="font-size-22 ec ec-user"></i></a></li> --}}
                                <li class="col pr-xl-0 px-2 px-sm-3">
                                    <a href="@auth {{ route('cart.index') }} @else {{ route('login') }} @endauth"
                                       class="text-gray-90 position-relative d-flex"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Cart">
                                        <i class="font-size-22 ec ec-shopping-bag"></i>
                                        <span class="bg-lg-down-black width-22 height-22 bg-primary position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12">
                                            @auth
                                                {{ \App\Models\Cart::where('user_id', auth()->id())->count() }}
                                            @else
                                                0
                                            @endauth
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Header Icons -->
                </div>
            </div>
        </div>
        <!-- End Logo-Search-header-icons -->

        <!-- Primary-menu-wide -->
        <div class="d-none d-xl-block bg-primary">
            <div class="container">
                <div class="min-height-45">
                    <!-- Nav -->
                    <nav class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--wide u-header__navbar--no-space">
                        <!-- Navigation -->
                        <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                            <ul class="navbar-nav u-header__navbar-nav justify-content-center">
                                <!-- Home -->
                                <li class="nav-item hs-has-mega-menu u-header__nav-item"
                                    data-event="hover"
                                    data-animation-in="slideInUp"
                                    data-animation-out="fadeOut"
                                    data-position="left">
                                    <a id="homeMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle" href="{{route('client-home')}}" aria-haspopup="true" aria-expanded="false">Trang chủ</a>
                                </li>
                                <!-- End Home -->

                                <!-- TV & Audio -->
                                <li class="nav-item hs-has-mega-menu u-header__nav-item"
                                    data-event="hover"
                                    data-animation-in="slideInUp"
                                    data-animation-out="fadeOut">
                                    <a id="TVMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle" href="{{route('shop-home')}}" aria-haspopup="true" aria-expanded="false">Cửa hàng</a>

                                    <!-- End TV & Audio - Mega Menu -->
                                </li>
                            </ul>
                        </div>
                        <!-- End Navigation -->
                    </nav>
                    <!-- End Nav -->
                </div>
            </div>
        </div>
        <!-- End Primary-menu-wide -->
    </div>
</header>
