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
        .des{
            font-size: 14px;
            color: lightgrey;
        }
        .cell{
            float: left;
            width: 120px;
            padding: 6px;
            text-align: right;
        }
    </style>

@endsection
@section('content')

    <div style="width: 100%;">
        <div id="hotel_detail" v-cloak style="width: 800px;margin-left: auto;margin-right: auto" >
            <div style="height: 30px;width: 100%"></div>
            <i-input v-model="paras.id" placeholder="36dong合约ID">
                <i-button slot="append" icon="ios-search" @click="get_data(paras.id)"></i-button>
            </i-input>
            <div style="height: 30px;width: 100%"></div>
            <div v-if="loading" class="demo-spin-container">
                <spin size="large" fix></spin>
            </div>
            <div v-else style="position: relative;">
                <div v-if="contract.hotel_name" style="border: 1px solid lightgrey;background-color: lightgrey;padding: 8px">
                    <h1 v-html="contract.hotel_name"></h1>
                    <div style="font-size: 14px"><%contract.rate%></div>
                </div>

                <div style="height: 30px"></div>
                <div v-for="(c,i) in contract.contract_ids" style="margin-bottom: 12px;border-radius: 6px;border: 1px solid whitesmoke">
                        <div style="background-color: lightgrey;padding: 6px 12px;font-size: 16px;font-weight: bolder;"><%c.name%></div>
                        <table class="table">
                            <thead>
                                <th width="60px">#</th>
                                <th width="240px">房型名字</th>
                                <th>价格日历</th>
                            </thead>
                            <tbody>
                                <tr v-for="(item,index) in c.rooms">
                                    <td ><%item.id%></td>
                                    <td v-on:click="get_price(c.id,item.id)"><%item.name%></td>
                                    <td>
                                        <div v-for="(d, j) in item.date">
                                            <div class="cell"><%d.startDate%></div>
                                            <div class="cell"> <%d.endDate%></div>
                                            <div class="cell"> <%d.price%></div>
                                            <div style="clear: both"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>
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
                path: '/dong_hotel/contract/:id'
            }]
        });
        var hotelList = new Vue({
            router,
            el: '#hotel_detail',
            delimiters: ["<%","%>"],
            components: {
            },
            data: {
                loading : false,
                contract :{
                },
                paras : {
                    id : ""
                }
            },
            created:function () {
//                var _id = this.$route.params.id;
//
//                console.log("created");
//                if(_id){
//                    this.paras.id = _id;
//                    this.get_data(_id);
//                }
//                else{
//                    this.loading = false;
//                }
//                console.log(_id)
            },
            mounted:function(){
            },
            beforeDestroy: function () {
            },
            updated:function(){
            },
            methods:{
                get_data : function(_id){
                    if(!_id) return;
                    console.log(_id);
                    const self = this;
                    self.loading = true;
                    console.log("created");
                    ///dong/getHotelDetail
                    axios.get('/dong/contract/'+_id,{
                            })
                            .then(function(response){
                                console.log("hotel detail");
                                console.log(response.data);
                                self.contract = response.data.obj;
                                self.loading = false;
                                self.$Message.success('获取成功');
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                },
                get_price : function(contract_id,room_id){
                    const self = this;
                    console.log("created");
                    ///dong/getHotelDetail
                    axios.get('/dong/contract/price/get?contractId='+contract_id+"&roomId="+room_id,{
                            })
                            .then(function(response){
                                console.log(response.data);
                                var res = response.data;
                                if(res.code == 1){
                                    self.parse_price_arr(contract_id, room_id, res.data);
                                }
                                self.$Message.success('获取成功');
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                },
                parse_price_arr : function(cid, rid, arr){
                    console.log(cid);
                    console.log(rid);
                    var prices = [];
                    var p = 0;
                    var start = "";
                    var end = "";
                    for(var i= 0,iLen = arr.length;i<iLen;i++){
                        var c = arr[i];
                        if(c.price != p){
                            if(start){
                                prices.push({
                                    "startDate" : start,
                                    "endDate" : end,
                                    "price" : p
                                })
                            }
                            p = c.price;
                            start = c.date;
                            end = c.date;
                        }
                        else{
                            end = c.date;
                        }
                    }
                    if(start){
                        prices.push({
                            "startDate" : start,
                            "endDate" : end,
                            "price" : p
                        })
                    }


                    console.log(prices);
                    for(var i= 0,iLen = this.contract.contract_ids.length;i<iLen;i++) {
                        var c = this.contract.contract_ids[i];
                        if (c.id == cid) {
                            for (var j = 0, jLen = this.contract.contract_ids[i].rooms.length;j<jLen; j++) {
                                var r = this.contract.contract_ids[i].rooms[j];
                                if(r.id == rid){
                                    this.contract.contract_ids[i].rooms[j].date = prices;
                                    console.log(this.contract);
                                    break;
                                }
                            }
                        }
                    }
                }

            },
            computed : {

            }
        })

    </script>
@endsection