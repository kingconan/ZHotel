@extends("frontend.layout.base_main")
@section('style')
    <style>
        [v-cloak] {
            display: none;
        }
    </style>
@endsection
@section('content')
<h1>Welcome</h1>
<?php phpinfo() ?>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script>

</script>
@endsection