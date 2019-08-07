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
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">设备检修记录</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr style="font-size: 18px;">
                                <th>检修单流水号</th>
                                <th>检修时间</th>
                                <th>非周期修次数</th>
                                <th colspan="2">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fixWorkflows as $fixWorkflow)
                                <tr style="font-size: 18px; cursor: pointer;" onclick="location.href=`{{url('measurement/fixWorkflow',$fixWorkflow->serial_number)}}/edit`">
                                    <td>{{$fixWorkflow->serial_number}}</td>
                                    <td>{{$fixWorkflow->updated_at}}</td>
                                    <td>{{$fixWorkflow->EntireInstance->un_cycle_fix_count}}</td>
                                    <td colspan="2">{{$fixWorkflow->status}}</td>
                                </tr>
                                <tr>
                                    <th>检测时间</th>
                                    <th>检测阶段</th>
                                    <th>检测人</th>
                                    <th>检测结果</th>
                                    <th>检测备注</th>
                                </tr>
                                @foreach($fixWorkflow->FixWorkflowProcesses as $fixWorkflowProcess)
                                    <tr style="cursor: pointer;" onclick="location.href=`{{url('measurement/fixWorkflowProcess',$fixWorkflowProcess->serial_number)}}/edit?fixWorkflowSerialNumber={{$fixWorkflow->serial_number}}`">
                                        <td>{{$fixWorkflowProcess->updated_at}}</td>
                                        <td>{{$fixWorkflowProcess->stage}}</td>
                                        <td>{{$fixWorkflowProcess->Processor ? $fixWorkflowProcess->Processor->nickname : '无'}}</td>
                                        <td>{{$fixWorkflowProcess->is_allow ? '合格' : '不合格'}}</td>
                                        <td>{{$fixWorkflowProcess->node}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            @if($fixWorkflows->hasPages())
                                <div class="box-footer">
                                    {{ $fixWorkflows->appends(['factory_name'=>request()->get('factory_name'),'date'=>request()->get('date')])->links() }}
                                </div>
                            @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
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

            fnGetEntireModelByCategoryUniqueCode();
        });

        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = id => {
            $.ajax({
                url: `{{url('report/quality')}}/${id}`,
                type: "delete",
                data: {},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };

        /**
         * 根据种类获取型号
         */
        fnGetEntireModelByCategoryUniqueCode = () => {
            if ($("#selCategory").val()) {
                $.ajax({
                    url: `{{url('category')}}/${$("#selCategory").val()}`,
                    type: "get",
                    data: {},
                    async: true,
                    success: function (response) {
                        console.log('success:', response);
                        html = `<option value="">全部</option>`;
                        for (let key in response) {
                            html += `<option value="${response[key].unique_code}" ${response[key].unique_code == "{{request()->get('entire_model_unique_code')}}" ? 'selected' : ''}>${response[key].name}</option>`;
                        }
                        $("#selEntireModel").html(html);
                    },
                    error: function (error) {
                        // console.log('fail:', error);
                        if (error.status == 401) location.href = "{{url('login')}}";
                        alert(error.responseText);
                    },
                });
            }
        };
    </script>
@endsection
