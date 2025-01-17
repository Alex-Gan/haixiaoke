<!--加载头部-->
@include('layous.header')

<meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">
<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
<script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" charset="utf-8" src="/backend/ueditor1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/backend/ueditor1.4.3/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="/backend/ueditor1.4.3/lang/zh-cn/zh-cn.js"></script>


<body>
<div class="x-body">
    <form class="layui-form">
        {{csrf_field()}}
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                用户姓名
            </label>
            <div class="layui-form-mid layui-word-aux">{{$data->name}}</div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                手机号
            </label>
            <div class="layui-form-mid layui-word-aux">{{$data->mobile}}</div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                购买时间
            </label>

            <div class="layui-form-mid layui-word-aux">{{$data->created_at}}</div>
        </div>
        <!--
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                用户备注
            </label>
            <div class="layui-form-mid layui-word-aux">{{$data->remark}}</div>
        </div>
        -->
        <div class="layui-form-item">
            <label for="phone" class="layui-form-label">
                处理日志
            </label>
            <div class="layui-input-inline" style="width: 600px;">
                <div class="layui-upload">
                    <div id="action_upload_imgs">
                        <div class="layui-upload-list" >
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th style="text-align: center">处理时间</th>
                                    <th style="text-align: center">备注</th>
                                    <th style="text-align: center">阶段</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!$data->progress->isEmpty())
                                    @foreach($data->progress as $item)
                                    <tr>
                                        <td style="text-align: center" width="25%">{{$item->processing_at}}</td>
                                        <td>{{$item->remark}}</td>
                                        <td style="text-align: center" width="18%">{{$item->status_text}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr style="text-align: center;"><td colspan="3">暂无处理日志</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                处理备注
            </label>
            <div class="layui-input-inline" style="width: 280px;">
                <textarea name="remark" placeholder="输入本次处理备注信息" class="layui-textarea"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                处理进度
            </label>
            <div class="layui-input-inline">
                <select name="status" lay-verify="required" lay-search="" lay-filter="type" style="height: 30px;">
                    <option value="1" @if($data->status == 1) selected  @endif>已购买</option>
                    <option value="2" @if($data->status == 2) selected  @endif>已面试</option>
                    <option value="3" @if($data->status == 3) selected  @endif>正在体验</option>
                    <option value="4" @if($data->status == 4) selected  @endif>体验完成</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="save" lay-submit="">
                保存
            </button>
            <button class="layui-btn" lay-filter="back" onclick="javascript :history.back(-1);">
                取消
            </button>
        </div>
    </form>
</div>
<script>

    layui.use(['form','layer'], function(){

        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //自定义验证规则
        /*
        form.verify({
            username: function(value){
                if(value.length < 6){
                    return '加盟标题至少得6个字符啊';
                }
            }
            ,pass: [/(.+){6,20}$/, '密码必须6到20位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
        });
        */

        //监听提交
        form.on('submit(save)', function(data) {
            //已完成时给与二次确认提示
            var status_val = data.field.status;

            if (status_val == 4) {

                layer.confirm('执行此操作将给推广员结算佣金，确定要执行吗？', {
                    btn: ['确定', '取消']//按钮
                }, function (index) {
                    layer.close(index);

                    //开启load，防止重复提交
                    var index = layer.load();

                    $.ajax({
                        url: "/admin/purchase_history/handle/"+"{{$data->id}}",
                        data: data.field,
                        type: "PUT",
                        dataType: "json",
                        success:function(res) {
                            layer.close(index);

                            if (res.code == 0) {
                                layer.msg('保存成功!', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    window.location.href="/admin/purchase_history/list";
                                }, 1100);
                            } else {
                                layer.msg(res.msg);
                                return false;
                            }
                        },
                        error:function(data){
                            $.messager.alert('错误',data.msg);
                        }
                    });
                    return false;
                });
                return false;
            }


            //开启load，防止重复提交
            var index = layer.load();

            $.ajax({
                url: "/admin/purchase_history/handle/"+"{{$data->id}}",
                data: data.field,
                type: "PUT",
                dataType: "json",
                success:function(res) {
                    layer.close(index);

                    if (res.code == 0) {
                        layer.msg('保存成功!', {icon: 1, time: 1000});
                        setTimeout(function () {
                            window.location.href="/admin/purchase_history/list";
                        }, 1100);
                    } else {
                        layer.msg(res.msg);
                        return false;
                    }
                },
                error:function(data){
                    $.messager.alert('错误',data.msg);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>