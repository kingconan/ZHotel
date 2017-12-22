@extends("backend.layout.base_main")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/jquery-confirm.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/daterangepicker_ihotel.min.css')}}"/>
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

        .table_input{
            background: transparent;
            border: none;
            /*box-shadow: 0 1px 1px rgba(194, 156, 118, 0.075) inset, 0 0 8px rgba(194, 156, 118, 0.6);*/
            outline: 0 none;
            padding: 0px 8px;
        }

        .table_input:focus{
            box-shadow: 0 1px 1px rgba(194, 156, 118, 0.075) inset, 0 0 8px rgba(194, 156, 118, 0.6);
        }

        li{
            padding-left: 12px;
            list-style: none;
            font-size: 12px;
            color: grey;
        }
        .status{
            float: left;
            text-align: center;
        }
        .status div{
            border: 1px solid lightgrey;
            padding: 8px;
            font-size: 14px;
            text-align: center;
        }
        .status span{
            color: blue;
            font-size: 10px;
        }
        .status_focus{
            background-color: lightblue;
        }
        .status_line{
            height: 23px;width: 30px;border-bottom: 1px solid lightgrey;float: left
        }
    </style>
@endsection
@section('content')
<div style="width: 100%;clear: both">
    <div id="hotel_list">
        <div v-cloak>
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
                <div style="padding: 0 30px">
                    <div class="status">
                        <div :class="order.status == 0 ? 'status_focus' : ''">
                            未付定金
                        </div>
                        <span v-if="order.status == 0">
                            <%status_arr[order.status][0]%>
                        </span>
                    </div>
                    <div class="status_line"></div>
                    <div  class="status">
                        <div :class="order.status > 9 && order.status < 20 ? 'status_focus' : ''">
                            付款中
                        </div>
                        <span v-if="order.status > 9 && order.status < 20">
                            <%status_arr[order.status][0]%>
                        </span>
                    </div>
                    <div class="status_line"></div>
                    <div  class="status">
                        <div :class="order.status == 20 ? 'status_focus' : ''">
                            预订成功
                        </div>
                        <span v-if="order.status == 20">
                            <%status_arr[order.status][0]%>
                        </span>
                    </div>
                    <div class="status_line"></div>
                    <div  class="status">
                        <div :class="order.status > 99 && order.status < 200 ? 'status_focus' : ''">
                            已使用
                        </div>
                        <span v-if="order.status > 99 && order.status < 200">
                            <%status_arr[order.status][0]%>
                        </span>
                    </div>

                    <div style="clear: both"></div>
                </div>

                <div style="padding: 30px">
                    <select class="form-control" v-model="order.status">
                        <option v-for="(value,key) in status_arr" :value="key"><%value[0] + "(" + value[1] + ")"%></option>
                    </select>
                </div>

                <div style="padding: 30px">
                    <label>预订信息</label>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="120px">酒店信息</td>
                                <td>
                                    <%order.hotel_info.name%> <span style="color: grey"> <%order.hotel_info.name_en%> </span>
                                </td>
                            </tr>
                            <tr>
                                <td>房型</td>
                                <td>
                                    <%order.room_info.name%>
                                </td>
                            </tr>
                            <tr>
                                <td>价格计划</td>
                                <td>
                                    <strong><%order.plan_info.price%></strong> <br/>
                                    <%order.plan_info.name%> <br />
                                    <ul style="padding: 0">费用包含
                                        <li v-for="item in str_2_arr(order.plan_info.include)">
                                            <%item%>
                                        </li>
                                    </ul>
                                    <ul style="padding: 0">退改规则
                                        <li v-for="item in str_2_arr(order.plan_info.cancellation)">
                                            <%item%>
                                        </li>
                                    </ul>
                                    <%order.plan_info.memo%> <br />
                                </td>
                            </tr>
                            <tr>
                                <td>成人</td>
                                <td>
                                    <input type="number" min="1" max="100" class="table_input" v-model="order.book_info.adult"/>
                                </td>
                            </tr>
                            <tr>
                                <td>儿童</td>
                                <td>
                                    <input type="number" min="0" max="10" class="table_input" v-model="order.book_info.children"/>
                                </td>
                            </tr>
                            <tr>
                                <td>入住日期</td>
                                <td>
                                    <input id="input_checkin" class="table_input" v-model="order.book_info.checkin"/>
                                </td>
                            </tr>
                            <tr>
                                <td>退房日期</td>
                                <td>
                                    <input id="input_checkout" class="table_input" v-model="order.book_info.checkout"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <label>联系人</label>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td width="120px">名称</td>
                            <td>
                                <input class="table_input" v-model="order.user_info.name"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="120px">email</td>
                            <td>
                                <input class="table_input" type="email" v-model="order.user_info.email"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="120px">手机</td>
                            <td>
                                <input class="table_input" v-model="order.user_info.phone"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <label>支付信息</label>

                    <div style="padding: 15px;background-color: lightgrey">
                        <div style="float: left;width: 300px">
                            <div v-if="order.payment_id > 0" style="font-size: 10px;color: grey">支付流水号#<%order.payment_id%></div>
                            <div v-else style="font-size: 10px;color: red">当前无支付信息</div>
                            <div style="height: 8px;width: 1px"></div>
                            <input type="number" min="1" placeholder="当前需支付金额" v-model="order.payment_price"/>
                            <div style="height: 8px;width: 1px"></div>
                            <input placeholder="金额说明" v-model="order.payment_memo"/>
                            <div style="height: 8px;width: 1px"></div>
                            <button type="button" class="btn btn-default btn-sm" v-on:click="create_payment">生成支付信息</button>
                            <button type="button" class="btn btn-default btn-sm" v-on:click="clear_payment">取消当前支付</button>
                            <button type="button" class="btn btn-default btn-sm" v-on:click="confirm_payment">确认已支付</button>
                            {{--<button type="button" class="btn btn-default btn-sm" v-on:click="test">test</button>--}}
                        </div>
                        <div style="float: right;width: 500px;margin-right: 20px">
                            <table class="table">
                                <tr v-for="item in order.payment_log">
                                    <td>#<%item.id%></td>
                                    <td>¥<%item.price%></td>
                                    <td><%item.memo%></td>
                                    <td style="font-size: 9px;color: dimgrey">c : <%sec2date(item.id)%><br/>u : <%item.created_at%></td>
                                </tr>
                            </table>
                        </div>
                        <div style="clear: both"></div>
                    </div>

                </div>
                <div style="padding: 15px">
                    <button type="button" class="btn btn-default btn-sm" v-on:click="save">保存</button>
                </div>

            </div>

        </div>
    </div>
