@extends('Layout.index')
@section('content')
    <section class="content">
        <div class="row">
            {{--编辑用户基本信息--}}
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">编辑用户基本信息</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <br>
                    <form class="form-horizontal" id="frmUpdate">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">账号：</label>
                                <div class="col-sm-10 col-md-9">
                                    <input name="account" type="text" class="form-control" placeholder="账号" required value="{{$account->account}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">名称：</label>
                                <div class="col-sm-10 col-md-9">
                                    <input name="nickname" type="text" class="form-control" placeholder="名称" required value="{{$account->nickname}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">邮箱：</label>
                                <div class="col-sm-10 col-md-9">
                                    <input name="email" type="email" class="form-control" placeholder="邮箱" required value="{{$account->email}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">电话：</label>
                                <div class="col-sm-10 col-md-9">
                                    <input name="phone" type="text" class="form-control" placeholder="电话" required value="{{$account->phone}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否是监管员</label>
                                <div class="col-ms-10 col-md-8">
                                    <label style="font-weight: normal; text-align: left;"><input type="radio" class="minimal" name="supervision" value="1" {{session()->get('account.supervision') == 1 ? 'checked' : ''}}>是</label>
                                    <label style="font-weight: normal; text-align: left;"><input type="radio" class="minimal" name="supervision" value="0" {{session()->get('account.supervision') == 0 ? 'checked' : ''}}>否</label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{url('account')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                            <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right"><i class="fa fa-check">&nbsp;</i>编辑</a>
                        </div>
                    </form>
                </div>
            </div>
            {{--角色绑定--}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">角色绑定</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right">
                            <a href="{{url('rbac/role/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i>新建角色</a>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <form class="form-horizontal" id="frmBindRoles">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">绑定角色：</label>
                                <div class="col-sm-10 col-md-8">
                                    @foreach($roles as $role)
                                        @if(in_array($role->id,$roleIds))
                                            <label><input type="checkbox" name="role_ids[]" value="{{$role->id}}" checked>{{$role->name}}</label>&nbsp;&nbsp;
                                        @else
                                            <label><input type="checkbox" name="role_ids[]" value="{{$role->id}}">{{$role->name}}</label>&nbsp;&nbsp;
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="javascript:" onclick="fnBindRoles('{{$account->open_id}}')" class="btn btn-primary pull-right"><i class="fa fa-check">&nbsp;</i>确定</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection
@section('script')
    <script>
        /**
         * 退回前一页
         */
        fnBack = function () {
            location.history(-1);
        };

        /**
         * 编辑
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('account',$account->open_id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 绑定用户到角色
         * @param {string} accountOpenId 用户开放编号
         */
        fnBindRoles = function (accountOpenId) {
            $.ajax({
                url: "{{url('accountBindRoles')}}/" + accountOpenId,
                type: "post",
                data: $("#frmBindRoles").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    // location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };
    </script>
@endsection
