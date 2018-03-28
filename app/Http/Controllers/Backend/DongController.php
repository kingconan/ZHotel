<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class DongController extends Controller
{
    //
    public function post($url,$body){
        $headers = [
            ":authority" => "36dong.com",
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
    public function post2($url,$body){
        $headers = [
            ":authority" => "36dong.com",
            "accept" => "application/json, text/plain, */*",
            "accept-encoding" => "gzip, deflate",
            "accept-language" => "zh-CN,zh;q=0.8,en;q=0.6",
            "content-type" => "application/x-www-form-urlencoded",
            "x-requested-with"=>"XMLHttpRequest",
            "referer"=>"https://36dong.com/supplierAdmin",
            "cookie" => "loginAuth=197%3A1522852947%3A424c1bfd038a97c614ac456e53a7c9db; PHPSESSID=9aplfcj9qrjc3eufppavhunkv1; Hm_lvt_34bd17080e7fe077493dccb85ee0c1e6=1521796797; Hm_lpvt_34bd17080e7fe077493dccb85ee0c1e6=1521796797",
            "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36"
        ];
        $client = new Client();
        $response = $client->request("post",$url,[
                'form_params' => $body,
                'headers' => $headers
            ]
        );
        return $response;
    }
    //
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
    public function getHotelDetail(Request $request){
        $id = $request->input("id");
        $url = 'https://36dong.com/gdsHotel/detail/'.$id;
        $res = self::post($url,json_encode(""));
        $json = ($res->getBody());
        return $json;
    }
    public function getPricesOfRoom(Request $request){
        $contractId = $request->input("contractId");
        $roomId = $request->input("roomId");
        $url = 'https://36dong.com/hotel/searchPrice';
        $res = self::post2($url,[
            "contractId" => $contractId,
            "roomId" => $roomId
        ]);
        $json = ($res->getBody());
        return $json;
    }
    public function getContractInfo($id, Request $request){
        $url = 'https://36dong.com/hotel/priceList/'.$id;
        $res = self::get($url);
        $html = $res->getBody();
        $dom = HtmlDomParser::str_get_html($html);
        $tab = $dom->find("div[class=list-tab-rooms]");

        $arr_room = [];
        $arr_contract = [];
        $title = "";
        $rate = "";
        $script = "";
        $titleD = $dom->find("h1[class=hotle-title]");

        if($titleD){
            $title = trim($titleD[0]->text());
        }
        $rateD = $dom->find("div[class=rate]");

        if($rateD){
            $rate = trim($rateD[0]->text());
        }
        if($tab){

            $li = $tab[0]->find("li");

            foreach($li as $e){
                $id = $e->data;
                $name = $e->text();
                array_push($arr_room,[
                    "id"  => $id,
                    "name" => trim($name),
                    "date" => []
                ]);
            }

        }

        $contracts_root = $dom->find("div[class=list-tab]");
        if($contracts_root){
            $li = $contracts_root[0]->find("li");
            if($li && !empty($li)){
                foreach($li as $e){
                    $id = $e->data;
                    $name = $e->text();
                    array_push($arr_contract,[
                        "id"  => $id,
                        "name" => $name,
                        "rooms" => $arr_room
                    ]);
                }
            }
            else{
                $scripts = $dom->find('script');
                foreach($scripts as $s) {
                    if(strpos($s->innertext, 'currentContractId') !== false) {
                        $script = $s->innertext;
                        $p1 = strpos($script, 'currentContractId');
                        $p3 = strpos($script, ";", $p1);
                        $p2 = strpos($script, "=", $p1) + 1;
                        $str = substr($script,$p2, $p3-$p2);
                        array_push($arr_contract,[
                            "id"  => trim($str),
                            "name" => "默认合同",
                            "rooms" => $arr_room
                        ]);
                    }
                }
            }
        }


        return response()->json([
            "ok" => 0,
            "obj" => [
                "hotel_name" => $title,
                "rate" => $rate,
                "contract_ids" => $arr_contract,
                "contract_room" => $arr_room,
            ],
            "msg" => "ok"
        ]);
    }
}
