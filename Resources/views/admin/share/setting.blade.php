@extends('admin.public.header')
@section('listcontent')
    <form class="layui-form">
    <div class="layui-form layuimini-form">
       
        <input type="hidden" name="id" value="{{$info->id ?? 0}}" />
        
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>分销设置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label required">分销层级</label>
            <div class="layui-input-block">
                @foreach($ShareSettingModel->getLevelArr() as $k=>$v)
                    <input type="radio" name="level" lay-filter="level" value="{{$k}}" title="{{$v}}" @if($k == ($info->level ?? 0))checked="" @elseif(!isset($info->level) && $k==0)checked="" @endif>
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开启分销内购</label>
            <div class="layui-input-block">
                @foreach($ShareSettingModel->getIsRebateArr() as $k=>$v)
                    <input type="radio" name="is_rebate" lay-filter="is_rebate" value="{{$k}}" title="{{$v}}" @if($k == ($info->is_rebate ?? 0))checked="" @elseif(!isset($info->is_rebate) && $k==0)checked="" @endif>
                @endforeach
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>分销资格设置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label required">成为分销商条件</label>
            <div class="layui-input-block">
                @foreach($ShareSettingModel->getShareConditionArr() as $k=>$v)
                    <input type="radio" name="share_condition" lay-filter="share_condition" value="{{$k}}" title="{{$v}}" @if($k == ($info->share_condition ?? 0))checked="" @elseif(!isset($info->share_condition) && $k==0)checked="" @endif>
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">推广海报图</label>
            <div class="layui-input-block">
                <div class="layui-upload-drag" id="upload_image">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                    <br>
                    <div class="{{isset($info->share_image) ? '' : 'layui-hide'}}" id="uploadShowImg">
                        <img src="{{$info->share_image_show ?? ''}}" alt="上传成功后渲染" style="max-width: 196px">
                    </div>
                    <input type="hidden" name="share_image" value="{{$info->share_image ?? ''}}" />
                </div>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>分销提现设置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label required">提现方式</label>
            <div class="layui-input-block" style="width:380px;" id="pay_type">
                
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">用户提现须知</label>
            <div class="layui-input-block" style="width:380px;">
                <textarea name="content" placeholder="请输入提现须知" class="layui-textarea">{{$info->content ?? ''}}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">最少提现额度</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="number" name="min_money" lay-verify="required" lay-reqtext="提现额度不能为空" placeholder="请输入最少提现额度" value="{{$info->min_money ?? 0}}" class="layui-input" />
                <tip>分销提现满足设定金额后可提现！</tip>
                <button style="display: inline-block;position: absolute;right: 0px;margin-top: -38px;" type="button" class="layui-btn layui-btn-disabled">元</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">每日提现上限</label>
            <div class="layui-input-block" style="width:380px;">
                <input type="number" name="cash_max_day" lay-verify="required" lay-reqtext="每日提现上限不能为空" placeholder="请输入每日提现上限金额" value="{{$info->cash_max_day ?? 0}}" class="layui-input layui-col-xs6" />
                <tip>为0则表示不限制！</tip>
                <button style="display: inline-block;position: absolute;right: 0px;margin-top: -38px;" type="button" class="layui-btn layui-btn-disabled">元</button>
            </div>
            
            
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">提现手续费</label>
            <div class="layui-input-block" style="width:380px;">
                <input name="cash_fee_rate" lay-verify="required" value="{{$info->cash_fee_rate ?? 0}}" class="layui-input layui-col-xs6" lay-reqtext="提现手续费不能为空">
                <button style="display: inline-block;position: absolute;right: 0px;" type="button" class="layui-btn layui-btn-disabled">%</button>
                
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
        layui.use(['iconPickerFa', 'form', 'layer', 'upload', 'xmSelect'], function () {
            var iconPickerFa = layui.iconPickerFa,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                xmSelect = layui.xmSelect,
                $ = layui.$;
            //拖拽上传
            upload.render({
                elem: '#upload_image'
                ,url: '/admin/upload/upload' //改成您自己的上传接口
                ,accept: 'images'
                ,acceptMime: 'image/*'
                ,size: 400 //限制文件大小，单位 KB
                ,headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                ,done: function(res){
                    if(res.code==0){
                        layer.msg("上传成功",{icon: 1});
                        var domain = window.location.host;
                        layui.$('#uploadShowImg').removeClass('layui-hide').find('img').attr('src', "http://" + domain + "/" + res.data[0]);
                        $("input[name='share_image']").val(res.data[0]);
                    }else{
                        layer.msg(res.message,{icon: 2});
                        layui.$('#uploadShowImg').addClass('layui-hide');
                        $("input[name='share_image']").val('');
                    }
                }
            });
            var pay_type_arr = '{{$pay_type_arr ?? ''}}';
            pay_type_arr = Object.values(pay_type_arr)
            var pay_type = xmSelect.render({
                el: '#pay_type',
                language: 'zn',
                layVerify: 'required',
                tips: '请选择提现方式',
                searchTips: '搜索',
                name:'pay_type',
                toolbar: {
                    show: true,
                }, 
                filterable: true,
                theme: {
                    color: '#5FB878',
                },
                initValue: pay_type_arr,
                data: [
                    {name: '微信', value: 0},
                    {name: '支付宝', value: 1},
                    {name: '银行卡', value: 2},
                    {name: '余额', value: 3},
                ]
            })
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