@extends("backend.layout.base_empty")
@section('style')
<style>
    .form1{
        width: 500px;margin-right: auto;margin-left: auto;
        padding: 15px;
        -webkit-box-shadow: 0px 0px 16px -1px rgba(0,0,0,0.55);
        -moz-box-shadow: 0px 0px 16px -1px rgba(0,0,0,0.55);
        box-shadow: 0px 0px 16px -1px rgba(0,0,0,0.55);
    }
    .btn_book{
        font-size: 18px;
        color: white;
        background-color: #c29c76;
        border-radius: 0;
        width: 120px;
    }
    .btn_book:hover{
        color:white;
    }
</style>
@endsection
@section('content')
    <div style="text-align: center;margin-top: 100px;margin-bottom: 30px;">
        <h1>Z<span style="color: lightgrey">Hotel</span></h1>
    </div>
    <div class="form1">
        <form action="{{URL::to('/ss/register')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
            <div class="form-group has-feedback">
                <input class="form-control" placeholder="Name" name="name" required>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="row">
                <div class="col-xs-8">
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn_book">Register</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script>

</script>
@endsection