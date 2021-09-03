<?php
/**
 * 分销基础设置表
 * @author RenJianHong
 * @date 2021-7-27 9:28
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareSettingTable extends Migration
{
    public $tableName = "share_setting";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableName)) $this->create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }

    /**
     * 执行创建表
     */
    private function create()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';      // 设置存储引擎
            $table->charset = 'utf8';       // 设置字符集
            $table->collation  = 'utf8_general_ci';       // 设置排序规则
            $table->index('id');//普通索引

            $table->id();
            $table->tinyInteger("level")->nullable(false)->default(0)->comment("分销层级");
            $table->tinyInteger("share_type")->nullable(false)->default(0)->comment("分销模式 0--普通模式");
            $table->tinyInteger("price_type")->nullable(false)->default(0)->comment("分销方式 0--百分比金额 1固定金额");
            $table->string("first_name")->nullable(true)->default(null)->comment("一级分销名称");
            $table->decimal("first",10,2)->nullable(false)->default("0.00")->comment("一级分销");
            $table->string("second_name")->nullable(true)->default(null)->comment("二级分销名称");
            $table->decimal("second",10,2)->nullable(false)->default("0.00")->comment("二级分销");
            $table->decimal("rebate",10,2)->nullable(false)->default("0.00")->comment("自购返利");
            
            $table->tinyInteger("share_condition")->nullable(false)->default(0)->comment("成为分销商条件 0--无条件（需要审核）1--申请（需要审核）2--无需审核");
            $table->tinyInteger("is_rebate")->nullable(false)->default(0)->comment("是否开启自购返利 0--关闭 1--开启");
            $table->string("share_image")->nullable(true)->default(null)->comment("推广海报背景图");
            $table->string("pay_type")->nullable(false)->default(0)->comment("提现方式 0--微信 1--支付宝 2--银行卡 3--余额");
            $table->decimal("min_money",10,2)->nullable(false)->default("0.00")->comment("最少提现额度");
            $table->decimal("cash_max_day",10,2)->nullable(false)->default("0.00")->comment("每日提现上限 0--不限制");
            $table->decimal("cash_fee_rate",10,2)->nullable(false)->default("0.00")->comment("提现手续费");
            $table->longText("content")->nullable(true)->comment("用户提现须知");
            $table->longText("agree")->nullable(true)->comment("分销申请协议");
            $table->timestamps();
            
        });
        DB::statement("ALTER TABLE zzkj_{$this->tableName} comment '分销设置表'");
    }
}
