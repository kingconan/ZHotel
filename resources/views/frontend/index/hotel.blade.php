@extends("frontend.layout.base")
@section('style')

    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/swiper.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/zhotel_lib.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/daterangepicker_ihotel.min.css')}}"/>
    <link rel="stylesheet" href="https://cdn.staticfile.org/blueimp-gallery/2.25.0/css/blueimp-gallery-indicator.min.css"/>
    <link rel="stylesheet" href="https://cdn.staticfile.org/blueimp-gallery/2.25.0/css/blueimp-gallery.min.css"/>

    <style>
        [v-cloak] {
            display: none;
        }
    </style>
    <style>
        .hotel_content_title{
            font-size: 22px;
            font-weight: 300;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .hotel_content_title_small{
            font-size: 20px;
            font-weight: 300;
            margin-bottom: 10px;
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
            width: 25%;
            height: 60px;
            cursor: pointer;
        }
        .hotel_nav_item_focus{
            float: left;
            width: 25%;
            height: 60px;
            padding-top:18px;
            text-align: center;
            background-color: #5F6164;
            font-size: 16px;
            color: white;
            font-weight: 300;
            background-position: bottom center;
            background-size: 12px;
            background-repeat: no-repeat;
            background-image: url("/images/hotel_tr.png");
        }
        .hotel_nav{
            width: 100%;
            height: 60px;
            background-color: #373A3E;
        }
        .hotel_name{
            font-size: 26px;
            font-weight: bolder;
            line-height: 28px;
        }
        .hotel_name_en{
            font-size: 14px;
            line-height: 28px;
        }
        .hotel_bread{
            color: grey;
            font-size: 14px;
            line-height: 28px;
        }
        .hotel_price{
            color: #c99c76;
            font-size: 34px;
            font-weight: bolder;
        }
        .hotel_line{
            width: 100%;
            height: 1px;
            background-color: #EBEBEB;
            clear: both;
            margin: 15px 0;
        }
        .zy_float{
            float: left;width: 50%;line-height: 28px
        }
        .affix {
            top:0;
            z-index: 10 !important;
            display: block;
            position: fixed;
        }
        #right_picker.affix{
            top:60px;
            /*z-index: 10 !important;*/
            /*display: block;*/
            /*position: fixed;*/
            /*right: 0;*/
        }
        .font_sub{
            color: #666666;
            font-size: 14px;
            line-height: 28px;
        }
        .font_sub_4{
            color: #666666;
            font-size: 14px;
            font-weight: 200;
            line-height: 28px;
            float: left;
            width: 25%;
            padding-right: 30px;
        }
        .font_sub_fa{
            color: #666666;
            font-size: 14px;
            font-weight: 200;
            line-height: 28px;
            float: left;
            width: 25%;
            padding-right: 30px;
            margin-bottom: 20px;
        }
        .hotel_footer{
            background-color: #161D21;
            width: 100%;
            position: relative;
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

        .input_normal{
            font-size: 20px;
            font-weight: 200;
            border-bottom: 1px solid #CCCCCC;
            border-top: 1px solid #FFF;
            border-left: 1px solid #FFF;
            border-right: 1px solid #FFF;
            outline: none;
            padding: 6px 0;
            background-size: 12px 6px;
            background-position: right 6px center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_down.png')}}");
            width: 100%;
            margin-bottom: 20px;
        }

        .input_date{
            float: left;
            width: 120px;
            height: 30px;
            font-size: 14px;
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
        .date-picker-wrapper{
            -webkit-box-shadow: 0px 0px 8px rgba(0,0,0,.2);
            -moz-box-shadow: 0px 0px 8px rgba(0,0,0,.2);
            box-shadow:0px 0px 8px rgba(0,0,0,.2);
            border:1px solid #f0f0f0;
            z-index: 999999;
        }
        .input_people_box{
            -webkit-box-shadow: 0px 0px 8px rgba(0,0,0,.2);
            -moz-box-shadow: 0px 0px 8px rgba(0,0,0,.2);
            border:1px solid #f0f0f0;
            background-color:#FFF;
            padding:5px 12px;
            line-height:20px;
            color:#aaa;
            box-shadow:0px 0px 8px rgba(0,0,0,.2);
        }
        .unselectable {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .affix-bottom{
            position: absolute;
            bottom:0
        }

        .font_normal{
            font-size: 14px;
            font-weight: 200;
            line-height: 28px;
        }

        .plan_normal{
            background-color: #FFF;
            border: 1px solid lightgrey;
            cursor: pointer;

        }
        .plan_active{
            background-color: whitesmoke;
            border: 1px solid lightgrey;
            cursor: pointer;
        }
    </style>
    <style>

        .swiper-container {
            width: 100%;
        }
        .swiper-container1 {
            width: 100%;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            list-style: none;
            padding: 0;
            z-index: 1;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
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
            min-height: 300px;
        }
        .swiper-pagination-fraction{
            color:white;
            background: rgba(0,0,0,.5);
            width: 64px;
            border-radius: 24px;
            left:50%;
            margin-left: -60px;
        }
        .swiper-pagination .swiper-pagination-bullet-active {
            background: white;
        }
        .swiper-button-next2{
            position: absolute;
            top: 50%;
            width: 54px;
            height: 54px;
            margin-top: -27px;
            right: 20px;
            z-index: 10;
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
            top: 50%;
            left:20px;
            width: 54px;
            height: 54px;
            margin-top: -27px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 54px 54px;
            -webkit-background-size: 54px 54px;
            background-size: 54px 54px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_left.png')}}");
        }
        .swiper-pagination .swiper-pagination-bullet-active {
            background: white;
        }

        .markdown-image{
            width: 100%;
            margin: 8px 0;
            max-height: 360px;
            object-fit: cover;
        }
        .shadow_gallery{
            z-index: 2;background-color: rgba(0,0,0,0.6);height: 580px;position: absolute;left: 0;top: 0
        }
        .shadow_gallery_r{
            z-index: 2;background-color: rgba(0,0,0,0.6);height: 580px;position: absolute;right: 0;top: 0
        }
        .c1{
            position: relative;margin-left: auto;margin-right: auto;
        }


        #div_trans::-webkit-scrollbar {
            width: 4px;/*滚动条宽度*/
        }

        #div_trans::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(255,255,255,0.3);
            background-color: #FFFFFF;/*滚动条北京*/

        }

        #div_trans::-webkit-scrollbar-thumb {
            background-color: #F0F0F0;
            outline: 1px solid #F0F0F0;
        }
        .label_d{
            color:#999999;
            font-weight: 200;
        }
        .toast-top-center {
            top: 50%;
            margin: 0 auto;
        }
        .plan_check{
            list-style-image:url("{{URL::to('images/hotel_li_check.png')}}");
            padding: 0 20px;
            line-height: 24px;
            color: #666666;
            font-size: 14px;
        }
        strong{
            font-size: 30px;
            font-weight: 300;
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
            <div class="hotel_header" id="hotel_header">
                <div v-if="sorted_covers" class="swiper-container banner-gallery">
                    <div class="swiper-wrapper">
                        <div v-for="(image, index) in sorted_covers" class="swiper-slide" :style="style_gallery_image">
                            <img :style="style_gallery_image+';object-fit: cover'" :src="image.url" />
                        </div>
                    </div>

                    <div style="position: absolute;left: 0;top: 0;width: 100%">
                        <div class="shadow_gallery" :style="'width:'+ hack_width">
                        </div>
                        <div class="shadow_gallery_r" :style="'width:'+ hack_width">
                        </div>
                    </div>
                    <div class="swiper-button-next2"></div>
                    <div class="swiper-button-prev2"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="c1" :style="content_width">
                    <div style="float: left;width: 100%;padding-right: 300px;">
                        <div style="height: 30px"></div>
                        <div class="hotel_name"><% hotel.name %></div>
                        <div class="hotel_name_en"><% hotel.name_en %></div>
                        <div class="hotel_icons">ICON1 ICON2</div>
                        <div class="hotel_bread">
                            <span><% hotel.location.continent %></span> >
                            <span><% hotel.location.country %></span> >
                            <span><% hotel.location.city %></span> >
                            <span class="color_highlight">酒店</span>
                        </div>
                        <div style="height: 30px"></div>
                    </div>
                    <div style="float: left;width: 250px;margin-left: -250px">
                        <div style="height: 30px"></div>
                        <div class="hotel_price">??<span style="font-size: 16px;font-weight: normal;margin-left: 6px">起 / 晚</span></div>
                        <div style="padding: 10px 0;text-align: right">
                            <form style="display: none" id="form_book">
                                <div id="div_checkinout">
                                    <input style="color:#999999;margin-right: 10px" id="input_checkin" name="checkin" v-model="book.checkin" class="input_date" type="text" placeholder="入住日期" readonly/>
                                    <input style="color:#999999" id="input_checkout" name="checkout" v-model="book.checkout"  class="input_date" type="text" placeholder="退房日期" readonly/>
                                    <div style="clear: both"></div>
                                </div>
                                <div style="height: 10px;width: 10px;clear: both"></div>
                                <div>
                                    <input style="color:#999999" v-on:click="click_people" id="input_people" class="input_people" type="text" :value="book.adult+'成人, '+book.children+'儿童'" placeholder="请选择入住人数" readonly/>
                                </div>
                                <div style="height: 10px;width: 10px;clear: both"></div>
                            </form>
                            <button type="button" class="btn btn_book" v-on:click="book_check">查询预订</button>
                            <div style="height:15px;width: 10px"></div>
                        </div>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
            <div class="hotel_content">
                <div style="height: 60px">
                    <div class="hotel_nav" id="hotel_nav">
                        <div  class="c1" :style="content_width">
                            <div>
                                <div data-section="detail" v-on:click="nav_section" :class="section == 'detail' ? 'hotel_nav_item_focus' : 'hotel_nav_item'">酒店详情</div>
                                <div data-section="rooms" v-on:click="nav_section" :class="section == 'rooms' ? 'hotel_nav_item_focus' : 'hotel_nav_item'">客房类型</div>
                                <div data-section="section_facilities" v-on:click="nav_section" :class="section == 'section_facilities' ? 'hotel_nav_item_focus' : 'hotel_nav_item'">设施政策</div>
                                <div data-section="similar" v-on:click="nav_section" :class="section == 'similar' ? 'hotel_nav_item_focus' : 'hotel_nav_item'">相似酒店</div>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
                <div id="hotel_content_container" class="c1" :style="content_width">
                    <div style="width: 100%;padding-right:260px;float: left">
                        <div style="height: 30px;width:100px"></div>
                        <div v-if="section == 'detail'">
                            <div style="height: 30px"></div>
                            <div class="font_normal">
                                <img style="float: left" width="24px" src="/images/hotel_quote_left.png"/>
                                <p style="padding:0 40px;font-size: 16px;line-height: 38px;" v-html="hotel.description"></p>
                                <img style="float: right;margin-top: -30px" width="24px" src="/images/hotel_quote_right.png"/>
                            </div>
                            <div style="height: 30px;"></div>
                            <div class="hotel_line"></div>

                            <div>
                                <div class="zy_float">
                                    <div class="hotel_content_title">致游推荐</div>
                                    <ul class="font_normal" style="padding:0 20px;color: #C19B76;">
                                        <li style="margin-bottom: 20px;font-size: 14px;line-height: 28px" v-for="v in zy_recommend_arr">
                                            <span style="color: #3c3c3c;"><%v%></span></li>
                                    </ul>
                                </div>
                                <div class="zy_float">
                                    <div class="hotel_content_title">致游知道</div>
                                    <ul class="font_normal" style="padding:0 20px;color: #C19B76;">
                                        <li style="margin-bottom: 20px;font-size: 14px;line-height: 28px" v-for="v in zy_g2k_arr">
                                            <span style="color: #3c3c3c;"><%v%></span></li>
                                    </ul>
                                </div>
                                <div style="clear: both"></div>
                            </div>

                            <div class="hotel_line"></div>
                            <div style="height: 1px"></div>
                            <div v-if="hotel.detail.extend">
                                <div v-for="section in detail_extend">
                                    <div class="hotel_content_title"><%section.title%></div>
                                    <div class="font_normal" v-html="markdown(section.content)"></div>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="section == 'rooms'">
                            {{--no checkin checkout--}}
                            <div v-if="room_style == 0">
                                <div v-for="room in hotel.rooms">
                                    <div style="background-color: #FAFAFA">
                                        <div style="float: left;width: 240px;">
                                            <img v-if="room.images_str" :src="room.images_str.split('\n')[0]" width="240px"  v-on:click="view_room_images(room.images_str)">
                                            <div v-else style="width: 100%;padding:60px 0;background-color: whitesmoke;text-align: center;color: lightgrey">暂无图片</div>
                                        </div>
                                        <div style="float: left;width: 528px;padding:0 ;background-color: white">
                                            <div style="height: 1px;width: 100%;background-color: lightgrey"></div>
                                            <div style="padding: 0 30px">
                                                <div class="hotel_content_title"><% room.name %></div>
                                                <div style="height: 8px"></div>
                                                <ul class="font_normal" style="padding:0 15px;color: #C19B76">
                                                    <li v-for="h in str_2_arr(room.highlight)">
                                                        <span style="color: #3c3c3c;font-size: 14px;line-height: 22px"><% h %></span>
                                                    </li>
                                                </ul>
                                                <div style="height: 8px"></div>
                                                <p class="font_normal" v-html="markdown(room.description)"></p>
                                                <div style="height: 8px"></div>
                                                <p class="font_normal" v-html="markdown(room.facilities)"></p>
                                            </div>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both;height:15px;width: 10px"></div>
                                </div>
                            </div>
                            {{--with checkin checkout--}}
                            <div v-else>
                                    <div v-for="(room,roomIndex) in hotel.rooms">
                                        <div style="display: table;">
                                            <div style="display: table-cell;width: 240px;background-color: #FAFAFA">
                                                <img v-if="room.images_str" :src="room.images_str.split('\n')[0]" width="100%">
                                                <div v-else style="width: 100%;padding:60px 0;background-color: whitesmoke;text-align: center;color: lightgrey">暂无图片</div>
                                                <div style="padding:12px 8px">
                                                    <div style="font-size: 16px;"><% room.name %></div>
                                                    <div style="height: 8px"></div>
                                                    <p style="font-size: 12px;line-height: 20px;margin: 0" v-for="h in str_2_arr(room.highlight)">
                                                        <% h %>
                                                    </p>
                                                    <div v-if="room.is_show == true">
                                                        <p style="font-size: 12px;line-height: 20px;margin: 0" v-html="markdown(room.description)"></p>
                                                        <p style="font-size: 12px;line-height: 20px;margin: 0" v-html="markdown(room.facilities)"></p>
                                                    </div>
                                                    <span class="unselectable" style="cursor: pointer;color: #c29c76;font-size: 12px" v-on:click="room_show_detail(room)"><%room.is_show?'收起':'更多'%></span>
                                                </div>
                                            </div>
                                            <div style="display: table-cell;width: 528px;border-top: 1px solid lightgrey;min-height: 600px">
                                                {{--<div style="height: 1px;width: 100%;background-color: lightgrey"></div>--}}
                                                <div v-if="room.price" style="padding:30px">
                                                    <table v-if="room.price.plans">
                                                        <tr v-for="(plan, index) in room.price.plans"
                                                            :class="room.price.price_index == index ? 'plan_active' : 'plan_normal'"
                                                            v-on:click="room_plan_select(roomIndex,index)"
                                                        >
                                                            <td style="padding: 12px" width="200px"><%plan.name%></td>
                                                            <td style="padding: 12px" v-if="plan.ok"><%plan.price%> </td>
                                                            <td style="padding: 12px;color:red" v-else><%plan.reason%> </td>
                                                        </tr>
                                                    </table>
                                                    <div v-else>暂无价格</div>
                                                    <div style="height: 30px;width: 30px"></div>
                                                    <template v-if="room.price.price_index == 0">
                                                        <div style="font-size: 16px;">费用包含</div>
                                                        <ul class="plan_check">
                                                           <li v-for="item in str_2_arr(room.price.include)"><%item%></li>
                                                        </ul>
                                                        <div style="height: 30px;width: 30px"></div>
                                                        <div style="font-size: 16px;">退改规则</div>
                                                        <ul class="plan_check">
                                                            <li v-for="item in str_2_arr(room.price.cancellation)"><%item%></li>
                                                        </ul>
                                                    </template>
                                                    <template v-else-if="room.price.price_index > 0">
                                                        <div style="font-size: 16px;">费用包含</div>
                                                        <ul class="plan_check">
                                                            <li v-for="item in str_2_arr(room.price.plans[room.price.price_index].include)"><%item%></li>
                                                        </ul>
                                                        <div style="height: 30px;width: 30px"></div>
                                                        <div style="font-size: 16px;">退改规则</div>
                                                        <ul class="plan_check">
                                                            <li v-for="item in str_2_arr(room.price.plans[room.price.price_index].cancellation)"><%item%></li>
                                                        </ul>
                                                    </template>
                                                </div>
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div style="clear: both;height:15px;width: 10px"></div>
                                    </div>
                            </div>
                        </div>
                        <div v-else-if="section == 'section_facilities'">
                            <div class="hotel_content_title">酒店设施</div>
                            <template v-for="(item,index) in arr_facilities" >
                                <div class="font_sub_fa"><% item %></div>
                                <div v-if="index%4 == 3" style="clear: both"></div>
                            </template>
                            <div style="height: 30px;clear: both"></div>
                            <div class="hotel_content_title">订前必读</div>
                            <div class="font_sub">入住时间：<% hotel.policy.checkin %></div>
                            <div class="font_sub">退房时间：<% hotel.policy.checkout %></div>
                            <div class="font_sub">取消政策：<% hotel.policy.cancellation %></div>
                            <div style="height: 20px;width: 1px"></div>
                            <div class="font_sub">儿童政策</div>
                            <div v-for="item in str_2_arr(hotel.policy.children)" class="font_sub">
                                <% item %>
                            </div>
                            <div style="height: 20px;width: 1px"></div>
                            <div class="font_sub">加床</div>
                            <div v-for="item in str_2_arr(hotel.policy.extra_bed)" class="font_sub">
                                <% item %>
                            </div>
                            <div style="height: 20px;width: 1px"></div>
                            <div class="font_sub">宠物</div>
                            <div v-for="item in str_2_arr(hotel.policy.pet)" class="font_sub">
                                <% item %>
                            </div>
                            <div style="height: 20px;width: 1px"></div>
                            <div class="font_sub">酒店接受的银行卡类型：<% hotel.policy.payment %></div>
                            <div style="height: 30px;width: 100px"></div>
                        </div>
                        <div v-else-if="section == 'similar'"><div>similar</div></div>
                    </div>
                    <div  style="float:left;margin-left: -225px;width: 225px;">
                        <div id="right_picker" style="height: 333px;width: 223px;border: 1px solid lightgrey;padding: 15px;">
                            <div id="div_checkinout2">
                                <label class="label_d">入住日期</label>
                                <input style="color:#666666;" class="input_normal"
                                       id="input_checkin2" name="checkin" v-model="book.checkin" type="text" placeholder="入住日期" readonly/>
                                <label class="label_d">退房日期</label>
                                <input style="color:#666666"  class="input_normal"
                                       id="input_checkout2" name="checkout" v-model="book.checkout" type="text" placeholder="退房日期" readonly/>
                            </div>
                            <div>
                                <label class="label_d">房客</label>
                                <input style="color:#666666" class="input_normal" v-on:click="click_people" id="input_people2"
                                       type="text" :value="book.adult+'成人, '+book.children+'儿童'" placeholder="请选择入住人数" readonly/>
                            </div>
                            <button type="button" style="width: 100%" class="btn btn_book" v-on:click="book_check2">查询预订</button>
                            <div style="clear: both"></div>
                        </div>

                    </div>
                    <div style="clear: both"></div>
                </div>
                <div id="footer2">
                    <div id="div_map" v-show="section == 'detail'" class="hotel_map" style="position: relative;width: 100%">
                        <div id="map" style="width: 100%;height: 360px;"></div>
                        <div style="position: absolute;width: 60%;margin-left: auto;margin-right: auto;left: 0;top: 0;">
                            <div style="height: 20px"></div>
                            <div  style="padding: 15px 6px 15px 15px;background-color: #FFF;margin-left: 20%;width: 340px;height: 320px;">
                                <div id="div_trans" style="overflow-y: auto;height: 290px;padding-right: 8px">
                                    <div class="hotel_content_title_small">地址</div>
                                    <div class="font_normal"><%hotel.location.address%></div>
                                    <div style="height: 20px"></div>
                                    <div class="hotel_content_title_small">交通指南</div>
                                    <div class="font_normal" v-html="markdown(hotel.location.transportation)"></div>
                                </div>
                            </div>
                            <div style="height: 20px"></div>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div  :style="content_width+';margin-right: auto;margin-left: auto'">
                    <div style="float: left;width: 33%;padding: 30px">
                        <div class="hotel_content_title">品牌和荣誉</div>
                        <div>
                            <img v-for="img in str_2_arr(hotel.honor_img)"
                                 :src="img" width="100px" height="50px" style="margin-right: 10px"
                            />
                            <p class="font_normal"><%hotel.honor_word%></p>
                        </div>
                    </div>
                    <div style="float: left;width: 33%;padding: 30px">
                        <div class="hotel_content_title">主要奖项</div>
                        <p class="font_normal" v-html="markdown(hotel.honor)">

                        </p>
                    </div>
                    <div style="float: left;width: 33%;padding: 30px">
                        <div class="hotel_content_title">礼宾服务</div>
                        <div class="font_normal">
                            致游的顾问团队很乐意为您提供免费的礼宾服务
                            <br/>
                            <br/>
                            如果您希望预约前往酒店的专车、预约餐厅、更改行程或向酒店要求特殊需求等等，欢迎与我们联系：
                        </div>
                        <div style="font-size: 20px">
                            <br/>
                            周一至周六 10:00 - 19:00<br/>
                            <span>4001-567-165</span>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>
                </div>
            </div>
            <div class="hotel_footer" id="footer">
                <div :style="content_width+';text-align: center;color: lightgrey;margin-left: auto;margin-right: auto;padding: 30px 0;'">
                    <div style="float: left;width: 25%">
                        <div>礼宾服务</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                    </div>
                    <div style="float: left;;width: 25%">
                        <div>精品酒店</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                    </div>
                    <div style="float: left;;width: 25%">
                        <div>热门目的地</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                    </div>
                    <div style="float: left;;width: 25%">
                        <div>礼宾服务</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                        <div>联系我们</div>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div style="text-align: center;padding: 15px 0;background-color: #1F272B;color: lightgrey;font-weight: 200">
                    ZHOTEL
                </div>
            </div>

        </div>
        <div id="div_people" class="input_people_box" style="z-index: 99999;position: absolute;display: none;width: 250px">
            <div style="padding:8px 0">
                <div style="text-align: center;float: left;width: 100%;padding-left: 30px;padding-right: 30px">
                    <span style="color: #c29c76;font-size: 16px"><%book.adult%></span> 名大人
                </div>
                <div v-on:click="minus_adult" class="unselectable"
                     style="cursor: pointer; text-align: center;float: left;width: 30px;margin-left: -100%">-</div>
                <div v-on:click="add_adult" class="unselectable"
                     style="cursor: pointer; text-align: center;float: left;width: 30px;margin-left: -30px">+</div>
                <div style="clear: both"></div>
            </div>
            <div style="padding:8px 0">
                <div style="text-align: center;float: left;width: 100%;padding-left: 30px;padding-right: 30px">
                    <span style="color: #c29c76;font-size: 16px"><%book.children%></span> 名儿童
                </div>
                <div v-on:click="minus_children" class="unselectable"
                     style="cursor: pointer; text-align: center;float: left;width: 30px;margin-left: -100%">-</div>
                <div v-on:click="add_children" class="unselectable"
                     style="cursor: pointer; text-align: center;float: left;width: 30px;margin-left: -30px">+</div>
                <div style="clear: both"></div>
            </div>
            <div v-if="book.children > 0" style="text-align: center;font-size: 10px">儿童年龄</div>
            <div style="padding: 10px">
                <div v-for="(age,index) in book.children_age">
                    <select class="form-control" style="float:left;width: 60px;margin: 4px" v-model="book.children_age[index]">
                        <option value="0"><1 岁</option>
                        <option value="1">1 岁</option>
                        <option value="2">2 岁</option>
                        <option value="3">3 岁</option>
                        <option value="4">4 岁</option>
                        <option value="5">5 岁</option>
                        <option value="6">6 岁</option>
                        <option value="7">7 岁</option>
                        <option value="8">8 岁</option>
                        <option value="9">9 岁</option>
                        <option value="10">10 岁</option>
                        <option value="11">11 岁</option>
                        <option value="12">12 岁</option>
                        <option value="13">13 岁</option>
                        <option value="14">14 岁</option>
                        <option value="15">15 岁</option>
                        <option value="16">16 岁</option>
                        <option value="17">17 岁</option>
                    </select>
                </div>
            </div>
            <div style="clear: both"></div>
            <button onclick="close_people(this)" type="button" class="btn" style="width: 100%;margin-top: 10px;margin-bottom: 10px;background-color: white;border: 1px solid #efefef">关闭</button>
        </div>
        <form id="form_hidden" method="post" action="/order/booking/step"  style="display: none">
            {{ csrf_field() }}
            <input type="hidden" value="" id="form_id" name="sid"/>
            <input type="submit" id="form_btn" >
        </form>
    </div>
</div>
    <div id="blueimp-gallery" class="blueimp-gallery">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        {{--<a class="play-pause"></a>--}}
        <ol class="indicator"></ol>
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
        $("#div_checkinout2").dateRangePicker(
                {
                    separator : ' to ',
                    autoClose: true,
                    getValue: function()
                    {
                        if ($('#input_checkin2').val() && $('#input_checkout2').val() )
                            return $('#input_checkin2').val() + ' to ' + $('#input_checkout2').val();
                        else
                            return '';
                    },
                    setValue: function(s,s1,s2)
                    {
                        $('#input_checkin2').val(s1);
                        $('#input_checkout2').val(s2);
                        //hack for event
                        var evt = document.createEvent('HTMLEvents');
                        evt.initEvent('input', false, true);
                        $('#input_checkin2')[0].dispatchEvent(evt);
                        $('#input_checkout2')[0].dispatchEvent(evt);
                    },
                    hoveringTooltip: function (days, startTime, hoveringTime) {
                        return days > 1 ? days + "天" + (days - 1) + '晚' : '';
                    },
                    language:"cn"
                }
        );
    });

    var hotel_lat,hotel_lng;

    var book_adult = 2;
    var book_children = 0;

    function close_people(self){
        $("#div_people").hide();
    }

    $(document).click(function(e) {

        var container = $("#div_people");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
        }
    });

