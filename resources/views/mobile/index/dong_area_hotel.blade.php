@extends("mobile.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/iview.css')}}"/>
    <style>
        [v-cloak] {
            display: none;
        }
        .demo-spin-container{
            width: 200px;
            margin-right: auto;
            margin-left: auto;
            height: 100px;
            position: relative;
        }
        .title{
            font-size: 14px;
            font-weight: bolder;
            color: #3c3c3c;
            margin-bottom: 8px;
        }
    </style>

@endsection
@section('content')

    <div style="width: 100%;">
        <div id="hotel_detail" v-cloak >
            <div v-if="loading" class="demo-spin-container">
                <spin size="large" fix></spin>
            </div>
            <div v-else style="position: relative">
                <template v-if="paras.state == 0">
                    <div style="height: 30px;width: 100px"></div>
                    <div v-for="(k,index) in hotel.group" style="border: 1px solid whitesmoke;margin: 8px">
                        <div style="font-weight: bolder;font-size: 12px;padding: 8px 15px;background-color: whitesmoke"
                            v-on:click="change(index)"
                        >
                            <div><%index+1%> . <%k[0]%></div>
                            <div style="color: indianred">¥ <%k[1][0].bocCnyTotal%>起</div>
                        </div>
                        <table v-show="k[2]" class="table " style="margin: 0">
                            <thead style="color: lightgrey;font-size: 10px">
                                <th>计划</th>
                                <th width="60px">早餐</th>
                                <th width="60px">加床</th>
                                <th width="80px">取消政策</th>
                                <th width="70px">价格</th>
                            </thead>
                            <tbody>
                                <tr v-for="(plan,index) in k[1]" v-on:click="plan_detail(plan.base,plan.no,plan.ratePlanType)">
                                    <td><%plan.rate%></td>
                                    <td><%plan.mealTran%></td>
                                    <td><%plan.bedTran%></td>
                                    <td><%plan.cancelInfoShortTran%></td>
                                    <td style="color: lightcoral;">¥ <%plan.bocCnyTotal%></td>
                                </tr>
                            </tbody>
                        </table>
                </div>
                </template>
                <template v-if="paras.state == 1">
                    <div style="float: right;padding: 15px" v-on:click="close_detail"><icon type="close-round"></icon></div>
                    <div style="padding:0 15px;clear: both">
                        <div  class="title">酒店名</div>
                        <div><%hotel.detail.hotelDetail.nameCn%></div>
                        <div><%hotel.detail.hotelDetail.name%></div>
                        <div style="height: 15px"></div>
                        <div class="title">房型名</div>
                        <div><%hotel.detail.room%></div>
                        <div style="height: 15px"></div>
                        <div class="title">第三人费用</div>
                        <div class="title">价格包含</div>
                        <div><%hotel.detail.rate.rateDescription%></div>
                        <div style="height: 15px"></div>
                        <div class="title">取消政策</div>
                        <div><%hotel.detail.rule.cancellationDesc%></div>
                        <div style="height: 15px"></div>
                        <div class="title">每间房价</div>
                    </div>
                </template>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('js/libs/vue.min.js')}}"></script>
    <script src="{{asset('js/libs/vue-router.min.js')}}"></script>
    <script src="{{asset('js/libs/zhotel_lib.js')}}"></script>
    <script src="{{asset('js/libs/moment.min.js')}}"></script>
    <script src="{{asset('js/libs/iview.min.js')}}"></script>

    <script>

        var router = new VueRouter({
            mode: 'history',
            routes: [{
                path: '/dong_hotel/hotel/:id'
            }]
        });
        var hotelList = new Vue({
            router,
            el: '#hotel_detail',
            delimiters: ["<%","%>"],
            components: {
            },
            data: {
                loading : true,
                hotel : {
                    plans : [],
                    group : [
                        ["room_name",[],true]
                    ],
                    detail : null
                },
                paras : {
                    hotelId : "",
                    checkin : "2018-06-01",
                    checkout : "2018-06-06",
                    state : 0
                }
            },
            created:function () {
                var _id = this.$route.params.id;

                console.log("created");
                if(_id){
                    this.paras.hotelId = _id;
                    this.get_data(_id);
                }
                else{
                    this.loading = false;
                }
                console.log(_id)
            },
            mounted:function(){
            },
            beforeDestroy: function () {
            },
            updated:function(){
            },
            methods:{
                get_data : function(_id){
                    const self = this;
                    console.log("created");
                    axios.get('/dong/getRatePlanList?id='+_id+"&checkin=2018-06-01&checkout=2018-06-06",{
                            })
                            .then(function(response){
                                console.log(response.data);
                                self.hotel.plans = response.data.res.data;
                                self.group_plans();
                                self.loading = false;
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                },
                close_detail : function(){
                    this.paras.state = 0;
                    this.hotel.detail = null;
                },
                plan_detail : function(base,planId,planType){
                    var p = "?hotelId="+this.paras.hotelId+"&checkin="+this.paras.checkin+"&checkout="+this.paras.checkout+
                                    "&base="+base+"&planId="+planId+"&ratePlanType="+planType;
                    var url = "/dong/getRatePlanDetail"+p;
                    console.log(url);
                    const self = this;
                    axios.get(url,{
                            })
                            .then(function(response){
                                console.log(response.data);
                                self.paras.state = 1;
                                self.hotel.detail = response.data.res;

                            })
                            .catch(function(error){
                                console.log(error);
                            });
                },
                change : function(index){
                    this.hotel.group[index][2] = !this.hotel.group[index][2];
                    console.log(this.hotel.group[index]);
                    this.$forceUpdate();
                },
                group_plans : function(){
                    var g = {};
                    for(var i= 0,iLen = this.hotel.plans.length;i<iLen;i++){
                        var plan = this.hotel.plans[i];
                        if(!(plan.room in g)){
                            g[plan.room] = [];
                        }
                        g[plan.room].push(plan);//bocCnyTotal
                    }
                    var arr = []
                    for(var k in g){
                        g[k].sort(function(a,b){
                            return a.bocCnyTotal - b.bocCnyTotal;
                        });
                        arr.push([k,g[k],false]);
                    }
                    arr.sort(function(a,b){
                        return a[1][0].bocCnyTotal - b[1][0].bocCnyTotal;
                    });
                    this.hotel.group =  arr;
                }

            },
            computed : {

            }
        })

    </script>
@endsection