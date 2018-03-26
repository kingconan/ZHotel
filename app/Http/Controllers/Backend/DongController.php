<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class DongController extends Controller
{
    //
    public function post($url,$body){
        $headers = [
            "accept" => "application/json, text/plain, */*",
            "accept-encoding" => "gzip, deflate",
            "accept-language" => "zh-CN,zh;q=0.8,en;q=0.6",
            "content-type" => "application/json;charset=UTF-8",
            "x-requested-with"=>"XMLHttpRequest",
            "referer"=>"https://36dong.com/supplierAdmin",
            "cookie" => "loginAuth=197%3A1522852947%3A424c1bfd038a97c614ac456e53a7c9db; PHPSESSID=9aplfcj9qrjc3eufppavhunkv1; Hm_lvt_34bd17080e7fe077493dccb85ee0c1e6=1521796797; Hm_lpvt_34bd17080e7fe077493dccb85ee0c1e6=1521796797",
            "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36"
        ];
        $client = new Client();
        $response = $client->request("post",$url,[
            'body' => $body,
                'headers' => $headers
            ]
        );
        return $response;
    }
    public function get($url){
        $headers = [
            "accept" => "application/json, text/plain, */*",
            "accept-encoding" => "gzip, deflate",
            "accept-language" => "zh-CN,zh;q=0.8,en;q=0.6",
            "content-type" => "application/json;charset=UTF-8",
            "x-requested-with"=>"XMLHttpRequest",
            "referer"=>"https://36dong.com/supplierAdmin",
            "cookie" => "loginAuth=197%3A1522852947%3A424c1bfd038a97c614ac456e53a7c9db; PHPSESSID=9aplfcj9qrjc3eufppavhunkv1; Hm_lvt_34bd17080e7fe077493dccb85ee0c1e6=1521796797; Hm_lpvt_34bd17080e7fe077493dccb85ee0c1e6=1521796797",
            "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36"
        ];
        $client = new Client();
        $response = $client->request("get",$url,[
                'headers' => $headers
            ]
        );
        return $response;
    }

    public function test(Request $request){
        $url = "https://36dong.com/gdsHotel/search";
        $body = '{"areaId":"4684","checkoutDate":"2018-06-21","checkinDate":"2018-06-18","page":1,"perPage":15}';
        $res = self::post($url, $body);
        $json = ($res->getBody());
        return $json;
    }
    public function getAreaName(Request $request){
        $id = $request->input("id");
        $url = "https://36dong.com/gdsHotel/areaInfo/".$id;
        $res = self::get($url);
        $json = ($res->getBody());
        return $json;
    }
    public function getAreaHotels(Request $request){
        $page = $request->input("page");
        $checkin = $request->input("checkin");
        $checkout = $request->input("checkout");
        $areaId = $request->input("areaId");
        $body = [
          "areaId" => $areaId,
            "checkinDate" => $checkin,
            "checkoutDate" => $checkout,
            "page" => $page,
            "perPage" => 15
        ];
        $url = "https://36dong.com/gdsHotel/search";
        $res = self::post($url,json_encode($body));
        $json = ($res->getBody());
        return $json;
    }
    public function getRatePlanList(Request $request){
        $checkin = $request->input("checkin");
        $checkout = $request->input("checkout");
        $id = $request->input("id");

        $urlFormat = 'https://36dong.com/gdsHotel/ratePlanList?checkinDate=%s&checkoutDate=%s&nextResultReferenceCode=%s&no=%s';
        $url = sprintf($urlFormat,$checkin,$checkout,"",$id);
        $res = self::get($url);
        $json = ($res->getBody());
        return $json;
    }
    public function getRatePanDetail(Request $request){
        $base = $request->input("base");
        $checkin = $request->input("checkin");
        $checkout = $request->input("checkout");
        $hotelId = $request->input("hotelId");
        $planId = $request->input("planId");
        $ratePlanType = $request->input("ratePlanType");
        $urlFormat = 'https://36dong.com/gdsHotel/rateRule?base=%s&checkinDate=%s&checkoutDate=%s&no=%s&rateNo=%s&ratePlanType=%s';
        $url = sprintf($urlFormat,$base,$checkin,$checkout,$hotelId,$planId,$ratePlanType);
        $res = self::get($url);
        $json = ($res->getBody());
        return $json;
    }
}
