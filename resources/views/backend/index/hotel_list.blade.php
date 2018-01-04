@extends("backend.layout.base_main")
@section('style')
    <link rel="stylesheet" href="{{asset('css/libs/float-label.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/toastr.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/libs/jquery-confirm.min.css')}}"/>
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
        .tr_not_online{
            /*background-color: whitesmoke;*/
        }
    </style>
@endsection
@section('content')
<div style="width: 100%;clear: both">
    <div id="hotel_list">
        <div v-cloak>
            <form id="search_form" class="form">
                <div style="padding: 15px;background-color: lightgrey;width: 440px;">
                    <input name="keyword" class="form-control" style="float: left;width: 300px;" placeholder="目的地 / 酒店名字" >
                    <button v-on:click="search_hotel" type="button" style="float: left;width: 80px;height: 34px;margin-left: 20px" class="btn btn-default btn-sm">搜索</button>
                    <div style="clear: both"></div>
                </div>
            </form>
            <div style="height: 30px;width: 1px"></div>
            <div v-if="loading">
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
            <div v-else class="box">
                <div style="float: right;padding: 6px">
                    {{--<button type="button" class="btn btn-default btn-sm" v-on:click="get_data">reset</button>--}}
                    <a class="btn btn-default btn-sm" href="/create_hotel"
                       style="margin-left: 10px;color:indianred;font-weight: bolder">创建一个酒店</a>
                </div>
                <div style="float: left;padding: 6px;font-size: 10px">
                    找到 <span style="font-weight: bolder;color: #00a65a;font-size: 14px"><% hotels.total %></span> 酒店
                </div>
                <table class="table">
                    <thead>
                    <th width="60px">#</th>
                    <th width="60px">状态</th>
                    <th width="250px">酒店名称</th>
                    <th width="200px">标签</th>
                    <th width="200px">城市</th>
                    <th width="100px">Zer</th>
                    <th>操作</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <tr v-for="(hotel,index) in hotels.data">
                        <td>
                            <% hotels.from + index %>
                        </td>
                        <td v-if="hotel.status == 1">
                            <span style="font-size: 10px;background-color: lightgreen;padding: 3px 6px">已上线</span>
                        </td>
                        <td v-else>
                            <span style="font-size: 10px;background-color: whitesmoke;padding: 3px 6px">未上线</span>
                        </td>
                        <td class="hotel_name">
                            <% hotel.name %> <br/>
                            <span style="color: grey;font-size: 11px"><% hotel.name_en %></span>
                        </td>
                        <td class="hotel_name">
                            <% hotel.brand ? hotel.brand+" " : ""+hotel.tag %>
                        </td>
                        <td class="hotel_name">
                            <% parse_location(hotel.location) %>
                        </td>
                        <td class="hotel_name">
                            <span><% hotel.author?hotel.author:"Zer" %></span><br/>
                            <span style="color: grey;font-size: 12px"><%hotel.last_editor?hotel.last_editor:"Zer" %></span>
                        </td>
                        <td>
                            <a :href="'/edit_hotel?id='+hotel._id" target="_blank" class="btn btn-default btn-sm">编辑</a>
                            <a :href="'/plan?id='+hotel._id" target="_blank" class="btn btn-default btn-sm">合同</a>
                            <a :href="'/hotel/detail/'+hotel._id" target="_blank" class="btn btn-default btn-sm">预览</a>
                            <button v-on:click="online(hotel._id)" class="btn btn-default btn-sm" style="margin-left: 15px;">
                                <%hotel.status == 1 ? "下线" : "上线"%>
                            </button>
                            <button v-on:click="confirm_delete(hotel._id,hotel.name)" class="btn btn-default btn-sm" style="margin-left: 15px;color: palevioletred">删除</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div style="float: right;padding: 15px">
                    <button :disabled="hotels.prev_page_url == null" type="button" class="btn btn-default btn-sm" v-on:click="next_page(hotels.prev_page_url)">prev</button>
                    <span style="font-size: 12px;"><% hotels.current_page + " / " + hotels.last_page %></span>
                    <button :disabled="hotels.next_page_url == null" type="button" class="btn btn-default btn-sm" v-on:click="next_page(hotels.next_page_url)">next</button>
                </div>
                <div style="clear: both"></div>
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
<script src="{{asset('js/libs/toastr.min.js')}}"></script>
<script src="{{asset('js/libs/jquery-confirm.min.js')}}"></script>
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
            hotels:{
                data : [],
                current_page : 1,
                total : 1,
                prev_page_url : null,
                next_page_url : null
            },
            loading:true
        },
        created:function () {
            this.get_data();
        },
        methods:{
            get_data : function(){

                const self = this;
                self.loading = true;
                console.log("created");
                var url = '/api/hotels/';
                var page = this.$route.query.page;
                if(page){
                    url = url + "?page="+page;
                }
                axios.post(url,{
                            k1: "v1"
                        })
                        .then(function(response){
                            console.log(response.data);
                            self.hotels = response.data.obj;
                            self.loading = false;
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            next_page : function(url){
                const self = this;
                console.log("created");
                axios.post(url,{
                        })
                        .then(function(response){
                            console.log(response.data);
                            self.hotels = response.data.obj;
                            self.loading = false;
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            edit_hotel : function(hotel_id){
                console.log("edit hotel");
                console.log(hotel_id);
            },
            search_hotel : function(keyword){
                var input = $("#search_form").find("input[name='keyword']");
                if(!input.val()){
                    input.focus();
                    return;
                }
                var p = $("#search_form").serialize();
                const  self = this;
                self.loading = true;
                axios.post('/api/search/hotel/',p)
                        .then(function(response){
                            console.log(response.data);
                            self.hotels = response.data.obj;
                            self.loading = false;

                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            online : function(hotel_id){
                const self = this;
                axios.post('/api/online/hotel',{
                            hotel_id: hotel_id
                        })
                        .then(function(response){
                            console.log("delete_hotel");
                            console.log(hotel_id);
                            console.log(response.data);
                            if(response.data.ok == 0){
                                toastr["success"](response.data.msg);
                                for(var i= 0,len = self.hotels.data.length;i<len;i++){
                                    if(self.hotels.data[i]._id == hotel_id){
                                        self.hotels.data[i].status = response.data.obj.status;
                                        break;
                                    }
                                }
                            }
                            else{
                                toastr["error"](response.data.msg);
                            }
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            confirm_delete : function(hotel_id, name){
                const self = this;
                $.confirm({
                    title: '删除酒店',
                    content: "确定删除酒店 "+name,
                    buttons: {
                        confirm:{
                            text : "确定",
                            action : function(){
                                self.delete_hotel(hotel_id, name);
                            },
                        },
                        cancel: {
                            text : "不删了",
                        }
                    }});

            },
            delete_hotel : function(hotel_id,name){
                const self = this;
                axios.post('/api/delete/hotel',{
                            hotel_id: hotel_id
                        })
                        .then(function(response){
                            console.log("delete_hotel");
                            console.log(hotel_id);
                            console.log(response.data);
                            if(response.data.ok == 0){
                                toastr["success"](response.data.msg);
                                for(var i= 0,len = self.hotels.data.length;i<len;i++){
                                    if(self.hotels.data[i]._id == hotel_id){
                                        self.hotels.data.splice(i,1);
                                        break;
                                    }
                                }
                            }
                            else{
                                toastr["error"](response.data.msg);
                            }
                        })
                        .catch(function(error){
                            console.log(error);
                        });
            },
            parse_location : function(location){
//                console.log("parse_location");
//                console.log(location);
                if(location){
                    return location.country+ " " +location.city;
                }
                return "NO";
            }
        }
    })
</script>
@endsection