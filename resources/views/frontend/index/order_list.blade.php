@extends("frontend.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/swiper.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/iview.css')}}"/>

    <style>
        body{
            color: #3c3c3c;
            background-color: whitesmoke;
        }
        [v-cloak] {
            display: none;
        }
    </style>
    <style>
        .status_0{
            background-color:red;padding: 3px 6px;color: white;font-size: 9px;
            border-radius: 3px;
        }
        .time{
            font-size: 14px;
            font-weight: bold;
        }
        .content{
            padding-left: 5px;
        }
    </style>

@endsection
@section('content')
@if(Auth::guard('customer')->guest())
    <div style="background-color: #0a0a0a;color: lightgrey;padding: 3px;text-align: center">
        <span>guest</span>
        <a href="/login">login</a>
        <a href="/register">register</a>
    </div>
@else
    <div style="background-color: #0a0a0a;color: lightgrey;padding: 3px;text-align: center">
    <span>{{Auth::guard('customer')->user()->name}}</span>
        <a href="/logout">logout</a>
    </div>
@endif
<div style="width: 100%;">
    <div id="vue_root" v-cloak >
        <div v-if="loading">
            <div class="loading">
                <div class="loading-text">
                    <span class="loading-text-words">Z</span>
                    <span class="loading-text-words">H</span>
                    <span class="loading-text-words">O</span>
                    <span class="loading-text-words">T</span>
                    <span class="loading-text-words">E</span>
                    <span class="loading-text-words">L</span>
                </div>
            </div>
        </div>
        <div v-else >
            <h3 style="text-align: center">我的订单</h3>

            <table class="table"
                   style="width: 800px;margin-left: auto;margin-right: auto;background-color: white;font-size: 12px">
                <thead>
                    <th width="45px">#</th>
                    <th width="60px">状态</th>
                    <th width="100px">创建时间</th>
                    <th width="220px">酒店名称</th>
                    <th width="220px">房型名称</th>
                    <th>价格</th>
                    <th>操作</th>
                </thead>
                <tr v-for="(order, index) in orders">
                    <td>#<%index+1%></td>
                    <td v-html="status_str(order.status)"></td>
                    <td><%order.created_at%></td>
                    <td><%order.hotel_info.name%><br/><%order.hotel_info.name_en%></td>
                    <td><%order.room_info.name%><br/><%order.room_info.name_en%></td>
                    <td>¥ <%order.plan_info.price%></td>
                    <td><a :href="'/user/order/'+order._id">详情</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script src="{{asset('js/libs/vue-router.min.js')}}"></script>
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/zhotel_lib.js')}}"></script>
<script src="{{asset('js/libs/swiper.min.js')}}"></script>
<script src="{{asset('js/libs/iview.min.js')}}"></script>

<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "200",
        "hideDuration": "200",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $(document).ready(function(){
    });

</script>

<script>
    var vueRoot = new Vue({
        el: '#vue_root',
        delimiters: ["<%","%>"],
        components: {
        },
        data: {
            orders : [],
            user : [],
            id : "",
            loading : true

        },
        created:function () {
            console.log("created");
            this.get_data();
        },
        mounted:function(){
            window.addEventListener('resize', this.handle_resize)
            this.handle_resize(null);
        },
        beforeDestroy: function () {
            window.removeEventListener('resize', this.handle_resize)
        },
        updated:function(){
            console.log("updated");

        },
        methods:{
            get_data : function(){
                const self = this;
                axios.post('/api/order/get_user_orders',{
                        })
                        .then(function(response){
                            console.log(response.data);

                            self.orders = response.data.obj;
                            self.loading = false;
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            handle_resize : function(event){
                var height = document.documentElement.clientHeight;
                var width = document.documentElement.clientWidth;
            },
            status_str : function(status){
                if(status == 0){
                    return '<span class="status_0">未支付</span>'
                }

                return "未知状态"
            },
            view_order : function(id){
                console.log(id);
            }
        },
        computed : {

        }
    })



</script>
@endsection