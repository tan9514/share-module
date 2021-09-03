<?php
/**
 * Created By PhpStorm.
 * User: RenJianHong
 * Date: 2021-07-27 10:32
 * Fun:
 */

namespace Modules\Share\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ShareSetting extends BaseModel
{
    protected $table = "share_setting";
    protected $guarded = [];

    /**
     * 设置分销层级数组值
     * @return string[]
     */
    public static function getLevelArr()
    {
        return [
            0 => "不开启",
            1 => "一级分销",
            2 => "二级分销",
        ];
    }

    /**
     * 成为分销商条件数组值
     * @return string[]
     */
    public static function getShareConditionArr()
    {
        return [
            0 => "无条件（需要审核）",
            1 => "申请（需要审核）",
            2 => "无需审核",
        ];
    }

    /**
     * 成为分销商条件数组值
     * @return string[]
     */
    public static function getIsRebateArr()
    {
        return [
            0 => "关闭",
            1 => "开启",
        ];
    }

    /**
     * 分销玩法模式
     * @return string[]
     */
    public static function getShareType()
    {
        return [
            0 => "普通模式",
            //1 => "总代模式",
        ];
    }

    /**
     * 佣金发放方式
     * @return string[]
     */
    public static function getPriceType()
    {
        return [
            0 => "百分比金额",
            1 => "固定金额",
        ];
    }
    
}