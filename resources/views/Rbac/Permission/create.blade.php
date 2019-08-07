@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">新建权限</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('rbac/permissionGroup/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i>新建分组</a>
                </div>
            </div>
            <br>
            <form class="form-horizontal" id="frmCreate">
                <div class="form-group">
                    <label class="col-sm-2 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="名称" required value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">路由别名：</label>
                    <div class="col-sm-10 col-md-8">
                        <input name="http_path" type="text" class="form-control" placeholder="例如：rbac.permission.index （前缀+控制器+行为）" required value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">分组：</label>
                    <div class="col-sm-10 col-md-8">
                        <select name="rbac_permission_group_id" class="form-control select2" style="width: 100%;">
                            <option value="" selected>无</option>
                            @foreach($permissionGroups as $permissionGroup)
                                <option value="{{$permissionGroup->id}}">{{$permissionGroup->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('rbac/permission')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-success pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
        });

        /**
         * 新建
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('rbac/permission')}}",
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
