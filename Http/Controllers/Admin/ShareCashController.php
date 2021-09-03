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
use Modules\Share\Entities\ShareCash;


class ShareCashController extends Controller
{
    
    /**
     * 分销提现列表
     */
    public function list()
    {
        return view('shareview::admin.share_cash.list');
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request, ShareCash $ShareCashModel)
    {
        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $nickname = $request->input('nickname');
        $where = [
            ['users.nickname', 'like', '%'.$nickname.'%'],
            ['share_cash.is_delete', '=', '0'],
        ];
        
        //获取总条数
        $count = $ShareCashModel
                ->leftJoin('users', 'users.id', '=', 'share_cash.user_id')
                ->where($where)
                ->count();
        
        //求偏移量
        $offset = ($page-1)*$pagesize;
        $list = $ShareCashModel
                ->leftJoin('users', 'users.id', '=', 'share_cash.user_id')   
                ->where($where)
                ->select('share_cash.*','users.nickname','users.avatar')
                ->orderByDesc('id')->offset($offset)->limit($pagesize)->get();
        foreach($list as $key => $v){
            $is_domain = $ShareCashModel->getIsDomain($v['avatar']);
            if($is_domain){
                $list[$key]['avatar'] = $v['avatar'];
            }else{
                $list[$key]['avatar'] = $ShareCashModel->getShowImage($v['avatar']);
            }
        }
        
        return $this->success(compact('list', 'count'));
    }

    /**
     * 批量删除
     */
    public function batchDelete(Request $request, ShareCash $ShareCashModel){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id){
                $reulst = $ShareCashModel->whereIn('id',$id)->update(['is_delete'=>1]);
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
    
    /**
     * 通过提现申请
     */
    public function adopt(Request $request, ShareCash $ShareCashModel)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id){
                $reulst = $ShareCashModel->where('id',$id)->update(['status'=>1]);
                if($reulst){
                    return $this->success();
                }else{
                    return $this->failed('操作失败');
                }
            }else{
                return $this->failed('缺少参数id');
            }
        }
    }

    /**
     * 驳回提现申请
     */
    public function reject(Request $request, ShareCash $ShareCashModel)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id){
                $reulst = $ShareCashModel->where('id',$id)->update(['status'=>3]);
                if($reulst){
                    return $this->success();
                }else{
                    return $this->failed('操作失败');
                }
            }else{
                return $this->failed('缺少参数id');
            }
        }
    }

    /**
     * @param int $id
     * @param int $status
     * @return mixed|string
     * 确认打款 $status 1--线上微信自动打款 2--线下转账
     * 支付未做
     */
    public function confirmPayment(Request $request, ShareCash $ShareCashModel)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $status = $request->input('status');
            $cash = $ShareCashModel->where([
                ['id', '=', $id],
                ['is_delete', '=', 0],
            ])->first();
            
            if (!$cash) {
                return $this->failed('提现记录不存在');
            }
            if (!$cash->order_no) {
                $order_no = date('YmdHis') . mt_rand(100000, 999999);
                $cash->order_no = $order_no;
                $cash->save();
            }
            if ($status == 1) {  //微信自动打款
                $cash->status = 2;
                $cash->pay_time = date('Y-m-d H:i:s');
                $cash->type = 1;
                return $this->failed('暂未开发');
            } elseif ($status == 2) { //手动打款
                $cash->status = 2;
                $cash->pay_time = date('Y-m-d H:i:s');
                $cash->type = 2;
                if ($cash->pay_type == 3) {
                    $user = User::where('id', $cash->user_id)->first();
                    $user->money += doubleval($cash->amount);
                    if(!$user->save()){
                        return $this->failed('打款失败');
                    }
                }
                $cash->save();
                return $this->success();
            }
            
        }else{
            return $this->failed('请求错误');
        }
        
        
    }
    
}
