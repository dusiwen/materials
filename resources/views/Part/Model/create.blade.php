@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">新建物资</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form class="form-horizontal" id="frmCreate">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">物资名称：</label>
                        <div class="col-sm-10 col-md-8">
                            <input placeholder="物资名称" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="MaterialName" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">单位：</label>
                        <div class="col-sm-10 col-md-8">
                            <input placeholder="单位" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="unit" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">重量(kg)：</label>
                        <div class="col-sm-10 col-md-8">
                            <input placeholder="重量" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="EachWeight" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">使用年限：</label>
                        <div class="col-sm-10 col-md-8">
                            <input placeholder="使用年限" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="ServiceLife" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">备注：</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea name="remark" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{url('part/model')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                        <a href="javascript:" onclick="fnCreate()" class="btn btn-success btn-flat pull-right btn-lg"><i class="fa fa-check">&nbsp;</i>新建</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

        /**
         * 新建
         */
        fnCreate = function () {
            $.ajax({
                url: `{{url('part/model')}}`,
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href = "{{url("part/model")}}";
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
