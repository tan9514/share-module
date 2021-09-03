<?php

/**
 * 分销中心
 * @author renjianhong
 * @date 2021-7-27 16:47
 */
namespace Modules\Share\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Share\Http\Controllers\Controller;
use Modules\Share\Entities\ShareSetting;
use Modules\Share\Entities\Option;


class ShareController extends Controller
{
    
    /**
     * 分销设置查看编辑
     */
    public function setting(ShareSetting $ShareSettingModel, Request $request)
    {
        if($request->isMethod('post')) {
            $data = $request->post();
            if(array_key_exists('file',$data)){ //检查数组中此键名是否存在
                unset($data['file']);//删除键名及其值
            }
            
            if(!$data['id']){
                $reulst = $ShareSettingModel->insertGetId($data);
            }else{
                $reulst = $ShareSettingModel->where('id',$data['id'])->update($data);
            }
            if($reulst){
                return $this->success("操作成功");
            }else{
                return $this->failed("操作失败");
            }
            
        }
        
        $info = $ShareSettingModel->where("id",1)->first();
        if($info){
            $is_domain = $ShareSettingModel->getIsDomain($info->share_image);
            if($is_domain){
                $info->share_image_show = $info->share_image;
            }else{
                $info->share_image_show = $ShareSettingModel->getShowImage($info->share_image);
            }
            $pay_type_arr = explode(',',$info->pay_type);
            $pay_type_arr = json_encode($pay_type_arr,true);
        }
        return view('shareview::admin.share.setting', compact('info','ShareSettingModel','pay_type_arr'));
    }

    /**
     * 分销佣金设置
     */
    public function commission(ShareSetting $ShareSettingModel, Request $request)
    {
        if($request->isMethod('post')) {
            $data = $request->post();
            
            if(!$data['id']){
                $reulst = $ShareSettingModel->insertGetId($data);
            }else{
                $reulst = $ShareSettingModel->where('id',$data['id'])->update($data);
            }
            if($reulst){
                return $this->success("操作成功");
            }else{
                return $this->failed("操作失败");
            }
            
        }
        $info = $ShareSettingModel->where("id",1)->first();
        
        return view('shareview::admin.share.commission', compact('info','ShareSettingModel'));
    }
    
    /**
     * 分销自定义设置
     */
    public function custom(Option $OptionModel)
    {
        $option = $OptionModel->where('name','share_custom_data')->first();
        
        if($option){
            $json_str = json_decode($option->value,true);
            $info = $OptionModel->jsonToArray($json_str);
        }else{
            $info = $OptionModel->getDefaultData();
        }
        
        return view('shareview::admin.share.custom', compact('info'));
    }

    /**
     * 编辑分销自定义
     */
    public function editCustom(Request $request, Option $OptionModel)
    {
        if($request->isMethod('post')) {
            $input = $request->post();
            $data = json_encode($input);
            $option = $OptionModel->where('name','share_custom_data')->first();
            if(!$option){
                $param['group'] = 'share';
                $param['name'] = 'share_custom_data';
                $param['value'] = $data;
                $reulst = $OptionModel->insert($param);
            }else{
                $param['value'] = $data;
                $reulst = $OptionModel->where('name','share_custom_data')->update($param);
            }
            if($reulst){
                return $this->success("操作成功");
            }else{
                return $this->failed("操作失败");
            }
        }else{
            return $this->failed("请求错误");
        }
    }

    
}
