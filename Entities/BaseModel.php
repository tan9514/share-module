<?php
/**
 * Created By PhpStorm.
 * User: RenJianHong
 * Date: 2021-07-27 10:30
 * Fun:
 */

namespace Modules\Share\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    /**
     * 图片添加域名
     * @return string
     */
    public function getShowImage($upload_image)
    {
        if($upload_image == "") return "";

        $ht = env('APP_URL') ?? "";
        if($ht == ""){
            $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
            $ht = $http_type . $_SERVER["HTTP_HOST"];
        }
        return $ht . "/" . $upload_image;
    }

    /**
     * 图片是否带有域名
     * @return 
     */
    public function getIsDomain($url)
    {
        if(preg_match("/^(http:\/\/|https:\/\/).*$/",$url)){
            return true;
        }else{
            return false;
        }
    }

}