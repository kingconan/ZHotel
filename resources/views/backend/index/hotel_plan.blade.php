@extends("backend.layout.base_main")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    {{--<link rel="stylesheet" href="{{asset('css/libs/daterangepicker.min.css')}}"/>--}}
    <link rel="stylesheet" href="{{asset('css/libs/daterangepicker_ihotel.min.css')}}"/>
    <style>
        [v-cloak] {
            display: none;
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
        .contract_name{
            float: left;
            padding: 6px;
            border: 1px solid lightgrey;
            cursor: pointer;
            margin-right: 10px;
            height: 34px;
        }
        .contract_input{
            float: left;
            width: 200px;
            margin-right: 15px;

        }
        .bg_highlight{
            background-color: lightcyan;
        }
        .bg_white{
            background-color: white;
        }
        .room_normal{
            border-left: 1px solid lightgrey;
            border-right: 1px solid lightgrey;
            border-top: 1px solid lightgrey;
            padding: 8px
        }
        .room_selected{
            background-color: white;
            border-left: 1px solid lightgrey;
            border-top: 1px solid lightgrey;
            padding: 8px
        }
        .checkin{
            width: 120px;
            float: left;
        }
        .checkout{
            width: 120px;
            float: left;
            margin-left: 10px;
        }
        .table_input_l{
            width: 400px;
            float: left;
        }
        .table_input{
            width: 120px;
            float: left;
        }
        .table_input_s{
            width: 80px;
            float: left;
        }
        .table_span{
            float: left;
            height: 34px;
            font-size: 12px;
            padding: 12px;
            color: lightgrey;
        }
        label{
            font-size: 12px;
            color: lightgrey;
            -webkit-user-select: none; /* Safari */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* IE10+/Edge */
            user-select: none; /* Standard */
        }
        .btn-flat {
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            background:none;
            border:0;
        }
        .affix{
            top:0;
            width: 100%;
            z-index: 999;
        }
        .toast-top-center {
            top: 50%;
            margin: 0 auto;
        }
        .price_header{
            height: 30px;
        }
        .price_cell_week{
            width: 48px;
            height: 30px;
            text-align: center;
            border: 1px solid lightgrey;
        }
        .price_cell{
            width: 48px;
            height: 48px;
            text-align: center;
            border: 1px solid lightgrey;
            padding-top: 3px;
        }
        .price_color_base{
            font-size: 10px;font-weight: bolder;margin-top: -3px
        }
    </style>
@endsection
@section('content')
<div style="width: 100%;">
    <div id="hotel_list" v-cloak >
        <div v-if="loading">
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
        <div v-else>
            <div style="min-height: 115px">
                <div id="affix" style="background-color: white;border: 1px solid lightgrey;padding: 15px;"  >
                    <div v-on:click="ui_select_contract(index)" v-for="(c,index) in hotel.contracts" :class="index == currentIndex ? 'contract_name bg_highlight' : 'contract_name'">
                        <% c.name %>
                    </div>
                    <div class="contract_name" v-on:click="add_contract"> + 新建</div>
                    <div style="clear:both;"></div>
                    <div style="height: 15px;width: 100%"></div>
                    <button type="button" class="btn btn-info" v-on:click="save">保存</button>
                    <button type="button" class="btn btn-info" v-on:click="mode">MODE</button>
                    <button type="button" class="btn btn-info" style="margin-left: 60px" v-on:click="hack_copy">拷贝退改到优惠计划中</button>
                </div>
            </div>
            <div v-if="currentIndex >= 0" style="padding:15px 8px;margin-bottom: 15px;background-color: white;margin-top: 30px;border: 1px solid lightgrey;">

                <form class="form" id="form">
                    <div v-if="viewMode == 0">
                        <input placeholder="合同名字" class="form-control table_input_l" v-model="hotel.contracts[currentIndex].name" />
                        <select v-on:change="select_rate_changed" class="form-control table_input" style="margin-left: 10px" v-model="hotel.contracts[currentIndex].price_unit">
                            <option v-for="r in rates" :value="r.code"><%r.des%></option>
                        </select>
                        <input placeholder="汇率" class="form-control table_input" style="margin-left: 10px" type="number" v-model="hotel.contracts[currentIndex].price_rate" />
                        <div style="clear: both"></div>
                    </div>
                    <div v-if="viewMode == 1">
                        <strong>【<%hotel.contracts[currentIndex].name%>】</strong>
                        <span>币种 : <%hotel.contracts[currentIndex].price_unit%></span>
                        <span>( <%hotel.contracts[currentIndex].price_rate%> )</span>
                    </div>
                    <div style="height: 15px"></div>
                    <div>
                        <div style="float: left;width: 200px;min-height: 400px;background-color: whitesmoke" class="room_sidebar">
                            <div style="font-size: 16px;font-weight: bolder;padding: 12px;color: lightgrey">房型列表</div>
                            <div v-on:click="ui_select_room(roomIndex)"
                                 v-for="(room,roomIndex) in hotel.contracts[currentIndex].rooms"
                                 :class="roomIndex == currentRoom ? 'room_selected' : 'room_normal'"
                                 >
                                <% room.name %><br/><span style="font-size:12px;color: grey;"><% room.name_en %></span>
                            </div>
                            <div style="height: 1px;background-color: lightgrey;width: 100%"></div>
                        </div>
                        <div style="float: left;width: 800px;" class="room_setting">
                            {{--编辑模式--}}
                            <div v-if="viewMode == 0">
                                <div v-if="room_info" style="padding:0 15px">
                                    <div>
                                        <div style="font-size: 24px;font-weight: bolder;text-align: center"><%room_info.name%></div>
                                    </div>
                                    <div style="height: 15px"></div>
                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder" v-on:click="show_help_dates">基础班期</h6>
                                        <div v-if="is_help_date">
                                            <button type="button" class="btn btn-flat" v-on:click="reset_date(help_dates)">替换</button>
                                            <textarea class="form-control" rows="6" v-model="help_dates"></textarea>
                                        </div>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(price,priceIndex) in room_info.prices">
                                                <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%priceIndex+1%></td>
                                                <td width="280px">
                                                    <div class="tr_date">
                                                        <input class="form-control checkin" :data-default-time="pre_price(room_info.prices,priceIndex)" placeholder="开始日期" v-model="price.date_from"/>
                                                        <input class="form-control checkout" placeholder="结束日期" v-model="price.date_to"/>
                                                    </div>
                                                </td>
                                                <td width="200px"><input class="form-control" type="number" placeholder="价格"  v-model="price.price" /></td>
                                                <td><button v-on:click="delete_price_for_room(priceIndex)" type="button" class="btn btn-sm btn-default" style="color: indianred"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><%arr_min(room_info.prices)%> - <%arr_max(room_info.prices)%></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-default"  v-on:click="add_price_for_room">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 班期价格
                                        </button>
                                        <div>
                                            <label>+zy</label>
                                            <input class="form-control" type="number" placeholder="+zy" v-model="room_info.zy"/>
                                        </div>

                                        {{--基本价格的费用,退改,备注--}}
                                        <div style="border: 1px dashed lightgrey;padding: 12px;margin-top: 10px">
                                            <label>费用包含</label>
                                            <textarea class="form-control" rows="6" placeholder="费用包含" v-model="room_info.include"></textarea>
                                            <div style="height: 10px;width: 100%"></div>
                                            <label>退改规则</label>
                                            <textarea class="form-control" rows="6" placeholder="退改规则" v-model="room_info.cancellation"></textarea>
                                            <div style="height: 10px;width: 100%"></div>
                                            <label>备注</label>
                                            <textarea class="form-control" rows="6" placeholder="备注"  v-model="room_info.memo"></textarea>
                                        </div>
                                    </div>

                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">优惠计划</h6>
                                        <div v-for="(plan,planIndex) in room_info.plans" style="border: 2px solid lightgrey;padding: 8px;position: relative">
                                            <button type="button" v-on:click="delete_plan_for_room(planIndex)" class="btn btn-default btn-sm" style="position: absolute;top: 10px;right: 10px;color: indianred">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </button>
                                            <span style="position: absolute;top: 0;left: 0;padding: 1px 6px;background-color: grey;color: white;font-size: 8px"><%planIndex+1%></span>
                                            <table class="table">
                                                <tr>
                                                    <td width="200px">
                                                        <label>计划名字</label>
                                                        <input class="form-control" placeholder="计划名字" v-model="plan.name" />
                                                    </td>
                                                    <td width="140px">
                                                        <label>最小晚</label>
                                                        <input class="form-control" type="number" min="0" max="2018" placeholder="最小晚数" v-model="plan.night_min" />
                                                    </td>
                                                    <td width="140px">
                                                        <label>最大晚</label>
                                                        <input class="form-control" type="number" min="0" max="2018" placeholder="最大晚数" v-model="plan.night_max" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>有效日期</label>
                                                        <div class="tr_date" style="width: 260px">
                                                            <input class="form-control checkin" placeholder="开始日期" v-model="plan.date_from"/>
                                                            <input class="form-control checkout" placeholder="结束日期" v-model="plan.date_to"/>
                                                            <div style="clear: both"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <label>无效日期</label>
                                                        <button type="button" class="btn btn-sm btn-default"  v-on:click="add_date_no_for_plan(planIndex)">
                                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                                        </button>
                                                        <div v-for="(date_no,date_no_index) in plan.date_no">
                                                            <div style="clear: both;height: 10px;width: 100%"></div>
                                                            <div class="tr_date" style="width: 260px;float: left">
                                                                <input class="form-control checkin" placeholder="开始日期" v-model="date_no.date_from"/>
                                                                <input class="form-control checkout" placeholder="结束日期" v-model="date_no.date_to"/>
                                                                <div style="clear: both"></div>
                                                            </div>
                                                            <button v-on:click="delete_date_no_for_plan(planIndex,date_no_index)"
                                                                    type="button" class="btn btn-default btn-sm"
                                                                    style="float: left;color: indianred">删除</button>
                                                            <div style="clear: both"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="120px">
                                                        <select class="form-control" v-model="plan.type" v-on:change="select_changed">
                                                            <option>住X付Y</option>
                                                            <option>提起X天Z折扣</option>
                                                            <option>日期T之前Z折扣</option>
                                                            <option>住X延住K晚Z折扣</option>
                                                        </select>
                                                    </td>
                                                    <td colspan="3">
                                                        <div v-if="plan.type == '住X付Y' ">
                                                            <span class="table_span">住</span><input class="form-control table_input_s" type="number" min="0" placeholder="X" v-model="plan.obj.x" />
                                                            <span class="table_span">晚,付</span><input class="form-control table_input_s" type="number" min="0" placeholder="Y" v-model="plan.obj.y" />
                                                            <span class="table_span">晚</span>
                                                        </div>
                                                        <div v-else-if="plan.type == '提起X天Z折扣' ">
                                                            <span class="table_span">提前</span>
                                                            <input class="form-control table_input_s" type="number" min="1" placeholder="提前天数"  v-model="plan.obj.day"/>
                                                            <span class="table_span">天预订,</span>
                                                            <input class="form-control table_input_s" type="number" min="0.1" placeholder="折扣"  v-model="plan.obj.z"/>
                                                            <span class="table_span">折</span>
                                                        </div>
                                                        <div v-else-if="plan.type == '日期T之前Z折扣' ">
                                                            <input class="form-control table_input" placeholder="日期"  v-model="plan.obj.date"/>
                                                            <span class="table_span">之前预订,</span>
                                                            <input class="form-control table_input_s" type="number" min="0.1" placeholder="折扣"  v-model="plan.obj.z"/>
                                                            <span class="table_span">折</span>
                                                        </div>
                                                        <div v-else-if="plan.type == '住X延住K晚Z折扣' ">
                                                            <span class="table_span">住</span>
                                                            <input class="form-control table_input_s" type="number" min="0" placeholder="X"  v-model="plan.obj.x"/>
                                                            <span class="table_span">晚,延住</span>
                                                            <input class="form-control table_input_s" type="number" min="0" placeholder="Y"  v-model="plan.obj.y"/>
                                                            <span class="table_span">晚,</span>
                                                            <input class="form-control table_input_s" type="number" min="0.1" placeholder="折扣"  v-model="plan.obj.z"/>
                                                            <span class="table_span">折</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div style="border: 1px dashed lightgrey;padding: 12px">
                                                <label>费用包含</label>
                                                <textarea class="form-control" rows="6" placeholder="费用包含" v-model="plan.include"></textarea>
                                                <div style="height: 10px;width: 100%"></div>
                                                <label>退改规则</label>
                                                <textarea class="form-control" rows="6" placeholder="退改规则" v-model="plan.cancellation"></textarea>
                                                <div style="height: 10px;width: 100%"></div>
                                                <label>备注</label>
                                                <textarea class="form-control" rows="6" placeholder="备注"  v-model="plan.memo"></textarea>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-default"  v-on:click="add_plan_for_room">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 优惠计划
                                        </button>
                                    </div>

                                    {{--额外人费用--}}
                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">额外人费用</h6>
                                        <label>成人额外费用</label><button v-on:click="add_extra_adult_for_room" type="button" class="btn btn-default btn-sm">+额外人费用 - 成人</button>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(adult,adultIndex) in room_info.extra_adult">
                                                <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%adultIndex+1%></td>
                                                <td width="280px">
                                                    <div class="tr_date">
                                                        <input class="form-control checkin" placeholder="开始日期" v-model="adult.date_from"/>
                                                        <input class="form-control checkout" placeholder="结束日期" v-model="adult.date_to"/>
                                                    </div>
                                                </td>
                                                <td width="100px"><input class="form-control" type="number" placeholder="价格"  v-model="adult.price" /></td>
                                                <td><button v-on:click="delete_extra_adult_for_room(adultIndex)" type="button" class="btn btn-sm btn-default" style="color: indianred">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <label>额外人儿童费用</label><button v-on:click="add_extra_children_for_room" type="button" class="btn btn-default btn-sm">+额外人费用 - 儿童</button>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(child,childIndex) in room_info.extra_children">
                                                <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%childIndex+1%></td>
                                                <td width="280px">
                                                    <div class="tr_date">
                                                        <input class="form-control checkin" placeholder="开始日期" v-model="child.date_from"/>
                                                        <input class="form-control checkout" placeholder="结束日期" v-model="child.date_to"/>
                                                    </div>
                                                </td>
                                                <td width="320px">
                                                    <button v-on:click="add_extra_children_age_for_room(childIndex)" type="button" class="btn btn-default btn-sm">+年龄</button>
                                                    <div v-for="(age,ageIndex) in child.ages">
                                                        <input class="form-control table_input_s" type="number" min="0" max="18" placeholder="年龄起" v-model="age.age_from"/>
                                                        <input class="form-control table_input_s" style="margin-left: 8px" type="number" min="0" max="18" placeholder="年龄止" v-model="age.age_to"/>
                                                        <input class="form-control table_input_s" style="margin-left: 8px" type="number" placeholder="价格"  v-model="age.price" />
                                                        <button v-on:click="delete_extra_children_age_for_room(childIndex,ageIndex)" type="button" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                        <div style="clear: both"></div>
                                                    </div>
                                                </td>
                                                <td><button v-on:click="delete_extra_children_for_room(childIndex)" type="button" class="btn btn-sm btn-default" style="color: indianred">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    {{--额外费用--}}
                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">额外费用</h6>
                                        <button type="button" v-on:click="add_plus_for_room" class="btn btn-default btn-sm">添加</button>
                                        <div v-for="(plus,plusIndex) in room_info.plus"
                                             style="border: 2px solid lightgrey;padding: 8px;position: relative">
                                            <button type="button" v-on:click="delete_plus_for_room(plusIndex)" class="btn btn-default btn-sm" style="position: absolute;top: 10px;right: 10px;color: indianred">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </button>
                                            <span style="position: absolute;top: 0;left: 0;padding: 1px 6px;background-color: grey;color: white;font-size: 8px"><%plusIndex+1%></span>
                                            <div style="height: 10px;width: 100%"></div>
                                            <div>
                                                <input class="form-control" placeholder="费用名字" style="float: left;width: 200px" v-model="plus.name" />
                                                <select class="form-control table_input" style="margin-left: 10px" v-model="plus.type">
                                                    <option value="0">强制</option>
                                                    <option value="1">可选</option>
                                                </select>
                                                <div style="clear: both"></div>
                                            </div>
                                            <div style="height: 10px;width: 100%"></div>
                                            <label>成人费用</label><button v-on:click="add_plus_adult_for_room(plusIndex)" type="button" class="btn btn-default btn-sm">+费用 - 成人</button>
                                            <table class="table">
                                                <tbody>
                                                <tr v-for="(adult,adultIndex) in plus.adult">
                                                    <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%adultIndex+1%></td>
                                                    <td width="280px">
                                                        <div class="tr_date">
                                                            <input class="form-control checkin" placeholder="开始日期" v-model="adult.date_from"/>
                                                            <input class="form-control checkout" placeholder="结束日期" v-model="adult.date_to"/>
                                                        </div>
                                                    </td>
                                                    <td width="100px"><input class="form-control" type="number" placeholder="价格"  v-model="adult.price" /></td>
                                                    <td><button v-on:click="delete_plus_adult_for_room(adultIndex,plusIndex)" type="button" class="btn btn-sm btn-default" style="color: indianred"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <label>儿童费用</label><button v-on:click="add_plus_children_for_room(plusIndex)" type="button" class="btn btn-default btn-sm">+费用 - 儿童</button>
                                            <table class="table">
                                                <tbody>
                                                <tr v-for="(child,childIndex) in plus.children">
                                                    <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%childIndex+1%></td>
                                                    <td width="280px">
                                                        <div class="tr_date">
                                                            <input class="form-control checkin" placeholder="开始日期" v-model="child.date_from"/>
                                                            <input class="form-control checkout" placeholder="结束日期" v-model="child.date_to"/>
                                                        </div>
                                                    </td>
                                                    <td width="310px">
                                                        <button  v-on:click="add_plus_children_age_for_room(plusIndex,childIndex)" type="button" class="btn btn-default btn-sm">+年龄</button>
                                                        <div v-for="(age,ageIndex) in child.ages">
                                                            <input class="form-control table_input_s" type="number" min="0" max="18" placeholder="年龄起" v-model="age.age_from"/>
                                                            <input class="form-control table_input_s" style="margin-left: 8px" type="number" min="0" max="18" placeholder="年龄止" v-model="age.age_to"/>
                                                            <input class="form-control table_input_s" style="margin-left: 8px"  type="number" placeholder="价格"  v-model="age.price" />
                                                            <button  v-on:click="delete_plus_children_age_for_room(plusIndex,childIndex,ageIndex)" type="button" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                            <div style="clear: both"></div>
                                                        </div>
                                                    </td>
                                                    <td><button v-on:click="delete_plus_children_for_room(childIndex,plusIndex)" type="button" class="btn btn-sm btn-default" style="color: indianred"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{--入住限制--}}
                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">入住限制</h6>
                                        <button type="button" v-on:click="add_limit_for_room" class="btn btn-default btn-sm">添加</button>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(limit,limitIndex) in room_info.limit">
                                                <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%limitIndex+1%></td>
                                                <td width="280px">
                                                    <div class="tr_date">
                                                        <input class="form-control checkin" placeholder="开始日期" v-model="limit.date_from"/>
                                                        <input class="form-control checkout" placeholder="结束日期" v-model="limit.date_to"/>
                                                    </div>
                                                </td>
                                                <td width="200px">
                                                    <input class="form-control table_input_s" type="number" min="0" max="18" placeholder="晚数起" v-model="limit.night_min"/>
                                                    <input class="form-control table_input_s" style="margin-left: 8px" type="number" min="0" max="18" placeholder="晚数止" v-model="limit.night_max"/>
                                                </td>
                                                <td><button v-on:click="delete_limit_for_room(limitIndex)" type="button" class="btn btn-sm btn-default" style="color: indianred">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    {{--复制区域--}}
                                    <div style="border: 1px solid lightblue;padding: 15px">
                                        <div>
                                            <h5><label>当前房型 : </label><%hotel.contracts[currentIndex].rooms[currentRoom].name%></h5>
                                            <div>
                                                <div style="float: left;width: 120px">
                                                    <div>
                                                        <label v-on:click="ui_select_all_item">要copy的项目</label>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="prices" v-model="checkItems"> 班期价格
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="plans" v-model="checkItems"> 优惠计划
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="extra" v-model="checkItems"> 额外人费用
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="plus" v-model="checkItems"> 强制费用
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="limit" v-model="checkItems"> 入住限制
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="include" v-model="checkItems"> 费用包含
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="cancellation" v-model="checkItems"> 退改规则
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label style="color:#3c3c3c">
                                                                <input type="checkbox" value="memo" v-model="checkItems"> 备注
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="float: left;width: 60%">
                                                    <label v-on:click="ui_select_all_room">目标房型</label>
                                                    <div v-for="(room,roomIndex) in hotel.contracts[currentIndex].rooms">
                                                        <label style="color:#3c3c3c" v-if="currentRoom != roomIndex">
                                                            <input type="checkbox" :value="room.room_id" v-model="checkRooms"> <%room.name%>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div style="clear: both"></div>
                                            </div>
                                            {{--<span>Checked Rooms:  <%checkRooms%></span>--}}
                                        </div>
                                        <button type="button" class="btn btn-default btn-sm" v-on:click="helper_copy_rooms">复制(除班期)到其他房型</button>
                                    </div>
                                </div>
                            </div>
                            {{--阅读模式--}}
                            <div v-if="viewMode == 1">
                                <div v-if="room_info" style="padding:0 15px">
                                    <div>
                                        <div style="font-size: 24px;font-weight: bolder;text-align: center"><%room_info.name%></div>
                                    </div>
                                    <div style="height: 15px"></div>
                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">基础班期</h6>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(price,priceIndex) in room_info.prices">
                                                <td width="30px" height="34px" style="padding: 15px;color: lightgrey"><%priceIndex+1%></td>
                                                <td width="280px">
                                                    <%price.date_from + "-" + price.date_to %>
                                                </td>
                                                <td width="100px"><%price.price + hotel.contracts[currentIndex].price_unit%></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="price_view">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td class="price_header" style="text-align: center;border: 1px solid lightgrey" v-on:click="month_minus"> < </td>
                                                    <td class="price_header"  style="text-align: center;border: 1px solid lightgrey" colspan="5"><%priceView.year + "." + priceView.month%></td>
                                                    <td class="price_header"  style="text-align: center;border: 1px solid lightgrey" v-on:click="month_add"> > </td>
                                                </tr>
                                                <tr>
                                                    <td class="price_cell_week">日</td>
                                                    <td class="price_cell_week">一</td>
                                                    <td class="price_cell_week">二</td>
                                                    <td class="price_cell_week">三</td>
                                                    <td class="price_cell_week">四</td>
                                                    <td class="price_cell_week">五</td>
                                                    <td class="price_cell_week">六</td>
                                                </tr>
                                                <tr v-for="i in priceView.rows">
                                                    <td v-for="j in 7" class="price_cell">
                                                        <div v-if="priceView.date_arr[(i-1)*7+(j-1)].value == 0">

                                                        </div>
                                                        <div v-else>
                                                            <div style="font-size: 18px;">
                                                                <%priceView.date_arr[(i-1)*7+(j-1)].value%>
                                                            </div>
                                                            <div v-if="priceView.date_arr[(i-1)*7+(j-1)].price > 0"
                                                                 class="price_color_base"
                                                                 :style="'color:'+priceView.date_arr[(i-1)*7+(j-1)].color">
                                                                ¥ <%priceView.date_arr[(i-1)*7+(j-1)].price%>
                                                            </div>
                                                            <div v-else style="color:grey;margin-top: -3px;font-size: 10px;">
                                                                -
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="height: 10px;width: 10px"></div>
                                        <table class="table">
                                            <tr>
                                                <td width="120px">
                                                    <label>费用包含</label>
                                                </td>
                                                <td><p style="font-size:12px;line-height: 24px" v-html="helper_nl2br(room_info.include)"></p></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>退改规则</label>
                                                </td>
                                                <td><p style="font-size:12px;line-height: 24px" v-html="helper_nl2br(room_info.cancellation)"></p></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>备注</label>
                                                </td>
                                                <td><p style="font-size:12px;line-height: 24px"v-html="helper_nl2br(room_info.memo)"></p></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">优惠计划</h6>
                                        <div v-for="(plan,planIndex) in room_info.plans" style="border: 2px solid lightgrey;padding: 8px;position: relative">
                                            <span style="position: absolute;top: 0;left: 0;padding: 1px 6px;background-color: grey;color: white;font-size: 8px"><%planIndex+1%></span>
                                            <table class="table">
                                                <tr>
                                                    <td colspan="2" style="text-align: center">
                                                        <%plan.name%>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100px">
                                                        <label>晚数要求</label>
                                                    </td>
                                                    <td>
                                                        <%plan.night_min%><span style="padding: 0 12px;color: grey">~</span><%plan.night_max%>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>有效日期</label>
                                                    </td>
                                                    <td>
                                                        <%plan.date_from%><span style="padding: 0 12px;color: grey">~</span><%plan.date_to %>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>无效日期</label>
                                                    </td>
                                                    <td>
                                                        <div v-for="(date_no,date_no_index) in plan.date_no">
                                                            <div style="clear: both;height: 10px;width: 100%"></div>
                                                            <div class="tr_date" style="width: 260px;float: left">
                                                                <%date_no.date_from + "  -  " +date_no.date_to %>
                                                                <div style="clear: both"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>优惠设置</label>
                                                    </td>
                                                    <td>
                                                        <div v-if="plan.type == '住X付Y' ">
                                                            <span>住<%plan.obj.x%>晚,付<%plan.obj.y%>晚</span>
                                                        </div>
                                                        <div v-else-if="plan.type == '提起X天Z折扣' ">
                                                            <span>提前<%plan.obj.day%>天预订,<%plan.obj.z%>折</span>
                                                        </div>
                                                        <div v-else-if="plan.type == '日期T之前Z折扣' ">
                                                            <span><%plan.obj.date%>之前预订,<%plan.obj.z%>折</span>
                                                        </div>
                                                        <div v-else-if="plan.type == '住X延住K晚Z折扣' ">
                                                            <span>住<%plan.obj.x%>晚,延住<%plan.obj.y%>晚,<%plan.obj.z%>折</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>费用包含</label>
                                                    </td>
                                                    <td><p style="font-size:12px;line-height: 24px" v-html="helper_nl2br(plan.include)"></p></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>退改规则</label>
                                                    </td>
                                                    <td><p style="font-size:12px;line-height: 24px" v-html="helper_nl2br(plan.cancellation)"></p></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label>备注</label>
                                                    </td>
                                                    <td><p style="font-size:12px;line-height: 24px"v-html="helper_nl2br(plan.memo)"></p></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">额外人费用</h6>
                                        <label>成人额外费用</label>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(adult,adultIndex) in room_info.extra_adult">
                                                <td width="30px" style="color: lightgrey"><%adultIndex+1%></td>
                                                <td width="220px">
                                                    <%adult.date_from%><span style="padding: 0 12px;color: grey">~</span><%adult.date_to%>
                                                </td>
                                                <td style="color: indianred"><%adult.price%></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <label>额外人儿童费用</label>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(child,childIndex) in room_info.extra_children">
                                                <td width="30px"style="color: lightgrey"><%childIndex+1%></td>
                                                <td width="220px">
                                                    <%child.date_from%><span style="padding: 0 12px;color: grey">~</span><%child.date_to%>
                                                </td>
                                                <td>
                                                    <div v-for="age in child.ages">
                                                        <%age.age_from%>岁<span style="padding: 0 12px;color: grey">~</span><%age.age_to%>岁
                                                        <span style="color: indianred"><%age.price%></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">额外费用</h6>

                                        <div v-for="(plus,plusIndex) in room_info.plus" style="border: 2px solid lightgrey;padding: 8px;position: relative">
                                            <span style="position: absolute;top: 0;left: 0;padding: 1px 6px;background-color: grey;color: white;font-size: 8px"><%plusIndex+1%></span>
                                            <h4 style="text-align: center"><%plus.name%> <span style="font-size:12px;color:grey"><%plus.type == 0 ? '(强制)' : '(可选)'%></span></h4>
                                            <label>成人费用</label>
                                            <table class="table">
                                                <tbody>
                                                <tr v-for="(adult,adultIndex) in plus.adult">
                                                    <td width="30px" style="color: lightgrey"><%adultIndex+1%></td>
                                                    <td width="220px">
                                                        <%adult.date_from%><span style="padding: 0 12px;color: grey">~</span><%adult.date_to%>
                                                    </td>
                                                    <td style="color: indianred"><%adult.price%></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <label>儿童费用</label>
                                            <table class="table">
                                                <tbody>
                                                <tr v-for="(child,childIndex) in plus.children">
                                                    <td width="30px" style="color: lightgrey"><%childIndex+1%></td>
                                                    <td width="220px">
                                                        <%child.date_from%><span style="padding: 0 12px;color: grey">~</span><%child.date_to%>
                                                    </td>
                                                    <td>
                                                        <div v-for="age in child.ages">
                                                            <%age.age_from%>岁<span style="padding: 0 12px;color: grey">~</span><%age.age_to%>岁
                                                            <span style="color: indianred"><%age.price%></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="box">
                                        <h6 style="color: grey;font-weight: bolder">入住限制</h6>
                                        <table class="table">
                                            <tbody>
                                            <tr v-for="(limit,limitIndex) in room_info.limit">
                                                <td width="30px"  style="color: lightgrey"><%limitIndex+1%></td>
                                                <td width="220px">
                                                    <%limit.date_from%><span style="padding: 0 12px;color: grey">~</span><%limit.date_to%>
                                                </td>
                                                <td width="220px">
                                                    <%limit.night_min%>晚<span style="padding: 0 12px;color: grey">~</span><%limit.night_max%>晚
                                                </td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div style="clear: both"></div>
