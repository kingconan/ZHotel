<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ZHotel</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset("css/min.css")}}">
    @yield("style")

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-red  sidebar-mini">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="" class="logo">
            <span class="logo-mini"><b>Z</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Z</b>Hotel</span>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            {{--<div class="navbar-custom-menu">--}}
                {{--<div style="padding: 15px">--}}
                {{--<em style="color: #F0F0F0">{{Auth::user()->name}} :</em>--}}
                {{--<span style="cursor: pointer;color: darkred"--}}
                      {{--onclick="logout()">Sign out</span>--}}
                {{--</div>--}}
            {{--</div>--}}
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Menu List</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="active"><a href="hotel_list"><i class="fa fa-list"></i> <span>酒店</span></a></li>
                {{--<li><a href="#"><i class="fa fa-plus"></i> <span>Menu 2</span></a></li>--}}
                {{--<li class="treeview">--}}
                    {{--<a href="#"><i class="fa fa-safari"></i> <span>Menu 3</span>--}}
            {{--<span class="pull-right-container">--}}
                {{--<i class="fa fa-angle-left pull-right"></i>--}}
              {{--</span>--}}
                    {{--</a>--}}
                    {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="#">Sub-Menu-1</a></li>--}}
                        {{--<li><a href="#">Sub-Menu-2</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @if(isset($page_header))
        <section class="content-header">
            <h1>
                {{$page_header or "Page Header"}}
                <small>{{$page_description or ""}}</small>
            </h1>
            <ol class="breadcrumb">
                {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
                {{--<li class="active">Here</li>--}}
            </ol>
        </section>
        @endif
        <!-- Main content -->
        <section class="content container-fluid">
            @yield("content")
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Good to Know
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{date("Y")}} <a href="#">ZHotel</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- AdminLTE App -->
<script src="{{asset('js/min.js')}}"></script>
@yield("script")
<script>
    function logout(){
        $.ajax({
            url:"/ss/logout",
            type:'post',
            data:{},
            cache:false,
            dataType:'json',
            error:function(){

            },
            success:function(data){
                console.log(data);
                window.location = "/zhotel/ss/login";
            }
        });
    }
</script>
</body>
</html>