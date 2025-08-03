<!DOCTYPE html>
<html>

<head>
    @include('admin.widgets.head')

    <style>
        ul.right-button li {
            display: inline-block;
        }
        .mb-3{
            margin-bottom: 15px;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/admin" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>TRUYỆN TRANH</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>TRUYỆN TRANH</b> AD</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                                                page and may cause design problems
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-red"></i> 5 new members joined
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-user text-red"></i> You changed your username
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ url('/') }}/system/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ Auth::guard('admin')->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{ url('/') }}/system/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                    <p>{{ Auth::guard('admin')->user()->name }} - Admin</p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();" {{ __('Logout') }} class="btn btn-default btn-flat">Đăng xuất</a>
                                    </div>
                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu admin-menu" data-widget="tree">
                    @if(isset($menuAdmins) && !empty($menuAdmins))
                        @foreach($menuAdmins as $menu)
                            <li class="{{ isset($menu['childrens']) && !empty($menu['childrens']) ? 'treeview' : '' }}" data-url="{{isset($menu['route']) && $menu['route'] != ''  ? route($menu['route']) : '#'}}">
                                <a href="{{isset($menu['route']) && $menu['route'] != ''  ? route($menu['route']) : ''}}" title="{{$menu['description']}}">
                                    <i class="fa {{isset($menu['icon']) ? $menu['icon'] : ''}}"></i> <span>{{isset($menu['name']) ? $menu['name'] : ''}}</span>
                                    @if(isset($menu['childrens']))
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                    @endif
                                </a>
                                @if(isset($menu['childrens']) && !empty($menu['childrens']))
                                <ul class="treeview-menu">
                                    @foreach($menu['childrens'] as $children)
                                    <li data-url="{{isset($children['route']) && $children['route'] !=''  ? route($children['route']) : '#'}}"><a href="{{isset($children['route']) && $children['route'] !=''  ? route($children['route']) : '#'}}" title="{{isset($children['description']) ? $children['description'] : '' }}" ><i class="fa fa {{isset($children['icon']) ? $children['icon'] : ''}}"></i>{{isset($children['name']) ? $children['name'] : ''}}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.1.1.1
            </div>
            <strong>Copyright &copy; {{date('Y')}} <a href="{{url('/')}}">Admin Hentai</a>.</strong> All rights
            reserved.
        </footer>
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- select2 -->
    <script src="{{ url('/') }}/system/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- dropzone -->
    <script src="{{ url('/') }}/system/bower_components/dropzone/dist/dropzone.js"></script>
    <script src="{{ url('/') }}/system/bower_components/toastr/toastr.min.js"></script>

    @yield('script')
</body>

<script>
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
</script>

<script>

    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 2000);

    $('#example1').DataTable();
    // active menu
    $(document).ready(function(){
        var url  = window.location.href;
        $('.sidebar-menu li').each(function(){
            $(this).find('a').each(function(){
                let href = $(this).attr('href');
                if(href === url){
                    $(this).parent().addClass('active');
                    $(this).parent().parent().parent().addClass('active');
                }
            })
        });
    });
</script>
</html>