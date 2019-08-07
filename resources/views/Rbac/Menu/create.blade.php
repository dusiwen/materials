@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
@endsection
@section('content')
    <section class="content">
        <form class="form-horizontal" id="frmCreate">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">新建菜单</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <form class="form-horizontal" id="frmCreate">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">标题：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input name="title" type="text" class="form-control" placeholder="标题" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">副标题：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input name="sub_title" type="text" class="form-control" placeholder="副标题" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">图标：</label>
                                <div class="col-sm-10 col-md-4">
                                    <div class="input-group">
                                        <input name="icon" type="text" class="form-control" value="">
                                        <span class="input-group-addon"><a href="http://www.fontawesome.com.cn/faicons/#icons" target="_blank">实例</a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">父级：</label>
                                <div class="col-sm-10 col-md-8">
                                    <select name="parent_id" class="form-control select2">
                                        <option value="" selected>顶级</option>
                                        @foreach($menus as $menu)
                                            <option value="{{$menu->id}}">{{$menu->title}}{{$menu->sub_title?':'.$menu->sub_title:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">排序依据：</label>
                                <div class="col-sm-10 col-md-4">
                                    <input name="sort" type="number" min="0" max="999" step="1" class="form-control" placeholder="排序依据" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">URI：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input name="uri" type="text" class="form-control" placeholder="例如：/account" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">路由别名：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input name="action_as" type="text" class="form-control" placeholder="例如：account" required value="">
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{url('rbac/menu')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                <a href="javascript:" onclick="fnCreate()" class="btn btn-success pull-right btn-flat"><i class="fa fa-check">&nbsp;</i>新建</a>
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
                            <div class="box-body">
                                <div class="form-group">
                                    @foreach($roles as $role)
                                        <label class="col-md-4 text-lg"><input type="checkbox" name="role_ids[]" value="{{$role->id}}">{{$role->name}}</label>&nbsp;&nbsp;
                                    @endforeach
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </form>
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
        fnCreate = function () {
            $.ajax({
                url: "{{url('rbac/menu')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
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
    </script>
@endsection
