<?php

namespace App\Http\Controllers\Backend;

use App\Models\Hotel;
use App\Models\Master;
use App\Models\ZEvent;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth as BAuth;
use App\Models\Order;

class IndexController extends Controller
{
    //http://laravelacademy.org/post/3502.html
    const AK = "My5kLLe5AW7Tm4RaNivwbjT3jERbWDEKsJLuGn0s";
    const SK = "odKn-63W8vWvfnNrhqLAyVZLyMwF8kQg2nIh4gUx";
    const Q_DOMAIN = "http://oytstg973.bkt.clouddn.com";


    /**
     * json-rpc
     * request {
     *  jsonrpc : "2.0"
     *  method : "get_hotel",
     *  params : {},
     *  id : "request_id"
     * }
     * batch requests  [{request}, {request}]
     *
     * response {
     *  jsonrpc : "2.0"
     *  id : "request_id",
     *  result : {},
     *  error : {} //result or error
     * }
     *
     * Notification : without id
     *
     ******************************************************
     * Format : thrift(facebook), protobuf(google), or ?
     * Protocol : tcp or http
     */


    /**
     * LOGIN - LOGOUT
     */
    public function login(Request $request){
        $email = $request->input("email");
        $password = $request->input("password");
        ZEvent::log(self::getCurrentMaster(), "cmd",__METHOD__, [$email,$password]);
        if(BAuth::attempt(['email'=>$email,'password'=>$password],true)){
            return redirect()->intended('/zashboard/hotels');
        }
        else{
            echo "Failed Login ";
//            return Redirect::to('/zhotel/ss/login');
        }
    }


    public function logout(Request $request){
        ZEvent::log(self::getCurrentMaster(), "cmd",__METHOD__, "");
        BAuth::logout();
        return response()->json(['ok'=>0,'msg'=>'ok']);
    }
    public function register(Request $request){
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        ZEvent::log(self::getCurrentMaster(), "cmd",__METHOD__, [$name,$email,$password]);
        $dup = Master::where("email",$email)->get();
        if(empty($dup)){
            echo "<h1 style='text-align: center;margin-top: 100px'>User Already Exits</h1>";
        }
        else{
            /**
             * Role
             *
             */
            $master = new Master();
            $master->name = $name;
            $master->email = $email;
            $master->password = bcrypt($password);
            $master->save();
            return Redirect::to('/zhotel/ss/login');
        }

    }

