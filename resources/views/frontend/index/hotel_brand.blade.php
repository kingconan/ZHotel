@extends("frontend.layout.base")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/swiper.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>

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
        .section{
            position: relative;
        }
        .container{
            margin-left: auto;
            margin-right: auto;
            width: 800px;
            /*background-color: white;*/
            position: relative;
        }
        .item_filter{
            float: left;
            width: 120px;
            padding: 8px;
        }
        .line2{
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .page{
            padding: 2px 2px;
            width: 20px;
            text-align: center;
            font-size: 10px;
            margin-right: 8px;
            border: 1px solid lightgrey;
            background-color: lightgrey;
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
        <div v-else >
            <div style="height: 400px;width: 100%;background-color: lightblue">

            </div>
            <div style="width: 100%;background-color: white;">
                <div class="container">
                    <div style="float: left;width: 40%">
                        <img width="100%" height="200px" style="background-color: lightblue" />
                    </div>
                    <div style="float: left;width: 60%;padding: 15px">
                        <div style="font-size: 22px;font-weight: bold"><%brand.name%></div>
                        <div style="height: 15px"></div>
                        <div style="font-size: 12px;color: #909090"><%brand.des%></div>
                    </div>
                    <div style="clear: both"></div>
                </div>
            </div>
            <div class="container">
                <div style="height: 20px;width: 30px"></div>
                <div style="background-color: white;padding: 15px">
                    <div style="text-align: center;font-size: 22px;font-weight: bold"><%brand.x.title%></div>
                    <div><%brand.x.markdown%></div>
                </div>
                <div style="height: 20px;width: 30px"></div>
                <div>精选酒店</div>
                <div class="item" v-for="hotel in list.data">
                    <div style="float: left;position: relative;padding-left: 250px;width: 100%;background-color: white;min-height: 180px">
                        <div style="padding: 15px">
                            <div style="float: left;width: 100%;padding-right: 80px">
                                <div style="font-size: 18px;font-weight: bolder"><%hotel.name%></div>
                                <div style="font-size: 12px;margin-left: 1px"><%hotel.name_en%></div>
                                <div style="height: 8px"></div>
                                <div style="font-size: 12px;color:grey"><%hotel.location.address%></div>
                                <div style="font-size: 12px;color:grey"><%hotel.tag%></div>
                                <div style="height: 8px"></div>
                                <div class="line2" style="font-size: 12px;color:grey"><%hotel.description%></div>
                            </div>
                            <div style="float: left;width: 80px;color:#c99c76;margin-left: -80px;text-align: right">
                                ------/晚
                                <div style="font-size: 9px;">含税最优价</div>
                            </div>
                            <div style="position: absolute;bottom: 15px;right: 15px;background-color: #c99c76;padding: 3px 12px;font-size: 10px;color: white">酒店详情</div>
                        </div>
                    </div>
                    <div style="position: relative;float:left;width: 250px;background-color: lightblue;margin-left: -100%;height: 180px">
                        <img :src="hotel.images.length > 0 ? hotel.images[0].url+'?imageView2/2/w/300' : ''" width="250px" height="180px" style="object-fit: cover" />
                    </div>
                    <div style="clear: both;height: 15px"></div>
                </div>
                <div style="height: 10px;width: 30px"></div>
                <div v-if="list.last_page > 1" style="text-align: center">
                    <div :style="'width: '+(list.last_page+2)*28+'px;margin-right: auto;margin-left: auto'">
                        <div class="page" style="float: left;" v-on:click="page_next(list.current_page-1)"> < </div>
                        <template v-for="index in list.last_page" >
                            <div v-on:click="page_next(index)" class="page" v-if="index < 9 && index != list.last_page" style="float: left;">
                                <%index%>
                            </div>
                        </template>
                        <div class="page" v-if="list.last_page > 9" style="float: left;">
                            ...
                        </div>
                        <div v-on:click="page_next(list.last_page)" class="page" style="float: left;">
                            <%list.last_page%>
                        </div>
                        <div class="page" style="float: left;" v-on:click="page_next(list.current_page+1)"> > </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div style="height: 40px;width: 30px"></div>
            </div>
            <div class="foooooter" style="height: 200px;width: 100%;background-color: black">

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
<script src="{{asset('js/libs/swiper.min.js')}}"></script>

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
    var router = new VueRouter({
        mode: 'history',
        routes: [{
            path: '/list/brand/:brand'
        }]
    });
    var hotelList = new Vue({
        router,
        el: '#hotel_detail',
        delimiters: ["<%","%>"],
        components: {
        },
        data: {
            refer : null,
            loading:true,
            list : {
                data : [],
                current_page : 1,
                last_page : 0,
                total : 1,
                prev_page_url : "",
                next_page_url : ""
            },
            brand : {
            },
            url_format : "",
            para_brand : null
        },
        created:function () {
            console.log("created");
            this.para_brand = this.$route.params.brand;
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
                axios.get('/api/list/brand'+"?q="+self.para_brand,{
                        })
                        .then(function(response){
                            console.log(response.data);

                            self.list = response.data.obj;
                            self.url_format = response.data.url_format;
                            self.brand = response.data.brand;
                            self.loading = false;
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            next_page : function(url){
                const self = this;
                self.loading = true;
                console.log(url);
                axios.get(url,{})
                        .then(function(response){
                            console.log(response.data);
                            self.list = response.data.obj;
                            self.brand = response.data.brand;
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
            page_next : function(page){
                if(page < 1 || page > this.list.last_page) return;
                var len = this.url_format.length;
                var f = this.url_format.substring(0,len-1);
                var url =  f+page;
                this.next_page(url);
            }
        },
        computed : {
        }
    })



</script>
@endsection