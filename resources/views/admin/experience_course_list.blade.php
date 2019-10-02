<!--加载头部-->
@include('layous.header')
<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
<script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
    a{text-decoration:none;}
    a:hover{text-decoration:none;/*指鼠标在链接*/}
</style>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">体验课程管理</a>
        <a>
          <cite>体验课程列表</cite>
        </a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <input type="text" name="name" placeholder="请输入课程名" value="@if(!empty($data['_params']['name'])) {{$data['_params']['name']}}  @endif" autocomplete="off" class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <xblock>
        <button class="layui-btn" onclick="add_franchise_course()">
            <i class="layui-icon"></i>添加
        </button>
    </xblock>

    <table class="layui-table" style="text-align: center;">
        <thead>
        <tr>
            <th style="text-align: center">ID</th>
            <th style="text-align: center">课程名称</th>
            <th style="text-align: center">价格</th>
            <th style="text-align: center">banner图</th>
            <th style="text-align: center">佣金返利</th>
            <th style="text-align: center">排序</th>
            <th style="text-align: center">上下架状态</th>
            <th style="text-align: center">加入时间</th>
            <th style="text-align: center" width="18%">操作</th>
        </thead>
        <tbody>
            @if (!$data['data']->isEmpty())
            @foreach($data['data'] as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td>￥{{$item->experience_price}}元</td>
                    <td>
                        <img width="100" height="75" src="{{$item->banner}}"/>
                    </td>
                    <td>￥{{$item->rebate_commission}}元</td>
                    <td>{{$item->sort}}</td>
                    <td class="td-status">
                        @if ($item->status == 1)
                            <a href="javascript:;" style="color: red;">
                                <i class="glyphicon glyphicon-ok-circle"></i>
                                <span>上架</span>
                            </a>
                        @elseif ($item->status == 2)
                            <a href="javascript:;" style="color: green;">
                                <i class="glyphicon glyphicon-remove-circle"></i>
                                <span>下架</span>
                            </a>
                        @endif

                    </td>
                    <td>{{$item->created_at}}</td>
                    <td class="td-manage">
                        @if ($item->status == 2)
                            <a title="上架" onclick="change_experience_course_status(this, '{{$item->id}}')" href="javascript:;" data-type="2">
                                <i class="glyphicon glyphicon-ok-circle"></i>
                                <span>上架</span>
                            </a>
                        @elseif ($item->status == 1)
                            <a title="下架" onclick="change_experience_course_status(this, '{{$item->id}}')" href="javascript:;" data-type="1">
                                <i class="glyphicon glyphicon-remove-circle"></i>
                                <span>下架</span>
                            </a>
                        @endif
                        <a title="编辑" onclick="edit_franchise_course({{$item->id}})" href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>编辑
                        </a>
                        <a title="删除" onclick="member_del(this, {{$item->id}})" href="javascript:;">
                            <i class="layui-icon">&#xe640;</i>删除
                        </a>
                    </td>
                </tr>
            @endforeach
            @else
            <tr style="text-align: center;"><td colspan="8">暂无数据</td></tr>
            @endif
        </tbody>
    </table>

    <div id="page"></div>
</div>
<script>
    layui.use('laypage', function(){

        var url = '/admin/experience_course/list?'+"{{$data['_query']}}";

        var laypage = layui.laypage;

        //执行一个laypage实例
        laypage.render({
            elem: 'page' //注意，这里的 test1 是 ID，不用加 # 号
            ,count: '{{$data['_count']}}' //数据总数，从服务端得到
            ,limit: '{{$data['_limit']}}'
            ,curr: '{{$data['_curr']}}' //获取起始页
            ,layout: ['page', 'count']
            ,jump: function(obj, first) {
                //首次不执行
                if (!first) {
                    location.href = url + '&page='+ obj.curr;
                }
            }
        });
    });

    //搜索
    layui.use('form', function(){
        var form = layui.form;
        //监听提交
        form.on('submit(sreach)', function(data){
            if (data.field.title == '') {
                location.href = '/admin/experience_course/list';
            }

            location.href = '/admin/experience_course/list?name=' + data.field.name;
        });
    });

    //更改上下架状态
    function change_experience_course_status(obj, id) {
        var type = $(obj).data("type");
        if (type == 1) { //表示需要下架
            var title = '确认要下架吗？';
        } else {
            var title = '确认要上架吗？';
        }

        layer.confirm(title, function (index) {
            if (type == 1) { //表示需要下架
                layer.msg('已下架!', {icon: 1, time: 1000});
                $(obj).data("type", 2);
                $(obj).parents("tr").find(".td-status").find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle');
                $(obj).parents("tr").find(".td-status").find('span').html('下架');
                $(obj).parents("tr").find(".td-status").find('a').attr('style', 'color: green;');
                $(obj).find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle');
                $(obj).find('span').html('上架');
            } else { //表示需要下架
                layer.msg('已下架!', {icon: 1, time: 1000});
                $(obj).data("type", 1);
                $(obj).parents("tr").find(".td-status").find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle');
                $(obj).parents("tr").find(".td-status").find('span').html('上架');
                $(obj).parents("tr").find(".td-status").find('a').attr('style', 'color: red;');
                $(obj).find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle');
                $(obj).find('span').html('下架');
            }

            $.ajax({
                url: "/admin/experience_course/change_status",
                data: {id: id, _token: '{{ csrf_token() }}' },
                type: "PUT",
                dataType: "json",
                success:function(res) {

                }
            });
        });
    }

    /*删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {

            //删除服务器用户
            $.ajax({
                url: "/admin/experience_course/delete",
                data: {id: id, _token: '{{ csrf_token() }}' },
                type: "DELETE",
                dataType: "json",
                success:function(res) {
                    if(res.code == 0) {
                        //发异步删除数据
                        layer.msg('已删除!', {icon: 1, time: 1500});
                        $(obj).parents("tr").remove();

                        setTimeout(function () {
                            window.location.reload();
                        }, 1600);
                    }
                }
            });

        });
    }

    /*添加*/
    function add_franchise_course() {
        location.href = "/admin/experience_course/add";
    }

    /*编辑*/
    function edit_franchise_course(id) {
        location.href = "/admin/experience_course/edit/"+id;
    }
</script>
</body>
</html>