@extends("frontend.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>
@endsection
@section('content')
    <div style="text-align: center;">
        <h1>支付测试</h1>
        <form style="width: 200px;margin-right: auto;margin-left: auto" id="form">
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
            <a href="/ali/payment/test/123_456" class="btn btn-default">支付-支付宝</a>
            <a href="/wechat/payment/test/123_456" class="btn btn-default">支付-微信</a>
        </form>
    </div>
@endsection
@section('script')
    <script>
    </script>
@endsection