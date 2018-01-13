@extends("mobile.layout.base")
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
            height: 222px;
            object-fit: cover;
        }
        .markdown-gallery{
            height: 222px;
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
        .custom_p{
            float: right;
            padding: 3px 12px;
            color: white;
            background-color: rgba(0,0,0,.5);
            border-radius: 10px;
            font-size: 10px;
            margin-right: 10px;
            line-height: 16px;
            /*height: 20px;*/
        }
        .custom_p1{
            float: right;
            padding: 3px 12px;
            color: white;
            background-color: rgba(0,0,0,.5);
            border-radius: 10px;
            font-size: 10px;
            margin-right: 10px;
            line-height: 16px;
        }
        #markdown_wrapper span{
            display: block;
            padding: 0 15px;
            margin: 0;
        }
        #markdown_wrapper a{
            padding: 0 15px;
            margin: 0;
        }
        body{
            background-color: #f9f9f9;
        }
        .border_shadow{
            -webkit-box-shadow: 0px 1px 2px 1px rgba(0,0,0,0.1);
            -moz-box-shadow: 0px 1px 2px 1px rgba(0,0,0,0.1);
            box-shadow: 0px 1px 2px 1px rgba(0,0,0,0.1);
        }
        .picker_cell{
            width: 14%;padding: 12px 0;text-align: center;
            position: relative
        }
        .picker_cell_selected{
            width: 14%;padding: 12px 0;text-align: center;
            background-color: #c99c76;
            position: relative
        }
        .picker_cell_selected_middle{
            width: 14%;padding: 12px 0;text-align: center;
            background-color: whitesmoke;
            position: relative
        }
    </style>
    <style>
        .fade-enter-active, .fade-leave-active {
            transition: opacity .5s
        }
        .fade-enter, .fade-leave-to /* .fade-leave-active in below version 2.1.8 */ {
            transition: opacity .2s
        }
        .slide-fade-enter-active {
            transition: all .3s ease;
        }
        .slide-fade-leave-active {
            transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
        }
        .slide-fade-enter, .slide-fade-leave-to
            /* .slide-fade-leave-active for below version 2.1.8 */ {
            transform: translateX(10px);
            opacity: 0;
        }
    </style>

