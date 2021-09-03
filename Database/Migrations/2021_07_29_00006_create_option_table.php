<?php
/**
 * 分销基础设置表
 * @author RenJianHong
 * @date 2021-7-27 9:28
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionTable extends Migration
{
    public $tableName = "option";

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
            $table->string("group")->nullable(false)->default(null)->comment("分组标识");
            $table->string("name")->nullable(false)->default(null)->comment("名称");
            $table->longText("value")->nullable(false)->comment("内容");
            $table->timestamps();
            
        });
        DB::statement("ALTER TABLE zzkj_{$this->tableName} comment '分销自定义设置表'");
    }
}
