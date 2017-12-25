@extends("frontend.layout.base")
@section('style')

    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/swiper.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/daterangepicker_ihotel.min.css')}}"/>
    {{--<link rel="stylesheet" href="https://cdn.staticfile.org/blueimp-gallery/2.25.0/css/blueimp-gallery-indicator.min.css"/>--}}
    {{--<link rel="stylesheet" href="https://cdn.staticfile.org/blueimp-gallery/2.25.0/css/blueimp-gallery.min.css"/>--}}

    <style>
        [v-cloak] {
            display: none;
        }
    </style>
    <style>
        .d_nav{
            padding: 12px 0;
            text-align: center;
        }
        .line{
            width: 100%;height: 1px;
            background-color: whitesmoke;
        }
        .cover{
            width: 100%;
            padding-bottom: 60%;
            background-color: lightblue;
        }
        .m_title{
            font-size: 16px;
            font-weight: 300;
            margin-bottom: 12px;
        }
        .font_normal{
            font-size: 12px;line-height: 24px
        }
        .markdown-image{
            width: 100%;
            margin: 8px 0;
            max-height: 360px;
            object-fit: cover;
        }
        .affix {
            top:0;
            z-index: 10 !important;
            display: block;
            position: fixed;
        }
        .footer{
            font-size: 12px;
            text-align: center;
            background-color: #1f272a;
            padding: 15px;
            color: #505050;
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
        <div v-else>
            <div class="cover">
                <div>

                </div>
            </div>
            <div class="header" id="hotel_header">
                <div><%hotel.name%></div>
                <div><%hotel.name_en%></div>
                <span><% hotel.location.continent %></span> >
                <span><% hotel.location.country %></span> >
                <span><% hotel.location.city %></span> >
                <span>酒店</span>
            </div>
            <div style="color: white;font-size: 12px;font-weight: 200">
                <div id="hotel_nav" style="width: 100%">
                    <div style="float: left;width: 100%;padding-right: 120px;position: relative">
                        <div style="text-align: center;background-color: #29353e;padding: 12px 0" v-on:click="show_nav"><%section_str%></div>
                        <div style="position: absolute;top: 0;right: 130px;padding: 12px 0"><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                    </div>
                    <div style="float: left;width: 120px;margin-left: -120px;text-align: center;background-color: #232f36;padding: 12px 0">
                        搜索日期和房型
                    </div>
                    <div style="clear: both"></div>
                </div>
            </div>
            <div style="clear: both"></div>
            <template v-if="section == 'detail'">
                <div style="padding: 15px">
                    <div class="m_title">酒店详情</div>
                    <p class="font_normal" v-html="hotel.description"></p>
                </div>
                <div class="line"></div>
                <div style="padding: 15px">
                    <div class="m_title">致游推荐</div>
                    <ul style="color: #C19B76;padding: 0 20px">
                        <li class="font_normal" style="margin-bottom: 8px;" v-for="v in zy_recommend_arr">
                            <span style="color: #3c3c3c;"><%v%></span></li>
                    </ul>
                </div>
                <div class="line"></div>
                <div style="padding: 15px">
                    <div class="m_title">致游知道</div>
                    <ul style="color: #C19B76;padding: 0 20px">
                        <li class="font_normal" style="margin-bottom: 8px;" v-for="v in zy_g2k_arr">
                            <span style="color: #3c3c3c;"><%v%></span></li>
                    </ul>
                </div>
                <div class="line"></div>
                <div style="padding: 15px" v-if="hotel.detail.extend">
                    <div v-for="section in detail_extend">
                        <div class="m_title"><%section.title%></div>
                        <div class="font_normal" v-html="markdown(section.content)"></div>
                    </div>
                </div>
                <div class="line"></div>
                <div style="padding: 15px">
                    <div class="m_title">交通指南</div>
                    <div id="m_map" style="height: 120px;width: 100%;background-color: lightgreen">

                    </div>
                    <div style="padding: 15px 0">
                        <div class="font_normal" v-html="markdown(hotel.location.transportation)"></div>
                        <div class="font_normal"><strong>地址 : </strong><%hotel.location.address%></div>
                    </div>
                </div>

                <div class="line"></div>
                <div style="padding: 15px">
                    <div class="m_title">品牌和荣誉</div>
                    <div>
                        <img v-for="img in str_2_arr(hotel.honor_img)"
                             :src="img" width="100px" height="50px" style="margin-right: 10px"
                        />
                        <p class="font_normal"><%hotel.honor_word%></p>
                    </div>

                    <div class="m_title">主要奖项</div>
                    <p class="font_normal" v-html="markdown(hotel.honor)">

                    </p>

                    <div style="height: 15px;width: 15px"></div>
                    <div class="m_title">礼宾服务</div>
                    <div class="font_normal">
                        致游的顾问团队很乐意为您提供免费的礼宾服务
                        <br/>
                        <br/>
                        如果您希望预约前往酒店的专车、预约餐厅、更改行程或向酒店要求特殊需求等等，欢迎与我们联系：
                    </div>
                    <div >
                        <br/>
                        周一至周六 10:00 - 19:00<br/>
                        <span>4001-567-165</span>
                    </div>
                </div>

            </template>
            <template v-else-if="section == 'room'">
                房型介绍
            </template>
            <template v-else-if="section == 'facilities'">
                酒店设施
            </template>
            <div class="footer">
                &copy; 2018致游旅游咨询有限公司(上海)
            </div>
        </div>
        <div id="dialog" style="width: 100%;background-color: lightgrey;display: none;position: absolute">
            <div class="d_nav" v-on:click="select_nav('detail')">酒店详情</div><div class="line"></div>
            <div class="d_nav" v-on:click="select_nav('facilities')">酒店设施</div><div class="line"></div>
            <div class="d_nav" v-on:click="select_nav('room')">房型介绍</div>
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
<script src="{{asset('js/libs/moment.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.daterangepicker.min.js')}}"></script>

<script src="https://cdn.staticfile.org/blueimp-gallery/2.25.0/js/blueimp-helper.min.js"></script>
<script src="https://cdn.staticfile.org/blueimp-gallery/2.25.0/js/blueimp-gallery.min.js"></script>
<script src="https://cdn.staticfile.org/blueimp-gallery/2.25.0/js/blueimp-gallery-fullscreen.min.js"></script>
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
{{--<script async defer--}}
        {{--src="http://ditu.google.cn/maps/api/js?key=AIzaSyBJfv6WxdEoTqSgibZDdOL-m-lLWz6UO8E&libraries=geometry,places&callback=mapCallback">--}}
{{--</script>--}}
<script>

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
            hotel:{
                name:"",
                name_en:"",
                tag:"",
                description:"",
                facilities:"",
                location:{
                    continent:"",
                    country:"",
                    city:"",
                    district:"",
                    address:"",
                    transportation:"",
                    lat:"",
                    lng:""
                },
                zy:{
                    recommend:"",
                    good_to_known:""
                },
                detail:{
                    environment:"",
                    room:"",
                    experience:"",
                    restaurant:"",
                    extend:""
                },
                rooms:[],
                policy:{
                    checkin:"",
                    checkout:"",
                    cancellation:"",
                    children:"",
                    extra_bed:"",
                    pet:"",
                    payment:""
                },
                images:[]
            },
            refer : null,
            loading:true,
            section: "detail",
            book : {//change it when select
                checkin:"",
                checkout:"",
                adult:1,
                children:0,
                children_age : [
                ]
            },
            room_style:0, //0 def, 1 with price
            hack_width : "100px",
            style_gallery_image : "width:1028px;height:580px",
            content_width : "width:1028px"
        },
        created:function () {
            var _id = this.$route.params.id;
            this.refer = this.$route.query.refer

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
            window.addEventListener('resize', this.handle_resize)
            this.handle_resize(null);
        },
        beforeDestroy: function () {
            window.removeEventListener('resize', this.handle_resize)
        },
        updated:function(){
            $("#hotel_nav").affix(
                    {
                        offset:{
                            top: function(){
                                //hotel_header
                                var bottom_of_banner = $("#hotel_header").offset().top + $("#hotel_header").outerHeight();

                                var offset_nav = 0;
                                return bottom_of_banner - offset_nav;
                            }
                        }
                    }
            );
        },
        methods:{
            get_data : function(_id){
                const self = this;
                console.log("created");
                axios.post('/api/hotel/'+_id,{
                        })
                        .then(function(response){
                            console.log(response.data);

                            var obj = response.data.obj;
                            for(var i= 0,len = obj.rooms.length;i<len;i++){
                                obj.rooms[i].is_show = false;
                                obj.rooms[i].price = null;
                            }

                            self.hotel = response.data.obj;

                            self.loading = false;
                            hotel_lat = parseFloat(self.hotel.location.lat);
                            hotel_lng = parseFloat(self.hotel.location.lng);
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            markdown : function(str){
//                console.log(str);
                return zhotel_markdown(str);
            },
            str_2_arr : function(str){
                if(!str){
                    return "";
                }

                return str.split("\n").filter(function(entry) { return entry.trim() != ''; });
            },
            compare_image : function(a,b){
                //If compareFunction(a, b) is less than 0, sort a to an index lower than b, i.e. a comes first
                if(this.refer == null){
                    if (a.status > b.status)
                        return -1;
                    if (a.status < b.status)
                        return 1;
                    if (a.created_at > b.created_at)
                        return -1;
                    if (a.created_at < b.created_at)
                        return 1;
                    return 0;
                }
                else{
                    if(a.tag.indexOf(this.refer) !== -1 && b.tag.indexOf(this.refer) === -1){
                        return -1;
                    }
                    else if(b.tag.indexOf(this.refer) !== -1 && a.tag.indexOf(this.refer) === -1){
                        return 1;
                    }
                    else{
                        if (a.status > b.status)
                            return -1;
                        if (a.status < b.status)
                            return 1;
                        if (a.created_at > b.created_at)
                            return -1;
                        if (a.created_at < b.created_at)
                            return 1;
                        return 0;
                    }
                }
            },
            handle_resize : function(event){
                var height = document.documentElement.clientHeight;
                var width = document.documentElement.clientWidth;
                console.log("resize(wxh) = "+width+","+height);
                this.hack_width = (width - 1028 )/2 + "px";
            },
            show_nav : function(e){
                var self = e.target;
                var left = $(self).offset().left;
                var top = $(self).offset().top;
                var h = $(self).outerHeight();
                $("#dialog").css("left", (left)+"px");
                $("#dialog").css("top", (top+h)+"px");
                $("#dialog").slideDown(200);
                e.stopPropagation();
            },
            close_dialog : function(){
                $("#dialog").hide();
            },
            select_nav : function(str){
                this.section = str;
                this.close_dialog();
            }
        },
        computed : {
            zy_recommend_arr : function(){
                return this.hotel.zy.recommend.split("\n");
            },
            zy_g2k_arr : function(){
                return this.hotel.zy.good_to_known.split("\n");
            },
            arr_facilities: function(){
                return this.hotel.facilities.split("\n").filter(function(entry) { return entry.trim() != ''; });;
            },
            detail_extend : function(){
                if(this.hotel.detail.extend){
                    var arr = this.hotel.detail.extend.split("\n");
                    var res = [];
                    var title = "";
                    var content = "";
                    for(var i= 0,len = arr.length;i<len;i++){
                        var item = arr[i];
                        if(item.indexOf("#") == 0){
                            if(title && content){
                                res.push({
                                    "title":title,
                                    "content":content
                                });
                            }
                            title = item.substr(1);
                            content = "";
                        }
                        else{
                            content = content + item +  "\n";
                        }
                    }
                    if(title && content){
                        res.push({
                            "title":title,
                            "content":content
                        });
                    }
                }
                return res
            },
            sorted_covers : function(){
                var arr = [];
                console.log("sorted covers");
                for(var i= 0,len = this.hotel.images.length;i<len;i++){
                    if(this.hotel.images[i].status > 0){
                        arr.push(this.hotel.images[i]);
                    }
                }
                arr.sort(this.compare_image);
                return arr;
            },
            section_str : function(){
                if(this.section == "room"){
                    return "房型介绍";
                }
                else if(this.section == "detail"){
                    return "酒店详情";
                }
                else if(this.section == "facilities"){
                    return "酒店设施";
                }
                return this.section;
            }
        }
    })



</script>
@endsection