</script>

<script>

    Vue.component('z-float-input',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "dom_id"],
        template: '<label class="form-group has-float-label">' +
        '<input :id="dom_id" class="form-control form_input" :name="name" :placeholder="placeholder" ' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '<span><% placeholder %></span>',
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
            console.log("updated");
            try{
                var number = this.hotel.images.length;
                console.log("image number = "+number);
                var swiper = new Swiper('.banner-gallery', {
                    navigation: {
                        nextEl: '.swiper-button-next2',
                        prevEl: '.swiper-button-prev2',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        type : number > 5 ? "fraction" : 'bullets'
                    },
                    slidesPerView: 'auto',
                    centeredSlides: true,
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop:true
                });
            }
            catch (e){}
            try {
                var swiper1 = new Swiper('.markdown-gallery', {
                    navigation: {
                        nextEl: '.swiper-button-next2',
                        prevEl: '.swiper-button-prev2',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                    },
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop: true
                });
            }
            catch (e){}
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
            $("#right_picker").affix(
                    {
                        offset:{
                            top: function(){
                                //hotel_header
                                var bottom_of_banner = $("#hotel_header").offset().top + $("#hotel_header").outerHeight();

                                var offset_nav = 0;
                                return bottom_of_banner - offset_nav;
                            },
                            bottom : function(){
                                return ( $('#footer').outerHeight(true) + $('#footer2').outerHeight(true) )
                            }
                        }
                    }
            );
            initMap();
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
            nav_section : function(e){
                var self = e.target;
                var s = $(self).attr("data-section");
                this.section = s;
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
            book_check2 : function(e){
                if(!this.book.checkin || !this.book.checkout){
                    toastr["error"]("请填写入住日期");
                    return;
                }
                var thiz = this;
                //ajax data
                //switch to room section
                //display plans of all room with the checkin-out
                var paras = JSON.parse(JSON.stringify(this.$data.book));
                paras._id = this.hotel._id;
                console.log(paras);
                var url = "/api/price/hotel";
                axios.post(url, paras)
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                var rooms = response.data.obj;
                                for(var i= 0,len = thiz.hotel.rooms.length;i<len;i++){
                                    for(var j= 0,jLen = rooms.length;j<jLen;j++){
                                        if(thiz.hotel.rooms[i].id == rooms[j].id){
                                            rooms[j].price.price_index = 0;
                                            thiz.hotel.rooms[i].price = rooms[j].price;
                                            break;
                                        }
                                    }
                                }
                                console.log(thiz.hotel);
                            }

                        })
                        .catch(function(error){
                            console.log(error);
                        });
                this.room_style = 1;
                this.section = "rooms";
            },
            book_check : function(e){
                var self = e.target;

                if($("#form_book").is(':visible')){
                    this.book_check2(e);
                }
                else{
                    $("#form_book").slideDown(200);
                    $(self).animate({
                        width:"250px"
                    },200);
                }
            },
            click_people : function(e){
                var self = e.target;
                var left = $(self).offset().left;
                var top = $(self).offset().top;
                var h = $(self).outerHeight();
                $("#div_people").css("left", (left)+"px");
                $("#div_people").css("top", (top+h)+"px");
                $("#div_people").slideDown(200);
                e.stopPropagation();
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
            add_adult : function(){
                this.book.adult = this.book.adult + 1
            },
            minus_adult : function(){
                if(this.book.adult > 1)
                    this.book.adult = this.book.adult - 1
            },
            add_children : function(){
                if(this.book.children < 9) {
                    var age = "0";
                    this.book.children_age.splice(this.book.children, 0, age);
                    this.book.children = this.book.children + 1
                }
                console.log(this.book.children_age);
            },
            minus_children : function(){
                if(this.book.children > 0){
                    this.book.children_age.splice(this.book.children - 1,1);
                    this.book.children = this.book.children - 1
                }
            },
            room_show_detail : function(r){
                console.log("room_show_detail");
                console.log(r);
                if(r.hasOwnProperty("is_show")){
                    r.is_show = !r.is_show;
                }
                else{
                    r.is_show = false;
                }
            },
            view_room_images : function(images_str,event){
                var arr1 = images_str.split('\n');
                var arr = [];
                for(var i= 0,len = arr1.length;i<len;i++){
                    if(arr1[i].length > 3){
                        arr.push(arr1[i]);
                    }
                }
                console.log("view_room_images");
                event = event || window.event;
                var target = event.target || event.srcElement,
                        options = {index: arr[0], event: event},
                        links = arr;
                console.log(links);
                blueimp.Gallery(links, options);
            },
            handle_resize : function(event){
                var height = document.documentElement.clientHeight;
                var width = document.documentElement.clientWidth;
                console.log("resize(wxh) = "+width+","+height);
                this.hack_width = (width - 1028 )/2 + "px";
            },
            room_plan_select : function(roomIndex,index){
                console.log("room plan select")
                console.log(roomIndex)
                console.log(index)
                this.hotel.rooms[roomIndex].price.price_index = index;

                var paras = JSON.parse(JSON.stringify(this.$data));
                var plan = paras.hotel.rooms[roomIndex].price.plans[index];
                var room = paras.hotel.rooms[roomIndex];
                room.price = [];
                var book_para = {
                    book_info : paras.book,
                    hotel_info : {
                        id : paras.hotel._id,
                        name : paras.hotel.name,
                        name_en : paras.hotel.name_en,
                        zy : paras.hotel.zy,
                        location : paras.hotel.location
                    },
                    room_info : room,
                    plan_info : plan,
                    hotel_id : paras.hotel._id,
                    room_id : room.id
                }

                console.log(book_para);

                var url = "/api/order/create";
                axios.post(url, book_para)
                        .then(function(response){
                            console.log(response.data)
                            if(response.data.ok == 0){
                                $("#form_id").val(response.data.obj);
                                $("#form_btn").click();
                            }

                        })
                        .catch(function(error){
                            console.log(error);
                        });

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
            }
        }
    })



