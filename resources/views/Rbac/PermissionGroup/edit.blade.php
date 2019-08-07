@extends('Layout.index')
@section('content')
    <section class="content">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">保存权限分组</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate">
                <div class="form-group">
                    <label class="col-sm-2 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="名称" required value="{{$rbacPermissionGroup->name}}">
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('rbac/permissionGroup')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script>
        /**
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('rbac/permissionGroup',$rbacPermissionGroup->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.href="{{url('rbac/permissionGroup')}}?page{{request()->get('page',1)}}";
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