    /**
     * HOTEL OPs
     */
    public function createHotel(Request $request){
        $json = $request->json()->all();
        ZEvent::log(self::getCurrentMaster(), "cmd",__METHOD__, $json);
        $hotel = new Hotel();
        $hotel->name = $json["name"];
        $hotel->status = 0;//default is not online
        $hotel->name_en = $json["name_en"];
        $hotel->tag = $json["tag"];
        if(isset($json["brand"])){
            $hotel->brand = $json["brand"];
        }
        else{
            $hotel->brand = "";
        }
        $hotel->description = $json["description"];
        $hotel->location = $json["location"];
        $hotel->zy = $json["zy"];
        $hotel->detail = $json["detail"];
        $hotel->facilities = $json["facilities"];
        $hotel->honor = $json["honor"];
        $hotel->honor_img = $json["honor_img"];
        $hotel->honor_word = $json["honor_word"];
        $hotel->policy = $json["policy"];
        $hotel->rooms = $json["rooms"];
        $hotel->images = $json["images"];
        $hotel->author = self::getCurrentMaster();
        $hotel->last_editor = self::getCurrentMaster();
        $hotel->save();
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"创建成功",
                "obj"=>$hotel
            ]
        );
    }

    //hotel list should projection first
    public function getHotelList(Request $request){
        $projections = ['_id', 'status', 'name', 'name_en', 'tag', 'brand', 'author', 'last_editor', 'location', 'memo'];
        $res = Hotel::paginate(20, $projections);

        $last_url = $res->url(1);
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, "");
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res,
                "url_format"=>$last_url,
            ]
        );
    }
    public function searchHotel(Request $request){
        $keyword = $request->input("keyword");
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, $keyword);
        $projections = ['_id', 'status', 'name', 'name_en', 'tag', 'brand', 'author', 'last_editor', 'location', 'memo'];
        $res = Hotel::where("name",'like', '%'.$keyword."%")
                    ->orWhere("name_en",'like', '%'.$keyword."%")
                    ->orWhere("location.country",'like', '%'.$keyword."%")
                    ->orWhere("location.city",'like', '%'.$keyword."%")
                    ->paginate(20, $projections);
        $res->appends(["keyword"=>$keyword]);
        $last_url = $res->url(1);
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res,
                "url_format"=>$last_url,
            ]
        );
    }
    //end hotel list

    public function deleteHotel(Request $request){
        $hotel_id = $request->input("hotel_id");
        $hotel = Hotel::find($hotel_id);
        ZEvent::log(self::getCurrentMaster(), "cmd", __METHOD__, $hotel_id);
        if($hotel){
            $hotel->delete();
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"ok",
                    "obj"=>null
                ]
            );
        }
        else{
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"hotel not found",
                    "obj"=>$hotel_id
                ]
            );
        }

    }
    public function memoHotel(Request $request){
        $hotel_id = $request->input("hotel_id");
        $memo = $request->input("memo");
        ZEvent::log(self::getCurrentMaster(), "cmd", __METHOD__, $hotel_id);
        $hotel = Hotel::find($hotel_id);
        if($hotel){
            $hotel->memo = $memo;
            $hotel->save();
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"ok",
                    "obj"=>$hotel
                ]
            );
        }
        else{
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"hotel not found",
                    "obj"=>$hotel
                ]
            );
        }
    }
    public function onlineHotel(Request $request){
        $hotel_id = $request->input("hotel_id");
        ZEvent::log(self::getCurrentMaster(), "cmd", __METHOD__, $hotel_id);
        $hotel = Hotel::find($hotel_id);
        if($hotel){
            if($hotel->status == 1){
                $hotel->status = 0;
            }
            else{
                $hotel->status = 1;
            }
            $hotel->save();
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"ok",
                    "obj"=>$hotel
                ]
            );
        }
        else{
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"hotel not found",
                    "obj"=>$hotel
                ]
            );
        }
    }
    public function getHotel($id,Request $request){
        $res = Hotel::find($id);
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, $id);
        if(!$res){
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"not found",
                    "obj"=>null
                ]
            );
        }