</script>


<script>
    /**
     * Created by jingang on 17/1/5.
     */
    var map;
    var directionsDisplay;
    var directionsService;
    var infowindow;
    var curvature = -0.3; // how curvy to make the arc
    var curveMarker;
    var positions = [];
    var markers = [];
    var curveMarkers = [];
    var arrowMarkers = [];
    var mapLoaded = false;


    function mapCallback(){
        mapLoaded = true;
        initMap();
    }

    function initMap() {
        console.log(hotel_lat);
        console.log(hotel_lng);
        if(!mapLoaded){
            console.log("map js is not loaded");
            return;
        }
        if(map != undefined && map != null){
            console.log(map);
            console.log("map already init");
            return;
        }
        if(hotel_lng == undefined || hotel_lng == null || hotel_lng == ""){
            console.log("hotel lat lng not ready");
            return;
        }

        console.log("init map here");
        hotel_lat = 0;
        hotel_lng = 0;


        //define map
        var Map = google.maps.Map,
                LatLng = google.maps.LatLng,
                LatLngBounds = google.maps.LatLngBounds,
                Marker = google.maps.Marker,
                InfoWindow = google.maps.InfoWindow,
                Point = google.maps.Point;
        //style basic map
        var styleArray = [
            {
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#fcf8f9"
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#ccb2bb"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#bdbdbd"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "poi.business",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f2e9ec"
                    }
                ]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f2e9ec"
                    }
                ]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#f2eae8"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            }
        ]
        var center = {lat: hotel_lat, lng: hotel_lng};
        var mapOptions = {
            zoom: 10,
            center: center,
            styles: styleArray,
            scrollwheel: false,
            mapTypeControl: false,
        };


        map = new Map(document.getElementById('map'),mapOptions);
        var marker = new google.maps.Marker({
            position: center,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5,
                strokeColor: '#c99c76'
            },
            draggable: true,
            map: map
        });
    }

</script>

<script async defer
        src="http://ditu.google.cn/maps/api/js?key=AIzaSyBJfv6WxdEoTqSgibZDdOL-m-lLWz6UO8E&libraries=geometry&callback=mapCallback">
</script>
@endsection