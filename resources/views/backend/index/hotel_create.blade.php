@extends("backend.layout.base_main")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/swiper.min.css')}}"/>
    <style>
        [v-cloak] {
            display: none;
        }
        .width_small{
            width: 120px;
        }
        .width_normal{
            width: 300px;
        }
        .width_big{
            width: 400px;
        }
        .width_80{
            width: 80%;
        }
        .section_title{
            padding: 12px 8px;
            font-size: 16px;
            font-weight: bolder;
            background-color: lightgrey;
            margin-bottom: 15px;
        }
        .sub_title{
            font-size: 14px;
            font-weight: bolder;
            margin-bottom: 10px;
        }
        .form_input{
            font-size: 12px;
            font-weight: normal;
        }
        .btn_action{
            margin-left: 20px;
            font-size: 10px;
            padding: 3px;
            cursor: pointer;
            color: lightgrey;
            border: 1px solid lightgrey;
        }
        .btn_action:hover{
            background-color: lightgrey;
            color: white;
        }
        .info{
            background-color:whitesmoke;
            padding: 12px;
            font-size: 10px;
            color: grey;
            width: 400px;
        }
        .sidebar_hotel{
            padding: 0;
            text-align: center;
        }
        .sidebar_hotel li{
            list-style: none;
            color: #3c3c3c;
            padding: 15px;
        }
        .sidebar_hotel a{
            color: #3c3c3c;
        }
        .affix{
            top:0;
            width:120px;
        }
        .menu_btn{
            background-color: transparent;
            padding: 8px;
            margin: 0;
            width: 80px;
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
    </style>
    <style>
        .input_table{
            border: none;
        }
        .input_table:focus{
            border: 1px solid lightgrey;
        }
        .popover{
            max-width: 100%;
            max-height: 500px;
            overflow-y: auto;
        }


        /*typeahead style*/

        .dropdown-menu{
            -webkit-box-shadow: 0px 0px 46px -13px rgba(0,0,0,0.93);
            -moz-box-shadow: 0px 0px 46px -13px rgba(0,0,0,0.93);
            box-shadow: 0px 0px 46px -13px rgba(0,0,0,0.93);
        }
        .active a{
            background-color: grey !important;
        }
        .toast-top-center {
            top: 50%;
            margin: 0 auto;
        }
    </style>
    <style>
        .controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 300px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        .pac-container {
            font-family: Roboto;
        }

        #type-selector {
            color: #fff;
            background-color: #4d90fe;
            padding: 5px 11px 0px 11px;
        }

        #type-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }
        #target {
            width: 345px;
        }
        #map {
            height: 100%;
        }

        .btn_room{
            float:left;
            padding: 6px;
            border: 1px solid lightgrey;
            margin-right: 10px;
            margin-bottom: 10px;
            height: 34px;
            cursor: pointer;
        }
        .btn_room_focus{
            float:left;
            padding: 6px;
            border: 1px solid lightgrey;
            margin-right: 10px;
            margin-bottom: 10px;
            background-color: lightblue;
            height: 34px;
            cursor: pointer;
        }
        .btn_room_insert{
            float:left;
            border: 1px dashed lightgrey;
            padding:6px;
            margin-right: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }
    </style>
    <style>

        .swiper-container {
            width: 100%;
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
            min-height: 150px;
        }
        .swiper-pagination .swiper-pagination-bullet-active {
            background: white;
        }
        .swiper-button-next2{
            position: absolute;
            top: 50%;
            width: 36px;
            height: 36px;
            margin-top: -18px;
            right: 20px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 36px 36px;
            -webkit-background-size: 36px 36px;
            background-size: 36px 36px;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("{{URL::to('images/hotel_arrow_right.png')}}");
        }
        .swiper-button-prev2{
            position: absolute;
            top: 50%;
            left:20px;
            width: 36px;
            height: 36px;
            margin-top: -18px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 36px 36px;
            -webkit-background-size: 36px 36px;
            background-size: 36px 36px;
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
            max-height: 180px;
            object-fit: cover;
        }
        .bg_progress{
            padding:6px;font-size:10px;margin-top: 6px;border: 1px solid lightgrey;background-color: dimgrey;color:white
        }

    </style>
