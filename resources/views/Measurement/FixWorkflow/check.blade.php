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
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title">检修单列表</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
{{--                    <a href="{{url('fixWorkflow/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>--}}
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>序列号</th>
                        <th>时间</th>
                        <th>种类</th>
                        <th>型号</th>
                        <th>整件</th>
                        <th>状态</th>
                        <th>阶段</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fixWorkflows as $fixWorkflow)
                        <tr>
                            <td>{{$fixWorkflow->serial_number}}</td>
                            <td>{{$fixWorkflow->updated_at}}</td>
                            <td>{{$fixWorkflow->EntireInstance->Category->name}}</td>
                            <td>{{$fixWorkflow->EntireInstance->EntireModel->name}}</td>
                            <td>{{$fixWorkflow->EntireInstance->entire_model_id_code}}</td>
                            <td>{{$fixWorkflow->EntireInstance->serial_number ?: '新设备'}}</td>
                            <td>{{$fixWorkflow->status}}</td>
                            <td>{{$fixWorkflow->stage}}</td>
                            <td>
                                <div class="btn-group btn-group-lg">
{{--                                    <a href="{{url('measurement/fixWorkflow',$fixWorkflow->serial_number)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">详情</a>--}}
                                    <a href="{{url('measurement/fixWorkflow/create')}}?page={{request()->get('page',1)}}&type=identity_code&identity_code={{$fixWorkflow->EntireInstance->identity_code}}" class="btn btn-warning btn-flat">验收</a>
                                    <a href="javascript:" onclick="fnDelete('{{$fixWorkflow->serial_number}}')" class="btn btn-danger btn-flat">删除</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($fixWorkflows->hasPages())
                <div class="box-footer">
                    {{ $fixWorkflows->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            if ($('.select2')) {
                $('.select2').select2();
            }
            // iCheck for checkbox and radio inputs
            if ($('input[type="checkbox"].minimal, input[type="radio"].minimal')) {
                $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue'
                });
            }
            if ($("#datapicker")) {
                $('#datepicker').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                });
            }
        });

        /**
         * 删除
         * @param {string} fixWorkflowSerialNumber 编号
         */
        fnDelete = function (fixWorkflowSerialNumber) {
            $.ajax({
                url: `{{url('measurement/fixWorkflow')}}/${fixWorkflowSerialNumber}`,
                type: "delete",
                data: {},
                success: function (response) {
                    console.log('success:', response);
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

