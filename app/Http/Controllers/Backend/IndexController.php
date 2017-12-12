<?php

namespace App\Http\Controllers\Backend;

use App\Models\Hotel;
use App\Models\Master;
use App\Models\ZEvent;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth as BAuth;

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
            return redirect()->intended('/hotel_list');
        }
        else{
            return Redirect::to('/zhotel/ss/login');
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
        if($dup){
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
    public function getHotelList(Request $request){
        $res = Hotel::paginate(20);
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, "");
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
        ZEvent::log(self::getCurrentMaster(), "query", __METHOD__, $keyword);
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
                "obj"=>$res
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
                $all_price = ceil($basic_price * (int)$item["obj"]["z"] / 100);
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
                $all_price = ceil($basic_price * (int)$item["obj"]["z"] / 100);
            }

            //有效优惠
            array_push($ans_plans,[
                "name"=>$item["name"],
                "price"=>$all_price,
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

        if($find == 2){
            return [
                "basic" => $ans,
                "plans" => $ans_plans,
                "cancellation" => isset($plan["cancellation"]) ? $plan["cancellation"] : "",
                "include" => isset($plan["include"]) ? $plan["include"] : "",
                "memo" => isset($plan["memo"]) ? $plan["memo"] : "",
            ];
        }
        else{
            return null;
        }
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
