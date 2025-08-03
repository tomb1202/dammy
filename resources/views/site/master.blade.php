<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ url('/assets/css/style.css') }}" type="text/css">

    @yield('head')

    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">
    <link rel="apple-touch-icon" sizes="180x180" href=" {{ $settings['favicon'] != '' ? sourceSetting($settings['favicon']) : url('assets/img/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $settings['favicon'] != '' ? sourceSetting($settings['favicon']) : url('assets/img/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $settings['favicon'] != '' ? sourceSetting($settings['favicon']) : url('assets/img/favicon-16x16.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! $settings['google_console'] ?? null !!}
    {!! $settings['google_analytics'] ?? null !!}

    @if ($isDesktop)
        <link href="{{ url('assets/adv/desktop-adx.css') }}" rel="stylesheet" type="text/css">
    @else
        <link href="{{ url('assets/adv/mobile-adx.css') }}" rel="stylesheet" type="text/css">
    @endif

    @if (isset($headerScript))
        @foreach ($headerScript as $header)
            {!! $header->script !!}
        @endforeach
    @endif

    <style>
        .banner-row {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            margin-top: 20px;
        }

        .banner-side {
            width: 160px;
            flex-shrink: 0;
        }

        .banner-side img {
            width: 100%;
            height: auto;
            display: block;
        }

        .adv-center {
            flex: 1;
            max-width: 100%;
        }

        .main-content {
            margin-top: 20px;
        }


        .banner-row {
            width: 70%;
            margin: 0 auto;
        }

        .fixed-banner {
            position: fixed;
            top: 130px;
            z-index: 999;
            width: 120px;
        }

        .fixed-left {
            left: 8%;
        }

        .fixed-right {
            right: 8%;
        }

        .fixed-banner img {
            width: 100%;
            height: auto;
            display: block;
        }

        .banner-side img {
            margin-bottom: 5px;
        }


        #vl-header-adx {
            display: flex;
            flex-wrap: wrap;
        }

        #vl-header-adx p {
            width: 50%;
            /* mỗi hàng 2 ảnh */
            box-sizing: border-box;
        }

        @media screen and (max-width:1200px) {
            .banner-row {
                width: 100%;
                margin: 0 auto;
            }

            .banner-row {

                margin-top: 0px;
            }

            .main-content {
                margin-top: 0px;
            }

            div#vl-header-adx {
                padding: 15px;
            }

            div#vl-header-adx p {
                margin-bottom: 0;
            }

        }


        .banner-catfish-bottom img {
            width: 100%;
        }

        .banner-catfish-bottom a {
            /* width: 80%; */
        }

        .banner-catfish-bottom:nth-child(odd) img {
            width: 80%;
            display: block;
            margin-left: auto;
        }

        .banner-catfish-bottom:nth-child(even) img {
            width: 80%;
            display: block;
            margin-right: auto;
        }


        .banner-catfish-bottom {
            box-shadow: none;
        }


        .banner-preload-container>a {
            max-width: 560px;
        }

        @media screen and (max-width:720px) {
            .fixed-banner {
                display: none;
            }

            #vl-header-adx p {
                width: 100%;
                box-sizing: border-box;
            }

            #vl-header-adx {
                display: inline-flex;
                flex-wrap: wrap;
            }

            .catfish-bottom {
                text-align: center;
            }
        }
    </style>
</head>