@endsection
@section('content')
    <div id="div_map" style="border: 1px solid lightgrey;padding: 10px;height: 200px;width: 100%;display: none">
        <div style="float: left;width: 70%;height: 100%">
            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
            <div id="map"></div>
        </div>
        <div style="float: left;width: 30%;height: 100%;background-color: white">
            <table class="table" style="">
                <thead>
                <th width="20px">#</th><th>name</th>
                </thead>
                <tbody id="map_result">
                </tbody>
            </table>
        </div>
        <div style="clear: both"></div>
    </div>
    <div id="hotel_list" v-cloak >
        <div style="width: 100%;float: left;padding-left: 150px;">

                <div v-if="loading">
                    <div class="spinner">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>
                </div>
                <form v-else class="form" id="form">
                    <div class="box" style="padding: 30px">
                        <div style="padding: 15px 0">
                            <div style="float: left;width:500px">
                                <z-float-input placeholder="booking or lhw.cn" name="name" dom_id="magic_url"></z-float-input>
                            </div>
                            <button v-on:click="magic_url" style="float: left;margin-left: 10px;height: 34px;width: 120px"
                                    type="button" class="btn btn-default btn-sm" data-loading-text="parsing...">TRY</button>
                            <div style="clear: both"></div>
                        </div>
                        <div style="padding: 15px 0">
                            <span v-if="hotel.status == 1" style="background-color: lightgreen;padding: 3px 6px;font-size: 10px">已上线</span>
                            <span v-else style="background-color: indianred;padding: 3px 6px;font-size: 10px;color:white">未上线</span>
                        </div>
                        <div class="section_title" id="p1" onclick="hack_map(this)">基本信息</div>
                        <div class="width_normal" style="float: left">
                            <z-float-input placeholder="中文名" name="name" v-model="hotel.name"></z-float-input>
                            <z-float-input placeholder="英文名" name="name_en" v-model="hotel.name_en"></z-float-input>
                            <z-float-textarea placeholder="描述" v-model="hotel.description" name="description"></z-float-textarea>
                            <z-float-input placeholder="标签,空格隔开" v-model="hotel.tag" name="tag"></z-float-input>
                        </div>
                        <div class="width_big" style="float: left;margin-left: 12px">
                            <div>
                                <div class="width_small" style="float: left">
                                    <z-float-input-city v-on:city_select="event_city" dom_id="city" placeholder="城市" v-model="hotel.location.city" name="city"></z-float-input-city>
                                </div>
                                <div class="width_small" style="float: left;margin-left: 10px">
                                    <z-float-input placeholder="国家" v-model="hotel.location.country" name="country"></z-float-input>
                                </div>
                                <div class="width_small" style="float:left;margin-left: 10px">
                                    <z-float-input placeholder="洲" v-model="hotel.location.continent" name="continent"></z-float-input>
                                </div>
                                <div style="clear: both"></div>
                            </div>
                            <div>
                                <div class="width_small" style="float: left">
                                    <z-float-input-district v-on:district_select="event_district" dom_id="district" placeholder="区域" v-model="hotel.location.district" name="district"></z-float-input-district>
                                </div>
                                <div class="width_small" style="float: left;margin-left: 10px">
                                    <z-float-input placeholder="lat" v-model="hotel.location.lat" name="lat"></z-float-input>
                                </div>
                                <div class="width_small" style="float:left;margin-left: 10px">
                                    <z-float-input placeholder="lng" v-model="hotel.location.lng" name="lng"></z-float-input>
                                </div>
                                <div style="clear: both"></div>
                            </div>

                            <z-float-input placeholder="地址" v-model="hotel.location.address" name="address"></z-float-input>
                            <z-float-textarea placeholder="交通方式" v-model="hotel.location.transportation" name="transportation"></z-float-textarea>
                        </div>
                        <div style="clear: both"></div>
                        <select class="form-control" v-model="hotel.brand" style="width: 120px;border-radius: 0px">
                            <option v-for="o in brands"><%o%></option>
                        </select>
                        <div style="clear: both;height: 30px"></div>

                        <div class="section_title"  id="p2">致游推荐</div>
                        <div class="width_big" style="float: left;margin-right: 12px">
                            <z-float-textarea placeholder="致游推荐" v-model="hotel.zy.recommend" name="zy_recommend"></z-float-textarea>
                        </div>
                        <div class="width_big" style="float: left;">
                            <z-float-textarea placeholder="致游知道" v-model="hotel.zy.good_to_known" name="zy_description"></z-float-textarea>
                        </div>

                        <div style="clear: both;height: 30px"></div>

                        <div class="section_title"  id="p3">酒店详情</div>
                        <div id="detail_custom_div">
                            <div class="sub_title">自定义扩展
                                <span id="qn_custom" class="btn_action">图片</span>
                                <span class="btn_action" v-on:click="insert_image2">URL</span>
                                <a v-on:click="show_covers" class="btn_action">图库</a>
                            </div>

                            <div>
                                <div style="float: left;width: 420px;position: relative">
                                    <p class="info">
                                        格式说明<br />
                                        #title<br />
                                        description
                                    </p>
                                    <div class="width_big" style="float: left;">
                                        <z-float-textarea id="ta_custom" placeholder="自定义" v-model="hotel.detail.extend" rows="30" name="detail_environment"></z-float-textarea>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                                <div style="float:left;width: 400px;position: relative">
                                    <div style="height: 600px;overflow-y: auto;border: 1px solid lightgrey;padding: 15px;position: relative">
                                        <div v-if="hotel.detail.extend">
                                            <div v-for="section in detail_extend">
                                                <div style="font-size: 16px;font-weight: bolder;margin-bottom: 10px"><%section.title%></div>
                                                <div style="font-size: 12px;line-height: 24px" v-html="markdown(section.content)"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                            </div>
                        </div>
                        <div style="clear: both;height: 30px"></div>
                        <div class="section_title" id="p4">客房类型</div>
                        <div style="border: 1px solid lightgrey;padding-top: 10px;padding-right: 10px;padding-left: 10px">
                            <div class="btn_room_insert" v-on:click="add_room(-1)">+</div>
                            <div v-for="(room,index) in hotel.rooms" >
                                <div :class="current_room == index ? 'btn_room_focus' : 'btn_room' "
                                     v-on:click="ui_select_room(index)"><%room.name%></div>
                                <div class="btn_room_insert" v-on:click="add_room(index)">+</div>
                            </div>

                            <div style="clear: both"></div>
                        </div>
                        <div style="height: 15px;width: 100%"></div>
                        <div style="height: 15px;width: 100%"></div>
                        <div class="">

                            <div v-if="current_room >= 0" class="div_room">
                                {{--<div>--}}
                                    {{--<span class="span_room_index" style="background-color: grey;font-size: 12px;color: white;padding: 6px 12px">房型 <% current_room+1 %></span>--}}
                                {{--</div>--}}
                                <div style="height: 20px;width: 100%"></div>

                                <div>
                                    <div class="width_normal" style="float: left">
                                        <z-float-input placeholder="名称" v-model="hotel.rooms[current_room].name" name="name"></z-float-input>
                                        <z-float-input placeholder="英文名" v-model="hotel.rooms[current_room].name_en" name="name_en"></z-float-input>
                                    </div>
                                    <div style="float: left;margin-left: 10px;width: 80px">
                                        <select class="form-control" v-model="hotel.rooms[current_room].online">
                                            <option value="1">有效</option>
                                            <option value="0">无效</option>
                                        </select>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                                <div>
                                    <div class="width_small" style="float: left">
                                        <z-float-input placeholder="最大人数" v-model="hotel.rooms[current_room].max_persons" type="number"></z-float-input>
                                    </div>
                                    <div class="width_small" style="float: left;margin-left: 10px">
                                        <z-float-input placeholder="成人数" v-model="hotel.rooms[current_room].adult" type="number"></z-float-input>
                                    </div>
                                    <div class="width_small" style="float: left;margin-left: 10px">
                                        <z-float-input placeholder="儿童数" v-model="hotel.rooms[current_room].children" type="number"></z-float-input>
                                    </div>
                                    <div class="width_small" style="float:left;margin-left: 10px">
                                        <z-float-input placeholder="儿童年龄" v-model="hotel.rooms[current_room].children_age" type="number"></z-float-input>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                                <div>
                                    <div style="float:left;width: 400px">
                                        <z-float-textarea placeholder="描述" v-model="hotel.rooms[current_room].description" name="description"></z-float-textarea>
                                    </div>
                                    <div class="example" style="float: left;width: 300px;margin-left: 15px;font-size: 12px;color: lightgrey">
                                        样例 : <br/>
                                        错层别墅，卧室位于上层，阳光平台位于下层，阳光平台配备躺椅和私人用餐区，别墅分布在整个度假酒店内，享有部分泰国湾海景，宽大的空调浴室，另设室外淋浴。
                                    </div>
                                    <div style="clear: both"></div>
                                </div>

                                <div>
                                    <div style="float:left;width: 400px">
                                        <z-float-textarea placeholder="面积,床,入住人数" v-model="hotel.rooms[current_room].highlight" name="highlight"></z-float-textarea>
                                    </div>
                                    <div class="example" style="float: left;width: 300px;margin-left: 15px;font-size: 12px;color: lightgrey">
                                        样例, 3行 : <br/>
                                        1,400平方英尺（130平方米）<br/>
                                        一张大床或者2张单人床<br/>
                                        可入住2位成人
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                                <div>
                                    <div style="float:left;width: 400px">
                                        <z-float-textarea placeholder="酒店设施" v-model="hotel.rooms[current_room].facilities" name="facilities"></z-float-textarea>
                                    </div>
                                    <div class="example" style="float: left;width: 300px;margin-left: 15px;font-size: 12px;color: lightgrey">
                                        样例 : <br/>
                                        独立控制式空调和吸顶式风扇、宽大沙发床 、宽大的空调浴室，一个浴缸和双盥洗盆、室外淋浴、六善浴室设施 、电吹风 、迷你吧、咖啡机、茶设施 、六善瓶装水
                                    </div>
                                    <div style="clear: both"></div>
                                </div>

                                <div>
                                    <div class="sub_title">选封面
                                        <a v-on:click="show_covers" data-image="dir" class="btn_action">图库</a>
                                    </div>
                                    <div class="width_big">
                                        <z-float-textarea placeholder="点击图库选择" v-model="hotel.rooms[current_room].images_str" name="name"></z-float-textarea>
                                    </div>
                                    <div>
                                        <img v-for="item in str_2_arr(hotel.rooms[current_room].images_str)" :src="show_thumbnail(item,'?imageView2/2/w/200')"
                                             style="width: 100px;margin-left: 3px;margin-bottom: 3px" />
                                    </div>
                                </div>
                                <div style="padding: 8px 0">
                                    {{--<button type="button" class="btn btn-defaukt btn-sm" v-on:click="add_room(current_room)">插入一房型</button>--}}
                                    <button type="button" class="btn btn-defaukt btn-sm" v-on:click="delete_room(current_room)">删除当前房型</button>
                                </div>
                                <div style="background-color: lightgrey;height: 2px;width: 100%"></div>
                                <div style="height: 15px;width: 100%"></div>
                            </div>
                        </div>


                        <div style="clear: both;height: 30px"></div>
                        <div class="section_title" id="p5">设施政策</div>
                        <div class="width_big">
                            <z-float-textarea placeholder="酒店设施" v-model="hotel.facilities" name="name"></z-float-textarea>
                            <z-float-input placeholder="入住时间" v-model="hotel.policy.checkin" name="name"></z-float-input>
                            <z-float-input placeholder="退房时间" v-model="hotel.policy.checkout" name="name"></z-float-input>
                            <z-float-textarea placeholder="取消政策" v-model="hotel.policy.cancellation" name="name"></z-float-textarea>
                            <z-float-textarea placeholder="儿童政策" v-model="hotel.policy.children" name="name"></z-float-textarea>
                            <z-float-textarea placeholder="加床" v-model="hotel.policy.extra_bed" name="name"></z-float-textarea>
                            <z-float-textarea placeholder="宠物" v-model="hotel.policy.pet" name="name"></z-float-textarea>
                            <z-float-textarea placeholder="银行卡" v-model="hotel.policy.payment" name="name"></z-float-textarea>
                        </div>

                        <div style="clear: both;height: 30px"></div>

                        <div class="section_title" id="p6">奖项与荣誉</div>
                        <p class="info">
                            格式说明<br />
                            一行奖项名称<br />
                            一行获得奖项或者说明
                        </p>
                        <div class="width_big">
                            <z-float-textarea placeholder="奖项" v-model="hotel.honor" name="name"></z-float-textarea>
                        </div>
                        <div class="width_big">
                            <div class="sub_title">荣誉图片
                                {{--<span id="qn_custom2" class="btn_action">图片</span>--}}
                                <span class="btn_action" data-image="dir" v-on:click="insert_image2">URL</span>
                                <a v-on:click="show_covers" data-image="dir" class="btn_action">图库</a>
                            </div>
                            <z-float-textarea placeholder="图片" v-model="hotel.honor_img" name="name"></z-float-textarea>
                        </div>
                        <div class="width_big">
                            <z-float-textarea placeholder="品牌与荣誉" v-model="hotel.honor_word" name="name"></z-float-textarea>
                        </div>
                        <div id="img-preview" style="padding: 10px;border: 1px solid lightgrey">
                            <div>
                                <img v-for="img in str_2_arr(hotel.honor_img)"
                                    :src="img" width="100px" height="50px" style="margin-right: 10px"
                                />
                                <p style="font-size: 12px;line-height: 24px"><%hotel.honor_word%></p>
                            </div>
                        </div>


                        <div style="clear: both;height: 30px"></div>
                        <div id="p7" class="section_title">
                            <div style="float: left">图片管理</div>
                            {{--<span v-on:click="upload_covers" id="upload"  style="float: right;margin-right: 10px;color: grey;border: 1px solid grey;--}}
                            <button type="button" id="pickfiles"  style="float: right;margin-right: 10px;color: grey;border: 1px solid grey;
                            font-size: 10px;padding: 3px 6px;cursor: pointer">Upload</button>
                            <div style="clear: both"></div>
                        </div>

                        <div>
                            <div v-for="(image,index) in sortedCover" style="float: left;width: 200px;margin-right: 10px;margin-bottom: 10px;position: relative">
                                <img :src="image.url+'?imageView2/2/w/200'" style="object-fit: cover;width: 200px;height: 160px"/>
                                <div style="margin-top: 6px">
                                    <button v-on:click="set_image_valid(index)" type="button" class="btn btn-default btn-sm" style="color:green">有效</button>
                                    <button v-on:click="set_image_top(index)" type="button" class="btn btn-default btn-sm" style="color:blue">置顶</button>
                                    <button v-on:click="set_image_invalid(index)" type="button" class="btn btn-default btn-sm" style="color:indianred">无效</button>
                                    <button v-on:click="confirm_delete_cover(index)" type="button" class="btn btn-default btn-sm" style="color:indianred">删除</button>
                                </div>
                                <div style="margin-top: 6px">
                                    <input v-model="image.tag" class="form-control input_table" placeholder="tag" style="max-width: 120px" />
                                </div>
                                <span v-if="image.status == 0" style="position: absolute;left:0;top:0;background-color: darkred;padding: 3px 6px;font-size: 8px;color: white">无效</span>
                            </div>
                        </div>


                        <div style="clear: both;height: 30px"></div>
                    </div>
                </form>
                <div id="popupBottom" class="popover">
                    <div class="arrow"></div>
                    <h3 class="popover-title">选择图片</h3>
                    <input class="form-control" placeholder="filter by tag" v-model="filter_words" />
                    <div class="popover-content">
                        <div v-for="(image,index) in filter_covers" v-on:click="select_image" style="float: left;width: 200px;margin-right: 10px;margin-bottom: 10px;position: relative">
                            <img :src="image.url+'?imageView2/2/w/200'" style="object-fit: cover;width: 200px;height: 160px"/>
                            <div style="margin-top: 6px">
                                <%image.tag%>
                            </div>
                            <span v-if="image.status == 0" style="position: absolute;left:0;top:0;background-color: darkred;padding: 3px 6px;font-size: 8px;color: white">无效</span>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>

        </div>

        {{--sidebar--}}
        <div style="float: left;width: 120px;margin-left: -100%;">
            <section style="border: 1px solid whitesmoke;background-color: white" data-spy="affix" data-offset-top="70">
                <!-- Sidebar Menu -->
                <div style="height: 6px;width: 100%;background-color: whitesmoke"></div>
                <ul class="sidebar_hotel">
                    <li><a href="#p1">基本信息</a></li>
                    <li><a href="#p2">致游推荐</a></li>
                    <li><a href="#p3">酒店详情</a></li>
                    <li>
                        <a href="#p4">客房类型</a>
                    </li>
                    <li><a href="#p5">设施政策</a></li>
                    <li><a href="#p6">奖项</a></li>
                    <li><a href="#p7">图片管理</a></li>
                    <li v-if="hotel._id"><a :href="'plan?id='+hotel._id" style="color: lightblue;">合同</a></li>
                    <li v-if="hotel._id"><a :href="'hotel/detail/'+hotel._id" style="color: lightblue;">预览</a></li>
                </ul>
                <div style="height: 8px;width: 100%;background-color: whitesmoke"></div>
                <div style="height: 12px;width: 100%;"></div>
                <ul class="sidebar_hotel">
                    <li>
                        <button class="btn btn-default menu_btn" onclick="update_data(this)">save</button>
                    </li>
                </ul>


                <!-- /.sidebar-menu -->
            </section>
        </div>

        <div style="clear: both"></div>
        {{--<button id="pickfiles" type="button" class="btn btn-default">test</button>--}}
    </div>

@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script src="{{asset('js/libs/vue-router.min.js')}}"></script>
<script src="{{asset('js/libs/zhotel_lib.js')}}"></script>
<script src="{{asset('js/libs/swiper.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.ajaxupload.js')}}"></script>
<script src="{{asset('js/libs/bootstrap-modal-popover.js')}}"></script>
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/bootstrap3-typeahead.min.js')}}"></script>

<script src="https://cdn.staticfile.org/plupload/2.1.9/moxie.min.js"></script>
<script src="https://cdn.staticfile.org/plupload/2.1.9/plupload.min.js"></script>
<script src="https://cdn.staticfile.org/qiniu-js-sdk/1.0.14-beta/qiniu.min.js"></script>
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
    var all_cities = []
    var all_districts = []
    function try_to_find(name){
        for(var i= 0,len = all_cities.length;i<len;i++){
            if(all_cities[i].name == name || all_cities[i].name_en == name){
            console.log("find city")
            return all_cities[i];
            }
        }
        return null;
    }
    function progress_add(container){
        var progress = '<div id="div_progress" class="bg_progress">获取图片保存中...</div>'
        container.append(progress);
    }
    function progress_remove(){
        $("#div_progress").remove();
    }
    function progress_go(percent){
        $("#div_progress").text("上传中("+percent+")...");
    }

    var hash_back = "";

    window.addEventListener("beforeunload", function (e) {
        if(hash_back != hotelList.helper_hotel_json()){
            var confirmationMessage = '离开之前请确认保存,否则更改信息会丢失的!!!';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        }
    });
</script>
<script>
    var uploader = null;
    var uploader2 = null;

    var progress_files = 0;
    var progress_current = 1;
    function init_7n(){
        if(uploader == null){
            console.log("init 7n");
            uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',
                uptoken_url: '/qiniu/token',
                get_new_uptoken: true,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                domain: 'http://oytstg973.bkt.clouddn.com',     // bucket域名，下载资源时用到，必需
                max_file_size: '20mb',             // 最大文件体积限制
                flash_swf_url: 'https://cdn.staticfile.org/plupload/2.1.9/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                unique_names: false,
                save_key: false,
                init: {
                    'FilesAdded': function(up, files) {
                        progress_add($("#pickfiles").parent());
                        progress_files = files.length;
                        progress_current = 1;
                        console.log("files added "+progress_files+"("+progress_current+")");
                    },
                    'UploadProgress': function(up, file) {
                        // 每个文件上传时，处理相关的事情
                        var percent = file.percent;
                        var f = progress_current * 1.0 / progress_files;
                        var p = percent * f;
                        console.log(p);
                        progress_go(p + "%");
                    },
                    'FileUploaded': function(up, file, info) {
                        console.log("file uploaded");
                        console.log(info);
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        var sourceLink = domain +"/"+ res.key;
                        console.log(sourceLink);
                        hotelList.helper_append_hotel_images(sourceLink);
                        progress_current++;
                    },
                    'Key': function(up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在 unique_names: false , save_key: false 时才生效
                        console.log("get key here");
                        console.log(up);
                        console.log(file);
                        var ext = file.name.split('.').pop();;
                        var hotel_id = "none";
                        if(hotelList.hotel._id){
                            hotel_id = hotelList.hotel._id;
                        }
                        var key = "zhotel_"+hotel_id+"_"+ Date.now()+"."+ext;
                        // do something with key here
                        return key
                    },
                    'UploadComplete': function() {
                        progress_remove();
                        progress_current = 1;
                        console.log("UploadComplete");
                    },
                }
            });
        }
        if(uploader2 == null){
            console.log("init 7n 2");
            var Q2 = new QiniuJsSDK();
            uploader2 = Q2.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'qn_custom',
                uptoken_url: '/qiniu/token',
                get_new_uptoken: true,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                domain: 'http://oytstg973.bkt.clouddn.com',     // bucket域名，下载资源时用到，必需
                max_file_size: '20mb',             // 最大文件体积限制
                flash_swf_url: 'https://cdn.staticfile.org/plupload/2.1.9/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                unique_names: false,
                save_key: false,
                init: {
                    'FilesAdded': function(up, files) {
                        progress_add($("#qn_custom").parent());
                        progress_files = files.length;
                        progress_current = 1;
                        console.log("files added "+progress_files+"("+progress_current+")");
                    },
                    'UploadProgress': function(up, file) {
                        // 每个文件上传时，处理相关的事情
                        var percent = file.percent;
                        var f = progress_current * 1.0 / progress_files;
                        var p = percent * f;
                        console.log(p);
                        progress_go(p + "%");
                    },
                    'FileUploaded': function(up, file, info) {
                        console.log("file uploaded 2");
                        console.log(info);
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        var sourceLink = domain +"/"+ res.key;
                        helper_insert(sourceLink);
                        hotelList.helper_append_hotel_images(sourceLink);
                        progress_current++;
                    },
                    'UploadComplete': function() {
                        progress_remove();
                        progress_current = 1;
                        console.log("UploadComplete");
                    },
                    'Key': function(up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在 unique_names: false , save_key: false 时才生效
                        console.log("get key here");
                        var ext = file.name.split('.').pop();;
                        var hotel_id = "none";
                        if(hotelList.hotel._id){
                            hotel_id = hotelList.hotel._id;
                        }
                        var key = "zhotel_"+hotel_id+"_"+ Date.now()+"."+ext;
                        // do something with key here
                        return key;
                    }
                }
            });
        }
        function helper_insert(link){
            var editor = $("#ta_custom")[0];
            var text = link + "\n";

            if(editor.selectionStart || editor.selectionStart === 0) {//working in chrome
                // Others
                var start_pos = editor.selectionStart;
                var end_pos = editor.selectionEnd;
                editor.value = editor.value.substring(0, start_pos) +
                        text +
                        editor.value.substring(end_pos, editor.value.length);
                editor.selectionStart = start_pos + text.length;
                editor.selectionEnd = start_pos + text.length;
                editor.focus();
            }
            hotelList.helper_js_event(editor);
        }
    }
