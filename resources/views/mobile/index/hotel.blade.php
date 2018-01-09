@extends("frontend.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>

    <style>
        [v-cloak] {
            display: none;
        }
    </style>

@endsection
@section('content')

<div style="width: 100%;">
</div>

@endsection
@section('script')
<script src="{{asset('js/libs/zhotel_lib.js')}}"></script>
<script src="{{asset('js/libs/moment.min.js')}}"></script>

<script>
    function editAddress()
    {

    }
    window.onload = function(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', editAddress, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', editAddress);
                document.attachEvent('onWeixinJSBridgeReady', editAddress);
            }
        }else{
            editAddress();
        }
    }
</script>
<script>
    function _jsApiCall(){
        var config_str = '{{$config}}';
        var config = JSON.parse(config_str);

        WeixinJSBridge.invoke(
                'getBrandWCPayRequest',config,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
        );
    }
    function callLocalWechatPay(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', _jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', _jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', _jsApiCall);
            }
        }else{
            _jsApiCall();
        }
    }

</script>
@endsection