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
            width: 100%;
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
        .div_title{
            font-size: 14px;
            font-weight: bolder;
            color: #3c3c3c;
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
            <div style="float: left;width: 100%;padding-left:320px;border: 1px solid lightgrey;">
                <div class="div_block">
                    <div style="font-size: 22px;font-weight: bold"><%book.info.hotel_info.name%></div>
                    <div style="font-size: 16px;"><%book.info.hotel_info.name_en%></div>
                    {{--<div style="color: grey;font-size: 14px"><%book.info.hotel_info.location.address%></div>--}}
                    <img :src="book.info.hotel_info.image.url" style="width: 100%;object-fit: cover" />
                </div>
                <div>
                    <div class="div_block">
                        <div>输入个人信息</div>
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
                            <z-input placeholder="居住国" type="text"  v-model="book.user.country"></z-input>
                        </div>
                        <div style="width: 255px">
                            <z-input placeholder="电子邮箱" type="email"  v-model="book.user.email"></z-input>
                        </div>
                    </div>
                    <div class="div_block">
                        <div>为他人预定,并非自己亲自入住</div>
                        <div style="margin-top: 15px">
                            <div style="float: left;width: 120px;margin-right: 15px">
                                <z-input placeholder="姓 Last Name"  type="text" v-model="book.user2.last_name"></z-input>
                            </div>
                            <div style="float: left;width: 120px;">
                                <z-input placeholder="名 First Name" type="text"  v-model="book.user2.first_name"></z-input>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <div style="width: 255px">
                            <z-input placeholder="居住国" type="text"  v-model="book.user2.country"></z-input>
                        </div>
                    </div>

                    <div class="div_block">
                        <div>备注特殊需求</div>
                        <div style="height: 15px;width: 15px"></div>
                        <z-textarea placeholder="备注" v-model="book.user.memo"></z-textarea>
                        <div>*特殊需求能否满足要求,取决于各酒店住宿的实际情况,如产生额外费用于酒店前台支付</div>
                    </div>

                    <div class="div_block">
                        <div>支付定金并提交订单</div>

                        <div>总价格 : <%book.info.plan_info.price%></div>
                        <div>预付 : 99</div>

                    </div>

                    <div style="margin-top: 15px">
                        <label style="color:#3c3c3c">
                            <input type="checkbox" value="ok" v-model="book.user.is_read"> 我已阅读并同意<a href="">xxx</a>.
                        </label>
                    </div>
                    <div>
                        <button type="button" class="btn btn_book" v-on:click="pay">确认支付</button>
                    </div>

                </div>

                <div style="clear: both;height: 60px"></div>
            </div>
            <div style="float: left;width: 300px;margin-left: -100%;padding: 12px">
                <div>
                    <div class="div_title">预订信息</div>
                    <div>
                        <table class="table">
                            <tr>
                                <td width="80px">日期</td>
                                <td><%book.info.book_info.checkin + " - " + book.info.book_info.checkout%></td>
                            </tr>
                            <tr>
                                <td>房型</td>
                                <td><%book.info.room_info.name%></td>
                            </tr>
                            <tr>
                                <td>价格计划</td>
                                <td><%book.info.plan_info.name%></td>
                            </tr>
                            <tr>
                                <td>人数</td>
                                <td><%book.info.book_info.adult + "成人"%><%book.info.book_info.children + "儿童"%></td>
                            </tr>
                            <tr>
                                <td>包含</td>
                                <td>
                                    <li v-for="item in str_2_arr(book.info.plan_info.include)"><%item%></li>
                                </td>
                            </tr>
                            <tr>
                                <td>致游福利</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{--<div>--}}
                    {{--<div class="div_title">可选项目</div>--}}
                {{--</div>--}}
                <div>
                    <div class="div_title">价格详情</div>
                    <div>
                        <table class="table">
                            <tr v-for="item in book.info.plan_info.details">
                                <td width="160px"><%item[1]%></td>
                                <td style="text-align: right"><%item[2]%></td>
                            </tr>
                            <tr>
                                <td style="color: lightgrey">合计</td>
                                <td style="text-align: right"><%book.info.plan_info.price%></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="div_title">取消规定&预订须知</div>
                    <div>
                        <li v-for="item in str_2_arr(book.info.plan_info.cancellation)"><%item%></li>
                    </div>
                </div>
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
                    country:"",
                    phone : "",
                    email : "",
                    memo : "",
                    payment_channel : "",
                    is_read : "",
                },
                user2:{
                    last_name : "",
                    first_name : "",
                    country:""
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