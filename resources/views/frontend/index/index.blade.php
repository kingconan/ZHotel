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
        .title{
            font-size: 18px;
            font-weight: bolder;
        }
        .des{
            font-size: 12px;
        }
        .hotel_cover{
            height: 120px;
            width: 200px;
            background-color: lightcoral;
        }
        .hotel_name{
            font-size: 14px;
            font-weight: bolder;
        }
        .hotel_name_en{
            font-size: 10px;
        }
        .hotel_address{
            font-size: 10px;
        }
        .hotel_description{
            font-size: 10px;
        }
        .swiper-container {
            width: 100%;
            position: relative;
        }
        /*.swiper-button-next{*/
        /*}*/
        /*.swiper-button-prev{*/
        /*}*/
        .swiper-next{
            position: absolute;
            top: 50%;
            width: 14px;
            height: 26px;
            margin-top: -13px;
            right: 20px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 14px 26px;
            -webkit-background-size:14px 26px;
            background-size: 14px 26px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/arrow_right.png')}}");
        }
        .swiper-prev{
            position: absolute;
            top: 50%;
            left:20px;
            width: 14px;
            height: 26px;
            margin-top: -13px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 14px 26px;
            -webkit-background-size: 14px 26px;
            background-size: 14px 26px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/arrow_left.png')}}");
        }
        .hack_arrow{
            padding: 0 60px;
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
            <div style="height: 30px;width: 100%"></div>
            <div class="container">
                <div class="section">
                    <div v-for="item in arr1" class="arr">
                        <div class="hack_arrow">
                            <div class="title"><%item.title%></div>
                            <div class="des"><%item.des%></div>
                        </div>
                        <div style="padding:15px 60px;position: relative">
                            <div class="swiper-container g1">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide" v-for="hotel in item.hotels">
                                        <div class="hotel_cover"></div>
                                        <div class="hotel_name"><%hotel.name%></div>
                                        <div class="hotel_name_en"><%hotel.name_en%></div>
                                        <div class="hotel_address"><%hotel.address%></div>
                                        <div class="hotel_description"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-next"></div>
                            <div class="swiper-prev"></div>
                        </div>
                        <div style="height: 10px;width: 10px"></div>
                    </div>
                </div>
                <div style="height: 30px;width: 100%"></div>
                <div class="section hack_arrow">
                    <div v-for="item in arr2">
                        <div class="title"><%item.title%></div>
                        <div class="des"><%item.des%></div>
                        <div style="position: relative">
                            <template v-for="(sub,index) in item.items">
                                <div style="float: left;width: 33.3%;height: 120px;position: relative;padding-right:8px">
                                    <img style="width: 100%;height: 120px;background-color: lightblue" />
                                    <div style="position: absolute;top: 50%;left:50%;">
                                        <div style="text-align: center;width:120px;margin-left: -60px;margin-top: -8px">
                                            <%sub.name%>
                                            <br/>
                                            <br/>
                                            <span style="font-size: 9px"><%sub.des%></span>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="index % 3 == 2" style="clear: both;height: 8px;width: 2px"></div>
                            </template>
                            <div style="clear: both;"></div>
                        </div>

                    </div>

                </div>
                <div style="height: 30px;width: 100%"></div>
                <div class="section">
                    <div class="hack_arrow">
                        <div class="title">酒店品牌</div>
                        <div class="des">小众而奢华,啊哈哈哈</div>
                    </div>
                    <div style="padding: 10px 50px;position: relative">
                        <div class="swiper-container g2">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" v-for="item in arr3">
                                    <div style="position: relative;width: 120px">
                                        <img style="width: 120px;height: 120px;background-color: lightblue;border-radius: 60px" />
                                        <div style="position: absolute;top: 50%;left:50%;">
                                            <div style="text-align: center;width:120px;margin-left: -60px;margin-top: -10px">
                                                <%item.name%>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="swiper-next b1"></div>
                        <div class="swiper-prev b2"></div>
                    </div>

                </div>
                <div style="height: 30px;width: 100%"></div>
                <div class="section hack_arrow">
                    <div class="title">
                        关于我们
                    </div>
                    <div>
                        <div style="float: left;width: 33.3%">
                            <div>我们是谁</div>
                            <div>
                                blabla blabla
                            </div>
                        </div>
                        <div style="float: left;width: 33.3%">
                            <div>我们能帮到你什么</div>
                            <div>
                                blabla blabla
                            </div>
                        </div>
                        <div style="float: left;width: 33.3%">
                            <div>我们的优势是什么</div>
                            <div>
                                blabla blabla
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div style="height: 30px;width: 100%"></div>
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
        try{
            var sliders = [];

            $('.g1').each(function(index, element) {
                $(this).addClass('s' + index);
                $(this).parent().find('.swiper-next').addClass('r' + index);
                $(this).parent().find('.swiper-prev').addClass('l' + index);
                var slider = new Swiper('.s' + index, {
                    slidesPerView: 3,
                    spaceBetween: 8,
                    autoHeight : true,
                    navigation: {
                        nextEl: '.r' + index,
                        prevEl: '.l' + index
                    },
                    loop: true
                });
                sliders.push(slider);
            });
        }
        catch (e){}

        try{
            var swiper1 = new Swiper('.g2', {
                navigation: {
                    nextEl: '.b1',
                    prevEl: '.b2'
                },
                slidesPerView: 5,
                spaceBetween: 10,
                loop:true
            });
        }
        catch (e){}
    });

</script>

<script>
    var hotelList = new Vue({
        el: '#hotel_detail',
        delimiters: ["<%","%>"],
        components: {
        },
        data: {
            refer : null,
            loading:true,
            arr1 : [],
            arr2 : [],
            arr3 : [],
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
                axios.get('/api/index/op',{
                        })
                        .then(function(response){
                            console.log(response.data);

                            var obj = response.data.obj;
                            self.loading = false;
                            self.arr1 = obj.arr1;
                            self.arr2 = obj.arr2;
                            self.arr3 = obj.arr3;
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            handle_resize : function(event){
                var height = document.documentElement.clientHeight;
                var width = document.documentElement.clientWidth;
            }
        },
        computed : {
        }
    })



</script>
@endsection