<body class="text-ui-dark reading-manga">
    <header class="site-header">
        <div class="c-header__top">
            <ul class="search-main-menu">
                <li>
                    <form id="blog-post-search" class="ajax manga-search-form" action="{{ route('search') }}"
                        method="get">
                        <input type="text" placeholder="Tìm kiếm..." name="s" value=""
                            class="manga-search-field ui-autocomplete-input" autocomplete="off" required="">
                        <a href="javascript:void(0)" class="close-form-search"><i class="icon ion-md-close"></i></a>
                        <button type="submit"><i class="icon ion-md-search"></i></button>
                        <div class="loader-inner line-scale">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="ui-menu" style="display: none;"></div>
                    </form>
                </li>
            </ul>
            <div class="main-navigation style-1 ">
                <div class="container ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-navigation_wrap">
                                <div class="wrap_branding">
                                    <a class="logo" href="{{ url('/') }}" title="{{ $settings['title'] }}">
                                        <h1 class="m-0 h-home">
                                            <img width="159" height="30" class="img-responsive"
                                                src="{{ $settings['logo'] != '' ? sourceSetting($settings['logo']) ?? '/assets/img/logo.png' : '/assets/img/logo.png' }}"
                                                alt="{{ $settings['title'] }}">
                                        </h1>
                                    </a>
                                </div>

                                <div class="main-menu">
                                    <ul class="nav navbar-nav main-navbar">
                                        <li>
                                            <a href="{{ route('categories') }}">Thể Loại</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('completed') }}">Truyện Full</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('site.history') }}">Lịch Sử</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-toggle="modal"
                                                data-target="#form-login">Truyện theo dõi</a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="thememode">
                                    <label class="switch">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="search-navigation search-sidebar">
                                    <ul class="main-menu-search nav-menu">
                                        <li class="menu-search">
                                            <a href="javascript:;" class="open-search-main-menu">
                                                <i class="icon ion-md-search"></i>
                                                <i class="icon ion-android-close"></i> </a>
                                            <ul class="search-main-menu">
                                                <li>
                                                    <form class="manga-search-form search-form ajax"
                                                        action="{{ route('search') }}" method="get">
                                                        <input class="manga-search-field ui-autocomplete-input"
                                                            type="text" placeholder="Search..." name="s"
                                                            value="" autocomplete="off">
                                                        <input type="hidden" name="post_type" value="wp-manga"> <i
                                                            class="icon ion-ios-search"></i>
                                                        <div class="loader-inner ball-clip-rotate-multiple">
                                                            <div></div>
                                                            <div></div>
                                                        </div>
                                                        <input type="submit" value="Search">
                                                        <ul id="ui-id-2" tabindex="0"
                                                            class="ui-menu ui-widget ui-widget-content ui-autocomplete ui-front"
                                                            style="display: none;"></ul>
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="c-togle__menu">
                                    <button type="button" class="menu_icon__open">
                                        <span></span> <span></span> <span></span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mobile-menu off-canvas">
            <div class="close-nav">
                <button class="menu_icon__close">
                    <span></span> <span></span>
                </button>
            </div>

            <div class="c-modal_item">
                <!-- Button trigger modal -->
                <span class="c-modal_sign-in">
                    <a href="#" data-toggle="modal" data-target="#form-login" class="btn-active-modal">Đăng
                        Nhập</a>
                </span>

                <span class="c-modal_sign-up">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#form-sign-up"
                        class="btn-active-modal">Đăng Kí</a>
                </span>

            </div>

            <nav class="off-menu">
                <ul id="menu-main-menu-1" class="nav navbar-nav main-navbar">
                    <li>
                        <a href="{{ route('categories') }}" class="menu-link  main-menu-link"> Thể Loại </a>
                    </li>
                    <li>
                        <a href="{{ route('completed') }}" class="menu-link  main-menu-link"> Truyện Full </a>
                    </li>
                    <li>
                        <a href="{{ route('site.history') }}" class="menu-link  main-menu-link"> Lịch Sử </a>
                    </li>

                    <li>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#form-login">Truyện theo
                            dõi</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="sub-header-nav with-border">
            <div class="container">
                <div class="sub-nav_content">
                    <ul class="sub-nav_list list-inline second-menu">
                        <li>
                            <a href="{{ route('manhwa') }}">Manhwa</a>
                        </li>
                        <li>
                            <a href="{{ route('manga') }}">Manga</a>
                        </li>
                    </ul>
                </div>

                <div class="c-modal_item">
                    @guest
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#form-login"
                            class="btn-active-modal">Đăng Nhập</a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#form-sign-up"
                            class="btn-active-modal">Đăng Kí</a>
                    @endguest

                    @if (auth()->user())
                        <div class="c-user_item">
                            <span>Hi, {{ auth()->user()->name }}</span>
                            <div class="c-user_avatar">
                                <div class="c-user_avatar-image">
                                    <img alt="avatar"
                                        src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/avatar/default.png') }}"
                                        class="avatar avatar-50 photo" height="50" width="50" loading="lazy">
                                </div>
                                <ul class="c-user_menu">
                                    <li>
                                        <a href="{{ route('user.info') }}">User Settings</a>
                                    </li>
                                    <li>
                                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                        <a href="#" class="dropdown-item" role="button"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="banner-row">
        @if (isset($sidebarLeftBanners) && count($sidebarLeftBanners) > 0)
            <div class="banner-side fixed-banner banner-left fixed-left">

                @foreach ($sidebarLeftBanners as $ad)
                    @php $deskPath = route('web.adv.banner', ['path' => $ad->des_media]); @endphp
                    @if (!empty($ad->des_media))
                        <a href="{{ $ad->link }}">
                            <img src="{{ $deskPath }}" alt="{{ $ad->title }}">
                        </a>
                    @endif
                @endforeach
            </div>

        @endif
        <div class="adv-center">
            <div class="adv">
                <div id="vl-header-adx"></div>
                @if (isset($bannerTopScript))
                    <div class="banner-top-script">
                        @foreach ($bannerTopScript as $banner)
                            {!! $banner->script !!}
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="main-content">
                @yield('main')
            </div>
        </div>

        @if (isset($sidebarRightBanners) && count($sidebarRightBanners) > 0)
            <div class="banner-side fixed-banner banner-right fixed-right">

                @foreach ($sidebarRightBanners as $ad)
                    @php $deskPath = route('web.adv.banner', ['path' => $ad->des_media]); @endphp
                    @if (!empty($ad->des_media))
                        <a href="{{ $ad->link }}">
                            <img src="{{ $deskPath }}" alt="{{ $ad->title }}">
                        </a>
                    @endif
                @endforeach
            </div>

        @endif
    </div>

    <footer class="site-footer">
        <div class="bottom-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-footer">
                            <ul class="list-inline">
                                <li><a href="#">Điều Khoản</a></li>
                                <li><a href="#">Chính Sách</a></li>
                                <li><a href="#">Liên lạc</a></li>
                            </ul>
                        </div>
                        <div class="copyright">
                            <p>© {{ date('Y') }} <a href="{{ url('/') }}">Mehentai, </a> All rights
                                reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="top_footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="text_link_p pager">

                            @if (isset($textLinks) && count($textLinks) > 0)
                                @foreach ($textLinks as $textLink)
                                    <li><a title="{{ $textLink->title }}" target="_blank"
                                            href="{{ $textLink->link }}">{{ $textLink->title }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal -->
    <div class="wp-manga-section">
        <input type="hidden" name="bookmarking" value="0">
        <div class="modal fade" id="form-login" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="login" class="login">
                            <h3>
                                <a href="{{ url('/') }}" title="VipToon" tabindex="-1">Đăng Nhập</a>
                            </h3>
                            <p class="message login"></p>
                            <meta name="robots" content="max-image-preview:large">
                            <link rel="dns-prefetch" href="https://s.w.org/">

                            <form name="loginform" id="loginform" method="post">
                                <p>
                                    <label>Email * <br> <input type="text" name="log" class="input user_login"
                                            value="" size="20">
                                    </label>
                                </p>
                                <p>
                                    <label>Mật Khẩu * <br> <input type="password" autocomplete="" name="pwd"
                                            class="input user_pass" value="" size="20">
                                    </label>
                                </p>
                                <p>
                                </p>
                                <p class="forgetmenot">
                                    <label>
                                        <input name="rememberme" type="checkbox" id="rememberme" value="forever">Nhớ
                                        thông tin
                                    </label>
                                </p>
                                <input hidden id="login_url" value="{{ route('site.login') }}">
                                <input hidden id="register_url" value="{{ route('site.register') }}">
                                <input hidden id="reset_url" value="{{ route('password.email') }}">

                                <p class="submit">
                                    <input type="submit" name="wp-submit"
                                        class="button button-primary button-large wp-submit" value="Log In">
                                    <input type="hidden" name="testcookie" value="1">
                                </p>
                            </form>
                            <p class="nav">
                                <a href="javascript:void(0)" class="to-reset">Quên Mật Khẩu?</a>
                            </p>
                            <p class="backtoblog">
                                <a href="{{ url('/') }}">← Quay lại trang chủ</a>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="form-sign-up" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="sign-up" class="login">
                            <h3>
                                <a href="{{ url('/') }}" title="VipToon" tabindex="-1">Đăng Kí</a>
                            </h3>
                            <p class="message register">Đăng Kí Sử Dụng</p>
                            <form name="registerform" id="registerform" novalidate="novalidate" method="POST"
                                action="{{ route('site.register') }}">
                                @csrf

                                <p>
                                    <label>Tên Đăng Nhập * <br>
                                        <input type="text" name="user_sign-up" class="input user_login"
                                            value="" size="20">
                                    </label>
                                </p>
                                <p>
                                    <label>Email Địa Chỉ * <br>
                                        <input type="email" name="email_sign-up" class="input user_email"
                                            value="" size="20">
                                    </label>
                                </p>
                                <p>
                                    <label>Mật Khẩu *<br>
                                        <input type="password" name="pass_sign-up" autocomplete=""
                                            class="input user_pass" value="" size="25">
                                    </label>
                                </p>
                                <p>
                                </p>

                                <input type="hidden" name="redirect_to" value="">
                                <p class="submit">
                                    <input type="submit" name="wp-submit"
                                        class="button button-primary button-large wp-submit" value="Register">
                                </p>
                            </form>
                            <p class="nav">
                                <a href="javascript:void(0)" class="to-login">Đăng Nhập</a>
                                |
                                <a href="javascript:void(0)" class="to-reset">Quên Mật Khẩu?</a>
                            </p>
                            <p class="backtoblog">
                                <a href="{{ url('/') }}">← Quay lại trang chủ</a>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="form-reset" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="reset" class="login">
                            <h3>
                                <a href="javascript:void(0)" class="to-reset">Quên Mật Khẩu?</a>
                            </h3>
                            <p class="message reset">Vui lòng nhập email của bạn, bạn sẽ nhận một link qua email để cài
                                đặt lại password</p>
                            <form name="resetform" id="resetform" method="post">
                                <p>
                                    <label>Tên Đăng Nhập Hoặc Email Địa Chỉ <br>
                                        <input type="text" name="user_reset" id="user_reset" class="input"
                                            value="" size="20">
                                    </label>
                                </p>
                                <p class="submit">
                                    <input type="submit" name="wp-submit"
                                        class="button button-primary button-large wp-submit" value="Get New Password">
                                    <input type="hidden" name="testcookie" value="1">
                                </p>
                            </form>
                            <p>
                                <a class="backtoblog" href="{{ url('/') }}">← Quay lại trang chủ</a>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="form-report" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="padding: 40px 20px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="reset" class="login message">
                            <h5>
                                <p class="text-center" style="color: #000; font-size:20px">Cảm ơn bạn đã hỗ trợ báo
                                    lỗi, truyện sẽ được fix trong thời gian sớm nhất</p>
                            </h5>
                            <select class="form-control type-report" style="color: #3a3a3a">
                                <option value="-1" rel="">--Vui lòng chọn loại lỗi--</option>
                                <option value="1" rel="Ảnh tải lâu hay lỗi toàn bộ ảnh?">Ảnh lỗi, không thấy ảnh
                                </option>
                                <option value="2" rel="Trùng với chapter mấy?">Chapter bị trùng</option>
                                <option value="3" rel="Chưa dịch toàn bộ hay vài trang chưa dịch?">Chapter chưa
                                    được dịch</option>
                                <option value="4" rel="Chapter không phải truyện này?">Up nhầm truyện</option>
                                <option value="0"
                                    rel="Up thiếu ảnh, lộn xộn, quảng cáo gây khó chịu hay comment spam...?">Lỗi khác
                                </option>
                            </select>
                            <p class="submit" style="margin-top: 15px; ">
                                <input type="submit" name="wp-submit"
                                    class="button button-primary button-large wp-report wp-submit" value="send">
                                <input type="hidden" name="testcookie" value="1">
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

    </div>

    <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">

    <script>
        let base_url = "{{ url('/') }}";
        let label_button_no_more = "Hết hàng rồi bạn ơi !!!!";
        let show_more = "Xem thêm";
    </script>

    <script type="text/javascript" src="{{ url('/assets/js/all.js') }}"></script>
    <script src=" {{ url('/assets/js/sw.js') }}"></script>

    <script>
        if (!navigator.serviceWorker.controller) {
            navigator.serviceWorker.register("{{ url('assets/js/sw.js') }}").then(function(reg) {
                console.log("Service worker has been registered for scope: " + reg.scope);
            });
        }
    </script>

    @yield('script')
</body>

{{-- adx js --}}
<script type="text/javascript" src="{{ url('assets/adv/vl-header-adx.js?v=' . time()) }}"></script>

@if ($isDesktop)
    <script type="text/javascript" src="{{ url('assets/adv/vl-desktop-adx.js?v=' . time()) }}"></script>
@else
    <script type="text/javascript" src="{{ url('assets/adv/vl-mobile-adx.js?v=' . time()) }}"></script>
@endif

@if (Route::currentRouteName() == 'web.movie.view')
    <script type="text/javascript" src="{{ url('assets/adv/vl-underplayer-adx.js?v=' . time()) }}"></script>
@endif


<script>
    window.addEventListener('scroll', function() {
        const leftBanner = document.querySelector('.banner-left.fixed-left');
        const rightBanner = document.querySelector('.banner-right.fixed-right');

        const topValue = (window.scrollY > 130) ? '0px' : '130px';

        if (leftBanner) {
            leftBanner.style.top = topValue;
        }

        if (rightBanner) {
            rightBanner.style.top = topValue;
        }
    });
</script>



{{-- push js --}}
@if (isset($pushJs))
    @foreach ($pushJs as $push)
        {!! $push->script !!} <br>
    @endforeach
@endif

{{-- popup js --}}
@if (isset($popupJs))
    @foreach ($popupJs as $popup)
        {!! $popup->script !!} <br>
    @endforeach
@endif

{{-- bottom script --}}
@if (isset($bottomScript))
    @foreach ($bottomScript as $bottom)
        {!! $bottom->script !!} <br>
    @endforeach
@endif

<script>
    function setVCookie(key, value, date) {
        if (!date) {
            date = 31536000000;
        }
        var expires = new Date();
        expires.setTime(expires.getTime() + date);
        document.cookie = key + "=" + value + "; path=/; expires=" + expires.toUTCString();
    }

    function getVCookie(key) {
        var keyValue = document.cookie.match("(^|;)(?: )?" + key + "=([^;]*)(;|$)");
        return keyValue ? keyValue[2] : null;
    }
</script>

</html>
