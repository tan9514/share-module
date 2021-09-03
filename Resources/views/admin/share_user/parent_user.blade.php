@extends('admin.public.header')
@section('title','下级列表')

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
        var type = '{{ $type }}';
        var user_id = '{{ $user_id }}';
        table.render({
                elem: '#tableList',
                url:'/admin/share_user/ajaxParentUser',
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
                defaultToolbar: [{ //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                    title: '搜索',
                    layEvent: 'TABLE_SEARCH',
                    icon: 'layui-icon-search'
                }],
            title: '分销商列表',
            cols: [[
                {field:'id', title:'序号', width:80, align: 'center', unresize: true},
                {field:'nickname', title:'用户昵称', align: 'center'},
                {field:'avatar', align: 'center', title: '头像', templet: function(res){
                    return '<image style="width:50px;height:50px;" src="'+res.avatar+'">'
                }},
                
                {field:'parent_name', width:150, title:'下线等级', align: 'center'},
            ]],
            id: 'listReload',
            limits: [15, 20, 30, 50, 100,200],
            limit: 15,
            page: true,
            where: {
                type : type,
                user_id: user_id,
            },
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
                //自定义头工具栏右侧图标 - 提示
                case 'TABLE_SEARCH':
                    var display = $(".table-search-fieldset").css("display"); //获取标签的display属性
                    if(display == 'none'){
                        $(".table-search-fieldset").show();
                    }else{
                        $(".table-search-fieldset").hide();
                    }
                break;
            }
        });
        
        //监听行工具事件
        table.on('tool(tableList)', function(obj){
            var data = obj.data;
            var id = data.id;
            
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
