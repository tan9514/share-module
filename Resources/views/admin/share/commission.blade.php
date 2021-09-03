@extends('admin.public.header')
@section('title','佣金设置')
@section('listcontent')
    <form class="layui-form">
    <div class="layui-form layuimini-form">
       
        <input type="hidden" name="id" value="{{$info->id ?? 0}}" />
        
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>佣金设置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">分销玩法</label>
            <div class="layui-input-block">
                @foreach($ShareSettingModel->getShareType() as $k=>$v)
                    <input type="radio" name="share_type" value="{{$k}}" title="{{$v}}" @if($k == ($info->share_type ?? 0))checked="" @elseif(!isset($info->share_type) && $k==0)checked="" @endif>
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分销佣金类型</label>
            <div class="layui-input-block">
                @foreach($ShareSettingModel->getPriceType() as $k=>$v)
                    <input type="radio" name="price_type" lay-filter="price_type" value="{{$k}}" title="{{$v}}" @if($k == ($info->price_type ?? 0))checked="" @elseif(!isset($info->price_type) && $k==0)checked="" @endif>
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">自购返利佣金</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="number" name="rebate" lay-verify="required" lay-reqtext="自购返利佣金不能为空" placeholder="请输入自购返利佣金" value="{{$info->rebate ?? 0}}" class="layui-input" />
                <tip>需要去<a href="javascript:;" layuimini-content-href="{{('/admin/share/setting')}}" data-title="基础设置">分销中心->基础设置</a>开启分销内购才可使用，自购返利发放佣金金额。</tip>
                
                <button style="display: inline-block;position: absolute;right: 0px;margin-top: -38px;" type="button" class="layui-btn layui-btn-disabled bt">{{isset($info->price_type) ? '元' : '%'}}</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">一级名称</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="text" name="first_name" placeholder="请输入一级名称" value="{{$info->first_name ?? ''}}" class="layui-input" />
                <tip>分销一级名称！</tip>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">一级佣金</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="number" name="first" lay-verify="required" lay-reqtext="一级佣金不能为空" placeholder="请输入一级佣金" value="{{$info->first ?? 0}}" class="layui-input" />
                <tip>分销一级发放佣金金额</tip>
                <button style="display: inline-block;position: absolute;right: 0px;margin-top: -38px;" type="button" class="layui-btn layui-btn-disabled bt">{{isset($info->price_type) ? '元' : '%'}}</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">二级名称</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="text" name="second_name" placeholder="请输入二级名称" value="{{$info->second_name ?? ''}}" class="layui-input" />
                <tip>分销二级名称！</tip>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">二级佣金</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="number" name="second" lay-verify="required" lay-reqtext="二级佣金不能为空" placeholder="请输入一级佣金" value="{{$info->second ?? 0}}" class="layui-input" />
                <tip>分销二级发放佣金金额</tip>
                <button style="display: inline-block;position: absolute;right: 0px;margin-top: -38px;" type="button" class="layui-btn layui-btn-disabled bt">{{isset($info->price_type) ? '元' : '%'}}</button>
            </div>
        </div>
        
        <div class="hr-line"></div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" id="saveBtn" lay-submit lay-filter="saveBtn">保存</button>
            </div>
        </div>
        
    </div>
    
    </form>
@endsection

@section('listscript')
    <script type="text/javascript">
        layui.use(['iconPickerFa', 'form', 'layer', 'upload', 'xmSelect','miniTab'], function () {
            var iconPickerFa = layui.iconPickerFa,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                xmSelect = layui.xmSelect,
                miniTab = layui.miniTab,
                $ = layui.$;
                miniTab.listen();
            form.on('radio(price_type)', function (data) {
            　　
                if( data.elem.checked){　　　　　　//判断当前多选框是选中还是取消选中
                    var value = data.value;   //  当前选中的value值
                    if(value == 1){
                        $(".bt").html('元');
                    }else{
                        $(".bt").html('%');
                    }
                }
            　　
            });
            //监听提交
            form.on('submit(saveBtn)', function(data){
                $("#saveBtn").addClass("layui-btn-disabled");
                $("#saveBtn").attr('disabled', 'disabled');
                var loading = layer.msg('加载中..', {icon: 16,shade: 0.3,time: false});
                $.ajax({
                    url:'/admin/share/setting',
                    type:'post',
                    data:data.field,
                    dataType:'JSON',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        layer.close(loading);  //返回数据关闭loading
                        if(res.code==0){
                            layer.msg(res.message,{icon: 1},function (){
                                parent.location.reload();
                            });
                        }else{
                            layer.msg(res.message,{icon: 2});
                            $("#saveBtn").removeClass("layui-btn-disabled");
                            $("#saveBtn").removeAttr('disabled');
                        }
                    },
                    error:function (data) {
                        layer.msg(res.message,{icon: 2});
                        $("#saveBtn").removeClass("layui-btn-disabled");
                        $("#saveBtn").removeAttr('disabled');
                    }
                });
            });
        });
    </script>
@endsection