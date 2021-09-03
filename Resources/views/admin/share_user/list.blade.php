@extends('admin.public.header')
@section('title','分销商列表')

@section('listsearch')
    <fieldset class="table-search-fieldset" style="display:black">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane form-search" action="" id="searchFrom">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">用户昵称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="nickname" id="nickname" autocomplete="off" value="" class="layui-input">
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-sm layui-btn-normal"  lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary data-reset-btn" lay-submit lay-filter="data-reset-btn" >重置 </button>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>
@endsection

@section('listcontent')
<!-- 添加备注 -->
<div class="layui-row" id="popSearchRoleTest" style="display:none;">
    <div class="layui-col-md11" style="margin-top: 20px;">
        <form class="layui-form">
            <input type="hidden" name="id" value="">
            <div class="layui-form-item">
                <label class="layui-form-label">备注：</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="content" class="layui-textarea" name="content"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" id="saveBtn" lay-submit lay-filter="saveBtn">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- 审核 -->
<div class="layui-row" id="toExamine" style="display:none;">
    <div class="layui-col-md11" style="margin-top: 20px;">
        <form class="layui-form">
            <input type="hidden" name="id" value="">
            <div class="layui-form-item">
                <label class="layui-form-label">审核：</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="通过">
                    <input type="radio" name="status" value="2" title="驳回">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" id="saveBtnExamine" lay-submit lay-filter="saveBtnExamine">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
<table class="layui-hide" id="tableList" lay-filter="tableList"></table>
<!-- 表头左侧按钮 -->
<script type="text/html" id="toolbarColumn">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm layuimini-btn-primary" onclick="window.location.reload();" ><i class="layui-icon layui-icon-refresh-3"></i></button>
        <button class="layui-btn layui-btn-sm layui-btn-primary layui-border-red" lay-event="batch_delete"><i class="layui-icon layui-icon-delete"></i>批量删除</button> 
    </div>
</script>
<!-- 操作按钮 -->
<script type="text/html" id="barOperate">
    
    
</script>
@endsection

