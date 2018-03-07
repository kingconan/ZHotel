@extends("frontend.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <style>
        [v-cloak] {
            display: none;
        }
        .div_block{
            margin-bottom: 15px;
            border: 1px solid lightgrey;
            padding: 15px;
            background-color: #FFF;
        }
        body{
            background-color: #fafafa
        }
        .form-control{
            border-radius: 1px;
        }
        .btn_book{
            font-size: 18px;
            color: white;
            background-color: #c29c76;
            border-radius: 0;
            width: 160px;
            height: 40px;
            outline: none;
            border: 1px solid #c29c76;
        }
        .btn_book:focus{
            outline: none;
        }
        .btn_book:hover{
            color: white;
        }
        .btn_book:focus{
            color: white;
        }
        /*textarea:focus, input:focus, input[type]:focus, .uneditable-input:focus {*/
            /*border-color: rgba(194, 156, 118, 0.8);*/
            /*box-shadow: 0 1px 1px rgba(194, 156, 118, 0.075) inset, 0 0 8px rgba(194, 156, 118, 0.6);*/
            /*outline: 0 none;*/
        /*}*/
        .form_input:focus{
            border-color: rgba(194, 156, 118, 0.8);
            box-shadow: 0 1px 1px rgba(194, 156, 118, 0.075) inset, 0 0 8px rgba(194, 156, 118, 0.6);
            outline: 0 none;
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
<input style="display: none" id="order_id" value="{{$order_id}}"/>
<div style="width: 100%;">
    <div id="hotel_detail" v-cloak >
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
        <div v-else style="max-width: 1024px;min-width:630px ;margin-left: auto;margin-right: auto;padding-top: 30px;">
            <div style="float: left;width: 100%;padding-right: 330px;">
                <div>
                    <div class="div_block">
                        <div>入住信息</div>
                        <div style="margin-top: 15px">
                            <div style="float: left;width: 120px;margin-right: 15px">
                                <z-input placeholder="姓 Last Name"  type="text" v-model="book.user.last_name"></z-input>
                            </div>
                            <div style="float: left;width: 120px;">
                                <z-input placeholder="名 First Name" type="text"  v-model="book.user.first_name"></z-input>
                            </div>
                            <div style="clear: both"></div>
                        </div>

                        <div style="width: 255px">
                            <z-input placeholder="手机号" type="text"  v-model="book.user.phone"></z-input>
                        </div>
                        <div style="width: 255px">
                            <z-input placeholder="电子邮箱" type="email"  v-model="book.user.email"></z-input>
                        </div>
                    </div>
                    <div class="div_block">
                        <div>备注</div>
                        <div style="height: 15px;width: 15px"></div>
                        <z-textarea placeholder="备注" v-model="book.user.memo"></z-textarea>
                    </div>
                    <div class="div_block">
                        <div>付款信息</div>
                        <div style="padding: 12px;">支付宝</div>
                        <div style="padding: 12px;">微信</div>
                    </div>
                    <div class="div_block">
                        <div>条款</div>

                        <div style="margin-top: 15px">
                            <label style="color:#3c3c3c">
                                <input type="checkbox" value="ok" v-model="book.user.is_read"> 我已阅读并同意<a href="">xxx</a>.
                            </label>
                        </div>
                    </div>
                    <div class="div_block">
                        <div>价格</div>
                        <div>总价格 : <%book.info.plan_info.price%></div>
                        <div>预付 : 99</div>
                        <button type="button" class="btn btn_book" v-on:click="pay">支付</button>
                    </div>
                </div>

            </div>
            <div style="float: left;width: 300px;margin-left: -300px;border: 1px solid lightgrey;">
                <div style="background-color: lightgrey;padding: 12px;text-align: center;font-size: 16px">订单详情</div>
                <div style="padding: 15px;background-color: #FFF">
                    <div style="font-size: 22px;font-weight: bold"><%book.info.hotel_info.name%></div>
                    <div style="color: grey;font-size: 14px"><%book.info.hotel_info.location.address%></div>
                    <div style="height: 30px;width: 30px"></div>
                    <table class="table">
                        <tr>
                            <td>房型</td>
                            <td><%book.info.room_info.name%></td>
                        </tr>
                        <tr>
                            <td>日期</td>
                            <td><%book.info.book_info.checkin + " - " + book.info.book_info.checkout%></td>
                        </tr>
                        <tr>
                            <td>价格计划</td>
                            <td><%book.info.plan_info.name%></td>
                        </tr>
                        <tr>
                            <td>客人</td>
                            <td><%book.info.book_info.adult + "成人"%><%book.info.book_info.children + "儿童"%></td>
                        </tr>
                        <tr>
                            <td>费用包含</td>
                            <td>
                                <li v-for="item in str_2_arr(book.info.plan_info.include)"><%item%></li>
                            </td>
                        </tr>
                        <tr>
                            <td>退改</td>
                            <td>
                                <li v-for="item in str_2_arr(book.info.plan_info.cancellation)"><%item%></li>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="clear: both"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script src="{{asset('js/libs/vue-router.min.js')}}"></script>
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/zhotel_lib.js')}}"></script>
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
    $(document).ready(function(){
    });


</script>
<script>
    Vue.component('z-input',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "dom_id","type"],
        template: '<label class="form-group has-float-label">' +
        '<input :id="dom_id" class="form-control form_input" :type="type" :name="name" :placeholder="placeholder" ' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '<span><% placeholder %></span>',
        methods: {
            update:function(value){
                this.$emit('input', value)
            }
        }
    });
    Vue.component('z-textarea',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "rows","id"],
        template: '<label class="form-group has-float-label">' +
        '<textarea :id="id" class="form-control form_input" :name="name" :placeholder="placeholder" :rows="rows ? rows : 6"' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '</textarea>' +
        '<span><% placeholder %></span>' +
        '</label>',
        methods: {
            update:function(value){
                this.$emit('input', value)
            }
        }
    });
    var router = new VueRouter({
        mode: 'history',
        routes: [{
            path: '/hotel/detail/:id'
        }]
    });
    var hotelList = new Vue({
        router,
        el: '#hotel_detail',
        delimiters: ["<%","%>"],
        components: {
        },
        data: {
            book : {
                _id:"",
                info:{},
                user:{
                    last_name : "",
                    first_name : "",
                    phone : "",
                    email : "",
                    memo : "",
                    payment_channel : "",
                    is_read : "",
                }
            },
            refer : null,
            loading:true,
        },
        created:function () {
            var id = $("#order_id").val();
            console.log("in created");
            console.log("order id = "+id);
            this.book._id = id;
            this.get_data();
        },
        mounted:function(){
        },
        updated:function(){
        },
        methods:{
            get_data : function(){
                var thiz = this;
                axios.post('/api/order/detail/'+this.book._id,{
                        })
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                thiz.book.info = response.data.obj;
                                thiz.loading = false;
                            }
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            str_2_arr : function(str){
                if(!str){
                    return "";
                }

                return str.split("\n").filter(function(entry) { return entry.trim() != ''; });
            },
            pay : function(){
                var paras = JSON.parse(JSON.stringify(this.$data));
                console.log(paras);
            }
        },
        computed : {
        }
    })
</script>
@endsection