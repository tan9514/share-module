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

class ShareUser extends BaseModel
{
    protected $table = "share_user";
    protected $guarded = [];
    
}