<?php
/**
 * 分销订单表
 * @author RenJianHong
 * @date 2021-7-29 10:14
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareOrderTable extends Migration
{
    public $tableName = "share_order";

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
            $table->index('order_id');//普通索引
            $table->index('user_id');//普通索引

            $table->id();
            $table->tinyInteger("type")->nullable(false)->default(0)->comment("类型 0--普通订单 1--预约订单 2--拼团订单");
            $table->integer("order_id")->nullable(false)->default(0)->comment("订单ID");
            $table->integer("parent_id_1")->nullable(false)->default(0)->comment("一级分销商ID");
            $table->integer("parent_id_2")->nullable(false)->default(0)->comment("二级分销商ID");
            $table->decimal("first_price",10,2)->nullable(false)->default(0)->comment("一级分销佣金");
            $table->decimal("second_price",10,2)->nullable(false)->default(0)->comment("二级分销佣金");
            $table->decimal("rebate",10,2)->nullable(false)->default(0)->comment("自购返利");    
            $table->integer("user_id")->nullable(false)->default(0)->comment("下单用户ID"); 
            $table->tinyInteger("is_delete")->nullable(false)->default(0)->comment("是否删除 0--否 1--是");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE zzkj_{$this->tableName} comment '分销订单表'");
    }
}
