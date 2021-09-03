<?php
namespace Modules\Share\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 生成后台分销中心菜单
 * @author RenJianHong
 * @date 2021-07-27 10:46
 */
class ShareAuthMenuSeeder extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('auth_menu')){
            $arr = $this->defaultInfo();
            if(!empty($arr) && is_array($arr)) {
                // 删除原来已存在的菜单
                $module = config('shareconfig.module') ?? "";
                if($module != ""){
                    DB::table('auth_menu')->where("module", $module)->delete();
                }

                $this->addInfo($arr);
            }
        }
    }

    /**
     * 遍历新增菜单
     * @param array $data
     * @param int $pid
     */
    private function addInfo(array $data, $pid = 0)
    {
        foreach ($data as $item) {
            $newPid = DB::table('auth_menu')->insertGetId([
                'pid' => $item['pid'] ?? $pid,
                'href' => $item['href'],
                'title' => $item['title'],
                'icon' => $item['icon'],
                'type' => $item['type'],
                'status' => $item['status'],
                'sort' => $item['sort'] ?? 0,
                'remark' => $item['remark'],
                'target' => $item['target'],
                'createtime' => $item['createtime'],
                'module' => $item["module"],
                'menus' => $item["menus"],
            ]);
            if($newPid <= 0) break;
            if(isset($item["contents"]) && is_array($item["contents"]) && !empty($item["contents"])) $this->addInfo($item["contents"], $newPid);
        }
    }

    /**
     * 设置后台管理菜单路由信息
     * @pid 父级
     * @href 路由
     * @title 菜单标题
     * @icon 图标
     * @type int 类型 0 顶级目录 1 目录 2 菜单 3 按钮
     * @status 状态 1 正常 2 停用
     * @remark 备注
     * @target 跳转方式
     * @createtime 创建时间
     */
    private function defaultInfo()
    {
        $module = config('shareconfig.module') ?? "";
        $time = time();
        return [
            [
                "pid" => 10004,
                "href" => "",
                "title" => "分销中心",
                "icon" => 'fa fa-group',
                "type" => 1,
                "status" => 1,
                "sort" => 80,
                "remark" => "分销中心",
                "target" => "_self",
                "createtime" => $time,
                'module' => $module,
                "menus" => $module == "" ? $module : $module . "-1",
                "contents" => [
                    [   //  分销设置
                        "href" => "",
                        "title" => "分销设置",
                        "icon" => 'fa fa-gears',
                        "type" => 1,
                        "status" => 1,
                        "remark" => "分销设置",
                        "target" => "_self",
                        "sort" => 10,
                        "createtime" => $time,
                        'module' => $module,
                        "menus" => $module == "" ? $module : $module . "-2",
                        "contents" => [
                            [
                                "href" => "/admin/share/setting",
                                "title" => "基础设置",
                                "icon" => 'fa fa-gear',
                                "type" => 2,
                                "status" => 1,
                                "remark" => "基础设置",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-3",
                                "contents" => [
                                    [
                                        "href" => "/admin/share/setting",
                                        "title" => "查看编辑基础设置",
                                        "icon" => 'fa fa-window-maximize',
                                        "type" => 3,
                                        "status" => 1,
                                        "remark" => "查看编辑基础设置",
                                        "target" => "_self",
                                        "sort" => 10,
                                        "createtime" => $time,
                                        'module' => $module,
                                        "menus" => $module == "" ? $module : $module . "-4",
                                    ],
                                ]
                            ],
                            [
                                "href" => "/admin/share/commission",
                                "title" => "佣金设置",
                                "icon" => 'fa fa-gear',
                                "type" => 2,
                                "status" => 1,
                                "remark" => "佣金设置",
                                "target" => "_self",
                                "sort" => 9,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-5",
                                "contents" => [
                                    [
                                        "href" => "/admin/share/commission",
                                        "title" => "查看编辑佣金设置",
                                        "icon" => 'fa fa-window-maximize',
                                        "type" => 3,
                                        "status" => 1,
                                        "remark" => "查看编辑佣金设置",
                                        "target" => "_self",
                                        "sort" => 10,
                                        "createtime" => $time,
                                        'module' => $module,
                                        "menus" => $module == "" ? $module : $module . "-6",
                                    ],
                                ]
                            ],
                            [
                                "href" => "/admin/share/custom",
                                "title" => "自定义设置",
                                "icon" => 'fa fa-gear',
                                "type" => 2,
                                "status" => 1,
                                "remark" => "自定义设置",
                                "target" => "_self",
                                "sort" => 8,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-7",
                                "contents" => [
                                    [
                                        "href" => "/admin/share/custom",
                                        "title" => "查看自定义设置",
                                        "icon" => 'fa fa-window-maximize',
                                        "type" => 3,
                                        "status" => 1,
                                        "remark" => "查看自定义设置",
                                        "target" => "_self",
                                        "sort" => 10,
                                        "createtime" => $time,
                                        'module' => $module,
                                        "menus" => $module == "" ? $module : $module . "-8",
                                    ],
                                    [
                                        "href" => "/admin/share/editCustom",
                                        "title" => "编辑自定义设置",
                                        "icon" => 'fa fa-window-maximize',
                                        "type" => 3,
                                        "status" => 1,
                                        "remark" => "编辑自定义设置",
                                        "target" => "_self",
                                        "sort" => 10,
                                        "createtime" => $time,
                                        'module' => $module,
                                        "menus" => $module == "" ? $module : $module . "-9",
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [   //  分销用户
                        "href" => "/admin/share_user/list",
                        "title" => "分销用户",
                        "icon" => 'fa fa-child',
                        "type" => 2,
                        "status" => 1,
                        "remark" => "分销用户",
                        "target" => "_self",
                        "sort" => 9,
                        "createtime" => $time,
                        'module' => $module,
                        "menus" => $module == "" ? $module : $module . "-10",
                        "contents" => [
                            [
                                "href" => "/admin/share_user/list",
                                "title" => "查看分销用户列表",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "查看分销用户列表",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-11",
                            ],
                            [
                                "href" => "/admin/share_user/ajaxList",
                                "title" => "获取分销用户列表数据",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "获取分销用户列表数据",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-12",
                            ],
                            [
                                "href" => "/admin/share_user/delete",
                                "title" => "删除分销用户",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "删除分销用户",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-13",
                            ],
                            [
                                "href" => "/admin/share_user/batchDelete",
                                "title" => "批量删除分销用户",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "批量删除分销用户",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-14",
                            ],
                            [
                                "href" => "/admin/share_user/saveContent",
                                "title" => "添加备注",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "添加备注",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-15",
                            ],
                            [
                                "href" => "/admin/share_user/saveStatus",
                                "title" => "审核操作",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "审核操作",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-16",
                            ],
                            [
                                "href" => "/admin/share_user/getParentUser",
                                "title" => "查看下级列表",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "查看下级列表",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-17",
                            ],
                            [
                                "href" => "/admin/share_user/ajaxParentUser",
                                "title" => "异步获取下级信息",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "异步获取下级信息",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-18",
                            ],
                        ],
                    ],
                    [   //  分销订单
                        "href" => "/admin/share_order/list",
                        "title" => "分销订单",
                        "icon" => 'fa fa-align-justify',
                        "type" => 2,
                        "status" => 1,
                        "remark" => "分销订单",
                        "target" => "_self",
                        "sort" => 8,
                        "createtime" => $time,
                        'module' => $module,
                        "menus" => $module == "" ? $module : $module . "-19",
                        "contents" => [
                            [
                                "href" => "/admin/share_order/list",
                                "title" => "查看分销订单列表",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "查看分销订单列表",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-20",
                            ],
                            [
                                "href" => "/admin/share_order/ajaxList",
                                "title" => "获取分销订单列表数据",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "获取分销订单列表数据",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-21",
                            ],
                            [
                                "href" => "/admin/share_order/batchDelete",
                                "title" => "批量删除分销订单",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "批量删除分销订单",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-22",
                            ],
                        ],
                    ],
                    [   //  分销提现
                        "href" => "/admin/share_cash/list",
                        "title" => "分销提现",
                        "icon" => 'fa fa-yen',
                        "type" => 2,
                        "status" => 1,
                        "remark" => "分销提现",
                        "target" => "_self",
                        "sort" => 7,
                        "createtime" => $time,
                        'module' => $module,
                        "menus" => $module == "" ? $module : $module . "-23",
                        "contents" => [
                            [
                                "href" => "/admin/share_cash/list",
                                "title" => "查看分销提现列表",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "查看分销提现列表",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-24",
                            ],
                            [
                                "href" => "/admin/share_cash/ajaxList",
                                "title" => "获取分销提现列表数据",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "获取分销提现列表数据",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-25",
                            ],
                            [
                                "href" => "/admin/share_cash/batchDelete",
                                "title" => "批量删除提现数据",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "批量删除提现数据",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-26",
                            ],
                            [
                                "href" => "/admin/share_cash/adopt",
                                "title" => "通过提现申请",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "通过提现申请",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-27",
                            ],
                            [
                                "href" => "/admin/share_cash/reject",
                                "title" => "驳回提现申请",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "驳回提现申请",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-28",
                            ],
                            [
                                "href" => "/admin/share_cash/confirmPayment",
                                "title" => "确认打款",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "确认打款",
                                "target" => "_self",
                                "sort" => 10,
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-29",
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}