</script>
{{--<script async defer--}}
        {{--src="http://ditu.google.cn/maps/api/js?key=AIzaSyBsq4XZqfUvjU686OBnxAV9FZSwfXjXy9k&libraries=geometry,places&callback=cb_map">--}}
{{--</script>--}}

<script>
    Vue.component('z-float-input',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "dom_id","type"],
        template: '<label class="form-group has-float-label">' +
        '<input :id="dom_id" class="form-control form_input" :type="type" :name="name" :placeholder="placeholder" ' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '<span><% placeholder %></span>',
        methods: {
            update:function(value){
                this.$emit('input', value)
            }
        }
    });
    Vue.component('z-float-input-city',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "dom_id"],
        template: '<label class="form-group has-float-label">' +
        '<input :id="dom_id" class="form-control form_input" :name="name" :placeholder="placeholder" ' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '<span><% placeholder %></span>',
        mounted:function(){
            const self = this;
            const id = ("#"+this.dom_id);
            axios.get('json/all_city_qyer.json',{
                    })
                    .then(function(response){
                        self.source = response.data;
                        all_cities = response.data;

                        $(id).typeahead({
                            source: self.source,
                            autoSelect: false,
                            fitToElement:true,
                            afterSelect:function(v){
                                self.update(v);
                            },
                            displayText:function(item){
                                return item.name + " " + item.name_en
                            }
                        });
                    })
                    .catch(function(error){
                        console.log(error);
                    });
        },
        data : function(){
            return {
                dom_id: this.dom_id,
                source: [
                ]
            }
        },
        methods: {
            update:function(item){
                this.$emit('input', item.name)
                this.$emit('city_select', item)
            }
        }
    });
    Vue.component('z-float-input-district',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "dom_id"],
        template: '<label class="form-group has-float-label">' +
        '<input :id="dom_id" class="form-control form_input" :name="name" :placeholder="placeholder" ' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '<span><% placeholder %></span>',
        mounted:function(){
            const self = this;
            const id = ("#"+this.dom_id);
            axios.get('json/district.json',{
                    })
                    .then(function(response){
                        self.source = response.data;
                        all_districts = response.data;

                        $(id).typeahead({
                            source: self.source,
                            autoSelect: false,
                            fitToElement:true,
                            afterSelect:function(v){
                                self.update(v);
                            },
                            displayText:function(item){
                                return item.district
                            }
                        });
                    })
                    .catch(function(error){
                        console.log(error);
                    });
        },
        data : function(){
            return {
                dom_id: this.dom_id,
                source: [
                ]
            }
        },
        methods: {
            update:function(item){
                if(item.hasOwnProperty("district")){
                    this.$emit('input', item.district)
                    this.$emit('district_select', item)
                }
            }
        }
    });
    Vue.component('z-float-input-continent',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "dom_id"],
        template: '<label class="form-group has-float-label">' +
        '<input :id="dom_id" class="form-control form_input" :name="name" :placeholder="placeholder" ' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '<span><% placeholder %></span>',
        mounted:function(){
          console.log("mounted");
            var id = ("#"+this.dom_id);
            const self = this;
            $(id).typeahead({
                source: self.source,
                autoSelect: false,
                showHintOnFocus:'all',
                fitToElement:true,
                afterSelect:function(v){
                    console.log(v);
                    self.update(v.name);
                }
            });
        },
        data : function(){
            return {
                dom_id: this.dom_id,
                source: [
                    {id: "1", name: "亚洲"},
                    {id: "2", name: "欧洲"},
                    {id: "3", name: "北美洲"},
                    {id: "4", name: "南美洲"},
                    {id: "5", name: "非洲"},
                    {id: "6", name: "大洋洲"},
                    {id: "7", name: "南极洲"},
                ]
            }
        },
        methods: {
            update:function(value){
                this.$emit('input', value)
            }
        }
    });
    Vue.component('z-float-textarea',{
        delimiters: ["<%","%>"],
        props:["placeholder","name", "value", "rows","id"],
        template: '<label class="form-group has-float-label">' +
        '<textarea :id="id" class="form-control form_input" :name="name" :placeholder="placeholder" :rows="rows ? rows : 6"' +
        'v-bind:value="value"' +
        'v-on:input="update($event.target.value)"/>' +
        '</textarea>' +
        '<span><% placeholder %></span>' +
        '</label>',
        methods: {
            update:function(value){
                this.$emit('input', value)
            }
        }
    });

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
            parentTitle:"hotelList Title",
            hotel:{
                name:"",
                name_en:"",
                tag:"",
                description:"",
                facilities:"",
                honor:"",
                honor_img:"",
                honor_word:"",
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
            current_btn : null,
            current_room : -1,
            loading:true,
            filter_words : "",
            filter_covers : [],
            brands : [
                    "品牌1","品牌2"
            ]
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
        updated :  function(){
            console.log("updated")
            var swiper = new Swiper('.markdown-gallery', {
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                pagination: {
                    el: '.swiper-pagination',
                },
                paginationClickable: true,
                spaceBetween: 0,
                loop:true
            });
            init_7n();
        },
        mounted:function(){
            console.log("mounted")
        },
        methods:{
            check_data : function(){
                if(this.hotel.name == ""){
                    return [false, "酒店名字不能为空"]
                }
                if(this.hotel.location.city == ""){
                    return [false, "城市不能为空"]
                }
                var rooms = this.hotel.rooms;
                if(rooms){
                    var dict = {}
                    for(var i= 0,len = rooms.length;i<len;i++){
                        if(dict.hasOwnProperty(rooms[i].name)){
                            //dup
                            return [false, "房型名字重复 ===> "+rooms[i].name + " [ "+dict[rooms[i].name]+" , "+(i+1)+" ] "];
                        }
                        else{
                            dict[rooms[i].name] = i+1 ;
                        }
                    }
                }
                return [true, "ok"]
            },
            get_data : function(_id){
                const self = this;
                console.log("created");
                axios.post('/api/hotel/'+_id,{
                        })
                        .then(function(response){
                            console.log(response.data);
                            self.hotel = response.data.obj;

                            if(self.hotel.rooms && self.hotel.rooms.length > 0){
                                self.current_room = 0;
                            }
                            self.loading = false;
                            hash_back = self.helper_hotel_json();
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            update_data : function(){
                var check = this.check_data();
                if(check[0] == false){
                    toastr["info"](check[1]);
                    return;
                }
                const self = this;
                var paras = JSON.parse(JSON.stringify(this.$data));
                console.log(paras);
                var url = 'api/update/hotel';
                if(paras.hotel.hasOwnProperty("_id") && paras.hotel._id != ""){
                    //has id
                    console.log("update hotel")
                }
                else{
                    url = 'api/create/hotel';
                    console.log("create hotel")
                }
                axios.post(url, paras.hotel)
                        .then(function(response){
                            console.log(response.data);
                            self.hotel = response.data.obj;
                            toastr["success"](response.data.msg);
                            hash_back = self.helper_hotel_json();
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            add_room : function(index){
                var id = "room."+performance.now();
                var name = "房型名字";
                var empty = {
                    id : id,
                    name : name,
                    name_en : "",
                    online : 1,
                    description : "",
                    highlight : "",
                    zy : "",
                    info : "",
                    facilities : "",
                    max_persons : "2",
                    adult : "2",
                    children : "0",
                    children_age : "12",

                }
                this.hotel.rooms.splice(index+1, 0, empty);
                this.current_room = index+1;
            },
            set_image_valid : function(index){
                this.hotel.images[index].status = 1;
            },
            set_image_top : function(index){
                this.hotel.images[index].status = Date.now();
            },
            set_image_invalid : function(index){
                this.hotel.images[index].status = 0;
            },
            delete_room : function(index){
                var name = this.hotel.rooms[index].name;
                if(confirm("确定要删除房型 【"+(name)+"】?"))
                    this.hotel.rooms.splice(index,1);

                if(this.hotel.rooms.length == 0){
                    this.current_room = -1;
                }
            },
            confirm_delete_cover : function(index){
                if(confirm("确定要删除图片 "+(index+1)+"?"))
                    this.hotel.images.splice(index,1);
            },
            upload_covers : function(e){//批量上传,使用js方式,本方法暂时弃用
                var self = e.target;
                const vue_data = this;
                var progress = '<div id="div_progress" style="padding:8px;margin-top: 6px">上传图片中...</div>'


                $.ajaxUploadSettings.name = 'file[]';
                $.ajaxUploadPrompt({
                    url : '/uploader/image',
                    multiple : true,
                    beforeSend : function(){
                        $(self).parent().append(progress);
                    },
                    onprogress : function(e){
                        if (e.lengthComputable) {
                            var percentComplete = e.loaded / e.total;
//                            $("#progress").css("width",percentComplete*100+"%");
                            console.log(percentComplete)
                        }
                    },
                    error : function(){
                        $("#div_progress").remove();
                    },
                    success : function(data){
                        console.log(data);
                        if(data.ok == 0){
                            var urls = data.obj.urls;
                            for(var i= 0,len = urls.length;i<len;i++){
                                var url = urls[i];
                                var image = {
                                    url : url,
                                    tag : "",
                                    status : 1,
                                    created_at : Date.now()
                                }
                                if(!vue_data.hotel.images){
                                    vue_data.hotel.images = [];
                                }
                                vue_data.hotel.images.splice(0,0,image);
                            }

                        }
                        else{
                            toastr["error"](data.msg);
                        }
                        $("#div_progress").remove();
                    }
                });
            },
            insert_image :  function(e){//暂时弃用,使用js-sdk上传
                var self = e.target;
                const thiz = this;
                var editor = $(self).parent().parent().find('textarea')[0];

                var progress = '<div id="div_progress" class="progress" style="height: 10px;margin-top: 6px">\
                        <div id="progress" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"\
                        style="width: 0;">\
                        <span class="sr-only">40% Complete (success)</span>\
                        </div>\
                        </div>'

                $.ajaxUploadSettings.name = 'file';
                $.ajaxUploadPrompt({
                    url : '/uploader/image',
                    data : {_id:thiz.hotel._id},
                    beforeSend : function(){
                        $(self).parent().append(progress);
                    },
                    onprogress : function(e){
                        if (e.lengthComputable) {
                            var percentComplete = e.loaded / e.total;
                            $("#progress").css("width",percentComplete*100+"%");
                            console.log(percentComplete)
                        }
                    },
                    error : function(){
                        $("#div_progress").remove();
                    },
                    success : function(data){
                        console.log(data);
                        if(data.ok == 0){
                            var text = '![]('+data.obj.url+')\n';
                            if(editor.selectionStart || editor.selectionStart === 0) {//working in chrome
                                // Others
                                var start_pos = editor.selectionStart;
                                var end_pos = editor.selectionEnd;
                                editor.value = editor.value.substring(0, start_pos) +
                                        text +
                                        editor.value.substring(end_pos, editor.value.length);
                                editor.selectionStart = start_pos + text.length;
                                editor.selectionEnd = start_pos + text.length;
                                editor.focus();
                                toastr["success"](data.msg);
                            }
                            this.helper_js_event(editor);
                        }
                        else{
                            toastr["error"](data.msg);
                        }
                        $("#div_progress").remove();
                    }
                });


            },
            insert_image2 : function(e){//通过url方式下载
                var url= prompt("Image url is ","")
                if(url){
                    console.log(url);
                    const thiz = this;
                    var self = e.target;
                    var dir = $(self).attr("data-image");
                    var editor = $(self).parent().parent().find('textarea')[0];
                    var post_url = "/fetcher/image";
                    progress_add($(self).parent());
                    axios.post(post_url, {url:url,_id:thiz.hotel._id})
                            .then(function(response){
                                console.log(response.data);
                                if(response.data.ok == 0){
                                    var text = '![]('+response.data.obj.url+')\n';
                                    if(dir){
                                        text = response.data.obj.url+'\n';
                                    }
                                    if(editor.selectionStart || editor.selectionStart === 0) {//working in chrome
                                        // Others
                                        var start_pos = editor.selectionStart;
                                        var end_pos = editor.selectionEnd;
                                        editor.value = editor.value.substring(0, start_pos) +
                                                text +
                                                editor.value.substring(end_pos, editor.value.length);
                                        editor.selectionStart = start_pos + text.length;
                                        editor.selectionEnd = start_pos + text.length;
                                        editor.focus();
                                        toastr["success"](response.data.msg);
                                    }
                                    thiz.helper_js_event(editor);
                                    thiz.helper_append_hotel_images(response.data.obj.url);
                                }
                                else{
                                    toastr["error"](data.msg);
                                }
                                progress_remove();
                            })
                            .catch(function(error){
                                console.log(error)
                                progress_remove();
                            });
                }
                else{
                    console.log("no url");
                }
            },
            show_covers : function(event){
                console.log("show images");
                this.current_btn = event.target;

                this.filter_words = "";
                this.helper_filter_cover();
                $("#popupBottom").modalPopover({
                    backdrop:true,
                    target:event.target
                });
                $("#popupBottom").modalPopover("show");
            },
            select_image : function(e){
//                $('#popupBottom').modalPopover('hide')
                var self = this.current_btn;
                var type = $(self).attr("data-image");
                var editor_j = $(self).parent().parent().find('textarea');
                var editor = editor_j[0];
                var text = zhotel_clear_url_parameters($(e.target).parent().find("img").attr("src"));

                if(type && type == "dir"){
                    text = text + "\n";
                }
                else{
                    text = "![]("+text+")\n";
                }
                console.log("select image");
                console.log(text);
                if(editor.selectionStart || editor.selectionStart === 0) {//working in chrome
                    // Others
                    var start_pos = editor.selectionStart;
                    var end_pos = editor.selectionEnd;
                    editor.value = editor.value.substring(0, start_pos) +
                            text +
                            editor.value.substring(end_pos, editor.value.length);
                    editor.selectionStart = start_pos + text.length;
                    editor.selectionEnd = start_pos + text.length;
                    editor.focus();
                }
                this.helper_js_event(editor);
            },
            helper_js_event : function(obj){
                var evt = document.createEvent('HTMLEvents');
                evt.initEvent('input', false, true);
                obj.dispatchEvent(evt);
            },
            event_city : function(item){
                console.log("parent event callback");
                this.$data.hotel.location.continent = item.continent
                this.$data.hotel.location.country = item.country_name
            },
            event_district : function(item){
                if(item){
                    this.$data.hotel.location.continent = item.continent
                    this.$data.hotel.location.country = item.country
                    this.$data.hotel.location.city = item.city
                }
            },
            str_2_arr : function(str){
                if(!str) return "";
                return str.trim().split("\n");
            },
            magic_url : function(e){
                const ele = e.target;
                const self = this;
                $(ele).button("loading");
                let config = {
                    headers : {
                        'Content-Type':'application/json;charset=UTF-8',
                         'Accept': 'application/json'
                    }
                }
                var url = $("#magic_url").val();
                console.log("paras");
                console.log(url);

                url = encodeURIComponent(url);
                console.log(url);

                if(!url){
                    toastr["info"]("请填写url")
                    $(ele).button("reset");
                    return;
                }
                //http://127.0.0.1:5000http://jingang.info/
                axios.post("api/parse/hotel", {url:url}, config)
                        .then(function(response){
                            console.log("hotel parse response");
                            console.log(response.data);
                            if(response.data.ok == 0){
                                var d = response.data.obj;
                                if(d.refer == "booking"){
                                    self.hotel.policy.checkin = d.checkin;
                                    self.hotel.policy.checkout = d.checkout;
                                    self.hotel.policy.children = d.children;
                                    self.hotel.policy.pet = d.pet;
                                    self.hotel.policy.payment = d.payment;
                                    self.hotel.policy.cancellation = d.cancellation;

                                    self.hotel.description = d.description;
                                    self.hotel.name = d.name;
                                    self.hotel.name_en = d.name_en;

                                    self.hotel.location.address = d.address;
                                    self.hotel.location.city = d.city;
                                    self.hotel.location.district = d.district;
                                    var s = try_to_find(d.city);
                                    if(s){
                                        self.hotel.location.country = s.country_name;
                                        self.hotel.location.continent = s.continent;
                                    }
                                }
                                else if(d.refer=="leading"){
                                    console.log("magic link");
                                    self.hotel.description = d.description;
                                    self.hotel.name = d.name;
                                    self.hotel.name_en = d.name_en;
                                    self.hotel.honor = d.honor;
                                    self.hotel.location.lat = d.lat;
                                    self.hotel.location.lng = d.lng;
                                    self.hotel.location.address = d.address;
                                    self.hotel.location.city = d.city;
                                    self
                                    var s = try_to_find(d.city);
                                    if(s){
                                        self.hotel.location.country = s.country_name;
                                        self.hotel.location.continent = s.continent;
                                    }
                                    if(d.room){

                                        for(var i= 0,len = d.room.length;i<len;i++){
                                            var id = "room."+performance.now();
                                            var empty = {
                                                id : id,
                                                name : d.room[i].name,
                                                name_en : d.room[i].name_en,
                                                online : 1,
                                                description : "",
                                                highlight : d.room[i].highlight,
                                                zy : "",
                                                info : "",
                                                facilities : "",
                                                max_person : d.room[i].max_persons,
                                                adult : d.room[i].adult,
                                                children : d.room[i].children,
                                                children_age : "12",

                                            }
                                            self.hotel.rooms.splice(i, 0, empty);
                                        }
                                    }
                                }
                                else{

                                }
                            }
                            else{
                                toastr["error"](response.data.msg)
                            }
                            $(ele).button("reset");
                        })
                        .catch(function(error){
                            console.log(error);
                            $(ele).button("reset");
                        });
            },
            ui_select_room : function(index){
                this.current_room = index;
            },
            markdown : function(str){
                return zhotel_markdown(str);
            },
            show_thumbnail : function(url, hack){
                  if(url.indexOf("?") == 0 ) {
                      return url + hack;
                  }
                return url;
            },
            helper_append_hotel_images : function (link){
                var image = {
                    url : link,
                    tag : "",
                    status : 0,
                    created_at : Date.now()
                }
                if(!this.hotel.images){
                    this.hotel.images = [];
                }
                this.hotel.images.splice(0,0,image);
            },
            helper_filter_cover :function(){
                function compare(a, b) {
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
                console.log("filter_covers");
                console.log(this.hotel.images);

                if(this.filter_words == ""){
                    this.filter_covers = this.hotel.images.sort(compare);
                }
                else{
                    this.filter_covers = [];
                    for(var i= 0,len = this.hotel.images.length;i<len;i++){
                        if(this.hotel.images[i].tag.indexOf(this.filter_words) !== -1)
                            this.filter_covers.push(this.hotel.images[i]);
                    }
                }

            },
            helper_hotel_json :function(){
                return JSON.stringify(this.$data.hotel);
            }
        },
        watch:{
            'filter_words' : {
                handler : function(newData, oldData){
                    console.log("filter words");
                    console.log("new : "+newData);
                    console.log("old : "+oldData);
                    this.helper_filter_cover();
                }
            }
        },
        computed : {
            sortedCover :function(){
                function compare(a, b) {
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

                return this.hotel.images.sort(compare);
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
            }
        }
    })

    function update_data(self){
        hotelList.update_data();
    }

</script>

<script>
    // google map
    var all_cities = []
    function try_to_find(name){
        for(var i= 0,len = all_cities.length;i<len;i++){
            if(all_cities[i].name == name || all_cities[i].name_en == name){
                console.log("find city")
                return all_cities[i];
            }
        }
        return null;
    }
    axios.get('json/all_city_qyer.json',{
            })
            .then(function(response){
                all_cities = response.data;
                console.log("get all cities for map")
            })
            .catch(function(error){
                console.log(error);
            });
    var map_items = [];
    function hack_map(self){
        if($("#div_map").is(":visible")){
            $("#div_map").hide()
        }
        else{
            $("#div_map").show();
        }
    }
    function map_select(index){
        console.log("map select");
        var data = map_items[index-1];


        hotelList.$data.hotel.location.address = data.formatted_address
        hotelList.$data.hotel.location.lat = data.geometry.location.lat()
        hotelList.$data.hotel.location.lng = data.geometry.location.lng()

        //京都 -> Tyoto
        //东京 -> Tokyo
        //上海市 -> 上海市
        console.log("send request");
        service.getDetails({
            placeId: data.place_id
        }, function(place, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                console.log("get detail")
                console.log(place)
                var len = place.address_components.length;
                var find = null
                for(var i= len-1;i >= 0;i--){
                    var p = place.address_components[i];
                    find = try_to_find(p.long_name);
                    if(find){
                        console.log("Found")
                        hotelList.$data.hotel.location.continent = find.continent;
                        hotelList.$data.hotel.location.country = find.country_name;
                        hotelList.$data.hotel.location.city = find.name;
                        return;
                    }
                    else{
                        console.log("Not Found")
                    }
                }
                if(!find){
                    toastr["error"]("城市解析失败,请手工填写");
                }
            }
        });
    }
    var service = null;
    function cb_map(){
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -33.8688, lng: 151.2195},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        service = new google.maps.places.PlacesService(map);
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            map_items = places;
            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            console.log(places);
            var max = 5;
            var index = 0;
            $("#map_result").empty();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                index = index + 1;
                if(index <= max){
                    var row = '<tr onclick="map_select('+index+')"><td>'+index+'</td>';
                    row = row + '<td>'+place.name+'<td></tr>'
                    $("#map_result").append(row);
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

</script>


@endsection