<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1,user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>TraveliD x Hotels</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    {{--<link rel="apple-touch-icon" href="{{URL::to('images/h5_logo.png')}}"/>--}}
    <link rel="stylesheet" href="{{asset('css/min.css')}}">
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/libs/daterangepicker_ihotel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/libs/swiper.min.css')}}">
    @yield('style')
    <style>
        html, body {
        }
        .hotel_footer{
            background-color: #000;
            min-height: 200px;
            width: 100%;
        }
        .hotel_content{
            clear: both;
            background-color: #f8f8f8;
        }
        .hotel_name{
            font-size: 26px;
            font-weight: bolder;
        }
        .hotel_name_en{
            font-size: 14px;
        }
        .hotel_bread{
            color: grey;
            font-size: 14px;
        }
        .hotel_price{
            color: #c99c76;
            font-size: 34px;
            font-weight: bolder;
        }
        .color_highlight{
            color: #c99c76;
        }
        .hotel_icons{
            padding: 8px 0;
        }
        .hotel_nav_item{
            float: left;
            width: 25%;
            padding-top: 20px;
            height: 60px;
            text-align: center;
            color: white;
            font-size: 14px;

        }
        .hotel_nav_item:hover{
            background-color: #5F6164;
            cursor: pointer;
        }
        .hotel_nav_item_focus{
            float: left;
            width: 25%;
            /*padding: 20px;*/
            height: 60px;
            padding-top:18px;
            text-align: center;
            background-color: #5F6164;
            font-size: 16px;
            color: white;
            font-weight: bolder;
            background-position: bottom center;
            background-size: 12px;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_tr.png')}}");
        }
        .hotel_nav{
            width: 100%;
            background-color: #373A3E;
        }
        .hotel_line{
            width: 100%;
            height: 1px;
            background-color: #e4e4e4;
            clear: both;
            margin: 15px 0;
        }
        .hotel_des{
            line-height: 28px;
        }

        .hotel_content_title{
            font-size: 22px;
            font-weight: bolder;
            margin-bottom: 10px;
        }
        .hotel_content_title_small{
            font-size: 20px;
            font-weight: bolder;
            margin-bottom: 10px;
        }
        .hotel_text{
            font-size: 14px;
            line-height: 24px;
        }

        .hotel_map{
            width: 100%;
            min-height:300px;
            background-color: linen;
        }

        .hotel_ins{
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }


        .swiper-button-prev{
            position: absolute;
            top: 50%;
            width: 36px;
            height: 36px;
            margin-top: -22px;
            margin-left: 0px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 36px 36px;
            -webkit-background-size: 36px 36px;
            background-size: 36px 36px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_left.png')}}");
        }
        .swiper-button-next{
            position: absolute;
            top: 50%;
            width: 44px;
            height: 44px;
            margin-top: -22px;
            margin-right: 0px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 36px 36px;
            -webkit-background-size: 36px 36px;
            background-size: 36px 36px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_right.png')}}");
        }
        .swiper-button-next2{
            position: absolute;
            top: 273px;
            width: 54px;
            height: 54px;
            right: 20px;
            cursor: pointer;
            -moz-background-size: 54px 54px;
            -webkit-background-size: 54px 54px;
            background-size: 54px 54px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_right.png')}}");
        }
        .swiper-button-prev2{
            position: absolute;
            top: 273px;
            left:20px;
            width: 54px;
            height: 54px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 54px 54px;
            -webkit-background-size: 54px 54px;
            background-size: 54px 54px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_left.png')}}");
        }
        .swiper-pagination-travelid .swiper-pagination-bullet-active {
            background: white;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            /*width: 60%;*/
            padding: 0;
            margin: 0;
            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .swiper-container2{
            width: 100%;
            height: 600px;
        }

        .affix {
            top:0;
            z-index: 10 !important;
            display: block;
        }
        .zy_float{
            float: left;width: 50%;line-height: 28px
        }
        .btn_book{
            font-size: 18px;
            color: white;
            background-color: #c29c76;
            border-radius: 0;
            width: 160px;
            height: 40px;
        }
        .btn_book:hover{
            color: white;
        }
        .btn_book:focus{
            color: white;
        }
        .input_date{
            float: left;
            width: 120px;
            height: 30px;
            font-size: 14px;
            margin-right: 10px;
            border: 1px solid #CCCCCC;
            outline: none;
            padding: 6px;
            background-size: 12px 6px;
            background-position: right 6px center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_down.png')}}");
        }
        .input_people{
            width: 250px;
            font-size: 14px;
            height: 30px;
            border: 1px solid #CCCCCC;
            outline: none;
            padding: 6px;
            background-size: 12px 6px;
            background-position: right 6px center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_down.png')}}");
        }
        .input_people_box{
            -webkit-box-shadow: 3px 3px 10px rgba(0,0,0,.5);
            -moz-box-shadow: 3px 3px 10px rgba(0,0,0,.5);
            border:1px solid #bfbfbf;
            background-color:#FFF;
            padding:5px 12px;
            line-height:20px;
            color:#aaa;
            box-shadow:3px 3px 10px rgba(0,0,0,.5);
        }
        .unselectable {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .font_sub{
            color: #666666;
            font-size: 14px;
            line-height: 24px;
        }
        .font_sub_4{
            color: #666666;
            font-size: 14px;
            line-height: 24px;
            float: left;
            width: 25%;
            padding-right: 30px;
        }
        .width_100{
            width: 100%;

            position: relative;
        }
        .plan_check{
            list-style-image:url("{{URL::to('images/hotel_li_check.png')}}");
            padding: 0 20px;
            line-height: 24px;
            color: #666666;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div>
    <div class="hotel_header" id="hotel_header">
        <div class="swiper-container2">
            <div class="swiper-wrapper">
                @foreach($hotel["images"] as $url)
                    <div class="swiper-slide" style="width: 70%">
                        <img style="height: 600px;width: 100%;object-fit: cover" src="{{$url}}" />
                    </div>
                @endforeach
            </div>

            <div style="position: absolute;left: 0;top: 0;width: 100%">
                <div style="z-index: 9999;width: 15%;background-color: rgba(0,0,0,0.3);height: 600px;position: absolute;left: 0;top: 0">
                </div>
                <div style="z-index: 9999;width: 15%;background-color: rgba(0,0,0,0.3);height: 600px;position: absolute;right: 0;top: 0">
                </div>
            </div>
            <div class="swiper-button-prev2" style="z-index: 999999"></div>
            <div class="swiper-button-next2" style="z-index: 999999"></div>
            <div class="swiper-pagination"></div>
        </div>
        <div style="position: relative;width: 60%;margin-left: auto;margin-right: auto">
            <div style="width: 100%;padding-right: 300px;float: left">
                <div style="height: 30px"></div>
                <div class="hotel_name">{{$hotel["name"]}}</div>
                <div class="hotel_name_en">{{$hotel["name_en"]}}</div>
                <div class="hotel_icons">ICON1 ICON2</div>
                <div class="hotel_bread">
                    <span>{{$hotel["continent"]}}</span> >
                    <span>{{$hotel["country"]}}</span> >
                    <span>{{$hotel["city"]}}</span> >
                    <span class="color_highlight">酒店</span>
                </div>
                <div style="height: 30px"></div>
            </div>
            <div style="width: 300px;float: left;margin-left: -300px">
                <div style="height: 30px"></div>
                <div class="hotel_price">2755<span style="font-size: 16px;font-weight: normal;margin-left: 6px">起 / 晚</span></div>
                <div style="padding: 10px 0">
                    <form style="display: none" id="form_book">
                        <div id="div_checkinout">
                            <input id="input_checkin" class="input_date" type="text" placeholder="入住日期" readonly/>
                            <input id="input_checkout"  class="input_date" type="text" placeholder="退房日期" readonly/>
                            <div style="clear: both"></div>
                        </div>
                        <div style="height: 10px;width: 10px;clear: both"></div>
                        <div>
                            <input id="input_people" class="input_people" type="text" value="2成人，0儿童" placeholder="请选择入住人数" readonly/>
                        </div>
                        <div style="height: 10px;width: 10px;clear: both"></div>
                    </form>
                    <button type="button" class="btn btn_book" onclick="book_check(this)">查询预订</button>
                </div>
            </div>
        </div>
        <div style="clear: both"></div>
    </div>
    <div class="hotel_content">
        <div style="height: 60px">
            <div class="hotel_nav" id="hotel_nav">
                <div style="width: 60%;position: relative;margin-left: auto;margin-right: auto;">
                    <div>
                        <div data-section="detail" onclick="section(this)" class="hotel_nav_item_focus">酒店详情</div>
                        <div data-section="rooms" onclick="section(this)" class="hotel_nav_item">客房类型</div>
                        <div data-section="section_facilities" onclick="section(this)" class="hotel_nav_item">设施政策</div>
                        <div data-section="similar" onclick="section(this)" class="hotel_nav_item">相似酒店</div>
                    </div>
                    <div style="clear: both"></div>
                </div>
            </div>
        </div>
        <div id="hotel_content_container" style="width: 60%;position: relative;margin-left: auto;margin-right: auto;">
            <div style="height: 30px;width:100px"></div>
            <div style="width: 100%">
                <div style="float: left;width: 30%;padding-right: 15px;">
                    <img style="width: 100%;height: 200px;background-color: lightblue" />
                    <div style="padding: 15px;color: #666666">
                        <div>Twin Room City View</div>
                        <div style="height: 10px;width: 10px"></div>
                        <p>
                            Relax in this City Room with twin beds
                            Relax in this City Room with twin beds
                            Relax in this City Room with twin beds
                        </p>
                        <span>Read more</span>
                    </div>
                </div>
                <div style="float: left;width: 70%">
                    <div class="room_plan">
                        <div style="float:left;width: 100%;padding-right: 80px;padding-left: 20px;padding-top: 10px">
                            <div style="color: #333333;font-size: 20px;font-weight: bolder;line-height: 20px">Best Available Rate</div>
                            <div style="height: 15px;width: 100px"></div>
                            <ul class="plan_check">
                                <li>Includes Premium Benefits</li>
                                <li>Includes Premium Benefits</li>
                                <li>Includes Premium Benefits</li>
                                <li>Includes Premium Benefits</li>
                            </ul>

                            <button style="background: none;border: none;outline: none;padding: 0;margin: 0" onclick="plan_show_detail(this)">View Rate Details <img class="icon_down" width="12px" src="{{URL::to('images/hotel_icon_down.png')}}"/> </button>
                            <div class="plan_detail" style="display:none">
                                <div style="height: 20px;width: 100px"></div>
                                <p class="font_sub">
                                    <strong>Cancellation: </strong>
                                    This rate plan is for select Visa Premium Card holders
                                    Cardholder must reserve and pay for the room using a qualifying credit card. Qualifying Visa premium cards include Visa Signature and Visa Infinite cards and select Visa Gold and Visa Platinum cards
                                </p>
                                <div style="height: 20px;width: 100px"></div>
                                <p class="font_sub">
                                    <strong>Cancellation: </strong>
                                    This rate plan is for select Visa Premium Card holders
                                    Cardholder must reserve and pay for the room using a qualifying credit card. Qualifying Visa premium cards include Visa Signature and Visa Infinite cards and select Visa Gold and Visa Platinum cards
                                </p>
                            </div>
                        </div>
                        <div style="float: left;width: 80px;margin-left: -80px">
                            2755/晚
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
        </div>
        <div class="hotel_map" style="position: relative;width: 100%">
            <div id="map" style="width: 100%;height: 300px;position: absolute;left: 0;top: 0"></div>
            <div style="position: absolute;width: 60%;margin-left: auto;margin-right: auto">
                <div style="height: 20px"></div>
                <div  style="padding: 20px;background-color: #FFF;margin-left: 20%;width: 320px;height: 260px;">
                    <div style="overflow-y: auto;height: 220px;">
                        <div class="hotel_content_title_small">地址</div>
                        <div class="hotel_text">{{$hotel["location"]["address"]}}</div>
                        <div style="height: 20px"></div>
                        <div class="hotel_content_title_small">交通指南</div>
                        <div class="hotel_text">{{$hotel["location"]["transportation"]}}</div>
                    </div>
                </div>
                <div style="height: 20px"></div>
            </div>
        </div>

        <div class="hotel_footer"></div>
    </div>
</div>
<div id="div_people" class="input_people_box" style="z-index: 99999;position: absolute;display: none;">
        <div style="float:left;text-align: center;padding: 30px 15px">
            <label>成人</label>
            <div style="">
                <span onclick="adult_add(this)" class="unselectable" style="float: left;font-size: 22px;cursor: pointer;">+</span>
                <p style="float: left;width: 60px;text-align: center;color: #3c3c3c">2</p>
                <span onclick="adult_minus(this)" class="unselectable" style="float: left;font-size: 22px;cursor: pointer;">-</span>
                <div style="clear: both"></div>
            </div>
        </div>
        <div style="float:left;width: 1px;height: 100px;background-color: #CCC">

        </div>
        <div style="float:left;text-align: center;padding: 30px 15px">
            <label>儿童</label>
            <div style="">
                <span onclick="children_add(this)" class="unselectable" style="float: left;font-size: 22px;cursor: pointer;">+</span>
                <p style="float: left;width: 60px;text-align: center;color: #3c3c3c">0</p>
                <span onclick="children_minus(this)" class="unselectable" style="float: left;font-size: 22px;cursor: pointer;">-</span>
                <div style="clear: both"></div>
            </div>
        </div>
        <div style="clear: both"></div>
        <button type="button" class="btn" style="width: 100%;margin-top: 10px;margin-bottom: 10px;background-color: white;border: 1px solid #efefef">关闭</button>
    </div>
</div>
@yield('script')
<script src="{{ asset('js/min.js')}}"></script>
<script src="{{asset('js/libs/sprintf.min.js')}}"></script>
<script src="{{ asset('js/libs/toastr.min.js')}}"></script>
<script src="{{ asset('js/libs/moment.min.js')}}"></script>
<script src="{{ asset('js/libs/jquery.daterangepicker.min.js')}}"></script>
<script src="{{ asset('js/libs/swiper.min.js')}}"></script>
<script src="{{ asset ("js/libs/g_map_ihotel.js") }}" type="text/javascript"></script>
<script async defer
        src="http://ditu.google.cn/maps/api/js?key=AIzaSyBJfv6WxdEoTqSgibZDdOL-m-lLWz6UO8E&libraries=geometry,places&callback=initMap">
</script>
<script type="text/javascript">
    var hotel = {!! json_encode($hotel) !!}
    console.log(hotel)
    var hotel_lat = parseFloat(hotel.location.lat)
    var hotel_lng = parseFloat(hotel.location.long);
    var icon_down = "{{URL::to('images/hotel_icon_down.png')}}";
    var icon_up = "{{URL::to('images/hotel_icon_up.png')}}";
    function book_check(self){
        if($("#form_book").is(':visible')){
            //ajax data
            //switch to room section
            //display plans of all room with the checkin-out
        }
        else{
            $("#form_book").slideDown(200);
            $(self).animate({
               width:"250px"
            },200);
        }
    }
    function plan_show_detail(self){
        var div = $(self).parent().find(".plan_detail");
        var img = $(self).parent().find(".icon_down");
        console.log(img);
        if(div.is(':visible')){
            div.css("display","none");
            img.attr("src",icon_down);
        }
        else{
            div.css("display","block");
            img.attr("src",icon_up);
        }
    }
    function refresh(self){
        location.reload();
    }
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        prevButton: '.swiper-button-next',
        nextButton: '.swiper-button-prev',
        loop : true
    });
    var swiper2 = new Swiper('.swiper-container2', {
//        pagination: '.swiper-pagination',
        slidesPerView: 'auto',
        centeredSlides: true,
        paginationClickable: true,
        prevButton: '.swiper-button-next2',
        nextButton: '.swiper-button-prev2',
        spaceBetween: 0,
        loop:true
    });
    function section(self){
        $(".hotel_nav_item_focus").each(function(i,obj){
           $(obj).attr("class","hotel_nav_item");
        });
        $(self).attr("class", "hotel_nav_item_focus");
        var s = $(self).attr("data-section");
        if(s == "section_facilities"){
            section_facilities();
        }
        else if(s == "detail"){
            section_detail();
        }
        else if(s == "rooms"){
            section_room();
        }
    }
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
    function section_facilities(){
        var root = $("#hotel_content_container");
        root.empty();
        var res = "";
        var item1 = '<div style="height: 30px"></div><div class="hotel_content_title">酒店设施</div>';
        res = res + item1;
        var f_c = "";
        for(var i = 0,len = hotel.section.facilities.length;i<len;i++){
            f_c = f_c + sprintf('<div class="font_sub_4">%s</div>',hotel.section.facilities[i]);
        }
        res = res + sprintf("<div class='width_100'>%s</div>",f_c);
        var item2 = '<div style="height: 30px;clear: both"></div><div class="hotel_content_title">订前必读</div>';
        res = res + item2;
        var g2k = hotel.section.good_to_know;
        console.log(g2k);
        res = res + sprintf('<div class="font_sub">入住时间：%s</div>',g2k.checkin);
        res = res + sprintf('<div class="font_sub">退房时间：%s</div>',g2k.checkout);

        for(i = 0,len = g2k.cancellation.length;i<len;i++){
            res = res + sprintf('<div  class="font_sub">取消政策：%s</div>',g2k.cancellation[i]);
        }
        res = res + '<div style="height: 20px;width: 1px"></div>';
        res = res + '<div class="font_sub">儿童政策</div>';
        for(i = 0,len = g2k.children.length;i<len;i++){
            res = res + sprintf('<div class="font_sub">· %s</div>',g2k.children[i]);
        }
        res = res + '<div style="height: 20px;width: 1px"></div>';
        res = res + '<div class="font_sub">加床</div>';
        for(i = 0,len = g2k.extra_bed.length;i<len;i++){
            res = res + sprintf('<div class="font_sub">%s</div>',g2k.extra_bed[i]);
        }
        res = res + '<div style="height: 20px;width: 1px"></div>';
        res = res + '<div class="font_sub">宠物</div>';
        for(i = 0,len = g2k.pets.length;i<len;i++){
            res = res + sprintf('<div class="font_sub">%s</div>',g2k.pets[i]);
        }
        res = res + '<div style="height: 20px;width: 1px"></div>';
        var cards = g2k.cards[i];
        for(i = 1,len = g2k.cards.length;i<len;i++){
            cards = cards + " / " + g2k.cards[i] ;
        }
        res = res + sprintf('<div class="font_sub">酒店接受银行卡类型：%s</div>',cards);
        root.append(res);
    }
    function section_detail(){
        var root = $("#hotel_content_container");
        root.empty();
        var res = "";
        res = res + sprintf(html_detail_header,image_quote_left,nl2br(hotel.description),image_quote_right);
        var item1 = "";
        var item2 = "";
        var item3 = "";
        for(var i= 0,len = hotel.section.zy_recommendation.length;i<len;i++){
            item1 = item1 + sprintf(zy_item,hotel.section.zy_recommendation[i]);
        }
        for(i= 0,len = hotel.section.zy_introduction.length;i<len;i++){
            item2 = item2 + sprintf(zy_item,hotel.section.zy_introduction[i]);
        }
        res = res + sprintf(html_detail_zy,item1,item2);

        for(i=0,len = hotel.section.detail.length;i<len;i++){
            item3 = item3 + sprintf(html_detail_kv,hotel.section.detail[i].title,
                            hotel.section.detail[i].markdown
                    );
        }
        res = res + item3;
        root.append(res);
    }
    function section_room(){
        var root = $("#hotel_content_container");
        root.empty();
        var res = '<div style="height: 30px;width:100px"></div>';
        for(var i= 0,len =  hotel.section.rooms.length;i<len;i++){
            var lis = "";
            var room = hotel.section.rooms[i];
            for(var j= 0,jLen = room.highlight.length;j<jLen;j++){
                lis = lis + sprintf('<li><span style="color: #3c3c3c;font-size: 14px;line-height: 24px">%s</span></li>',room.highlight[j]);
            }
            var title = sprintf('<div class="hotel_content_title">%s</div>',room.title);
            var des = sprintf('<p class="hotel_text">%s</p>',nl2br(room.description));
            var img = '<img src="'+room.images[0]+'" width="100%"/>';

            res = res + '<div style="width: 100%"><div style="float: left;width: 100%;padding-left: 33%;padding-top: 30px;padding-bottom: 30px;min-height: 200px;">';
            res = res + title;
            res = res + '<ul style="padding:0 20px;color: #C19B76">';
            res = res + lis;
            res = res + '</ul>';
            res = res + des;
            res = res + '</div><div style="float:left;margin-left: -100%;width: 30%;height: 170px">';
            res = res + img;
            res = res + '</div><div style="clear: both"></div></div><div style="height:30px;width: 100px"></div><div style="height:1px;width: 100%;background-color: #999999"></div>'
        }
        root.append(res);
    }
    var image_quote_left = "{{URL::to('images/hotel_quote_left.png')}}";
    var image_quote_right = "{{URL::to('images/hotel_quote_right.png')}}";
    var html_detail_header = ''
                    +'                <div style="height: 30px"></div>'
                    +'            <div class="hotel_des">'
                    +'                <img style="float: left" width="24px" src="%s"/>'
                    +'                <p style="padding:0 40px;font-size: 16px;line-height: 38px">%s</p>'
                    +'                <img style="float: right;margin-top: -30px" width="24px" src="%s"/>'
                    +'            </div>'
                    +'            <div class="hotel_line"></div>'
            ;

    var zy_item = '<li><span style="color: #3c3c3c;font-size: 14px;line-height: 24px">%s</span></li>';
    var html_detail_zy = ''
                    +'            <div>'
                    +'                <div class="zy_float">'
                    +'                    <div class="hotel_content_title">致游推荐</div>'
                    +'                    <ul style="padding:0 20px;color: #C19B76">' +
                                            '%s'
                    +'                    </ul>'
                    +'                </div>'
                    +'                <div class="zy_float">'
                    +'                    <div class="hotel_content_title">致游知道</div>'
                    +'                    <ul style="padding:0 20px;color: #C19B76">' +
                                            '%s'
                    +'                    </ul>'
                    +'                </div>'
                    +'                <div style="clear: both"></div>'
                    +'            </div>'
                    +'            <div class="hotel_line"></div>'
            ;
    var html_detail_kv = ''
                    +'            <div>'
                    +'            <div class="hotel_content_title">%s</div>'
                    +'            <div style="font-size: 14px;line-height: 24px">'
                    +'                %s'
                    +'            </div>'
                    +'        </div>'
                    +'        <div style="height: 30px;width: 100px"></div>'
            ;
    function nl2br (str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
    }
    $("#div_checkinout").dateRangePicker(
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
                },
                hoveringTooltip: function (days, startTime, hoveringTime) {
                    return days > 1 ? days + "天" + (days - 1) + '晚' : '';
                },
                language:"cn"
            }
    );
    $("#input_people").click(function(e){
        var left = $(this).offset().left;
        var top = $(this).offset().top;
        var h = $(this).outerHeight();
        console.log(top);
        $("#div_people").css("left", (left)+"px");
        $("#div_people").css("top", (top+h)+"px");
        $("#div_people").slideDown(200);
        e.stopPropagation();
    });
    $(document).click(function(e) {

        var container = $("#div_people");

        // if the target of the click isn't the container nor a descendant of the container
        if (container.is(':visible') && !container.is(e.target) && container.has(e.target).length === 0)
        {
            container.slideUp(200);
        }
    });
    var book_adult = 2;
    var book_children = 0;
    function adult_add(self){
        var c = $(self).next();
        var n = book_adult;
        c.text(n+1);
        book_adult = n + 1;
        set_adult_children();
    }
    function adult_minus(self){
        var c = $(self).prev();
        var n = book_adult;
        if(n-1 > -1){
            c.text(n-1);
            book_adult = n-1;
        }
        else{
            c.text(0);
            book_adult = 0
        }
        set_adult_children();
    }
    function children_add(self){swiper.min.js
        var c = $(self).next();
        var n = book_children;
        c.text(n+1);
        book_children = n + 1;
        set_adult_children();
    }
    function children_minus(self){
        var c = $(self).prev();
        var n = book_children;
        if(n-1 > -1){
            c.text(n-1);
            book_children = n-1;
        }
        else{
            c.text(0);
            book_children = 0
        }
        set_adult_children();
    }
    function set_adult_children(){
        $("#input_people").val(""+book_adult+"成人，"+book_children+"儿童")
    }
</script>
</body>
</html>