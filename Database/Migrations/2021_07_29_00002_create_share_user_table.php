<?php
/**
 * 分销用户表
 * @author RenJianHong
 * @date 2021-7-29 10::13
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareUserTable extends Migration
{
    public $tableName = "share_user";

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
            $table->string("mobile",32)->nullable(true)->default(null)->comment("手机号码");
            $table->string("name",32)->nullable(true)->default(null)->comment("用户名");
            $table->tinyInteger("status")->nullable(false)->default(0)->comment("审核状态 0--未审核 1--审核通过 2--审核不通过");
            $table->tinyInteger("is_delete")->nullable(false)->default(0)->comment("是否删除 0--否 1--是");
            $table->longText("content")->nullable(true)->comment("备注");
            $table->decimal("total_price",10,2)->nullable(false)->default("0.00")->comment("累计佣金");
            $table->decimal("price",10,2)->nullable(false)->default("0.00")->comment("可提现佣金");

            $table->timestamps();
        });
        DB::statement("ALTER TABLE zzkj_{$this->tableName} comment '分销用户表'");
    }
}
