<?php
/**
 * Created By PhpStorm.
 * User: RenJianHong
 * Date: 2021-07-29 10:32
 * Fun:
 */

namespace Modules\Share\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ShareOrder extends BaseModel
{
    protected $table = "share_order";
    protected $guarded = [];
    
    /**
     * 订单状态
     */
    public function getOrderStatus($order_status)
    {
        if($order_status == 0){
            return '待支付';
        }if($order_status == 1){
            return '待发货';
        }if($order_status == 2){
            return '已发货';
        }if($order_status == 3){
            return '已完成';
        }if($order_status == 4){
            return '已取消';
        }if($order_status == 5){
            return '待评价';
        }if($order_status == 6){
            return '已评价';
        }if($order_status == 7){
            return '待售后';
        }if($order_status == 8){
            return '已退款';
        }else{
            return '其他';
        }
    }
}