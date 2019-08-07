@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">编辑供应商</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate">
                <div class="form-group">
                    <label class="col-sm-2 control-label">名称：</label>
                    <div class="col-sm-10 col-md-9">
                        <input placeholder="名称" class="form-control input-lg" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                               name="name" value="{{$factory->name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-sm">联系电话：</label>
                    <div class="col-sm-10 col-md-9">
                        <input placeholder="联系电话" class="form-control input-lg" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                               name="phone" value="{{$factory->phone}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网址：</label>
                    <div class="col-sm-10 col-md-9">
                        <input placeholder="网址" class="form-control input-lg" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                               name="official_home_link" value="{{$factory->official_home_link}}">
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('factory')}}?page={{request()->get('page',1)}}" class="btn btn-default pull-left btn-flat btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right btn-flat btn-lg"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });

        /**
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('factory',$factory->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href="{{url('factory')}}?page{{request()->get('page',1)}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };
    </script>
@endsection