</div>

<div style="clear: both"></div>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script src="{{asset('js/libs/vue-router.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.ajaxupload.js')}}"></script>
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/jquery-confirm.min.js')}}"></script>
<script src="{{asset('js/libs/moment.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.daterangepicker.min.js')}}"></script>
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
    var router = new VueRouter({
        mode: 'history',
        routes: []
    });
    var hotelList = new Vue({
        router,
        el: '#hotel_list',
        delimiters: ["<%","%>"],
        components: {
        },
        data: {
            parentTitle:"order detail",
            order:{
                payment_id : 0,
                payment_price : 0,
                payment_memo : "",
            },
            loading:true,
            status_arr : {
                0 : ["未付款", "用户未付定金"],
                10 : ["已付定金","已付定金-待确认房态"],
                11 : ["待付尾款", "确认有房-待付尾款"],
                12 : ["已付尾款", "已付尾款-待预订"],
                20 : ["预订成功","上传预订凭证"],
                13 : ["待付尾款","订单变更-待补款"],
                35 : ["未付申请退款款","申请退款"],
                36 : ["定金申请退款款","申请退款"],
                37 : ["全款申请退款款","申请退款"],
                100 : ["已使用","已退房"],
                101 : ["已退款","退款成功"],
                102 : ["无效","人工设置无效"],
                1024 : ["未知","未定义"]
            }
        },
        created:function () {
            var _id = this.$route.query.id;
            if(_id){
                this.get_data(_id);
            }
            else{
                this.loading = false;
            }
            console.log(_id)
        },
        methods:{
            get_data : function(id){
                const self = this;
                console.log("created");
                axios.post('/api/order/'+id,{
                            k1: "v1"
                        })
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                self.order = response.data.obj;
                                self.loading = false;
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
            save : function(){
                const self = this;
                var paras = JSON.parse(JSON.stringify(this.$data));
                console.log(paras);
                axios.post("/api/update/order", paras.order)
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                self.order = response.data.obj;
                                toastr["success"](response.data.msg);
                            }
                            else{
                                toastr["error"](response.data.msg);
                            }
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            test : function(){
                const self = this;
                var paras = JSON.parse(JSON.stringify(this.$data));
                console.log(paras);
                axios.post("/api/test/order", paras.order)
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                self.order = response.data.obj;
                                toastr["success"](response.data.msg);
                            }
                            else{
                                toastr["error"](response.data.msg);
                            }
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            create_payment : function(){
                if(this.order.payment_id){
                    toastr["error"]("当前有未支付项目,请先清除");
                }
                else{
                    this.order.payment_id = Date.now();;
                    this.order.payment_price = 1;
                    this.order.payment_memo = "";
                }
            },
            clear_payment : function(){
                this.order.payment_id = 0;
                this.order.payment_price = 0;
                this.order.payment_memo = "";
            },
            confirm_payment : function(){
                var self = this;
                if(!self.order.payment_id){
                    toastr["error"]("当前木有要支付的项目,无法完成确认");
                    return;
                }
                $.confirm({
                    title: '确认已经支付',
                    content: "该款项已经支付 ",
                    buttons: {
                        confirm:{
                            text : "确定",
                            action : function(){
                                if(!self.order.payment_log){
                                    self.order.payment_log = [];
                                }
                                self.order.payment_log.push({
                                    id : self.order.payment_id,
                                    price : self.order.payment_price,
                                    memo : self.order.payment_memo,
                                    created_at : moment().local().format('YYYY-MM-DD HH:mm:ss')
                                });
                                self.clear_payment();
                            },
                        },
                        cancel: {
                            text : "取消",
                        }
                    }});


            },
            sec2date : function(sec){
                return moment(sec).local().format('YYYY-MM-DD HH:mm:ss')
            }
        },
        computed:{
            status_str : function(){
                return this.status_arr[this.order.status];
            },
        }
    })

    $(document).ready(function(){
        $("#input_checkin").dateRangePicker(
                {
                    separator : ' to ',
                    autoClose: true,
                    getValue: function()
                    {
                        if ($('#input_checkin').val() && $('#input_checkout').val() )
                            return $('#input_checkin').val() + ' to ' + $('#input_checkout').val();
                        else
                            return '';
                    },
                    setValue: function(s,s1,s2)
                    {
                        $('#input_checkin').val(s1);
                        $('#input_checkout').val(s2);
                        //hack for event
                        var evt = document.createEvent('HTMLEvents');
                        evt.initEvent('input', false, true);
                        $('#input_checkin')[0].dispatchEvent(evt);
                        $('#input_checkout')[0].dispatchEvent(evt);
                    },
                    hoveringTooltip: function (days, startTime, hoveringTime) {
                        return days > 1 ? days + "天" + (days - 1) + '晚' : '';
                    },
                    language:"cn"
                }
        );
    });
</script>
@endsection