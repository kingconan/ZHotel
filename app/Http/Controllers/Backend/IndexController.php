<?php

namespace App\Http\Controllers\Backend;

use App\Models\Hotel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IndexController extends Controller
{
    //
    const AK = "My5kLLe5AW7Tm4RaNivwbjT3jERbWDEKsJLuGn0s";
    const SK = "odKn-63W8vWvfnNrhqLAyVZLyMwF8kQg2nIh4gUx";
    const Q_DOMAIN = "http://oytstg973.bkt.clouddn.com";


    public function createHotel(Request $request){
        $json = $request->json()->all();
        $hotel = new Hotel();
        $hotel->name = $json["name"];
        $hotel->status = 0;//default is not online
        $hotel->name_en = $json["name_en"];
        $hotel->tag = $json["tag"];
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
        $hotel->save();
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"创建成功",
                "obj"=>$hotel
            ]
        );
    }
    public function test(Request $request){
//        $res = Hotel::withTrashed()->paginate(2);
        $res = Hotel::paginate(20);
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res
            ]
        );
    }
    public function searchHotel(Request $request){
        $keyword = $request->input("keyword");
        $res = Hotel::where("name",'like', '%'.$keyword."%")
                    ->orWhere("name_en",'like', '%'.$keyword."%")
                    ->orWhere("location.country",'like', '%'.$keyword."%")
                    ->orWhere("location.city",'like', '%'.$keyword."%")
                    ->paginate(20);
        $res->appends(["keyword"=>$keyword]);
        return response()->json(
            [
                "ok"=>0,
                "msg"=>"ok",
                "obj"=>$res
            ]
        );
    }
    public function deleteHotel(Request $request){
        $hotel_id = $request->input("hotel_id");
        $hotel = Hotel::find($hotel_id);
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
    public function onlineHotel(Request $request){
        $hotel_id = $request->input("hotel_id");
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
                "obj"=>$res
            ]
        );

    }
    public function checkPrice(Request $request){
        $json = $request->json()->all();
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
                    $ans = self::calculateRoomPrice($c,$checkin,$checkout,$adult,$children,$children_age);
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
    private function calculateRoomPrice($plan,$checkin,$checkout,$adult,$children,$ages){
        Log::info("calculateRoomPrice");
        $last_night = self::lastNightDate($checkout);
        $room_prices = $plan["prices"];
        $plans = $plan["plans"];

        $find = 0;
        $ans_plans = [];
        $ans = [];
        $total_cnt = self::diffDateString($checkin,$checkout);
        $basic_price = 0;
        foreach($room_prices as $price){
            //先找checkin 范围
            if($find == 0){
                if($checkin <= $price["date_to"] && $checkin >= $price["date_from"]){
                    $find++;
                    if($checkout <= $price["date_to"]){
                        $find++;
                        $count = self::diffDateString($checkin,$checkout);
                        for($i=0;$i<$count;$i++){
                            $basic_price = $basic_price + $price["price"];
                            array_push($ans,$price["price"]);
                        }
                    }
                    else{
                        //算一部分钱
                        $count = self::diffDateString($checkin,$price["date_to"]) + 1;
                        for($i=0;$i<$count;$i++){
                            $basic_price = $basic_price + $price["price"];
                            array_push($ans,$price["price"]);
                        }
                    }
                }
            }
            else if($find == 1){
                if($checkout <= $price["date_to"]){
                    $find++;
                    $count = self::diffDateString($price["date_from"],$checkout);
                    for($i=0;$i<$count;$i++){
                        $basic_price = $basic_price + $price["price"];
                        array_push($ans,$price["price"]);
                    }
                }
                else{//
                    $count = self::diffDateString($price["date_from"],$price["date_to"]) + 1;
                    for($i=0;$i<$count;$i++){
                        $basic_price = $basic_price + $price["price"];
                        array_push($ans,$price["price"]);
                    }
                }
            }
        }


        //基本价格
        array_push($ans_plans,[
            "name"=>"basic",
            "price"=>$basic_price,
            "ok"=> true,
            "reason"=> true
        ]);
        //优化价格计划
        foreach($plans as $plan){
            //无班期拒绝
            if($checkin < $plan["date_from"]  || $last_night > $plan["date_to"]) {
                continue;
            }

            $ok = true;
            foreach($plan["dates_not"] as $not){
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
                    "name"=>$plan["name"],
                    "price"=>-1,
                    "ok"=> false,
                    "reason"=>"date not allowed"
                ]);
                continue;
            }
            //最低入住晚数拒绝
            if($total_cnt < (int)$plan["night_min"]){
                array_push($ans_plans,[
                    "name"=>$plan["name"],
                    "price"=>-1,
                    "ok"=> false,
                    "reason"=>"min nights ".$plan["night_min"]
                ]);
                continue;
            }

            $all_price = -1;
            if($plan["type"] == "住X付Y"  ){
                $x = (int)$plan["obj"]["x"];
                $y = (int)$plan["obj"]["y"];
                $left = $total_cnt % $x;
                $num = ($total_cnt - $left)/$x;
                $all_price = $num*$y*$ans[0] + $left*$ans[0];
            }
            else if($plan["type"] == "日期T之前Z折扣"){
                if($checkout > $plan["obj"]["date"]){
                    array_push($ans_plans,[
                        "name"=>$plan["name"],
                        "price"=>-1,
                        "ok"=> false,
                        "reason"=>"before date  ".$plan["obj"]["date"]
                    ]);
                    continue;
                }
                $all_price = ceil($basic_price * (int)$plan["obj"]["z"] / 100);
            }
            else if($plan["type"] == "提起X天Z折扣"){
                $now = Carbon::now();
                $left_day = self::diffDateString($now, $checkin);

                if($left_day <  0 || $left_day < (int)$plan["obj"]["day"]){
                    array_push($ans_plans,[
                        "name"=>$plan["name"],
                        "price"=>-1,
                        "ok"=> false,
                        "reason"=>"days  ".$plan["obj"]["day"]
                    ]);
                    continue;
                }
                $all_price = ceil($basic_price * (int)$plan["obj"]["z"] / 100);
            }

            //有效优惠
            array_push($ans_plans,[
                "name"=>$plan["name"],
                "price"=>$all_price,
                "ok"=> true,
                "reason"=>""
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
        //强制费用 plus type=0,
        if(isset($plan["plus"]) && $plan["plus"]){
            //adult[] date_from date_to price
            //children[] date_from date_to age_from age_to price
        }
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
            }
        }


        Log::info($ans_plans);
        if($find == 2){
            return [
                "basic" => $ans,
                "plans" => $ans_plans
            ];
        }
        else{
            return null;
        }
    }

    private function diffDateString($date1,$date2){
        //return $date2 - $date1
        $d1 = Carbon::parse($date1);
        $d2 = Carbon::parse($date2);
        return $d2->diffInDays($d1);
    }
    private function lastNightDate($checkout){
        $c = Carbon::parse($checkout);
        $d2 = $c->subDay(1);
        return $d2->toDateString();
    }
    public function updateHotel( Request $request){
        $json = $request->json()->all();
        $hotel = Hotel::find($json["_id"]);
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
    public function updateContract(Request $request){
        $json = $request->json()->all();
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

    public function getRate(Request $request){
        $code = $request->input("code");
        $url = 'http://api.fixer.io/latest?base='.$code;
        $client = new Client();
        $res = $client->get($url);
        $body = $res->getBody();
        return $body;
    }

    public function magicLink(Request $request){
        $url = $request->input("url");
        if(!$url){
            $url = "https://www.booking.com/hotel/vn/the-nam-hai-hoi-an.zh-cn.html?label=gen173nr-1FCAEoggJCAlhYSDNiBW5vcmVmaDGIAQGYATK4AQfIAQzYAQHoAQH4AQuSAgF5qAID;sid=46f795c7599dcc2ab51a4ab19898e3b0;dest_id=-3715584;dest_type=city;dist=0;hapos=1;hpos=1;room1=A%2CA;sb_price_type=total;srepoch=1510379327;srfid=e6a5c4d192f1500a7b686ebacddc8a53f4ae951cX1;srpvid=f62628dfdcfd0161;type=total;ucfs=1&#hotelTmpl";
        }
        $client = new Client();
        $res = $client->get($url);
        $body = $res->getBody();

    }


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



    public function index($id,Request $request){
        $hotel = self::getHotelDetail($id);
        return view("frontend.index.hotel_detail",["hotel" => $hotel]);
    }


    public function getHotelDetail($id){
        $hotel = [
            //header
            "name" => "巴厘岛阿雅娜水疗度假酒店",
            "name_en" => "AYANA Resort and Spa BALI",
            "continent" => "亚洲",
            "country" => "印度尼西亚",
            "city" => "巴厘岛",
            "location" => [
                "lat" => "-8.4559965",
                "long" => "114.7913486",
                "address" => "Jl. Karang Mas Sejahtera,金巴兰地区,巴厘岛,巴厘省,80364,印度尼西亚",
                "transportation" => "AYANA酒距离巴厘岛国际机场约20分钟车程，巴厘岛的公共交通不发达，以打车或包车游览为主。从酒店到巴厘岛最热门的两大景点：情人崖和海神庙分钟约30分种和2小时。前往乌布约1小时车程",
            ],
            "description"=>"巴厘岛最不缺乏好酒店，综合考虑，无论你这次旅行的主题是蜜月、亲子还是闺蜜旅行，Ayana都是不错的选择！自从吴奇隆和刘诗诗在阿雅娜度假别墅举办婚礼后，在国内更是名声大噪，酒店内更是有多达18家餐厅",
            "tag" => "",
            "brand"=>[
                "images" => [],
                "description" => "Viceroy Bali Hotel是LHW立鼎世酒店集团成员"
            ],
            "prize"=>[
                "World Travel Awards 2016 - Bali's Leading Villa Resort",
                "Prix Villegiature Awards 2016 - Best Hotel in Asia",
                "Conde Nast 2016 - Best Service in the World, Best View in the World",
                "World Boutique Hotel Awards - Best Boutique Hotel in Asia - 2015",
                "World Boutique Hotel Awards - Best Honeymoon Boutique Hotel in Asia - 2015",
                "World Boutique Hotel Awards - Best Honeymoon Boutique Hotel in the World - 2015"
            ],
            "images" => [
//                "http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg",
                "http://ucdn.travelid.cn/upload/0f5317a021d9979a8c5808e7b15e0a6d.jpg",
                "http://ucdn.travelid.cn/upload/560187a47ed8a138ae43bfdc4c96d091.jpg",
//                "http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg",
                "http://ucdn.travelid.cn/upload/0f5317a021d9979a8c5808e7b15e0a6d.jpg",
                "http://ucdn.travelid.cn/upload/560187a47ed8a138ae43bfdc4c96d091.jpg",
            ],
            //section part
            "section" => [
                //致游推荐
                "zy_recommendation"=>[
                    "地处金巴兰中心位置，到各处热门景点十分方便",
                    "海滩上有露天电影院，电影、爆米花和冰镇香槟",
                    "豪华游艇往返于加途国内机场",
                    "24小时私人管家服务，随时随地提供方便。还专门有中国管家“Mr. Friday”，对中国客人服务更贴心",
                ],
                //致游知道
                "zy_introduction"=>[
                    "地处金巴兰中心位置，到各处热门景点十分方便",
                    "海滩上有露天电影院，电影、爆米花和冰镇香槟",
                    "豪华游艇往返于加途国内机场",
                    "24小时私人管家服务，随时随地提供方便。还专门有中国管家“Mr. Friday”，对中国客人服务更贴心",
                ],
                //酒店图文介绍
                "detail"=>[
                    [
                        "title" => "酒店环境",
                        "markdown" => self::markdownParse("巴厘岛阿雅娜水疗度假酒店是世界顶级的度假胜地，占地90公顷，\n坐落于悬崖之上，俯瞰金巴兰湾，并拥有美誉远扬的壮丽日落海景与白色沙滩，\n![图片描述](http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=800)\n这里堪称巴厘岛酒店中设施最丰富集中的度假村。"),
                    ],
                    [
                        "title" => "推荐房型",
                        "markdown" => self::markdownParse("酒店的豪华海景房拥有私人观景阳台，您可以在此近赏葱郁繁茂的花园景色，远眺壮美开阔的印度洋风光\n![图片描述]{}(http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=800)\n![图片描述](http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=800)\n"),
                    ],
                    [
                        "title" => "活动体验",
                        "markdown" => self::markdownParse("入住阿雅纳期间，您可以在高尔夫推杆场满足您的打球欲望；在瑜伽冥想中放松心灵，获得内心喜悦；\n![图片描述]{}(http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=800)\n![图片描述](http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=800)\n"),
                    ],
                    [
                        "title" => "餐饮&SPA",
                        "markdown" => "度假村内共有19家餐厅和酒吧，如果您想尝尝经典的金巴兰风味海鲜宴，Kisik烧烤餐厅可让您在欣赏海滨美景和享受清爽海风的同时，品味海鲜烧烤。",
                    ]

                ],
                //酒店设施
                "facilities" => [
                    "Video conference",
                    "Meeting / Private Room",
                    "Complimentary access to Fitness Center",
                    "24-hour Fitness Center",
                    "Laundry Service",
                    "Parking",
                ],
                //订前必读
                "good_to_know"=>[
                    "checkin" => "14:00",
                    "checkout" => "12:00",
                    "cancellation" =>[
                        "不同类型的客房附带不同的取消预订和预先付费政策，具体请参阅各房型价格的详细说明",
                    ],
                    "children" => [
                        "使用现有床铺免费，不含儿童早餐",
                        "4-11岁儿童入住加床收取USD29，含早（不含税）",
                        "12岁以上儿童一律按照成人标准收费",
                        "俱乐部房和俱乐部套房不允许儿童入住。",
                    ],
                    "extra_bed"=>[
                        "部分房型不可加床"
                    ],
                    "pets" => [
                        "不允许携带宠物入住"
                    ],
                    "cards" => [
                        "visa",
                        "master",
                        "银联",
                    ]
                ],
                //客房类型
                "rooms" => [
                    [
                        "id" => "1",
                        "images" => [
                            "http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=400"
                        ],
                        "title" => "度假村景观房 Resort View Room",
                        "description" => "",
                        "highlight" => [
                            "48平米",
                            "大床或双床",
                            "可入住2成人",
                        ],

                    ],
                    [
                        "id" => "2",
                        "images" => [
                            "http://ucdn.travelid.cn/upload/9ef165ac02055ad089e966d950a580df.jpg?iopcmd=thumbnail&type=4&width=400"
                        ],
                        "title" => "度假村景观房 Resort View Room",
                        "description" => "别墅提供传统的巴厘式建筑，设有私人泳池、露天起居区用餐区以及带有超大下沉式浴缸的私人浴室，俯瞰着Ayung River河。别墅内的一张大型双人床可以换成两张单人床。

                        别墅设施： 阳台, 山景, 河景, 电视, 电话, DVD播放机, 有线频道, 平板电视, 保险箱, 空调, 书桌, 客厅角, 电风扇, 暖气, 更衣室, 私人入口, 沙发, 瓷砖/大理石地板, 蚊帐, 私人游泳池, 沙发床, 浴缸, 吹风机, 浴袍, 免费洗浴用品, 卫生间, 浴室, 拖鞋, 额外的卫生间, 迷你吧, 冰箱, 用餐区, 餐桌, 户外家具, 户外用餐区, 唤醒服务, 毛巾, 亚麻织品
                        ",
                        "highlight" => [
                            "48平米",
                            "大床或双床",
                            "可入住2成人",
                        ]
                    ],
                ]
            ]
        ];
        $plans = [
            "hotel_id" => 1,
            "room_id" => 1,
            [
                "plan_id" => 1,
                "plan_name" => "大床含早",
                "price" => 666,
                "price_detail" => [
                    "nights"=>[
                        "2017-10-01"=>123,
                        "2017-10-02"=>234,
                    ],
                    "tax"=>1000,
                    "discount"=>11,
                    "total"=>1111,
                ],
                "include" =>[
                    "Includes Premium Benefits",
                    "Includes Breakfast",
                    "Free Cancellation before Sep 22, 2017"
                ],
                "zy" =>[
                    "Best available rate guarantee",
                    "Complimentary continental breakfast daily"
                ],
                "others"=>[
                    [
                        "key"=>"取消政策",
                        "value"=>"This rate plan is for select Visa Premium Card holders
Cardholder must reserve and pay for the room using a qualifying credit card. Qualifying Visa premium cards include Visa Signature and Visa Infinite cards and select Visa Gold and Visa Platinum cards"
                    ],
                    [
                        "key"=>"儿童说明",
                        "value"=>"儿童加床说明"
                    ]
                ]
            ]
        ];
        return $hotel;
    }
    public static function markdownParse($content){
        $str = '';
        if($content){
            $lines = explode(PHP_EOL,$content);
            $len = count($lines);
            for($i=0;$i<$len;){
                if(substr($lines[$i],0,2) == '//'){//注释
                    $i++;
                    continue;
                }
                if(substr($lines[$i],0,2) == '!['){//图片
                    $des_start = 2;
                    $des_end = strpos($lines[$i],']');
                    $url_start = strpos($lines[$i],'(')+1;
                    $url_end = strpos($lines[$i],')');

                    $margin_start = strpos($lines[$i],'{');
                    $margin_end = strpos($lines[$i],'}');

                    $des = substr($lines[$i],$des_start,$des_end-$des_start);
                    $url = substr($lines[$i],$url_start,$url_end-$url_start);
                    if($margin_start === false){
                        $html_img = '<img style="max-height:300px;" src="'.$url.'"/>';

                        if(!empty($des)){
                            $html_img = $html_img.''.'<br/><small style="font-size:11px;color:#999999;">'.$des.'</small>';
                        }
                        $str = $str . "<div style='margin:8px 0px;'>".$html_img."</div>";
                        $i++;
                    }
                    else{
                        //gallery
                        $res = "";
                        $gallery_url = '<div class="swiper-slide"><img style="width: 100%" src="'.$url.'"/></div>';
                        $res = $res . $gallery_url;
                        $i++;
                        while($i < $len && (substr($lines[$i],0,2) == '![')){
                            $url_start = strpos($lines[$i],'(')+1;
                            $url_end = strpos($lines[$i],')');
                            $url = substr($lines[$i],$url_start,$url_end-$url_start);
                            $gallery_url = '<div class="swiper-slide"><img style="width: 100%" src="'.$url.'"/></div>';
                            $res = $res . $gallery_url;
                            $i++;
                        }
//                        $str = $str . $res;
                        $str = $str . IndexController::gallery_pre .$res.IndexController::gallery_post;
                    }
                }
                else{//poi,一般超链接,加粗体
                    $str = $str. $lines[$i];
                    $i++;
                }

            }
        }
        return $str;
    }
    const gallery_pre = '<div class="swiper-container" style="margin:8px 0px;" >
            <div class="swiper-wrapper" >';
    const gallery_post = '</div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-pagination swiper-pagination-travelid"></div>
        </div>';
}