@section('listscript')
<script type="text/javascript">
    layui.use(['form','table','miniTab'], function(){
        var table = layui.table, $=layui.jquery, form = layui.form , miniTab = layui.miniTab;
        miniTab.listen();
        // 渲染表格
        table.render({
                elem: '#tableList',
                url:'/admin/share_user/ajaxList',
                parseData: function(res) { //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.message, //解析提示文本
                        "count": res.data.count, //解析数据长度
                        "data": res.data.list //解析数据列表
                    }
                },
                cellMinWidth: 80,//全局定义常规单元格的最小宽度
                toolbar: '#toolbarColumn',//开启头部工具栏，并为其绑定左侧模板
                defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                    title: '搜索',
                    layEvent: 'TABLE_SEARCH',
                    icon: 'layui-icon-search'
                }],
            title: '分销商列表',
            cols: [[
                {type: 'checkbox', align: 'center'},
                {field:'id', title:'ID', width:80, align: 'center', unresize: true},
                {field:'nickname', title:'用户昵称', align: 'center'},
                {field:'avatar', align: 'center', title: '头像', templet: function(res){
                    return '<image style="width:50px;height:50px;" src="'+res.avatar+'">'
                }},
                {field:'total_price', title:'累计佣金', align: 'center'},
                {field:'price', title:'可提现佣金', align: 'center'},
                {field:'parent_name', title:'推荐人', align: 'center', templet: function(res){
                    if(res.parent_name){
                        return '<span style="color:#1E9FFF;">'+res.parent_name+'</span>'
                    }else{
                        return '<span style="color:#1E9FFF;">平台</span>'
                    }
                    
                }},
                {field:'price', width:150, title:'下级用户', align: 'center', templet: function(res){
                    return '<a onclick="getParent(1,'+res.user_id+')" href="javascript:;" style="color:#1E9FFF;">【一级：'+res.parent_count_1+'人】</a><br/><a onclick="getParent(2,'+res.user_id+')" data-value="2" href="javascript:;" style="color:#1E9FFF;">【二级：'+res.parent_count_2+'人】</a>'
                }},
                {field:'order_count', title:'会员订单', align: 'center', templet: function(res){
                    return '<span style="color:#1E9FFF;">【订单：0】</span>'
                }},
                {field:'status', title:'状态', align: 'center', templet: function(res){
                    if(res.status == 1){
                        return '<span style="color:#1E9FFF;">正常</span>'
                    }else if(res.status == 0){
                        return '未审核'
                    }else if(res.status == 2){
                        return '<span style="color:#FF5722;">未审核通过</span>'
                    }
                    
                }},
                {field:'content', title:'备注信息', align: 'center'},
                {field:'created_at', title:'申请时间',width:200, align: 'center'},
                {title:'操作',  width:300, align: 'center', templet: function(res){
                    if(res.status == 0){
                        return'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-green" lay-event="examine">审核</a>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-blue" lay-event="edit">添加备注</a>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-black" layuimini-content-href="{{("/admin/share_order/list")}}" data-title="分销订单">分销订单</a><br/>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-black" layuimini-content-href="{{("/admin/share_cash/list")}}" data-title="提现明细">提现明细</a>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-red" lay-event="delete""><i class="layui-icon layui-icon-delete"></i>删除</a>'
                    }else{
                        return'<a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>添加备注</a>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary" layuimini-content-href="{{("/admin/share_order/list")}}" data-title="分销订单"><i class="layui-icon layui-icon-list"></i>分销订单</a><br/>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary" layuimini-content-href="{{("/admin/share_cash/list")}}" data-title="提现明细"><i class="layui-icon layui-icon-list"></i>提现明细</a>'
                        +'<a class="layui-btn layui-bg-red layui-btn-xs" lay-event="delete""><i class="layui-icon layui-icon-delete"></i>删除</a>'
                    }
                    
                }}
            ]],
            id: 'listReload',
            limits: [15, 20, 30, 50, 100,200],
            limit: 15,
            page: true,
            text: {
                none: '抱歉！暂无数据~' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
            }
        });

        //头工具栏事件
        table.on('toolbar(tableList)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            var ids = [];
            var data = checkStatus.data;
            for (var i=0;i<data.length;i++){
                ids.push(data[i].id);
            }
            switch(obj.event){
                case 'batch_delete':
                    if(!ids.length){
                        return layer.msg('请勾选要删除的数据',{icon: 2});
                    }
                    layer.confirm('确定删除选中的数据吗？', {
                    title : "操作确认",
                    skin: 'layui-layer-lan'
                    },function(index){
                        $.ajax({
                            url:'/admin/share_user/batchDelete',
                            type:'post',
                            data:{'id':ids},
                            dataType:"JSON",
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success:function(data){
                                if(data.code == 0){
                                    layer.msg(data.message,{icon: 1,time:1500},function(){
                                        setTimeout('window.location.reload()',500);
                                    });
                                }
                                else{
                                    layer.msg(data.message,{icon: 2});
                                }
                            },
                            error:function(e){
                                layer.msg(data.message,{icon: 2});
                            },

                        });
                    });
                break;
                
                //自定义头工具栏右侧图标 - 提示
                case 'TABLE_SEARCH':
                    var display = $(".table-search-fieldset").css("display"); //获取标签的display属性
                    if(display == 'none'){
                        $(".table-search-fieldset").show();
                    }else{
                        $(".table-search-fieldset").hide();
                    }
                break;
               
            };
        });
        
        //监听行工具事件
        table.on('tool(tableList)', function(obj){
            var data = obj.data;
            var id = data.id;
            if(obj.event === 'delete'){
                layer.confirm('确定删除这条数据吗？', {
                    title : "操作确认",
                    skin: 'layui-layer-lan'
                },function(index){
                    $.ajax({
                        url:'/admin/share_user/delete',
                        type:'post',
                        data:{'id':id},
                        dataType:"JSON",
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success:function(data){
                            if(data.code == 0){
                                layer.msg(data.message,{icon: 1,time:1500},function(){
                                    setTimeout('window.location.reload()',500);
                                });
                            }
                            else{
                                layer.msg(data.message,{icon: 2});
                            }
                        },
                        error:function(e){
                            layer.msg(data.message,{icon: 2});
                        },
                        
                    });
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                $("input[name=id]").val(id);
                $("#content").text(data.content);
                var index = layer.open({
                       //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type:1,
                        title: '添加备注',
                        shade: 0.2,
                        maxmin:true,
                        skin:'layui-layer-lan',
                        area: ['25%','30%'],
                        content:$("#popSearchRoleTest").html()
                    });
                
            } else if(obj.event === 'examine'){
                $("input[name=id]").val(id);
                var index = layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type:1,
                        title: '审核',
                        shade: 0.2,
                        maxmin:true,
                        skin:'layui-layer-lan',
                        area: ['20%','25%'],
                        content:$("#toExamine").html()
                    });
                form.render();
            }
        });

        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            //var result = JSON.stringify(data.field);
            
            //执行搜索重载
            table.reload('listReload', {
                where: {
                    nickname: $("#nickname").val(),
                }
            });
            return false;
        });

        // 监听重置操作
        form.on('submit(data-reset-btn)', function (data) {
            var title = $("#nickname").val();
            $("#nickname").val('');
            
        });

        //监听提交
        form.on('submit(saveBtn)', function(data){
            $("#saveBtn").addClass("layui-btn-disabled");
            $("#saveBtn").attr('disabled', 'disabled');
            
            $.ajax({
                url:'/admin/share_user/saveContent',
                type:'post',
                data:data.field,
                dataType:'JSON',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success:function(data){
                    if(data.code==0){
                        layer.msg(data.message,{icon: 1,time:1500},function(){
                            setTimeout('parent.location.reload()',500);
                        });
                    }else{
                        layer.msg(data.message,{icon: 2});
                        $("#saveBtn").removeClass("layui-btn-disabled");
                        $("#saveBtn").removeAttr('disabled');
                    }
                }
            });
        });

        //监听提交
        form.on('submit(saveBtnExamine)', function(data){
            $("#saveBtnExamine").addClass("layui-btn-disabled");
            $("#saveBtnExamine").attr('disabled', 'disabled');
            
            $.ajax({
                url:'/admin/share_user/saveStatus',
                type:'post',
                data:data.field,
                dataType:'JSON',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success:function(data){
                    if(data.code==0){
                        layer.msg(data.message,{icon: 1,time:1500},function(){
                            setTimeout('parent.location.reload()',500);
                        });
                    }else{
                        layer.msg(data.message,{icon: 2});
                        $("#saveBtnExamine").removeClass("layui-btn-disabled");
                        $("#saveBtnExamine").removeAttr('disabled');
                    }
                }
            });
        });

    });

    //获取下级列表
    function getParent(type,user_id){
        let title = '';
        if(type == 1){
            title = '下线一级列表'
        }else if(type == 2){
            title = '下线二级列表'
        }
        layer.open({
            title: title,
            type: 2,
            shade: 0.2,
            maxmin:true,
            shadeClose: true,
            area: ['70%', '90%'],
            skin: 'layui-layer-lan',
            content: '/admin/share_user/getParentUser?type='+type+'&user_id='+user_id,
        });
    }
</script>
@endsection
