<?php

/**
 * 分销用户
 * @author renjianhong
 * @date 2021-7-27 16:47
 */
namespace Modules\Share\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Share\Http\Controllers\Controller;
use Modules\Share\Entities\ShareUser;
use Modules\Share\Entities\User;
use Modules\Share\Entities\ShareUserParent;


class ShareUserController extends Controller
{
    
    /**
     * 分销用户列表
     */
    public function list()
    { 
        return view('shareview::admin.share_user.list');
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request, ShareUser $ShareUserModel)
    {
        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $nickname = $request->input('nickname');
        $where = [
            ['users.nickname', 'like', '%'.$nickname.'%'],
            ['share_user.is_delete', '=', '0'],
        ];
        
        //获取总条数
        $count = $ShareUserModel
                ->leftJoin('users', 'users.id', '=', 'share_user.user_id')
                ->leftJoin('share_user_parent', 'share_user_parent.user_id', '=', 'share_user.user_id')
                ->where($where)
                ->count();
        
        //求偏移量
        $offset = ($page-1)*$pagesize;
        $list = $ShareUserModel
                ->leftJoin('users', 'users.id', '=', 'share_user.user_id')   
                ->leftJoin('share_user_parent', 'share_user_parent.user_id', '=', 'share_user.user_id')     
                ->where($where)
                ->select('share_user.*','users.nickname','users.avatar','share_user_parent.parent_id_1')
                ->orderByDesc('id')->offset($offset)->limit($pagesize)->get();
        foreach($list as $key => $v){
            $is_domain = $ShareUserModel->getIsDomain($v['avatar']);
            if($is_domain){
                $list[$key]['avatar'] = $v['avatar'];
            }else{
                $list[$key]['avatar'] = $ShareUserModel->getShowImage($v['avatar']);
            }
            
            $list[$key]['parent_name'] = User::where('id',$v['parent_id_1'])->value('nickname');
            $list[$key]['parent_count_1'] = ShareUserParent::where('parent_id_1',$v['user_id'])->count();
            $list[$key]['parent_count_2'] = ShareUserParent::where('parent_id_2',$v['user_id'])->count();
        }
        
        return $this->success(compact('list', 'count'));
    }

    /**
     * 删除分销商
     */
    public function delete(Request $request, ShareUser $ShareUserModel){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id){
                $reulst = $ShareUserModel->where('id',$id)->update(['is_delete'=>1]);
                if($reulst){
                    return $this->success('操作成功');
                }else{
                    return $this->failed('操作失败');
                }
            }else{
                return $this->failed('缺少参数id');
            }
        }
    }

    /**
     * 批量删除
     */
    public function batchDelete(Request $request, ShareUser $ShareUserModel){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id){
                $reulst = $ShareUserModel->whereIn('id',$id)->update(['is_delete'=>1]);
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
     * 添加备注
     */
    public function saveContent(Request $request, ShareUser $ShareUserModel)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $content = $request->input('content');
            if($id){
                $reulst = $ShareUserModel->where('id',$id)->update(['content'=>$content]);
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
     * 审核
     */
    public function saveStatus(Request $request, ShareUser $ShareUserModel)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $status = $request->input('status');
            if($id){
                $reulst = $ShareUserModel->where('id',$id)->update(['status'=>$status]);
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
     * 下级列表
     */
    public function getParentUser(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->input('user_id');

        return view('shareview::admin.share_user.parent_user',compact('type','user_id'));
    }

    /**
     * 获取下级数据
     */
    public function ajaxParentUser(Request $request, ShareUserParent $ShareUserParentModel)
    {
        $type = $request->input('type');
        $user_id = $request->input('user_id');

        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $nickname = $request->input('nickname');
        $where = [
            ['users.nickname', 'like', '%'.$nickname.'%'],
        ];
        //求偏移量
        $offset = ($page-1)*$pagesize;

        if($type == 1){
            //一级用户
            $where[] = ['share_user_parent.parent_id_1','=',$user_id];
        }else if($type == 2){
            //二级用户
            $where[] = ['share_user_parent.parent_id_2','=',$user_id];
        }
        
        $count = $ShareUserParentModel
            ->leftJoin('users', 'users.id', '=', 'share_user_parent.user_id')
            ->where($where)
            ->count();
    
        $list = $ShareUserParentModel
            ->leftJoin('users', 'users.id', '=', 'share_user_parent.user_id')   
            ->where($where)
            ->select('users.*')
            ->orderByDesc('id')->offset($offset)->limit($pagesize)->get();
        foreach($list as $key => $v){
            if($type == 1){
                $list[$key]['parent_name'] = '一级';
            }else if($type == 2){
                $list[$key]['parent_name'] = '二级';
            }
            $is_domain = $ShareUserParentModel->getIsDomain($v['avatar']);
            if($is_domain){
                $list[$key]['avatar'] = $v['avatar'];
            }else{
                $list[$key]['avatar'] = $ShareUserParentModel->getShowImage($v['avatar']);
            }
        }
        return $this->success(compact('list', 'count'));
    }

    
}
