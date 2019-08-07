@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">保存菜单</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <br>
                    <form class="form-horizontal" id="frmUpdate">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">标题：</label>
                            <div class="col-sm-10 col-md-8">
                                <input name="title" type="text" class="form-control" placeholder="标题" required value="{{$menu->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">副标题：</label>
                            <div class="col-sm-10 col-md-8">
                                <input name="sub_title" type="text" class="form-control" placeholder="副标题" required value="{{$menu->sub_title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">图标：</label>
                            <div class="col-sm-10 col-md-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-{{$menu->icon}}">&nbsp;</i></span>
                                    <input name="icon" type="text" class="form-control" value="{{$menu->icon}}">
                                    <span class="input-group-addon"><a href="http://www.fontawesome.com.cn/faicons/#icons" target="_blank">实例</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">父级：</label>
                            <div class="col-sm-10 col-md-8">
                                <select name="parent_id" class="form-control select2">
                                    <option value="" selected>顶级</option>
                                    @foreach($parent_menus as $parent_menu)
                                        <option value="{{$parent_menu->id}}" {{$parent_menu->id == $menu->parent_id ? 'selected' : ''}}>{{$parent_menu->title}}{{$parent_menu->sub_title?':'.$parent_menu->sub_title:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序依据：</label>
                            <div class="col-sm-10 col-md-5">
                                <input name="sort" type="number" min="0" max="999" step="1" class="form-control" placeholder="排序依据" required value="{{$menu->sort}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">URI：</label>
                            <div class="col-sm-10 col-md-8">
                                <input name="uri" type="text" class="form-control" placeholder="例如：/account" required value="{{$menu->uri}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">路由别名：</label>
                            <div class="col-sm-10 col-md-8">
                                <input name="action_as" type="text" class="form-control" placeholder="例如：account" required value="{{$menu->action_as}}">
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{url('rbac/menu')}}?page={{request()->get('page',1)}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                            <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right btn-flat"><i class="fa fa-check">&nbsp;</i>保存</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">绑定角色</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <form id="frmBindRoles">
                        <div class="box-body">
                            <div class="form-group">
                                @foreach($roles as $role)
                                    @if(in_array($role->id,$roleIds))
                                        <label><input type="checkbox" name="role_ids[]" value="{{$role->id}}" checked>{{$role->name}}</label>&nbsp;&nbsp;
                                    @else
                                        <label><input type="checkbox" name="role_ids[]" value="{{$role->id}}">{{$role->name}}</label>&nbsp;&nbsp;
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="javascript:" onclick="fnBindRoles('{{$menu->id}}')" class="btn btn-primary pull-right btn-flat"><i class="fa fa-check">&nbsp;</i>确定</a>
                        </div>
                    </form>
                </div>
            </div>
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
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('rbac/menu',$menu->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href="{{url('rbac/menu')}}?page{{request()->get('page',1)}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 绑定菜单到角色
         * @param menuId
         */
        fnBindRoles = function (menuId) {
            $.ajax({
                url: `{{url('menuBindRoles')}}/${menuId}`,
                type: "post",
                data: $("#frmBindRoles").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
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