@endsection
@section('script')
<script src="{{asset('js/libs/vue.min.js')}}"></script>
<script src="{{asset('js/libs/vue-router.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.ajaxupload.js')}}"></script>
<script src="{{asset('js/libs/bootstrap-modal-popover.js')}}"></script>
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/bootstrap3-typeahead.min.js')}}"></script>
<script src="{{asset('js/libs/moment.min.js')}}"></script>
<script src="{{asset('js/libs/jquery.daterangepicker.min.js')}}"></script>
{{--<script src="{{asset('js/libs/jquery.daterangepicker.js')}}"></script>--}}
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
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $(document).ready(function(){
        $('#affix').affix({
            offset: {
                top: 70
            }
        })
    });
    var hash_back = "";

    window.addEventListener("beforeunload", function (e) {
        if(hash_back != hotelList.helper_json()){
            var confirmationMessage = '离开之前请确认保存,否则更改信息会丢失的!!!';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        }
    });
</script>

<script>
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
                rooms:[],
                price_unit:"¥",
                price_rate:1,
                contracts:[
                    {
                        name : "name",
                        rooms : [
                            {//房型 - 合同
                                room : "name",
                                room_id : "",
                                state : "none",
                                prices : [//班期
                                    {
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        price : 123
                                    }
                                ],
                                plans : [//优惠,价格计划,所以同一优惠不能分段设置
                                    {
                                        name:"住x付x-n",
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        date_no:[
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            },
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            }
                                        ],
                                        min : 1,
                                        max : 100,
                                        type : "x:x-n",
                                        x : 4,
                                        n : 3
                                    },
                                    {
                                        name:"提前N天折扣",
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        date_no:[
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            },
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            }
                                        ],
                                        min : 1,
                                        max : 100,
                                        type : "x:day-zx",
                                        z : 0.8,
                                        day : 180
                                    },
                                    {
                                        name:"X日之前折扣",
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        date_no:[
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            },
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            }
                                        ],
                                        min : 1,
                                        max : 100,
                                        type : "x:date-zx",
                                        z : 0.8,
                                        date : "2017-10-10"
                                    },
                                    {
                                        name:"住x延住折扣",
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        date_no:[
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            },
                                            {
                                                date_from : "2017-10-10",
                                                date_to : "2017-10-10",
                                            }
                                        ],
                                        min : 1,
                                        max : 100,
                                        type : "x:extended-z",
                                        z : 0.8,
                                        x : 4,
                                        extended : 2
                                    }
                                ],
                                extra_adult : [//额外人费用
                                    {
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        price : ""
                                    }
                                ],
                                extra_children : [
                                    {
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        ages : [
                                            {
                                                age_from : "",
                                                age_to : "",
                                                price : ""
                                            }
                                        ]

                                    }
                                ],
                                plus_adult : [//强制费用

                                ],
                                plus_children : [],
                                plus : [
                                    {
                                        name : "",
                                        type : 0,//0 force, 1 optional
                                        adult : [],
                                        children : []
                                    }
                                ],
                                limit : [//入住限制
                                    {
                                        date_from : "2017-10-10",
                                        date_to : "2017-10-10",
                                        min : 8,
                                        max : 12
                                    }
                                ],
                                include : "",
                                cancellation : "",
                                memo : ""
                            }
                        ]

                    }
                ]
            },
            currentIndex:-1,//for contract
            currentRoom:-1,
            checkRooms : [],
            checkItems : ['plans', 'extra', 'plus', 'limit', 'include', 'cancellation', 'memo'],
            loading:true,
            viewMode:0,
            rates : [
                {
                    code : "CNY",
                    des : "人民币"
                },
                {
                    code : "USD",
                    des : "美元"
                },
                {
                    code : "JPY",
                    des : "日元"
                },
                {
                    code : "ZAR",
                    des : "南非"
                },
                {
                    code : "EUR",
                    des : "欧元"
                },
                {
                    code : "AUD",
                    des : "澳大利亚元"
                },
                {
                    code : "CAD",
                    des : "加拿大元"
                },
                {
                    code : "DKK",
                    des : "丹麦克郎"
                },
                {
                    code : "GBP",
                    des : "英镑"
                },
                {
                    code : "HKD",
                    des : "港币"
                },
                {
                    code : "DKK",
                    des : "丹麦克郎"
                },
                {
                    code : "IDR",
                    des : "印尼卢比"
                },
                {
                    code : "KRW",
                    des : "韩国"
                },
                {
                    code : "MYR",
                    des : "马来西亚"
                },
                {
                    code : "NZD",
                    des : "新西兰"
                },
                {
                    code : "PHP",
                    des : "菲律宾比索"
                },
                {
                    code : "SEK",
                    des : "瑞典克郎"
                },
                {
                    code : "SGD",
                    des : "新加坡"
                },
                {
                    code : "THB",
                    des : "泰铢"
                },

            ],
            priceView : {
                year : 2017,
                month : 11,
                rows : 0,
                date_arr : [
                    {
                        row : 0,
                        col : 0,
                        price : -1,
                        value : -1
                    }
                ],
                config : [],
                config_unit : "",
                config_rate : 1
            },
            priceColor : {

            },
            help_dates : "",
            is_help_date : false
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
        mounted:function(){

        },
        methods:{
            month_minus : function(){
                if(this.priceView.month == 1){
                    this.priceView.month = 12;
                    this.priceView.year = this.priceView.year - 1;
                }
                else{
                    this.priceView.month = this.priceView.month - 1;
                }
                this.update_price_view();
            },
            month_add : function(){
                if(this.priceView.month == 12){
                    this.priceView.month = 1;
                    this.priceView.year = this.priceView.year + 1;
                }
                else{
                    this.priceView.month = this.priceView.month + 1;
                }
                this.update_price_view();
            },
            helper_random_color:function(){
                var letters = '456789ABCD';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 10)];
                }
                return color;
            },
            update_price_view : function(){
                var setting_arr = this.priceView.config;
                var current = moment({
                    year : this.priceView.year,
                    month : this.priceView.month-1,
                    day : 1
                });

                var monthStr = ""+this.priceView.month;
                if(this.priceView.month < 10){
                    monthStr = "0"+monthStr;
                }
                var yearStr = ""+ this.priceView.year;

                console.log(current);
                var weekOfFirst = current.day();//3 周三, 0 周日
                var daysInMonth = current.daysInMonth();
                var currentRow = 0;
                var arr = [];
                var last_color = 0;
                for(var i=0;i<weekOfFirst;i++){//填补前面
                    var item = {
                        row : 1,
                        col : i,
                        price : -1,
                        value : 0,
                        color : 0
                    }
                    arr.push(item)
                }

                for(var i=0;i<daysInMonth;i++){
                    if(weekOfFirst == 7){
                        weekOfFirst = 0;
                        currentRow = currentRow + 1;
                    }
                    var dayStr = ""+(i+1);
                    if(i+1 < 10){
                        dayStr = "0"+dayStr;
                    }
                    var currentStr = yearStr+"-"+monthStr+"-"+dayStr;
                    var found = false;
                    for(var j= 0,jLen = setting_arr.length;j<jLen;j++){
                        if(currentStr >= setting_arr[j].date_from && currentStr <= setting_arr[j].date_to){
                            var p = Math.ceil( setting_arr[j].price * this.priceView.config_rate );
                            console.log(p);
                            var str_p = p.toString();
                            if(this.priceColor.hasOwnProperty(str_p)){

                            }
                            else{
                                this.priceColor[str_p] = this.helper_random_color();
                            }

                            var item = {
                                row : currentRow,
                                col : weekOfFirst,
                                price : p,
                                value : i+1,
                                color : this.priceColor[str_p]
                            }
                            arr.push(item);
                            found = true;
                            break;
                        }
                    }
                    if(!found){
                        var item = {
                            row : currentRow,
                            col : weekOfFirst,
                            price : -1,
                            value : i+1,
                            color : 0
                        }
                        arr.push(item);
                    }
                    weekOfFirst = weekOfFirst + 1;

                }
                var c = arr.length;
                var l = c % 7;
                var left = 7 - l;

                for(var i=0;i<left;i++){//填补前面
                    var item = {
                        row : currentRow,
                        col : weekOfFirst,
                        price : -1,
                        value : 0,
                        color : 0
                    }
                    weekOfFirst = weekOfFirst + 1;
                    arr.push(item)
                }
                console.log(arr.length)

                this.priceView.rows = currentRow+1;
                this.priceView.date_arr = arr;
                console.log(this.priceView.date_arr);
            },
            init_price_view : function(setting_arr,unit,rate){
                console.log("init_price_view");
                console.log(setting_arr);
                this.priceView.config = setting_arr;
                this.priceView.config_unit = unit;
                this.priceView.config_rate = rate;

                var max = "2016-10-10";
                var min = "2116-10-10";
                for(var i= 0,len = setting_arr.length;i<len;i++){
                    if(setting_arr[i].date_from < min){
                        min = setting_arr[i].date_from;
                    }
                    if(setting_arr[i].date_to > max){
                        max = setting_arr[i].date_to;
                    }
                }

                var year = parseInt(min.substr(0,4));
                var month = parseInt(min.substr(5,2));

                console.log(year);
                console.log(month);

                this.priceView.year = year;
                this.priceView.month = month;

                this.update_price_view();
            },
            mode: function(){
                if(this.viewMode == 0){
                    this.viewMode = 1;
                }
                else{
                    this.viewMode = 0;
                }
            },
            save:function(){
                console.log("save");
                var paras = JSON.parse(JSON.stringify(this.$data));
                console.log(paras.hotel);

                const self = this;
                axios.post('api/update/contract/',paras.hotel)
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                toastr["success"](response.data.msg)
                                hash_back = self.helper_json();
                            }
                            else{
                                toastr["error"](response.data.msg)
                            }
                        })
                        .catch(function(error){
                           console.log(error);
                        });
            },
            get_data : function(_id){
                const self = this;
                console.log("created");
                axios.post('api/hotel/'+_id,{
                        })
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.ok == 0){
                                var obj = response.data.obj;
                                if(obj.hasOwnProperty("contracts")){

                                }
                                else{
                                    obj.contracts = []
                                }

                                self.hotel = obj;

                                for(var j= 0,jLen = self.hotel.contracts.length;j<jLen;j++){
                                    var rooms = self.hotel.contracts[j].rooms;
                                    console.log("contract rooms");
                                    console.log(rooms);
                                    //update room state
                                    for(var k= 0,kLen = rooms.length;k<kLen;k++){
                                        var found_room = self.has_room(rooms[k].room_id);

                                        if( found_room != null){
                                            //has room, update room info
                                            self.hotel.contracts[j].rooms[k].name_en = found_room.name_en;
                                            self.hotel.contracts[j].rooms[k].name = found_room.name;
                                        }
                                        else{
                                            //deleted room
                                            rooms[k].state = "deleted";
                                        }
                                    }
                                    var new_rooms = self.append_new_rooms(self.hotel.rooms,rooms);
                                    if(new_rooms){
                                        console.log("add new room to contracts")
                                        Array.prototype.push.apply(self.hotel.contracts[j].rooms,new_rooms);
                                    }
                                }

                                self.loading = false;
                                hash_back = self.helper_json();
                            }
                            else{
                                toastr["error"](response.data.msg)
                            }

                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            has_room : function(room_id){
                if(this.hotel.rooms) {
                    for(var i= 0,len = this.hotel.rooms.length;i<len;i++){
                        if(this.hotel.rooms[i].id == room_id){
                            return this.hotel.rooms[i];
                        }
                    }
                }
                return null;
            },
            append_new_rooms : function(room_hotel,room_contract){
                console.log("append_new_rooms");
                var res = [];
                for(var i= 0,len = room_hotel.length;i<len;i++){
                    var name = room_hotel[i].name;
                    var name_en = room_hotel[i].name_en;
                    var room_id = room_hotel[i].id;
                    var found = false;
                    for(var j=0,jLen = room_contract.length;j<jLen;j++){
                        if(room_contract[j].name == name){
                            found = true;
                            break;
                        }
                    }
                    if(!found){
                        var empty_room = {
                            name : name,
                            name_en : name_en,
                            room_id : room_id,
                            state : "none",//ok,deleted,none
                            prices : [],//班期
                            plans : [],//优惠计划
                            extra_adult : [],//额外人-成人
                            extra_children : [],//额外人-儿童
                            plus_adult : [],//强制-成人
                            plus_children : [],//强制-儿童
                            plus : [],
                            limit : [],//入住限制
                            include : "",//费用包含
                            cancellation : "",//取消政策
                            memo : ""//备注

                        }
                        res.push(empty_room);
                    }
                }
                console.log(res);
                return res;
            },
            add_contract : function(){
                if(!this.hotel.contracts){
                    this.hotel.contracts = []
                }
                var s = this.hotel.contracts.length;
                var name = "合同"+s;
                var rooms = this.hotel.rooms;
                var contract_room = [];
                for(var i= 0,len = rooms.length;i<len;i++){
                    var empty_room = {
                        name : rooms[i].name,
                        name_en : rooms[i].name_en,
                        room_id : rooms[i].id,
                        price_unit:"¥",
                        price_rate:1,
                        state : "none",//ok,deleted,none
                        prices : [//班期
                        ],
                        plans : [//优惠
                        ],
                        plus : [//额外费用
                        ],
                        limit : [//入住限制
                        ]
                    }
                    contract_room.push(empty_room);
                }
                var empty_contract = {
                    name : name,
                    rooms : contract_room
                }
                this.hotel.contracts.splice(s, 0, empty_contract);
                this.currentIndex = s;
                console.log("add contract");
                console.log(this.hotel.contracts);
            },
            pre_price : function(prices, index){
              if(index > 0){
                  return prices[index-1].date_to;
              }
              return "";
            },
            arr_min : function(prices){
                var min = "2222-22-22";
                for(var i= 0,len = prices.length;i<len;i++){
                    if(prices[i].date_from < min){
                        min = prices[i].date_from;
                    }
                }
                this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].min = min;
                return min;
            },
            arr_max : function(prices){
                var max = "1111-11-11";
                for(var i= 0,len = prices.length;i<len;i++){
                    if(prices[i].date_to > max){
                        max = prices[i].date_to;
                    }
                }
                this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].max = max;
                return max;
            },
            add_price_for_room : function(){
                console.log("add_price_for_room")
                var price = {
                    date_from : "",
                    date_to : "",
                    price : ""
                }
                this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].prices.push(price);
                console.log(this.hotel.contracts);
                this.$forceUpdate();
            },
            delete_price_for_room : function(index){
                var price = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].prices[index];
                if(price.date_from || price.date_to || price.price > 0){
                    if(confirm("确定要删除价格 "+(index+1)+"?")){
                        this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].prices.splice(index,1);
                        this.$forceUpdate();
                    }
                }
                else{
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].prices.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_plan_for_room : function(){
                console.log("add_plan_for_room")
                var plan = {
                    name : "",
                    date_from : "",
                    date_to : "",
                    dates_not : [],
                    night_min : "",
                    night_max : "",
                    type : "住X付Y",
                    include : this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].include,
                    cancellation : this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].cancellation,
                    memo : this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].memo,
                    obj : {
                        x : "",
                        y : "",
                        day : "",
                        date : "",
                        z : ""
                    }
                };
                this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plans.push(plan);
                console.log(this.hotel.contracts);
                this.$forceUpdate();
            },
            delete_plan_for_room : function(index){
                var plan = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plans[index];
                if(confirm("确定要删除优惠计划 "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plans.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_date_no_for_plan : function(index){
                console.log("add_date_no_for_plan")
                var empty = {date_from : "",
                    date_to : ""};
                var plan = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plans[index];
                if(!plan.hasOwnProperty("date_no")){
                    plan.date_no = [];
                }
                plan.date_no.push(empty);
                this.$forceUpdate();
            },
            delete_date_no_for_plan : function(planIndex,index){
                if(confirm("确定要删除无效日期  "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plans[planIndex].date_no.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_extra_adult_for_room : function(){
                var empty = {
                    date_from : "",
                    date_to : "",
                    price : ""
                };
                var room = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom];
                if(!room.hasOwnProperty("extra_adult")){
                    room.extra_adult = [];
                }
                room.extra_adult.push(empty);
                this.$forceUpdate();
            },
            delete_extra_adult_for_room : function(index){
                if(confirm("确定要删除额外人-成人费用  "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].extra_adult.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_extra_children_for_room : function(){
                var empty = {
                    date_from : "",
                    date_to : "",
                    ages : [{
                        age_from : "",
                        age_to : "",
                        price : ""
                    }]

                };
                var room = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom];
                if(!room.hasOwnProperty("extra_children")){
                    room.extra_children = [];
                }
                room.extra_children.push(empty);
                this.$forceUpdate();
            },
            add_extra_children_age_for_room : function(index){
                var empty = {
                    age_from : "",
                    age_to : "",
                    price : ""
                };
                var children = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].extra_children;
                children[index].ages.push(empty);
                this.$forceUpdate();
            },
            delete_extra_children_for_room : function(index){
                if(confirm("确定要删除额外人-儿童费用  "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].extra_children.splice(index,1);
                    this.$forceUpdate();
                }
            },
            delete_extra_children_age_for_room : function(childIndex,index){
                if(confirm("确定要删除额外人-儿童年龄  "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].extra_children[childIndex].ages.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_plus_for_room : function(){
                var empty = {
                    name : "",
                    type : 0,
                    adult : [],
                    children : []
                }
                var room = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom];
                if(!room.hasOwnProperty("plus")){
                    room.plus = [];
                }
                room.plus.push(empty);
                this.$forceUpdate();
            },
            delete_plus_for_room : function(index){
                if(confirm("确定要删除额外费用  "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_plus_adult_for_room : function(index){
                var empty = {
                    date_from : "",
                    date_to : "",
                    price : ""
                };
                var plus = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus[index];
                if(!plus.hasOwnProperty("adult")){
                    plus.adult = [];
                }
                plus.adult.push(empty);
                this.$forceUpdate();
            },
            delete_plus_adult_for_room : function(index,pIndex){
                if(confirm("确定要删除额外费用-成人费用  "+(pIndex+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus[pIndex].adult.splice(index,1);
                    this.$forceUpdate();
                }
            },
            add_plus_children_for_room : function(index){
                var empty = {
                    date_from : "",
                    date_to : "",
                    ages : [{
                        age_from : "",
                        age_to : "",
                        price : ""
                    }]
                };
                var plus = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus[index];
                if(!plus.hasOwnProperty("children")){
                    plus.children = [];
                }
                plus.children.push(empty);
                this.$forceUpdate();
            },
            add_plus_children_age_for_room : function(index,childIndex){
                var empty = {
                    age_from : "",
                    age_to : "",
                    price : ""
                };
                var children = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus[index].children;

                children[childIndex].ages.push(empty);
                this.$forceUpdate();
            },
            delete_plus_children_for_room : function(index,pIndex){
                if(confirm("确定要删除额外-儿童费用  "+(pIndex+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus[pIndex].children.splice(index,1);
                    this.$forceUpdate();
                }
            },
            delete_plus_children_age_for_room : function(index,childIndex,pIndex){
                if(confirm("确定要删除额外-儿童年龄  "+(pIndex+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].plus[index].children[childIndex].ages.splice(pIndex,1);
                    this.$forceUpdate();
                }
            },
            add_limit_for_room : function(){
                var empty = {
                    date_from : "",
                    date_to : "",
                    night_min : "",
                    night_max : "",
                };
                var room = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom];
                if(!room.hasOwnProperty("limit")){
                    room.limit = [];
                }
                room.limit.push(empty);
                this.$forceUpdate();
            },
            delete_limit_for_room : function(index){
                if(confirm("确定要删除入住限制  "+(index+1)+"?")){
                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].limit.splice(index,1);
                    this.$forceUpdate();
                }
            },
            hack_copy : function(){
                for(var i= 0,iLen = this.hotel.contracts.length;i<iLen;i++){
                    for(var j= 0,jLen = this.hotel.contracts[i].rooms.length;j<jLen;j++){
                        var include = this.hotel.contracts[i].rooms[j].include;
                        var cancellation = this.hotel.contracts[i].rooms[j].cancellation;
                        var memo = this.hotel.contracts[i].rooms[j].memo;
                        for(var k= 0,kLen = this.hotel.contracts[i].rooms[j].plans.length;k<kLen;k++){
                            this.hotel.contracts[i].rooms[j].plans[k].include = include;
                            this.hotel.contracts[i].rooms[j].plans[k].cancellation = cancellation;
                            this.hotel.contracts[i].rooms[j].plans[k].memo = memo;
                        }
                    }
                }
            },
            helper_has_copy : function(item){
                if(this.checkItems){
                    for(var i= 0,len = this.checkItems.length;i<len;i++){
                        if(this.checkItems[i] == item){
                            console.log("found "+item);
                            return true;
                        }
                    }
                }
                return false;
            },
            helper_copy_rooms : function(){
                console.log("helper copy rooms");
                if(this.checkRooms.length == 0 || this.checkItems.length == 0){
                    toastr["error"]("选择为空啊")
                    return;
                }
//                console.log(this.checkRooms);
                var room = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom];
                var prices = room.prices;
                var limit = room.limit;
                var plans = room.plans;
                var extra_adult = room.extra_adult;
                var plus_adult = room.plus_adult;
                var plus = room.plus;
                var extra_children = room.extra_children;
                var plus_children = room.plus_children;
                var include = room.include;
                var cancellation = room.cancellation;
                var memo = room.memo;
                //skip prices
                var cnt = 0;
                for(var i= 0,len = this.checkRooms.length;i<len;i++){
                    for(var j= 0,jLen = this.hotel.contracts[this.currentIndex].rooms.length;j<jLen;j++){
                        if(this.hotel.contracts[this.currentIndex].rooms[j].room_id == this.checkRooms[i]){
                            console.log("found one room "+this.checkRooms[i]);
                            console.log("copy room setting");
                            cnt = cnt + 1;
                            if(this.helper_has_copy("prices")) {
                                this.hotel.contracts[this.currentIndex].rooms[j].prices = clone(prices);
                                for(var k= 0,kLen = this.hotel.contracts[this.currentIndex].rooms[j].prices.length;k < kLen;k++){
                                    this.hotel.contracts[this.currentIndex].rooms[j].prices[k].price = "";
                                }
                            }
                            if(this.helper_has_copy("plans"))
                                this.hotel.contracts[this.currentIndex].rooms[j].plans = clone(plans);
                            if(this.helper_has_copy("limit"))
                                this.hotel.contracts[this.currentIndex].rooms[j].limit = clone(limit);
                            if(this.helper_has_copy("extra")){
                                this.hotel.contracts[this.currentIndex].rooms[j].extra_adult = clone(extra_adult);
                                this.hotel.contracts[this.currentIndex].rooms[j].extra_children = clone(extra_children);
                            }
                            if(this.helper_has_copy("plus")){
                                this.hotel.contracts[this.currentIndex].rooms[j].plus_adult = clone(plus_adult);
                                this.hotel.contracts[this.currentIndex].rooms[j].plus_children = clone(plus_children);
                                this.hotel.contracts[this.currentIndex].rooms[j].plus = clone(plus);
                            }
                            if(this.helper_has_copy("include"))
                                this.hotel.contracts[this.currentIndex].rooms[j].include = clone(include);
                            if(this.helper_has_copy("cancellation"))
                                this.hotel.contracts[this.currentIndex].rooms[j].cancellation = clone(cancellation);
                            if(this.helper_has_copy("memo"))
                                this.hotel.contracts[this.currentIndex].rooms[j].memo = clone(memo);
                            break;
                        }
                    }
                }

                toastr["success"]("复制完成 "+cnt+"个房型")
            },

            plugin_datepicker : function(){
//                console.log("init datepciker")
                var className = ".tr_date";
                const self = this;
                $(className).each(function(index, element) {
                    var tid = "datepicker"+index;
                    var id = $(element).attr("id");
                    if(id == null || id == "") {//avoid initialize twice
                        $(element)
                                .dateRangePicker(
                                        {
                                            separator: ' to ',
                                            autoClose: true,
                                            getValue: function () {
                                                if ($(this).find(".checkin").val() && $(this).find(".checkout").val())
                                                    return $(this).find(".checkin").val() + ' to ' + $(this).find(".checkout").val();
                                                else
                                                    return '';
                                            },
                                            setValue: function (s, s1, s2) {
                                                var ele_in = $(this).find(".checkin");
                                                ele_in.val(s1)
                                                var ele_out = $(this).find(".checkout");
                                                ele_out.val(s2);
                                                self.dom_event(ele_in[0]);
                                                self.dom_event(ele_out[0]);

                                            },
                                            defaultTime:$(this).find(".checkin").attr("data-default-time"),
                                            stickyMonths: true,
                                            language: "cn",
                                            format: "YYYY-MM-DD"
                                        }
                                );
                    }
                    $(element).attr("id",tid)
                });
            },
            ui_select_contract : function(index){
                this.currentIndex = index;
            },
            ui_select_room : function(index){
                this.currentRoom = index;
                this.ui_clear_copy_check();

                var setting = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].prices
                this.init_price_view(setting,
                        this.hotel.contracts[this.currentIndex].price_unit,
                        this.hotel.contracts[this.currentIndex].price_rate
                );
            },
            ui_clear_copy_check : function(){
                this.checkRooms = [];
            },
            ui_select_all_item : function(){
                if(this.checkItems.length > 0){
                    this.checkItems = [];
                }
                else{
                    this.checkItems = ['prices', 'plans', 'extra', 'plus', 'limit', 'include', 'cancellation', 'memo'];
                }
            },
            ui_select_all_room : function(){
                if(this.checkRooms.length > 0){
                    this.checkRooms = [];
                }
                else{
                    for(var i= 0,len = this.hotel.contracts[this.currentIndex].rooms.length;i<len;i++){
                        if(i != this.currentRoom){
                            this.checkRooms.push(this.hotel.contracts[this.currentIndex].rooms[i].room_id);
                        }
                    }
                }
            },
            dom_event : function(dom_ele){
                var e = document.createEvent('HTMLEvents');
                e.initEvent('input', true, true);
                dom_ele.dispatchEvent(e);
            },
            select_changed: function(e){
                this.$forceUpdate();
            },
            select_rate_changed : function(){
                var code = this.hotel.contracts[this.currentIndex].price_unit;
                if(code == "CNY"){
                    this.hotel.contracts[this.currentIndex].price_rate = 1;
                }
                else{
                    const self = this;
                    axios.get('/api/parse/get_rate?code='+code)
                            .then(function(response){
                                console.log(response.data);
                                if(response.data.rates.CNY){
                                    self.hotel.contracts[self.currentIndex].price_rate = response.data.rates.CNY;
                                }

                            })
                            .catch(function(error){
                                console.log(error);
                            });
                }
                console.log("select_rate_changed");
                console.log(code);
            },
            help_sort_by_date : function(arr){
                function compare(a, b) {
                    if (a.date_from > b.date_from)
                        return -1;
                    if (a.date_from < b.date_from)
                        return 1;
                    return 0;
                }
                return arr.sort(compare);
            },
            helper_nl2br : function  (str, is_xhtml) {
                if(!str) return "";
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            },
            helper_json :function(){
                return JSON.stringify(this.$data.hotel);
            },
            show_help_dates : function(){
                this.is_help_date  = !this.is_help_date;
            },
            reset_date : function(text){
                console.log(text);
                var arr = text.split(/\r?\n/);
                var res = [];
                if(arr){
                    for(var i= 0, iLen = arr.length;i<iLen;i++){
                        var a = arr[i].trim().split(" ")
                        if(a && a.length == 3){
                            var price = {
                                date_from : a[0],
                                date_to : a[1],
                                price : a[2]
                            }
                            res.push(price);
                        }
                    }

                    this.hotel.contracts[this.currentIndex].rooms[this.currentRoom].prices = res;
                    this.$forceUpdate();
                }
            }
        },
        computed : {
            room_info : function(){
                if(this.currentRoom >= 0){
                    var room = this.hotel.contracts[this.currentIndex].rooms[this.currentRoom];
                    console.log("room_info")
                    return room;
                }
                return null;
            }
        },
        updated: function(){
            console.log("updated")
            this.plugin_datepicker();

        }
    })

    function clone(obj) {
        var copy;

        // Handle the 3 simple types, and null or undefined
        if (null == obj || "object" != typeof obj) return obj;

        // Handle Date
        if (obj instanceof Date) {
            copy = new Date();
            copy.setTime(obj.getTime());
            return copy;
        }

        // Handle Array
        if (obj instanceof Array) {
            copy = [];
            for (var i = 0, len = obj.length; i < len; i++) {
                copy[i] = clone(obj[i]);
            }
            return copy;
        }

        // Handle Object
        if (obj instanceof Object) {
            copy = {};
            for (var attr in obj) {
                if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
            }
            return copy;
        }

        throw new Error("Unable to copy obj! Its type isn't supported.");
    }

</script>
@endsection