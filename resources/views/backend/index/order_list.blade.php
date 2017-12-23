@extends("backend.layout.base_main")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/jquery-confirm.min.css')}}"/>
    <style>
        [v-cloak] {
            display: none;
        }

        /*spinner*/
        .spinner {
            margin: 100px auto;
            width: 50px;
            height: 40px;
            text-align: center;
            font-size: 10px;
        }

        .spinner > div {
            background-color: #333;
            height: 100%;
            width: 6px;
            display: inline-block;

            -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
        }

        .spinner .rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }

        .spinner .rect3 {
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;
        }

        .spinner .rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }

        .spinner .rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }

        @-webkit-keyframes sk-stretchdelay {
            0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
            20% { -webkit-transform: scaleY(1.0) }
        }

        @keyframes sk-stretchdelay {
            0%, 40%, 100% {
                transform: scaleY(0.4);
                -webkit-transform: scaleY(0.4);
            }  20% {
                   transform: scaleY(1.0);
                   -webkit-transform: scaleY(1.0);
               }
        }
    </style>
    <style>
        .tr_not_online{
            /*background-color: whitesmoke;*/
        }
        .order_status{
            padding: 3px 6px;
            background-color: whitesmoke;
            border:1px solid lightgrey;
            color: #3c3c3c;
            font-size: 10px;
            border-radius: 3px;
        }
        tbody{
            color: #3c3c3c;
        }
    </style>
@endsection
@section('content')
<div style="width: 100%;clear: both">
    <div id="hotel_list">
        <div v-cloak>
            <form id="search_form" class="form">
                <div style="padding: 15px;background-color: lightgrey;width: 440px;">
                    <input name="keyword" class="form-control" style="float: left;width: 300px;" placeholder="订单ID | 酒店名字 | 手机号 " >
                    <button v-on:click="search_hotel" type="button" style="float: left;width: 80px;height: 34px;margin-left: 20px" class="btn btn-default btn-sm">搜索</button>
                    <div style="clear: both"></div>
                </div>
            </form>
            <div style="height: 30px;width: 1px"></div>
            <div v-if="loading">
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
            <div v-else class="box">
                <div style="float: left;padding: 6px;font-size: 10px">
                    找到 <span style="font-weight: bolder;color: #00a65a;font-size: 14px"><% orders.total %></span> 订单
                </div>
                <table class="table table-striped">
                    <thead>
                    <th width="60px">#</th>
                    <th width="120px">订单状态</th>
                    <th width="120px">酒店名称</th>
                    <th width="200px">预订信息</th>
                    <th width="200px">联系人</th>
                    <th width="120px">时间</th>
                    <th>操作</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <tr v-for="(order,index) in orders.data">
                        <td><%index+1%></td>
                        <td>
                            <span style="font-size: 9px;color: grey"><%order._id%></span><br/>
                            <span v-if="order.status == 0" class="order_status">未付定金</span>
                            <span v-else-if="order.status == 1">其他</span>
                        </td>
                        <td><%order.hotel_info.name%></td>
                        <td style="font-size: 10px">
                            <span>计划</span> : <%order.plan_info.name%><br/>
                            <span>人数</span> : <%order.book_info.adult+'成人'%>
                             <%order.book_info.children+'儿童'%><br/>
                            <span>日期</span> : <%order.book_info.checkin+' - ' + order.book_info.checkout%>
                        </td>
                        <td style="font-size: 10px">
                            <span>名称 : <%order.user_info.name%></span><br/>
                            <span>邮箱 : <%order.user_info.email%></span><br/>
                            <span>电话 : <%order.user_info.phone%></span><br/>
                            <span>备注 : <%order.user_info.memo%></span>
                        </td>
                        <td><span style="font-size: 10px;color: grey"><%moment.utc(order.created_at).local().format('YYYY-MM-DD HH:mm:ss')%></span></td>
                        <td>
                            <a type="button" class="btn btn-sm btn-default" :href="'/zashboard/order?id='+order._id">查看</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div style="clear: both"></div>

                <div style="float: right;padding: 15px">
                    <button :disabled="orders.prev_page_url == null" type="button" class="btn btn-default btn-sm" v-on:click="next_page(orders.prev_page_url)">prev</button>
                    <span style="font-size: 12px;"><% orders.current_page + " / " + orders.last_page %></span>
                    <button :disabled="orders.next_page_url == null" type="button" class="btn btn-default btn-sm" v-on:click="next_page(orders.next_page_url)">next</button>
                </div>
                <div style="clear: both"></div>

            </div>

        </div>
    </div>
</div>

<div style="clear: both"></div>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.ajaxupload.js')}}"></script>
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/jquery-confirm.min.js')}}"></script>
<script src="{{asset('js/libs/moment.min.js')}}"></script>
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
</script>
<script>
    var hotelList = new Vue({
        el: '#hotel_list',
        delimiters: ["<%","%>"],
        components: {
        },
        data: {
            parentTitle:"orderList Title",
            orders:{
                data : [],
                current_page : 1,
                total : 1,
                prev_page_url : null,
                next_page_url : null
            },
            loading:true
        },
        created:function () {
            this.get_data();
        },
        methods:{
            get_data : function(){
                const self = this;
                console.log("created");
                axios.post('/api/order_list',{
                            k1: "v1"
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
            next_page : function(url){
                const self = this;
                console.log("created");
                axios.post(url,{
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
            search_hotel : function(keyword){
                var input = $("#search_form").find("input[name='keyword']");
                if(!input.val()){
                    input.focus();
                    return;
                }
                var p = $("#search_form").serialize();
                const  self = this;
                self.loading = true;
                console.log(p);
                axios.post('/api/search/order',p)
                        .then(function(response){
                            console.log(response.data);
                            self.orders = response.data.obj;
                            self.loading = false;

                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
        },
        computed:{
        }
    })
</script>
@endsection