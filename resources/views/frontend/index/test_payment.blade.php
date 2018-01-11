@extends("frontend.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>
    <style>
        .payment_ali{
            background-image: url("/images/pay_ali.png");
            background-position: 6px 50%;
            background-size: 20px;
            background-repeat: no-repeat;
            padding-left: 32px !important;
            width: 120px;
            margin: 10px;
        }
        .payment_wechat{
            background-image: url("/images/pay_wx.png");
            background-position: 6px 50%;
            background-size: 20px;
            background-repeat: no-repeat;
            padding-left: 32px !important;
            width: 120px;
            margin: 10px;
        }
    </style>
@endsection
@section('content')
    <div style="text-align: center;">
        <h1>支付测试</h1>
        <form style="width: 500px;margin-right: auto;margin-left: auto" id="form" method="post" action="/payment/test">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div >
                <input class="form-control" type="number" name="price" placeholder="price"  value="0.01"/>
            </div>
            <div style="height: 10px;width: 100%"></div>
            <div >
                <input class="form-control" type="text" name="order_id" placeholder="order id"  value="order123"/>
            </div>
            <div style="height: 10px;width: 100%"></div>
            <div >
                <input class="form-control"type="text" name="payment_id" placeholder="payment id"  value="payment456"/>
            </div>
            <div style="height: 10px;width: 100%"></div>
            <div  >
                <input class="form-control" type="text" name="title" placeholder="title"  value="支付标题"/>
            </div>
            <div style="height: 10px;width: 100%"></div>
            <div >
                <input class="form-control" type="text" name="description" placeholder="description"  value="支付描述"/>
            </div>
            <div style="height: 10px;width: 100%"></div>
            <input type="submit" class="btn btn-default payment_ali" name="action" value="支付宝" />
            <input type="submit" class="btn btn-default payment_wechat" name="action" value="微信" />
        </form>
    </div>
@endsection
@section('script')
    <script>
    </script>
@endsection