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
    </style>

@endsection
@section('content')

    <div style="width: 100%;">
        <div id="hotel_detail" v-cloak >
            <div v-if="loading" class="demo-spin-container">
                <spin size="large" fix></spin>
            </div>
            <div v-else style="">
                <div style="height: 30px;width: 100px"></div>
                <a v-for="(hotel, index) in hotels.data"
                   :href="'/dong_hotel/hotel/'+hotel.no">

                    <div style="">
                        <img :src="hotel.mainImage" width="100%" height="180px" style="object-fit: cover"/>
                    </div>
                    <div style="padding: 8px 12px">
                        <div style="font-size: 14px;font-weight: bolder;color: #3c3c3c"><%hotel.nameCn%></div>
                        <div style="font-size: 12px;font-weight: bolder;color: #909090"><%hotel.name%></div>
                        <div style="font-size: 8px;font-weight: bolder;color: darkseagreen"><%hotel.brandRes.nameCn%> <%hotel.brandRes.name%></div>
                        <div style="height: 6px"></div>
                        <div style="font-size: 10px;font-weight: bolder;color: #909090"><icon type="location"></icon> <%hotel.address%></div>
                    </div>
                    <div style="height: 15px;width: 100%;background-color: whitesmoke"></div>
                </a>
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
                path: '/dong_hotel/area/:id'
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
                hotels : null
            },
            created:function () {
                var _id = this.$route.params.id;

                console.log("created");
                if(_id){
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
                    axios.get('/dong/getAreaHotels?areaId='+_id+"&checkin=2018-06-01&checkout=2018-06-06&page=1",{
                            })
                            .then(function(response){
                                console.log(response.data);

                                var obj = response.data.res;
                                self.loading = false;
                                self.hotels = obj;
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                },
            },
            computed : {
            }
        })

    </script>
@endsection