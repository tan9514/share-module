<?php
/**
 * 分销用户表
 * @author RenJianHong
 * @date 2021-7-29 10::13
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareUserParentTable extends Migration
{
    public $tableName = "share_user_parent";

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
            
            $table->integer("parent_id_1")->nullable(false)->default(0)->comment("一级ID");
            $table->integer("parent_id_2")->nullable(false)->default(0)->comment("二级ID");
            $table->longText("relation")->nullable(true)->comment("关系链");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE zzkj_{$this->tableName} comment '推荐关系链表'");
    }
}