@endsection
@section('content')

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
                <template v-if="state == 1">
                    <template v-if="user">
                        <div style="background-color: #0a0a0a;color: lightgrey;padding: 3px;text-align: center">
                            <span><%user.name%></span>
                            <a href="/logout">logout</a>
                        </div>
                    </template>
                    <template v-else>
                        <div style="background-color: #0a0a0a;color: lightgrey;padding: 3px;text-align: center">
                            <span>guest</span>
                            <button type="button" class="btn btn-default btn-sm" v-on:click="show_login">login</button>
                            <a href="/register">register</a>
                        </div>
                    </template>
                    <div :style="style_cover">
                        <div v-if="sorted_covers" class="swiper-container banner-gallery">
                            <div class="swiper-wrapper">
                                <div v-for="(image, index) in sorted_covers" class="swiper-slide">
                                    <img  :src="image.url" :style="style_cover"/>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="header" id="hotel_header" style="padding: 15px;font-weight: 200;background-color: #FFF">
                        <div style="font-weight: 300;font-size: 20px"><%hotel.name%></div>
                        <div><%hotel.name_en%></div>
                        <div style="margin-top: 10px">
                            <span><% hotel.location.continent %></span> >
                            <span><% hotel.location.country %></span> >
                            <span><% hotel.location.city %></span> >
                            <span>酒店</span>
                        </div>
                    </div>
                    <div style="color: white;font-size: 12px;font-weight: 200;min-height: 41px">
                        <div id="hotel_nav" style="width: 100%">
                            <div style="float: left;width: 100%;padding-right: 120px;position: relative">
                                <div style="text-align: center;background-color: #29353e;padding: 12px 0" v-on:click="show_nav"><%section_str%></div>
                                <div style="position: absolute;top: 0;right: 130px;padding: 12px 0"><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                            </div>
                            <div style="position: relative;float: left;width: 120px;margin-left: -120px;text-align: center;background-color: #232f36;padding: 12px 0"  v-on:click="show_cc" >
                                搜索日期和房型
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <template v-if="section == 'detail'">
                        <div style="background-color: #FFF">
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
                            <div style="padding: 15px 0" v-if="hotel.detail.extend">
                                <div v-for="section in detail_extend">
                                    <div class="m_title" style="padding: 0 15px"><%section.title%></div>
                                    <div id="markdown_wrapper" class="font_normal" v-html="markdown(section.content)"></div>
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
                        </div>
                    </template>
                    <template v-else-if="section == 'room'">
                        <div v-for="room in hotel.rooms">
                            <div style="background-color: #FFF;margin: 15px 15px 0 15px;" class="border_shadow">
                                <div style="width: 100%;">
                                    <img style="width: 100%;object-fit: cover;height: 222px" v-if="room.images_str" :src="room.images_str.split('\n')[0]">
                                    <div v-else style="width: 100%;padding:60px 0;background-color: whitesmoke;text-align: center;color: lightgrey">暂无图片</div>
                                </div>
                                <div style="padding: 15px;">
                                    <div class="m_title"><% room.name %></div>
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
                        </div>
                        <div style="height: 15px;width: 100%"></div>
                    </template>
                    <templatediv v-else-if="section == 'facilities'">
                        <div style="background-color: #FFF">
                            <div style="padding: 15px">
                                <div class="m_title">酒店设施</div>
                                <template v-for="(item,index) in arr_facilities" >
                                    <div style="width: 50%;float: left;margin-bottom: 10px"><% item %></div>
                                </template>
                                <div style="clear: both"></div>
                            </div>
                            <div class="line"></div>
                            <div style="padding: 15px">
                                <div class="m_title">订前必读</div>
                                <div class="font_normal">入住时间：<% hotel.policy.checkin %></div>
                                <div class="font_normal">退房时间：<% hotel.policy.checkout %></div>
                                <div class="font_normal">取消政策：<% hotel.policy.cancellation %></div>
                                <div style="height: 20px;width: 1px"></div>
                                <div class="font_normal">儿童政策</div>
                                <div v-for="item in str_2_arr(hotel.policy.children)" class="font_normal">
                                    <% item %>
                                </div>
                                <div style="height: 20px;width: 1px"></div>
                                <div class="font_normal">加床</div>
                                <div v-for="item in str_2_arr(hotel.policy.extra_bed)" class="font_normal">
                                    <% item %>
                                </div>
                                <div style="height: 20px;width: 1px"></div>
                                <div class="font_normal">宠物</div>
                                <div v-for="item in str_2_arr(hotel.policy.pet)" class="font_normal">
                                    <% item %>
                                </div>
                                <div style="height: 20px;width: 1px"></div>
                                <div class="font_normal">酒店接受的银行卡类型：<% hotel.policy.payment %></div>
                                <div style="height: 30px;width: 100px"></div>
                            </div>
                            <div class="line"></div>
                        </div>
                    </templatediv>
                </template>
                <div v-else-if="state == 2" style="padding: 15px;min-height: 600px;background-color: white">

                    <div>
                        <div style="float: left;width: 100%;text-align: center">选择日期,人数</div>
                        <div style="float: left;width: 60px;margin-left: -100%" v-on:click="close_cc"><i class="fa fa-times" aria-hidden="true"></i></div>
                        <div style="clear: both"></div>
                    </div>
                    <div style="height: 20px;width: 100%"></div>
                    <div style="text-align: center;position: relative;border: 1px solid whitesmoke" v-on:click="picker_show">
                        <div style="float:left;width: 50%;padding: 12px;"><%selected_date_checkin%></div>
                        <div style="float:left;width: 50%;padding: 12px"><%selected_date_checkout%></div>
                        <div style="position: absolute;top:0;left: 50%;padding: 12px 0;margin-left: -4px"> ~ </div>
                        <div style="clear: both"></div>
                    </div>

                    <transition name="fade">
                        <table style="width: 100%" v-if="date_picker.show">
                            <tbody>
                            <tr style="">
                                <td v-on:click="picker_minus_month" class="price_header" style="text-align: center;border: 1px solid lightgrey">
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </td>
                                <td style="text-align: center;border: 1px solid lightgrey;padding: 12px 0" colspan="5">
                                    <%date_picker.year%> . <%date_picker.month%>
                                </td>
                                <td v-on:click="picker_add_month" class="price_header"  style="text-align: center;border: 1px solid lightgrey">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td class="" style="padding: 12px 0">日</td>
                                <td class="">一</td>
                                <td class="">二</td>
                                <td class="">三</td>
                                <td class="">四</td>
                                <td class="">五</td>
                                <td class="">六</td>
                            </tr>
                            <tr v-for="i in date_picker.rows">
                                <template v-for="j in 7">
                                    <template v-if="date_picker.checkin && date_picker.checkin._value == date_picker.date_arr[(i-1)*7+(j-1)]._value">
                                        <td v-on:click="picker_selected(i,j)" class="picker_cell_selected">
                                            <%date_picker.date_arr[(i-1)*7+(j-1)].value%>
                                            <div style="position: absolute;left:50%;bottom:2px;margin-left: -8px;font-size: 8px;color: white">入住</div>
                                        </td>
                                    </template>
                                    <template v-else-if="date_picker.checkout && date_picker.checkout._value == date_picker.date_arr[(i-1)*7+(j-1)]._value">
                                        <td v-on:click="picker_selected(i,j)" class="picker_cell_selected">
                                            <%date_picker.date_arr[(i-1)*7+(j-1)].value%>
                                            <div style="position: absolute;left:50%;bottom:2px;margin-left: -8px;font-size: 8px;color: white">退房</div>
                                        </td>
                                    </template>
                                    <template v-else-if="date_picker.checkin && date_picker.checkout &&
                            date_picker.date_arr[(i-1)*7+(j-1)]._value < date_picker.checkout._value &&
                            date_picker.date_arr[(i-1)*7+(j-1)]._value > date_picker.checkin._value">
                                        <td v-on:click="picker_selected(i,j)" class="picker_cell_selected_middle">
                                            <%date_picker.date_arr[(i-1)*7+(j-1)].value%>
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td v-on:click="picker_selected(i,j)" class="picker_cell">
                                            <%date_picker.date_arr[(i-1)*7+(j-1)].value%>
                                        </td>
                                    </template>
                                </template>
                            </tr>
                            </tbody>
                        </table>
                    </transition>

                    <div>
                        <div>
                            <div style="float: left;width: 100%;padding: 10px 60px;text-align: center">
                                <div><%book.adult%> 成人</div>
                            </div>
                            <div v-on:click="minus_adult" style="border: 1px solid whitesmoke;width: 60px;float: left;margin-left: -100%;text-align: center;padding: 10px"><i class="fa fa-minus" aria-hidden="true"></i></div>
                            <div v-on:click="add_adult"  style="border: 1px solid whitesmoke;width: 60px;float: left;margin-left: -60px;text-align: center;padding: 10px"><i class="fa fa-plus" aria-hidden="true"></i></div>
                            <div style="clear: both"></div>
                        </div>
                        <div>
                            <div style="float: left;width: 100%;padding: 10px 60px;text-align: center">
                                <div><%book.children%> 儿童</div>
                            </div>
                            <div v-on:click="minus_children"  style="border: 1px solid whitesmoke;width: 60px;float: left;margin-left: -100%;text-align: center;padding: 10px"><i class="fa fa-minus" aria-hidden="true"></i></div>
                            <div v-on:click="add_children"  style="border: 1px solid whitesmoke;width: 60px;float: left;margin-left: -60px;text-align: center;padding: 10px"><i class="fa fa-plus" aria-hidden="true"></i></div>
                            <div style="clear: both"></div>
                        </div>

                        <div v-for="(age,index) in book.children_age" style="float:left;width: 33%;padding: 4px">
                            <select class="form-control"  v-model="book.children_age[index]">
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
                        <div style="clear: both"></div>
                    </div>
                    <div style="text-align: center;margin-top: 30px">
                        <button v-on:click="close_cc" type="button" class="btn btn-default btn-sm">确定</button>
                    </div>

                </div>


                <div class="footer">
                    &copy; 2018致游旅游咨询有限公司(上海)
                </div>
            </div>
            <div id="dialog" style="width: 100%;background-color: lightgrey;display: none;position: absolute">
                <div class="d_nav" v-on:click="select_nav('detail')">酒店详情</div><div class="line"></div>
                <div class="d_nav" v-on:click="select_nav('facilities')">酒店设施</div><div class="line"></div>
                <div class="d_nav" v-on:click="select_nav('room')">房型介绍</div>
            </div>
            <div id="dialog_login" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form id="dialog_form">
                                <input name="email" placeholder="email" />
                                <input name="password" placeholder="password" />
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" v-on:click="login()">登录</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
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
        var flag_picker = 0;
        function init_picker(){
            if($("#div_checkinout") == undefined || $("#div_checkinout")  == null || !$("#div_checkinout").is(":visible")){
                console.log("init picker failed")
                return;
            }
            console.log("init picker ok")

            if(flag_picker == 1){
                return;
            }
            flag_picker = 1;
            console.log($("#div_checkinout"));
            $("#div_checkinout").dateRangePicker(
                    {
                        separator : ' to ',
                        autoClose: true,
                        getValue: function()
                        {
                            if ($('#checkin').val() && $('#checkout').val() )
                                return $('#checkin').val() + ' to ' + $('#checkout').val();
                            else
                                return '';
                        },
                        setValue: function(s,s1,s2)
                        {
                            $('#checkin').val(s1);
                            $('#checkout').val(s2);
                            //hack for event
                            var evt = document.createEvent('HTMLEvents');
                            evt.initEvent('input', false, true);
                            $('#checkin')[0].dispatchEvent(evt);
                            $('#checkout')[0].dispatchEvent(evt);
                        },
                        hoveringTooltip: function (days, startTime, hoveringTime) {
                            return days > 1 ? days + "天" + (days - 1) + '晚' : '';
                        },
                        language:"cn",
                        singleMonth: true
                    }
            );

            console.log($("#div_checkinout"));
        }
        $(document).click(function(e) {
            console.log("global click");
            console.log(e);

            var container = $("#dialog");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.hide();
            }
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
                style_cover : "width:100%;height:120px",
                state : 1,
                date_picker:{
                    year : "",
                    month : "",
                    date_arr : [],
                    rows : 0,
                    checkin : null,
                    checkout : null,
                    show : false
                },
                timer : null,
                user : null
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

                init_picker();

                try{
                    var swiper = new Swiper('.banner-gallery', {
                        pagination: {
                            el: '.swiper-pagination',
                            type : "custom",
                            renderCustom: function (swiper, current, total) {
                                return "<div class='custom_p'>"+current + ' / ' + total+"</div>"
                            }
                        },
                        loop:true
                    });
                }
                catch (e){

                }
                try {
                    var swiper1 = new Swiper('.markdown-gallery', {
                        pagination: {
                            el: '.swiper-pagination',
                            type : "custom",
                            renderCustom: function (swiper, current, total) {
                                return "<div class='custom_p1'>"+current + ' / ' + total+"</div>"
                            }
                        },
                        loop: true
                    });
                }
                catch (e){}
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
                                if(response.data.user){
                                    self.user = response.data.user;
                                }

                                self.loading = false;
                                hotel_lat = parseFloat(self.hotel.location.lat);
                                hotel_lng = parseFloat(self.hotel.location.lng);
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                },
                markdown : function(str){
                    return zhotel_markdown(str,true);
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
                    this.style_cover = "width:100%;height:"+width*0.6+"px";
                },
                show_nav : function(e){
                    var self = e.target;
                    var left = $(self).offset().left;
                    var top = $(self).offset().top;
                    var h = $(self).outerHeight();
                    if($("#dialog").is(":visible")){
                        this.close_dialog();
                        return;
                    }

                    $("#dialog").css("left", (left)+"px");
                    $("#dialog").css("top", (top+h)+"px");
                    $("#dialog").slideDown(200);
                    e.stopPropagation();
                },
                show_cc : function(){
                    console.log("show cc");
                    this.state = 2;
                    this.picker_init();

                },
                close_cc : function(){
                    console.log("close cc");
                    flag_picker = 0;
                    this.state = 1;
                },
                close_dialog : function(){
                    $("#dialog").hide();
                },
                select_nav : function(str){
                    this.section = str;
                    this.close_dialog();
                },
                show_login : function(e){
                    $("#dialog_login").modal("show");
                },
                login : function(){
                    var p = $("#dialog_form").serialize();
                    var self = this;
                    axios.post('/customer/alogin',p)
                            .then(function(response){
                                console.log(response.data);
                                if(response.data.ok == 0){
                                    $("#dialog_login").modal("hide");
                                    self.user = response.data.user;
                                }
                                else{
                                    alert(response.data.msg);
                                }
                            })
                            .catch(function(error){
                                console.log(error);
                            });
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
                picker_init : function(checkin){
                    var current = moment();
                    var year = current.year();
                    var month = current.month();
                    if(checkin){
                        year = parseInt(checkin.substr(0,4));
                        month = parseInt(checkin.substr(5,2));
                    }
                    this.date_picker.year = year;
                    this.date_picker.month = month+1;
                    this.picker_update();
                },
                picker_2_str : function(y,m,d){
                    var m_str = m < 10 ? "0"+m : ""+m;
                    var d_str = d < 10 ? "0"+d : ""+d;
                    return y + "-" + m_str + "-" + d_str;
                },
                picker_minus_month : function(){
                    if(this.date_picker.month == 1){
                        this.date_picker.month = 12;
                        this.date_picker.year = this.date_picker.year - 1;
                    }
                    else{
                        this.date_picker.month = this.date_picker.month - 1;
                    }
                    this.picker_update();
                },
                picker_add_month : function(){
                    if(this.date_picker.month == 12){
                        this.date_picker.month = 1;
                        this.date_picker.year = this.date_picker.year + 1;
                    }
                    else{
                        this.date_picker.month = this.date_picker.month + 1;
                    }
                    this.picker_update();
                },
                picker_update : function(){
                    var current = moment({
                        year : this.date_picker.year,
                        month : this.date_picker.month-1,
                        day : 1
                    });

                    var weekOfFirst = current.day();
                    var daysInMonth = current.daysInMonth();
                    var currentRow = 0;
                    var arr = [];
                    for(var i=0;i<weekOfFirst;i++){//填补前面,周日开始
                        var item = {
                            row : currentRow,
                            col : i,
                            value : "",
                            selected : 0
                        }
                        arr.push(item)
                    }
                    for(i=0;i<daysInMonth;i++){
                        if(weekOfFirst == 7){
                            weekOfFirst = 0;
                            currentRow = currentRow + 1;
                        }
                        arr.push({
                            row : currentRow,
                            col : weekOfFirst,
                            value : i+1,
                            _value : this.picker_2_str(this.date_picker.year,this.date_picker.month,i+1),
                            selected : 0
                        });
                        weekOfFirst = weekOfFirst + 1;
                    }
                    var c = arr.length;
                    console.log(c);
                    var l = c % 7;
                    var left = 7 - l;

                    for(i=0;i<left;i++){//填补前面
                        item = {
                            row : currentRow,
                            col : weekOfFirst,
                            value : "",
                            selected : 0,
                            hint:""
                        }
                        weekOfFirst = weekOfFirst + 1;
                        arr.push(item)
                    }

                    this.date_picker.date_arr = arr;
                    this.date_picker.rows = currentRow + 1;
                },
                picker_selected : function(i,j){
                    clearTimeout(this.timer);
                    var _value = (this.date_picker.date_arr[(i-1)*7+(j-1)]._value);
                    if(this.date_picker.checkout){//reset
                        this.date_picker.checkin = null;
                        this.date_picker.checkout = null;
                    }
                    if(!this.date_picker.checkin){
                        this.date_picker.checkin = clone(this.date_picker.date_arr[(i-1)*7+(j-1)]);
                    }
                    else{
                        if(_value < this.date_picker.checkin._value){
                            this.date_picker.checkout = clone(this.date_picker.checkin);
                            this.date_picker.checkin = clone(this.date_picker.date_arr[(i-1)*7+(j-1)]);
                        }
                        else{
                            this.date_picker.checkout = clone(this.date_picker.date_arr[(i-1)*7+(j-1)]);
                        }
                        const self = this;
                        this.timer = setTimeout(function(){
                            self.date_picker.show = false;
                        },250);
                    }
                    console.log(this.date_picker);
                },

                picker_show : function(){
                    this.date_picker.show = !this.date_picker.show;
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
                },
                selected_date_checkin : function(){
                    var res = "入住日期";
                    if(this.date_picker.checkin){
                        res = this.date_picker.checkin._value;
                    }
                    return res;
                },
                selected_date_checkout : function(){
                    var res = "退房日期";
                    if(this.date_picker.checkout){
                        res = this.date_picker.checkout._value;
                    }
                    return res;
                }
            }
        })

    </script>
@endsection