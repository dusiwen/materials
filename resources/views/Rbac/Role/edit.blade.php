@extends('Layout.index')
@section('content')
    <section class="content">
        {{--保存角色--}}
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">保存角色</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate">
                <div class="form-group">
                    <label class="col-sm-2 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="名称" required value="{{$role->name}}">
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('rbac/role')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </form>
        </div>

        {{--权限绑定--}}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">权限绑定</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('rbac/permission/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i>新建权限</a>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <form class="form-horizontal" id="frmBindPermission">
                    @foreach($permissionGroups as $permissionGroup)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{$permissionGroup->name}}：
                                <a href="javascript:" onclick="fnAllChecked({{$permissionGroup->id}})">全选</a>
                            </label>
                            <div class="col-sm-10 col-md-10">
                                @foreach($permissionGroup->permissions as $permission)
                                    <label class="control-label text-left" style="font-weight: normal;">
                                        <input
                                            type="checkbox"
                                            name="permission_ids[]"
                                            class="permission-group-{{$permissionGroup->id}}"
                                            value="{{$permission->id}}"
                                            {{in_array($permission->id,$permissionIds) ? 'checked' : ''}}>{{$permission->name}}&nbsp;&nbsp;
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                <div class="box-footer">
                    <a href="javascript:" onclick="fnBindPermissions('{{$role->id}}')" class="btn btn-primary pull-right"><i class="fa fa-check">&nbsp;</i>确定</a>
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
                url: "{{url('rbac/role',$role->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.href="{{url('rbac/role')}}?page{{request()->get('page',1)}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                }
            });
        };

        /**
         * 绑定角色到权限
         * @param {int} roleId 角色编号
         */
        fnBindPermissions = function (roleId) {
            $.ajax({
                url: "{{url('roleBindPermissions')}}/" + roleId,
                type: "post",
                data: $("#frmBindPermission").serialize(),
                success: function (response) {
                    // console.log('success:', response);
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

        /**
         * 根据权限分组，全选
         */
        fnAllChecked = function (permissionGroupId) {
            $(`.permission-group-${permissionGroupId}`).each(function () {
                this.checked = true;
            });
        };
    </script>
@endsection
