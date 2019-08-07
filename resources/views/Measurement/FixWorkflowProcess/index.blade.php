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
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title">{{$type == 'ENTIRE' ? '整件' : '部件'}}检测列表</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <div class="btn-group btn-group-lg">
                        <a href="{{url('measurement/fixWorkflow',$fixWorkflowSerialNumber)}}/edit?fixWorkflowSerialNumber={{$fixWorkflowSerialNumber}}&type={{$type}}&page={{$page}}" class="btn btn-default btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                        <a href="{{url('measurement/fixWorkflowProcess/create')}}?fixWorkflowSerialNumber={{$fixWorkflowSerialNumber}}&type={{$type}}&page={{$page}}" class="btn btn-default btn-flat"><i class="fa fa-plus-square">&nbsp;</i>新建</a>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>检测序号</th>
                        <th>流水号</th>
                        <th>备注</th>
                        <th>阶段</th>
                        <th>说明</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fixWorkflowProcesses as $fixWorkflowProcess)
                        <tr>
                            <td>{{$fixWorkflowProcess->numerical_order}}</td>
                            <td>{{$fixWorkflowProcess->serial_number}}</td>
                            <td>{{mb_substr($fixWorkflowProcess->note,0,30)}}</td>
                            <td>{{$fixWorkflowProcess->stage}}</td>
                            <td>{{$fixWorkflowProcess->auto_explain}}</td>
                            <td>
                                <div class="btn-group btn-group-lg">
                                    <a href="{{url('measurement/fixWorkflowProcess',$fixWorkflowProcess->serial_number)}}/edit?fixWorkflowSerialNumber={{$fixWorkflowSerialNumber}}&type={{$type}}&page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">编辑</a>
                                    <a href="javascript:" onclick="fnDelete('{{$fixWorkflowProcess->serial_number}}')" class="btn btn-danger btn-flat">删除</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($fixWorkflowProcesses->hasPages())
                <div class="box-footer">
                    {{ $fixWorkflowProcesses->links() }}
                </div>
            @endif
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
         * 删除
         * @param {int} serialNumber 编号
         */
        fnDelete = serialNumber => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowProcess')}}/${serialNumber}`,
                type: "delete",
                data: {},
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };
    </script>
@endsection
