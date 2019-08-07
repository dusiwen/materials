@extends('Layout.index')
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">新建用户</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmCreate">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">账号：</label>
                        <div class="col-sm-10 col-md-8">
                            <input name="account" type="text" class="form-control" placeholder="账号" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-10 col-md-8">
                            <input name="nickname" type="text" class="form-control" placeholder="名称" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">邮箱：</label>
                        <div class="col-sm-10 col-md-8">
                            <input name="email" type="email" class="form-control" placeholder="邮箱" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">电话：</label>
                        <div class="col-sm-10 col-md-8">
                            <input name="phone" type="text" class="form-control" placeholder="电话" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">密码：</label>
                        <div class="col-sm-10 col-md-8">
                            <input name="password" type="password" class="form-control" placeholder="密码" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否是监管员</label>
                        <div class="col-ms-10 col-md-8">
                            <label style="font-weight: normal; text-align: left;"><input type="radio" class="minimal" name="supervision" value="1">是</label>
                            <label style="font-weight: normal; text-align: left;"><input type="radio" class="minimal" name="supervision" value="0">否</label>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('account')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-success pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                </div>
            </form>
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
         * 新建
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('account')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.relaod();
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