//        $res->contracts = null;
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res,
                "user"=>CustomerController::user()
            ]
        );

    }
    public function updateHotel( Request $request){
        $json = $request->json()->all();
        $hotel = Hotel::find($json["_id"]);
        ZEvent::log(self::getCurrentMaster(), "cmd", __METHOD__, $json);
        if($hotel){
            $hotel->name = $json["name"];
//            $hotel->status = $json["status"];
            $hotel->name_en = $json["name_en"];
            $hotel->tag = $json["tag"];
            $hotel->description = $json["description"];
            $hotel->location = $json["location"];
            $hotel->zy = $json["zy"];
            $hotel->detail = $json["detail"];
            $hotel->facilities = $json["facilities"];
            $hotel->honor = $json["honor"];
            if(isset($json["brand"])){
                $hotel->brand = $json["brand"];
            }
            if(isset($json["honor_img"]))
                $hotel->honor_img = $json["honor_img"];
            else{
                $hotel->honor_img = "";
            }
            if(isset($json["honor_word"]))
                $hotel->honor_word = $json["honor_word"];
            else{
                $hotel->honor_word = "";
            }
            $hotel->policy = $json["policy"];
            $hotel->rooms = $json["rooms"];
            $hotel->images = $json["images"];

            $hotel->last_editor = self::getCurrentMaster();
            $hotel->save();
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"更新成功",
                    "obj"=>$hotel
                ]
            );
        }


        return response()->json(
            [
                "ok"=>1,
                "msg"=>"not found hotel",
                "obj"=>null
            ]
        );
    }


    /**
     *
     */

    public function getOrderList(Request $request){
        $res = Order::orderBy("created_at","DESC")->paginate(10);
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, "");
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res
            ]
        );
    }
    public function searchOrder(Request $request){
        $keyword = $request->input("keyword");
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, $keyword);
        $res = null;
        if(substr($keyword,0,1) == "#"){
            $order = Order::find(substr($keyword,1));
            if($order){
                return response()->json(
                    [
                        "ok"=>0,
                        "msg"=>"ok",
                        "obj"=>[
                            "current_page" => 1,
                            "data" => [$order],
                            "from" => null,
                            "last_page" => 1,
                            "next_page_url" => null,
                            "per_page" => 20,
                            "prev_page_url" => null,
                            "to" => null,
                            "total" => 1
                        ]
                    ]
                );
            }
            else{
                return response()->json(
                    [
                        "ok"=>4,
                        "msg"=>"not found",
                        "obj"=>null
                    ]
                );
            }
        }
        $res = Order::where("hotel_name_en",'like', '%'.$keyword."%")
            ->orWhere("hotel_name",'like', '%'.$keyword."%")
            ->orWhere("user_phone", $keyword)
            ->paginate(20);
        $res->appends(["keyword"=>$keyword]);
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok1",
                "obj"=>$res,
            ]
        );
    }
    /**
     * PLAN OPs
     */
    public function checkPrice(Request $request){
        $json = $request->json()->all();
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, $json);
        $id = $json["_id"];
        $checkin = $json["checkin"];
        $checkout = $json["checkout"];
        $adult = $json["adult"];
        $children = $json["children"];
        $children_age = $json["children_age"];
        $age_str = "";
        foreach($children_age as $age){
            $age_str = $age_str."-".$age;
        }

        $hotel = Hotel::find($id);
        if(!$hotel){
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"not found hotel",
                    "obj"=>null
                ]
            );
        }

        //#1 select contract
        //#2 select rooms
        //#3 calculate price for every room

        //#1 TODO
        $contract = $hotel->contracts[0];

        //#2
        $rooms = [];
        foreach ($hotel->rooms as $room) {
            if(isset($room["online"]) && $room["online"] == 1){
                array_push($rooms,$room);
            }
        }

        foreach($rooms as &$room){
            $room_id = $room["id"];

            foreach($contract["rooms"] as $c){
                if($c["room_id"] == $room_id){
                    //#1 basic price
                    //$c 该合同对房型room_id的价格计划
                    $ans = self::calculateRoomPrice($c,$checkin,$checkout,$adult,$children,$children_age
                        ,$contract["price_rate"],$contract["price_unit"]);
                    if($ans){
                        $room["price"] = $ans;
                    }
                    break;
                }
            }
        }

        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$rooms
            ]
        );
    }
    public function updateContract(Request $request){
        $json = $request->json()->all();
        ZEvent::log(self::getCurrentMaster(), "cmd", __METHOD__, $json);
        $hotel = Hotel::find($json["_id"]);
        if($hotel){
            $hotel->contracts = $json["contracts"];
            $hotel->save();
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"更新成功",
                    "obj"=>$hotel
                ]
            );
        }
        return response()->json(
            [
                "ok"=>1,
                "msg"=>"not found hotel",
                "obj"=>null
            ]
        );
    }


    /**
     * 运营
     */

    public function getHotelListByOp(Request $request){
        $projections = ['_id', 'images', 'name', 'name_en', 'description', 'location'];
        $res = Hotel::paginate(20, $projections);

        $last_url = $res->url(1);
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, "");
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res,
                "url_format"=>$last_url,
            ]
        );
    }
    public function searchHotelByOp(Request $request){
        $json = $request->json()->all();
        $brand = $json["brand"];

        $projections = ['_id', 'images', 'name', 'name_en', 'description', 'location'];

        if($brand){
            $res = Hotel::where("brand",$brand)
                ->paginate(20, $projections);
            $res->appends(["brand"=>$brand]);
        }
        else{
            $res = Hotel::paginate(20, $projections);
        }

        $last_url = $res->url(1);
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res,
                "url_format"=>$last_url,
            ]
        );
    }
    public function getHotelBrandByOp(Request $request){
        $brand = $request->input("q");
        if(!$brand){
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"no brand parameter",
                    "obj"=>[]
                ]
            );
        }
        $projections = ['_id', 'images', 'name', 'name_en', 'description', 'location'];
        $res = Hotel::where("brand",$brand)->paginate(20, $projections);

        $last_url = $res->url(1);
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, "");

        $brand = [
            "name" => "Rosewood 瑰丽",
            "des" => "品牌的介绍介绍介绍介绍介绍",
            "icon" => "",
            "x" => [
                "title" => "TraveliD与xxx是yyy合作伙伴",
                "markdown" => "blabla"
            ]
        ];

        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res,
                "url_format"=>$last_url,
                "brand" => $brand
            ]
        );
    }

    public function getIndexPage(Request $request){
        $arr1 = [
            [
                "title" => "沙漠度假酒店巡礼",
                "des" => "啊哈哈哈,我是一个描述",
                "hotels" => [
                    [
                        "id" => "1",
                        "cover" => "",
                        "name" => "名字我是一个",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                    [
                        "id" => "2",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                    [
                        "id" => "3",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                    [
                        "id" => "4",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                ]
            ],
            [
                "title" => "沙漠度假酒店巡礼2",
                "des" => "啊哈哈哈,我是一个描述",
                "hotels" => [
                    [
                        "id" => "1",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                    [
                        "id" => "2",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                    [
                        "id" => "3",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                    [
                        "id" => "4",
                        "cover" => "",
                        "name" => "名字",
                        "name_en" => "name",
                        "address" => "address"
                    ],
                ]
            ]
        ];

        $arr2 = [
            [
                "title" => "兴趣主题",
                "des" => "",
                "items" => [
                    [
                        "id" => "12",
                        "name" => "海岛酒店",
                        "des" => "巴厘岛,泰国,大溪地",
                        "cover" => ""
                    ],
                    [
                        "id" => "12",
                        "name" => "海岛酒店2",
                        "des" => "巴厘岛,泰国,大溪地",
                        "cover" => ""
                    ],
                    [
                        "id" => "12",
                        "name" => "海岛酒店3",
                        "des" => "巴厘岛,泰国,大溪地",
                        "cover" => ""
                    ],
                    [
                        "id" => "12",
                        "name" => "海岛酒店4",
                        "des" => "巴厘岛,泰国,大溪地",
                        "cover" => ""
                    ],
                    [
                        "id" => "12",
                        "name" => "海岛酒店5",
                        "des" => "巴厘岛,泰国,大溪地",
                        "cover" => ""
                    ]
                ]
            ]
        ];

        $arr3 = [
            [
                "name" => "安曼",
                "cover" => "",
                "key" => "aman"
            ],
            [
                "name" => "四季",
                "cover" => "",
                "key" => "aman"
            ],
            [
                "name" => "安曼2",
                "cover" => "",
                "key" => "aman"
            ],
            [
                "name" => "安曼3",
                "cover" => "",
                "key" => "aman"
            ],
            [
                "name" => "安曼4",
                "cover" => "",
                "key" => "aman"
            ],
            [
                "name" => "安曼5",
                "cover" => "",
                "key" => "aman"
            ],
            [
                "name" => "安曼6",
                "cover" => "",
                "key" => "aman"
            ]
        ];


        return response()->json(
            [
                "ok"=>0,
                "msg"=>"index",
                "obj"=>[
                    "arr1" => $arr1,
                    "arr2" => $arr2,
                    "arr3" => $arr3
                 ]
            ]
        );
    }



    /**
     * +++++++
     */
    public function createMaster(Request $request){
        $master = new Master();
        $master->name = "jingang";
        $master->email = "jingang@travelid.cn";
        $master->password = bcrypt("123123");
        $master->save();
        dd($master);
    }

    /**
     * HELPER FUNs
     */
    //2017-02-22 - 2017-02-18 = 4
    private function diffDateString($date1,$date2){
        //return $date2 - $date1
        $d1 = Carbon::parse($date1);
        $d2 = Carbon::parse($date2);
        return $d2->diffInDays($d1);
    }
    private function getDateString($date, $index){
        $d = Carbon::parse($date);
        $dt = $d->addDay($index);
        return $dt->format('Y年m月d日');
    }
    //minus checkout-date one day
    private function lastNightDate($checkout){
        $c = Carbon::parse($checkout);
        $d2 = $c->subDay(1);
        return $d2->toDateString();
    }
    //algorithm for one plan
    private function calculateRoomPrice($plan,$checkin,$checkout,$adult,$children,$ages,$price_rate,$price_unit){
        Log::info("calculateRoomPrice");
        $last_night = self::lastNightDate($checkout);
        $room_prices = $plan["prices"];//普通班期价格 arr of {date_from,date_to,price}
        $plans = $plan["plans"];

        $find = 0;
        $ans_plans = [];
        $ans = [];
        $total_cnt = self::diffDateString($checkin,$checkout);
        $basic_price = 0;

        //根据price_unit去换算price_rate

        //将基础班期的每天价格放到$ans里
        $price_details = [];//type name price
        foreach($room_prices as $price){
            //先找checkin 范围
            $price_rmb = self::toNumber($price["price"]*$price_rate) ;//covert to RMB
            if($find == 0){
                if($checkin <= $price["date_to"] && $checkin >= $price["date_from"]){
                    $find++;
                    if($checkout <= $price["date_to"]){
                        $find++;
                        $count = self::diffDateString($checkin,$checkout);
                        for($i=0;$i<$count;$i++){
                            $basic_price = $basic_price + $price_rmb;
                            array_push($ans,$price_rmb);
                            //$chekcin + $i =>
                            $date_string = self::getDateString($checkin, $i);
                            array_push($price_details,["BASIC", $date_string, $price_rmb]);
                        }
                        break;//一次找到全部班期
                    }
                    else{
                        //算一部分钱
                        $count = self::diffDateString($checkin,$price["date_to"]) + 1;
                        for($i=0;$i<$count;$i++){
                            $basic_price = $basic_price + $price_rmb;
                            array_push($ans,$price_rmb);
                            //$chekcin + $i =>
                            $date_string = self::getDateString($checkin, $i);
                            array_push($price_details,["BASIC", $date_string, $price_rmb]);
                        }
                    }
                }
            }
            else if($find == 1){
                if($checkout <= $price["date_to"]){
                    $find++;
                    $count = self::diffDateString($price["date_from"],$checkout);
                    for($i=0;$i<$count;$i++){
                        $basic_price = $basic_price + $price_rmb;
                        array_push($ans,$price_rmb);

                        // + $i =>
                        $date_string = self::getDateString($price["date_from"], $i);
                        array_push($price_details,["BASIC", $date_string, $price_rmb]);
                    }
                    //找到第二部分
                    break;
                }
                else{//
                    //说明checkin checkout跨多个时间段了,本时间段全部加进去
                    $count = self::diffDateString($price["date_from"],$price["date_to"]) + 1;
                    for($i=0;$i<$count;$i++){
                        $basic_price = $basic_price + $price_rmb;
                        array_push($ans,$price_rmb);

                        // + $i =>
                        $date_string = self::getDateString($price["date_from"], $i);
                        array_push($price_details,["BASIC", $date_string, $price_rmb]);
                    }
                }
            }
        }

        if($total_cnt != count($ans)){//晚数相等
            //该计划不能给出答案
            return [
                "ok" => 1,
                "msg" => "班期不满足ci-co"
            ];
        }


        $basic_price = self::toNumber($basic_price);

        //基本价格
        array_push($ans_plans,[
            "name"=>"basic",
            "price"=>$basic_price,
            "ok"=> true,
            "reason"=> true,
            "cancellation"=>isset($plan["cancellation"]) ? $plan["cancellation"] : "",
            "include"=>isset($plan["include"]) ? $plan["include"] : "",
            "memo"=>isset($plan["memo"]) ? $plan["memo"] : "",
            "details"=>$price_details

        ]);


        //入住限制 limit
        if(isset($plan["limit"]) && $plan["limit"]){
            foreach ($plan["limit"] as $item) {
                /**
                 * date_from date_to night_max night_min
                 * if checkin & checkout in limit-date-range
                 *    nights >= night_min && nights <= night_max
                 * else
                 *    return NULL
                 */
                $night_in = -1;
                 if($checkin < $item["date_from"]){
                     if($checkout <= $item["date_from"]){
                         //不在时间范围内
                     }
                     else if($checkout <= $item["date_to"]){
                         $night_in = self::diffDateString($item["date_from"],$checkout);
                     }
                     else{
                         $night_in = self::diffDateString($item["date_from"],$item["date_to"]) + 1;
                     }
                 }
                else if($checkin >= $item["date_from"] && $checkin <= $item["date_to"]){
                    if($checkout <= $item["date_to"]){
                        $night_in = self::diffDateString($checkin,$checkout);
                    }
                    else{
                        $night_in = self::diffDateString($checkin,$item["date_to"]) + 1;
                    }
                }
                else{
                    //不在时间限制内
                }
                if($night_in == -1 || ($night_in >= $item["night_min"] && $night_in <= $item["night_max"])){
                    //有效
                }
                else{
                    return [
                        "ok" => 1,
                        "msg" => $item,
                        "reason" => "".$item["date_from"]." - ".$item["date_to"]."期间最少入住".$item["night_min"]."晚"
                    ];
                }
            }
        }


        //优化价格计划
        foreach($plans as $item){
            //无班期拒绝
            if($checkin < $item["date_from"]  || $last_night > $item["date_to"]) {
                continue;
            }

            $ok = true;
            foreach($item["dates_not"] as $not){
                if($checkin >= $not["date_from"] || $checkin <= $not["date_to"]){
                    $ok = false;
                    break;
                }
                if($checkout >= $not["date_from"] || $checkout <= $not["date_to"]){
                    $ok = false;
                    break;
                }
            }
            //特殊日期拒绝
            if($ok == false){
                array_push($ans_plans,[
                    "name"=>$item["name"],
                    "price"=>-1,
                    "ok"=> false,
                    "reason"=>"date not allowed",
                    "cancellation"=>isset($item["cancellation"]) ? $item["cancellation"] : "",
                    "include"=>isset($item["include"]) ? $item["include"] : "",
                    "memo"=>isset($item["memo"]) ? $item["memo"] : "",
                ]);
                continue;
            }
            //最低入住晚数拒绝
            if($total_cnt < (int)$item["night_min"]){
                array_push($ans_plans,[
                    "name"=>$item["name"],
                    "price"=>-1,
                    "ok"=> false,
                    "reason"=>"min nights ".$item["night_min"],
                    "cancellation"=>isset($item["cancellation"]) ? $item["cancellation"] : "",
                    "include"=>isset($item["include"]) ? $item["include"] : "",
                    "memo"=>isset($item["memo"]) ? $item["memo"] : "",
                ]);
                continue;
            }

            $all_price = -1;
            if($item["type"] == "住X付Y"  ){
                $x = (int)$item["obj"]["x"];
                $y = (int)$item["obj"]["y"];
                $left = $total_cnt % $x;
                $num = ($total_cnt - $left)/$x;
                $all_price = $num*$y*$ans[0] + $left*$ans[0];
            }
            else if($item["type"] == "日期T之前Z折扣"){
                if($checkout > $item["obj"]["date"]){
                    array_push($ans_plans,[
                        "name"=>$item["name"],
                        "price"=>-1,
                        "ok"=> false,
                        "reason"=>"before date  ".$item["obj"]["date"],
                        "cancellation"=>isset($item["cancellation"]) ? $item["cancellation"] : "",
                        "include"=>isset($item["include"]) ? $item["include"] : "",
                        "memo"=>isset($item["memo"]) ? $item["memo"] : "",
                    ]);
                    continue;
                }
                $all_price = ($basic_price * (float)$item["obj"]["z"] / 10);
            }
            else if($item["type"] == "提起X天Z折扣"){
                $now = Carbon::now();
                $left_day = self::diffDateString($now, $checkin);

                if($left_day <  0 || $left_day < (int)$item["obj"]["day"]){
                    array_push($ans_plans,[
                        "name"=>$item["name"],
                        "price"=>-1,
                        "ok"=> false,
                        "reason"=>"days  ".$item["obj"]["day"],
                        "cancellation"=>isset($item["cancellation"]) ? $item["cancellation"] : "",
                        "include"=>isset($item["include"]) ? $item["include"] : "",
                        "memo"=>isset($item["memo"]) ? $item["memo"] : "",
                    ]);
                    continue;
                }
                $all_price = ($basic_price * (float)$item["obj"]["z"] / 10);
            }
            else if($item["type"] == "住X延住K晚Z折扣"){
                $x = (int)$item["obj"]["x"];
                $y = (int)$item["obj"]["y"];
                $z = (int)$item["obj"]["z"];
                if($total_cnt < $x){
                    array_push($ans_plans,[
                        "name"=>$item["name"],
                        "price"=>-1,
                        "ok"=> false,
                        "reason"=>"<  ".$item["obj"]["x"],
                        "cancellation"=>isset($item["cancellation"]) ? $item["cancellation"] : "",
                        "include"=>isset($item["include"]) ? $item["include"] : "",
                        "memo"=>isset($item["memo"]) ? $item["memo"] : "",
                    ]);
                    continue;
                }
                $all_price = self::toNumber($basic_price * (float)$item["obj"]["z"] / 10);
            }

            $all_price = self::toNumber($all_price);


            $detais = self::copyArray($price_details);
            array_push($detais, ["PLAN", $item["name"],  $all_price - $basic_price]);

            //有效优惠
            array_push($ans_plans,[
                "name"=>$item["name"],
                "price"=>$all_price,
                "details"=>$detais,
                "ok"=> true,
                "reason"=>"",
                "cancellation"=>isset($item["cancellation"]) ? $item["cancellation"] : "",
                "include"=>isset($item["include"]) ? $item["include"] : "",
                "memo"=>isset($item["memo"]) ? $item["memo"] : "",
            ]);
        }

        //额外 成人费用
        if($adult > 2 && isset($plan["extra_adult"]) && $plan["extra_adult"]){
            foreach($plan["extra_adult"] as $item){
                /**
                 * date_from date_to price price
                 * if nights in date_range :
                 *   extract_adult = nights * price
                 */
            }
        }
        //额外 儿童费用
        if($children > 0 && isset($plan["extra_children"]) && $plan["extra_children"]){
            /**
             *  date_from date_to age_from age_to price
             * foreach children_age as age
             *   if age in age_range & nights in date_range:
             *      extract_child[] = nights*1*price
             *
             */
        }


        $options = [];
        //强制费用 plus type=0,
        if(isset($plan["plus"]) && $plan["plus"]){
            //adult[] date_from date_to price
            //children[] date_from date_to age_from age_to price
        }



        return [
            "ok" => 0,
            "basic" => $ans,
            "plans" => $ans_plans,
            "options" => $options
        ];
    }
    private function toNumber($number){
        return round((float)$number, 2, PHP_ROUND_HALF_UP);
    }
    //for log
    private function getCurrentUser(){

    }
    private function getCurrentMaster(){
        if(BAuth::user()){
            return BAuth::user()->name;
        }
        return "null";
    }
    private function copyArray($arr){
        $toArr = [];
        foreach($arr as $item){
            array_push($toArr, $item);
        }
        return $toArr;
    }

    /**
     * Qi-niu Image OPs
     */
    //save image by URL
    public function fetchImage(Request $request){
        $url = $request->input('url');
        $_id = $request->input('_id');
        if(!$_id){
            $_id = "none";
        }
        if(!$url){
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"no file found",
                    "obj"=>null
                ]
            );
        }
        $url_pure = strtok($url, '?');
        $ext_arr = explode(".",$url_pure);
        $ext = "jpg";
        if($ext_arr && count($ext_arr) > 1){
            $ext = $ext_arr[count($ext_arr)-1];
        }
        $auth = new Auth(IndexController::AK, IndexController::SK);
        $bucket = 'zhotel';
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new BucketManager($auth);
        $key = "zhotel_".$_id."_".time().'.'.$ext;
        list($ret, $err) = $uploadMgr->fetch($url,$bucket,$key);
        if($err){
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"upload to qiniu failed",
                    "obj"=>$err
                ]
            );
        }
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"上传成功",
                "obj"=>[
                    "domain"=>IndexController::Q_DOMAIN,
                    "ret"=>$ret,
                    "url"=>IndexController::Q_DOMAIN."/".$ret["key"]
                ]
            ]
        );
    }
    //token for js-sdk
    public function qToken(Request $request){
        $auth = new Auth(IndexController::AK, IndexController::SK);
        $bucket = 'zhotel';
        $token = $auth->uploadToken($bucket);
        return response()->json(
            [
                "uptoken"=>$token
            ]
        );
    }
    //save image by UPLOADER
    public function uploadImage(Request $request){
        if(!$request->hasFile('file')){
            return response()->json(
                [
                    "ok"=>1,
                    "msg"=>"no file found",
                    "obj"=>null
                ]
            );
        }
        $file = $request->file('file');
        $_id = $request->input('_id');
        if(!$_id){
            $_id = "none";
        }
        $auth = new Auth(IndexController::AK, IndexController::SK);
        $bucket = 'zhotel';
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();
        if(is_array($file)){//images
            Log::info("in array");
            $failed_cnt = 0;
            $urls = [];
            foreach($file as $f){
                $key = "zhotel_".$_id."_".time().'.'.$f->getClientOriginalExtension();
                list($ret, $err) = $uploadMgr->putFile($token, $key, $f->getRealPath());
                if($err){
                    $failed_cnt = $failed_cnt + 1;
                }
                else{
                    array_push($urls,IndexController::Q_DOMAIN."/".$ret["key"]);
                }
                Log::info("ok one");
            }
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"上传成功",
                    "obj"=>[
                        "domain"=>IndexController::Q_DOMAIN,
                        "failed" => $failed_cnt,
                        "urls"=>$urls
                    ]
                ]
            );
        }
        else{

            $key = "zhotel_".$_id."_".time().'.'.$file->getClientOriginalExtension();
            list($ret, $err) = $uploadMgr->putFile($token, $key, $file->getRealPath());
            if($err){
                return response()->json(
                    [
                        "ok"=>1,
                        "msg"=>"upload to qiniu failed",
                        "obj"=>$err
                    ]
                );
            }
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"上传成功",
                    "obj"=>[
                        "domain"=>IndexController::Q_DOMAIN,
                        "ret"=>$ret,
                        "url"=>IndexController::Q_DOMAIN."/".$ret["key"]
                    ]
                ]
            );
        }
    }
    private function _fetchImage($_id,$url){
        if(!$_id){
            $_id = "none";
        }
        $url_pure = strtok($url, '?');
        $ext_arr = explode(".",$url_pure);
        $ext = "jpg";
        if($ext_arr && count($ext_arr) > 1){
            $ext = $ext_arr[count($ext_arr)-1];
        }
        $auth = new Auth(IndexController::AK, IndexController::SK);
        $bucket = 'zhotel';
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new BucketManager($auth);
        $key = "zhotel_".$_id."_".time().'.'.$ext;
        list($ret, $err) = $uploadMgr->fetch($url,$bucket,$key);
        if($err) return null;
        return IndexController::Q_DOMAIN."/".$ret["key"];
    }


    /**
     * THIRD-PART APIs
     */
    //concurrent rate
    public function getRate(Request $request){
        $code = $request->input("code");
        $url = 'http://api.fixer.io/latest?base='.$code;
        $client = new Client();
        $res = $client->get($url);
        $body = $res->getBody();
        return $body;
    }
    //chrome plugin for aman rooms
    public function chromeTest(Request $request){//aman chrome plugin api
        Log::info("chrome test");
        $cnt = 0;
        $cnt_n = 0;
        $json = $request->json()->all();
        $hotel = Hotel::find($json["id"]);
        if($hotel){
            $arr = $json["arr"];

            $rooms = $hotel->rooms;
            foreach ($arr as $key=>$item) {
                $found = false;
                foreach($rooms as $room){
                    if($room["name"] == $item["name"]){
                        $found = true;
                        break;
                    }
                }
                if(!$found){
                    list($usec, $sec) = explode(" ", microtime());
                    $image_arr = explode(PHP_EOL,trim($item["images_str"]));
                    $image_str = "";
                    foreach($image_arr as $url){
                        $res = self::_fetchImage($hotel->_id,$url);
                        if($res){
                            $image_str = $image_str . $res . PHP_EOL;
                        }
                    }
                    $empty = [
                        "name" => $item["name"],
                        "highlight" => $item["highlight"],
                        "facilities" => $item["facilities"],
                        "adult"=>"2",
                        "children"=>"0",
                        "children_age"=>"12",
                        "description"=>"",
                        "id"=>"room.".$sec.$usec,
                        "images_str"=>$image_str,
                        "info"=>"",
                        "online"=>"0",
                    ];
                    $cnt++;
                    array_push($rooms,$empty);
                }
                else{
                    $cnt_n++;
                }
                $hotel->rooms = $rooms;
                $hotel->save();
            }
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"操作成功: 添加房型 <span style='color:green;font-size: 20px'>".$cnt."</span> 个。 重复房型名 <span style='color:red;font-size: 20px'>".$cnt_n."</span>个",
                    "obj"=>$hotel
                ]
            );
        }
        return response()->json(
            [
                "ok"=>1,
                "msg"=>"not found hotel",
                "obj"=>""
            ]
        );
    }
    //booking or lhw hotel info parser
    public function parseHotel(Request $request){
        $para = $request->input("url");
        //http://127.0.0.1:5000
        $url = 'http://jingang.info/api/hotel/parse';
        $client = new Client();
        $res = $client->post($url,[
            'json'=>[
                "url"=>urldecode($para)
            ]
        ]);
        $body = $res->getBody();
        return $body;
    }
    //google place api
    public function getPlaceDetailOfGooglemap(Request $request){
        $id = $request->input("id");
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyBJfv6WxdEoTqSgibZDdOL-m-lLWz6UO8E&placeid='.$id;
        $client = new Client();
        $res = $client->get($url);
        $body = $res->getBody();
        return response()->json(
            [
                "ok"=>1,
                "msg"=>"no file found",
                "obj"=>$body
            ]
        );
    }

}
