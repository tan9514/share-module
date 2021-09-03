<?php
/**
 * Created By PhpStorm.
 * User: RenJianHong
 * Date: 2021-08-01 10:32
 */

namespace Modules\Share\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Option extends BaseModel
{
    protected $table = "option";
    protected $guarded = [];
    
    /**
     * json对象转数组
     * @return $json_arr
     */
    public function jsonToArray($json_str)
    {
        $json_str1 = $json_str;
        if (is_array($json_str) || is_object($json_str)) {
            $json_str = $json_str1;
        } else if (is_null(json_decode($json_str))) {
            $json_str = $json_str1;
        } else {
            $json_str = strval($json_str);
            $json_str = json_decode($json_str, true);
        }
        $json_arr = array();
        foreach ($json_str as $k => $w) {
            if (is_object($w)) {
                $json_arr[$k] = $this->jsonToArray($w); //判断类型是不是object
            } else if (is_array($w)) {
                $json_arr[$k] = $this->jsonToArray($w);
            } else {
                $json_arr[$k] = $w;
            }
        }
        return $json_arr;
    }

    /**
     * 自定义内容
     */
    public function getDefaultData()
    {
        return [
            'menus' => [
                'money'=>[
                    'name' => '分销佣金',
                    'icon' => 'layuimini/images/share-custom/img-share-price.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-money/share-money',
                    'tel' => '',
                ],
                'order'=>[
                    'name' => '分销订单',
                    'icon' => 'layuimini/images/share-custom/img-share-order.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-order/share-order',
                    'tel' => '',
                ],
                'cash'=>[
                    'name' => '提现明细',
                    'icon' => 'layuimini/images/share-custom/img-share-cash.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/cash-detail/cash-detail',
                    'tel' => '',
                ],
                'team'=>[
                    'name' => '我的团队',
                    'icon' => 'layuimini/images/share-custom/img-share-team.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-team/share-team',
                    'tel' => '',
                ],
                'qrcode'=>[
                    'name' => '推广二维码',
                    'icon' => 'layuimini/images/share-custom/img-share-qrcode.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-qrcode/share-qrcode',
                    'tel' => '',
                ],
            ],
            'words' => [
                'can_be_presented'=>[
                    'name' => '可提现佣金',
                    'default' => '可提现佣金',
                ],
                'already_presented'=>[
                    'name' => '已提现佣金',
                    'default' => '已提现佣金',
                ],
                'parent_name'=>[
                    'name' => '推荐人',
                    'default' => '推荐人',
                ],
                'pending_money'=>[
                    'name' => '待打款佣金',
                    'default' => '待打款佣金',
                ],
                'cash'=>[
                    'name' => '提现',
                    'default' => '提现',
                ],
                'user_instructions'=>[
                    'name' => '用户须知',
                    'default' => '用户须知',
                ],
                'apply_cash'=>[
                    'name' => '我要提现',
                    'default' => '我要提现',
                ],
                'cash_type'=>[
                    'name' => '提现方式',
                    'default' => '提现方式',
                ],
                'cash_money'=>[
                    'name' => '提现金额',
                    'default' => '提现金额',
                ],
                'order_money_un'=>[
                    'name' => '未结算佣金',
                    'default' => '未结算佣金',
                ],
            ]
        ];
    }
}