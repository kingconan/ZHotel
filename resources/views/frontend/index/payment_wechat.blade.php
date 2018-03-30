<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ZHotel</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="keywords" content="{{\App\Http\Controllers\Backend\Constant::meta_keywords}}" >
    <meta name="description" content="{{\App\Http\Controllers\Backend\Constant::meta_description}}" >
    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>
<body style="background-color: whitesmoke;padding: 0;margin: 0;">
    <div style="max-width: 800px;margin-right: auto;margin-left: auto;padding: 30px;text-align: center;background-color: white;height: 100%">
        <h1>Zhotel 微信支付</h1>
        <div style="font-size: 16px;line-height: 32px">
            <div>{{$title or "--"}}</div>
            <div>{{$des or "--"}}</div>
        </div>
        <div style="height: 15px"></div>
        <div style="padding: 15px;border: 1px solid #CCCCCC">
            <img style="float:left" src="{{asset("images/WepayLogo.png")}}" width="100px"/>
            <div style="float:right;font-size: 16px;margin-top: 6px">支付
                <span style="color: lightsalmon;font-weight: bolder">{{$price or '-'}}</span> 元</div>
            <div style="clear: both"></div>
        </div>
        <div>
            {!! QrCode::size(280)->margin(2)->generate($url) !!}
        </div>
        <img src="{{asset("images/wepay_des.png")}}" width="220px"/>
        <div style="clear: both"></div>
    </div>
    <div style="max-width: 800px;margin-right: auto;margin-left: auto;background-color: black;padding: 30px;">
        <div style="color: #6c6c6c;text-align: center;font-size: 12px">
            <span style="font-weight: bolder">Copyright &copy; {{date("Y")}} Zhotel.</span> All rights reserved.
        </div>
    </div>
</body>
</html>