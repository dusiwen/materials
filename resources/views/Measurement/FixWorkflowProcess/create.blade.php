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
                <h1 class="box-title">添加检测记录</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form class="form-horizontal" id="frmCreate" style="font-size: 18px;">
                    <input type="hidden" name="fix_workflow_serial_number" value="{{$fixWorkflowSerialNumber}}">
                    <input type="hidden" name="type" value="{{$type}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">检测阶段：</label>
                        <div class="col-sm-10 col-md-9">
                            <select name="stage" class="form-control select2 input-lg" style="width: 100%;">
                                @if(session()->get('account.supervision') == 1)
                                    @foreach(\App\Model\FixWorkflowProcess::$STAGE as $stageKey => $stageValue)
                                        <option value="{{$stageKey}}">{{$stageValue}}</option>
                                    @endforeach
                                @else
                                    <option value="FIX_BEFORE">修前检</option>
                                    <option value="FIX_AFTER">修后检</option>
                                    <option value="CHECKED">验收员验收</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注：</label>
                        <div class="col-sm-10 col-md-9">
                            <textarea placeholder="备注" class="form-control input-lg" rows="5" type="text" name="note" value=""></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{url('measurement/fixWorkflowProcess')}}?fixWorkflowSerialNumber={{$fixWorkflowSerialNumber}}&type={{$type}}&page={{$page}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
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
                url: `{{url('measurement/fixWorkflowProcess')}}`,
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    location.href = "{{url('measurement/fixWorkflowProcess')}}?fixWorkflowSerialNumber={{$fixWorkflowSerialNumber}}&type={{$type}}&page={{$page}}";
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
