@extends('Layout.index')
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">新建权限分组</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmCreate">
                <div class="form-group">
                    <label class="col-sm-2 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <div class="input-group">
                            <input name="name" type="text" class="form-control" placeholder="名称" required value="">
                            <label class="input-group-addon">
                                视为资源路由
                                <input type="checkbox" name="is_resource" value="1" checked>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="form-group" id="divRoute">
                    <label class="col-sm-2 control-label">路由别名：<br>
                        <small>如不是资源路由则无需填写</small>
                    </label>
                    <div class="col-sm-10 col-md-8">
                        <input name="action_name" type="text" class="form-control" placeholder="例如：rbac.permission（前缀+控制器）" value="">
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('rbac/permissionGroup')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnCreate()" class="btn btn-success pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script>
        /**
         * 新建
         */
        fnCreate = function () {
            $.ajax({
                url: "{{url('rbac/permissionGroup')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };
    </script>
@endsection
