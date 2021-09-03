<?php

/**
 * 分销提现
 * @author renjianhong
 * @date 2021-7-27 16:47
 */
namespace Modules\Share\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Share\Http\Controllers\Controller;
use Modules\Share\Entities\ShareUser;
use Modules\Share\Entities\User;
use Modules\Share\Entities\ShareOrder;


class ShareOrderController extends Controller
{
    
    /**
     * 分销提现列表
     */
    public function list()
    {
        return view('shareview::admin.share_order.list');
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request, ShareOrder $ShareOrderModel)
    {
        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $nickname = $request->input('nickname');
        $order_no = $request->input('order_no');
        $where = [
            ['users.nickname', 'like', '%'.$nickname.'%'],
            ['share_order.is_delete', '=', '0'],
        ];
        if($order_no){
            $where[] = ['order.order_no', '=' , $order_no];
        }
        //获取总条数
        $count = $ShareOrderModel
                ->leftJoin('order', 'order.id', '=', 'share_order.order_id')
                ->leftJoin('users', 'users.id', '=', 'share_order.user_id')
                ->where($where)
                ->count();
        
        //求偏移量
        $offset = ($page-1)*$pagesize;
        $list = $ShareOrderModel
                ->leftJoin('order', 'order.id', '=', 'share_order.order_id')
                ->leftJoin('users', 'users.id', '=', 'share_order.user_id')   
                ->where($where)
                ->select('share_order.*','users.nickname','users.avatar','order.order_no','order.pay_price','order.total_price','order.order_status','order.pay_time')
                ->orderByDesc('id')->offset($offset)->limit($pagesize)->get();
        foreach($list as $key => $v){
            $is_domain = $ShareOrderModel->getIsDomain($v['avatar']);
            if($is_domain){
                $list[$key]['avatar'] = $v['avatar'];
            }else{
                $list[$key]['avatar'] = $ShareOrderModel->getShowImage($v['avatar']);
            }
            $list[$key]['status'] = $ShareOrderModel->getOrderStatus($v['order_status']);
        }
        
        return $this->success(compact('list', 'count'));
    }

    /**
     * 批量删除
     */
    public function batchDelete(Request $request, ShareOrder $ShareOrderModel){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id){
                $reulst = $ShareOrderModel->whereIn('id',$id)->update(['is_delete'=>1]);
                if($reulst){
                    return $this->success('操作成功');
                }else{
                    return $this->failed('操作失败');
                }
            }else{
                return $this->failed('请勾选要删除的数据');
            }
            
        }
    }
    
}
