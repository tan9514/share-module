@extends('admin.public.header')
@section('title','分销提现列表')

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
                url:'/admin/share_cash/ajaxList',
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
                {field:'mobile', title:'基本信息', align: 'center', templet: function(res){
                    if(res.pay_type == 1){
                        return '<span>'+res.mobile+'</span><br/><span>'+res.name+'</span>'
                    }else if(res.pay_type == 2){
                        return '<span>'+res.mobile+'</span><br/><span>'+res.bank_name+'</span>'
                    }else if(res.pay_type == 0){
                        return '<span>微信提现</span>'
                    }else if(res.pay_type == 3){
                        return '<span>余额提现</span>'
                    }
                    
                }},
                {field:'price', title:'提现金额（元）', align: 'center'},
                {field:'cash_fee', title:'手续费（元）', align: 'center'},
                {field:'amount', title:'实际到账（元）', align: 'center'},
                {field:'status', title:'状态', align: 'center', templet: function(res){
                    if(res.status == 0){
                        return '待审核'
                    }else if(res.status == 1){
                        return '待打款'
                    }else if(res.status == 2){
                        return '已打款'
                    }else if(res.status == 3){
                        return '无效'
                    }
                }},
                {field:'created_at', title:'申请时间',width:200, align: 'center'},
                {title:'操作',  width:260, align: 'center', templet: function(res){
                    if(res.status == 0){
                        return '<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-blue" lay-event="adopt">通过</a>'
                        +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-red" lay-event="reject">驳回</a>'
                        
                    }else if(res.status == 1 ){
                        if(res.pay_type == 0){
                            return '<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-red" lay-event="reject">驳回</a><br/>'
                            +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-blue" lay-event="online_payment">确认打款</a>（微信支付自动打款）'
                        }else{
                            return '<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-red" lay-event="reject">驳回</a><br/>'
                            +'<a class="layui-btn layui-btn-xs layui-btn-primary layui-border-blue" lay-event="offline_payment">手动打款</a>（线下打款）'
                        }
                    }else{
                        return ''
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
                            url:'/admin/share_cash/batchDelete',
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
            if(obj.event === 'adopt'){
                layer.confirm('确定通过该提现申请吗？', {
                    title : "操作确认",
                    skin: 'layui-layer-lan'
                },function(index){
                    $.ajax({
                        url:'/admin/share_cash/adopt',
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
            } else if(obj.event === 'reject'){
                layer.confirm('确定驳回该提现申请吗？', {
                    title : "操作确认",
                    skin: 'layui-layer-lan'
                },function(index){
                    $.ajax({
                        url:'/admin/share_cash/reject',
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
            } else if(obj.event === 'online_payment'){
                layer.confirm('是否确认微信线上打款？', {
                    title : "操作确认",
                    skin: 'layui-layer-lan'
                },function(index){
                    $.ajax({
                        url:'/admin/share_cash/confirmPayment',
                        type:'post',
                        data:{'id':id,'status':1},
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
            } else if(obj.event === 'offline_payment'){
                layer.confirm('是否确认已线下打款？', {
                    title : "操作确认",
                    skin: 'layui-layer-lan'
                },function(index){
                    $.ajax({
                        url:'/admin/share_cash/confirmPayment',
                        type:'post',
                        data:{'id':id,'status':2},
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

    });
</script>
@endsection
