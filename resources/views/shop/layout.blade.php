<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 31 Mar 2025 09:48:30 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <!-- Title -->
    <title>TechCom Computer</title>

    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('client/assets/favicon.png') }}">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;display=swap"
        rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('client/assets/vendor/font-awesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/assets/css/font-electro.css') }}">

    <link rel="stylesheet" href="{{ asset('client/assets/vendor/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/assets/vendor/hs-megamenu/src/hs.megamenu.css') }}">
    <link rel="stylesheet"
        href="{{ asset('client/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('client/assets/vendor/fancybox/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('client/assets/vendor/slick-carousel/slick/slick.css') }}">
    <link rel="stylesheet"
        href="{{ asset('client/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

    <!-- CSS Electro Template -->
    <link rel="stylesheet" href="{{ asset('client/assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>
    {{-- Thông báo SweetAlert2 được thiết kế lại --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Hàm hiển thị thông báo thành công với icon checkmark
        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#f0fff4', // Màu nền xanh nhạt
                iconColor: '#38a169', // Màu xanh lá đậm
                color: '#2f855a' // Màu chữ xanh đậm
            });
        }

        // Hàm hiển thị thông báo lỗi với icon chấm than
        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#fff5f5', // Màu nền đỏ nhạt
                iconColor: '#e53e3e', // Màu đỏ
                color: '#c53030' // Màu chữ đỏ đậm
            });
        }

        // Hàm hiển thị thông báo thông tin
        function showInfoAlert(message) {
            Swal.fire({
                icon: 'info',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#ebf8ff', // Màu nền xanh dương nhạt
                iconColor: '#3182ce', // Màu xanh dương
                color: '#2c5282' // Màu chữ xanh đậm
            });
        }

        // Xử lý thông báo từ session
        @if (session('success'))
            showSuccessAlert('{{ session('success') }}');
        @endif

        @if (session('error'))
            showErrorAlert('{{ session('error') }}');
        @endif
    </script>

    <!-- ========== HEADER ========== -->
    @include('shop.blocks.header')

    <!-- ========== END HEADER ========== -->
    @include('client.blocks.slider')

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main">
        <!-- breadcrumb -->
        <div class="bg-gray-13 bg-md-transparent">
            <div class="container">
                <!-- breadcrumb -->
                <div class="my-md-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                            <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a
                                    href="https://transvelo.github.io/electro-html/2.0/html/home/index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Shop
                            </li>
                        </ol>
                    </nav>
                </div>
                <!-- End breadcrumb -->
            </div>
        </div>
        <!-- End breadcrumb -->

        <div class="container">
            <div class="row mb-8">
                @include('shop.blocks.sidebar', [
                    'categories' => $categories ?? [],
                    'products' => $onSaleProducts ?? [],
                    'topSalesProducts' => $topSalesProducts ?? [],
                ])

                @yield('main')
            </div>
        </div>
    </main>
    @include('client.blocks.footer')

    <!-- JS Global Compulsory -->
    <script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
    <script src="../../assets/vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/vendor/bootstrap/bootstrap.min.js"></script>

    <!-- JS Implementing Plugins -->
    <script src="../../assets/vendor/appear.js"></script>
    <script src="../../assets/vendor/jquery.countdown.min.js"></script>
    <script src="../../assets/vendor/hs-megamenu/src/hs.megamenu.js"></script>
    <script src="../../assets/vendor/svg-injector/dist/svg-injector.min.js"></script>
    <script src="../../assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../../assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="../../assets/vendor/fancybox/jquery.fancybox.min.js"></script>
    <script src="../../assets/vendor/typed.js/lib/typed.min.js"></script>
    <script src="../../assets/vendor/slick-carousel/slick/slick.js"></script>
    <script src="../../assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

    <!-- JS Electro -->
    <script src="../../assets/js/hs.core.js"></script>
    <script src="../../assets/js/components/hs.countdown.js"></script>
    <script src="../../assets/js/components/hs.header.js"></script>
    <script src="../../assets/js/components/hs.hamburgers.js"></script>
    <script src="../../assets/js/components/hs.unfold.js"></script>
    <script src="../../assets/js/components/hs.focus-state.js"></script>
    <script src="../../assets/js/components/hs.malihu-scrollbar.js"></script>
    <script src="../../assets/js/components/hs.validation.js"></script>
    <script src="../../assets/js/components/hs.fancybox.js"></script>
    <script src="../../assets/js/components/hs.onscroll-animation.js"></script>
    <script src="../../assets/js/components/hs.slick-carousel.js"></script>
    <script src="../../assets/js/components/hs.show-animation.js"></script>
    <script src="../../assets/js/components/hs.svg-injector.js"></script>
    <script src="../../assets/js/components/hs.go-to.js"></script>
    <script src="../../assets/js/components/hs.selectpicker.js"></script>

    <!-- JS Plugins Init. -->
    <script>
        $(window).on('load', function() {
            // initialization of HSMegaMenu component
            $('.js-mega-menu').HSMegaMenu({
                event: 'hover',
                direction: 'horizontal',
                pageContainer: $('.container'),
                breakpoint: 767.98,
                hideTimeOut: 0
            });
        });

        $(document).on('ready', function() {
            // initialization of header
            $.HSCore.components.HSHeader.init($('#header'));

            // initialization of animation
            $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');

            // initialization of unfold component
            $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                afterOpen: function() {
                    $(this).find('input[type="search"]').focus();
                }
            });

            // initialization of popups
            $.HSCore.components.HSFancyBox.init('.js-fancybox');

            // initialization of countdowns
            var countdowns = $.HSCore.components.HSCountdown.init('.js-countdown', {
                yearsElSelector: '.js-cd-years',
                monthsElSelector: '.js-cd-months',
                daysElSelector: '.js-cd-days',
                hoursElSelector: '.js-cd-hours',
                minutesElSelector: '.js-cd-minutes',
                secondsElSelector: '.js-cd-seconds'
            });

            // initialization of malihu scrollbar
            $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

            // initialization of forms
            $.HSCore.components.HSFocusState.init();

            // initialization of form validation
            $.HSCore.components.HSValidation.init('.js-validate', {
                rules: {
                    confirmPassword: {
                        equalTo: '#signupPassword'
                    }
                }
            });

            // initialization of show animations
            $.HSCore.components.HSShowAnimation.init('.js-animation-link');

            // initialization of fancybox
            $.HSCore.components.HSFancyBox.init('.js-fancybox');

            // initialization of slick carousel
            $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

            // initialization of go to
            $.HSCore.components.HSGoTo.init('.js-go-to');

            // initialization of hamburgers
            $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

            // initialization of unfold component
            $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                beforeClose: function() {
                    $('#hamburgerTrigger').removeClass('is-active');
                },
                afterClose: function() {
                    $('#headerSidebarList .collapse.show').collapse('hide');
                }
            });

            $('#headerSidebarList [data-toggle="collapse"]').on('click', function(e) {
                e.preventDefault();

                var target = $(this).data('target');

                if ($(this).attr('aria-expanded') === "true") {
                    $(target).collapse('hide');
                } else {
                    $(target).collapse('show');
                }
            });

            // initialization of unfold component
            $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

            // initialization of select picker
            $.HSCore.components.HSSelectPicker.init('.js-select');
        });
    </script>
</body>

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 31 Mar 2025 09:49:00 GMT -->

</html>
