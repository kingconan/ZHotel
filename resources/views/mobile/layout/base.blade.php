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
    <meta name="keywords" content="{{\App\Http\Controllers\Backend\Constant::meta_keywords}}" >
    <meta name="description" content="{{\App\Http\Controllers\Backend\Constant::meta_description}}" >
    <link rel="stylesheet" href="{{asset("css/min_2.css")}}">
    @yield("style")

</head>
<body >
<div>
    @yield("content")
</div>
<script src="{{asset('js/min_2.js')}}"></script>
@yield("script")
</body>
</html>