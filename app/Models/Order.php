<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Order extends Eloquent
{
    //
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $collection = 'order_collection';


    const STATUS_INIT = 0;
    /**
     * id,
     * customer-id,
     * hotel-id,
     * room-id,
     * orderStatus:
     *
     * 订单初始(未付款,等待用户付定金)
     * 已付定金(等待酒店确认)
     * 待付尾款(等待用户付款)
     * 已付尾款(等待酒店预订)
     * 预订成功(待使用)
     * 已使用(订单完结,用户完成入住)
     * 申请退款(等待酒店确认)
     * 已退款(订单完结)
     * 无效(订单完结,用户未付定金,超时,系统自动设置无效)
     * ?(用户部分付款,但是未付尾款,超时,系统自动退款或者pending)
     *
     * hotel-info:
     * plan-info:
     * book-info: can update by master
     * price-info:
     *      - total_price
     *      - pre_price
     *      - left_price
     *
     * custom-info:
     *
     * //付完定金产生的字段
     * order-id = user_id+timestamp
     * contact-info = {}
     *
     *
     * search keys =
     * hotel-name
     * phones
     * brand
     * checkin
     * checkout
     * status
     */
}
