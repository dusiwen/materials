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
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">添加整件检修</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form class="form-horizontal" id="frmCreate">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">型号：</label>
                        <label class="col-sm-8 control-label" style="text-align: left; font-weight: normal;">{{$entireInstance->EntireModel->name}}（{{$entireInstance->EntireModel->unique_code}}）</label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="entireInstanceIdentityCode" value="{{$entireInstance->identity_code}}">
                        <input type="hidden" name="type" value="FIXING">
                        <label class="col-sm-3 control-label">入库经办人：</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="processor_id" class="form-control select2" style="width: 100%;">
                                @foreach($accounts as $accountId => $accountNickname)
                                    <option value="{{$accountId}}" {{old('processor_id',null) ? $accountId == old('processor_id') ? 'selected' : '' : $accountId == session()->get('account.id') ? 'selected' : ''}}>{{$accountNickname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系人：</label>
                        <div class="col-sm-10 col-md-8">
                            <input placeholder="联系人" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="connection_name" value="{{old('connection_name')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系电话：</label>
                        <div class="col-sm-10 col-md-8">
                            <input placeholder="联系电话" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="connection_phone" value="{{old('connection_phone')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">入库日期：</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input name="processed_at" type="text" class="form-control pull-right" id="datepicker" value="{{old('processed_at',date('Y-m-d'))}}">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{url('entire/instance')}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                        <a href="javascript:" onclick="fnCreate()" class="btn btn-warning btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>确定</a>
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
                url: `{{url('entire/instance/fixing')}}`,
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
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
