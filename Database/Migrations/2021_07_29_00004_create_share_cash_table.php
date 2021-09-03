<?php
/**
 * 分销订单表
 * @author RenJianHong
 * @date 2021-7-29 10:14
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareCashTable extends Migration
{
    public $tableName = "share_cash";

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
            $table->index('user_id');//普通索引

            $table->id();
            $table->integer("user_id")->nullable(false)->default(0)->comment("用户ID"); 
            $table->decimal("price",10,2)->nullable(false)->default(0)->comment("提现金额");    
            $table->tinyInteger("status")->nullable(false)->default(0)->comment("申请状态 0--申请中 1--确认申请 2--已打款 3--驳回  5--余额通过");
            $table->timestamp("pay_time")->nullable(true)->default(null)->comment("付款时间");
            $table->tinyInteger("pay_type")->nullable(false)->default(0)->comment("支付方式 0--微信支付  1--支付宝  2--银行卡  3--余额");
            $table->string("mobile",32)->nullable(true)->default(null)->comment("支付宝账号");
            $table->string("name",32)->nullable(true)->default(null)->comment("支付宝姓名");
            $table->string("bank_name",64)->nullable(true)->default(null)->comment("开户行名称");
            $table->tinyInteger("type")->nullable(false)->default(0)->comment("打款方式 0--之前未统计的 1--微信自动打款 2--手动打款");
            $table->string("order_no",255)->nullable(true)->default(null)->comment("微信自动打款订单号");
            $table->decimal("cash_fee_rate",10,2)->nullable(false)->default(0)->comment("提现手续费比例");
            $table->decimal("cash_fee",10,2)->nullable(false)->default(0)->comment("提现手续费金额");
            $table->decimal("amount",10,2)->nullable(false)->default(0)->comment("实际到账金额");
            $table->tinyInteger("is_delete")->nullable(false)->default(0)->comment("是否删除 0--否 1--是");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE zzkj_{$this->tableName} comment '分销提现表'");
    }
}
