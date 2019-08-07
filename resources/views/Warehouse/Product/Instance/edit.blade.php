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
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">设备实例详情</h3>
                        {{--右侧最小化按钮--}}
{{--                        <div class="box-tools pull-right">--}}
{{--                            @if($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'BUY_IN')--}}
{{--                                --}}{{--采购入库--}}
{{--                                <a href="javascript:" onclick="fnCreateInstallOut('{{$warehouseProductInstance->open_code}}')" class="btn btn-sm btn-default">安装出库</a>--}}
{{--                            @elseif($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'INSTALLED')--}}
{{--                                --}}{{--已安装--}}
{{--                                <a href="javascript:" onclick="fnCreateFixBySend('{{$warehouseProductInstance->open_code}}')" class="btn btn-sm btn-default">维修入库</a>--}}
{{--                            @elseif($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'FIX_BY_SEND')--}}
{{--                                --}}{{--返修入库--}}
{{--                                <a href="{{url('measurement/fixWorkflow',$warehouseProductInstance->fix_workflow_id)}}/edit" class="btn btn-sm btn-default">查看工单</a>--}}
{{--                            @elseif($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'FIX_TO_OUT')--}}
{{--                                --}}{{--返修入库--}}
{{--                                <a href="{{url('measurement/fixWorkflow',$warehouseProductInstance->fix_workflow_id)}}/edit" class="btn btn-sm btn-default">查看工单</a>--}}
{{--                            @endif--}}
{{--                            <a href="javascript:" class="btn btn-sm btn-danger" onclick="fnScrap({{$warehouseProductInstance->id}})"><i class="fa fa-times">&nbsp;</i>报废</a>--}}
{{--                        </div>--}}
                    </div>
                    <br>
                    <form class="form-horizontal" id="frmUpdate">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">编号：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->open_code}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">名称：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->warehouseProduct->name}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">类目：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->warehouseProduct->category->name}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">厂家：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->factory->name}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">厂家编码：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->factory_open_code}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">当前状态：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->status}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-sm">是否是主要设备：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$warehouseProductInstance->is_using ? '主要设备' : '备用设备'}}</label>
                        </div>

                        <div class="box-footer">
                            <a href="{{url('warehouse/product/instance')}}?warehouseProductId={{$warehouseProductInstance->id}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                            {{--                            <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right"><i class="fa fa-check">&nbsp;</i>编辑</a>--}}
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">工单历史</h3>
                    </div>
                    <br>
                    <div class="box-body">
                        <ul class="timeline">
                        @foreach($fixWorkflows as $fixWorkflow)
                            <!-- timeline time label -->
                                <li class="time-label"><span class="bg-red">{{$fixWorkflow['updated_at']}}</span></li>
                                @if($fixWorkflow['fix_workflow_processes'])
                                    @foreach($fixWorkflow['fix_workflow_processes'] as $fixWorkflowProcessKey => $fixWorkflowProcessValue)
                                        <li>
                                            <i class="fa fa-wrench bg-blue"></i>
                                            <div class="timeline-item">
                                                {{--                                                <span class="time"><i class="fa fa-clock-o"></i></span>--}}
                                                <h3 class="timeline-header"><a href="{{url('measurement/fixWorkflow',$fixWorkflow['id'])}}/edit">{{$fixWorkflowProcessKey}}</a></h3>
                                                <div class="timeline-body">
                                                    @foreach($fixWorkflowProcessValue as $key => $fixWorkflowProcess)
                                                        参考值：{{$fixWorkflowProcess['measurement']['allow_min']!= $fixWorkflowProcess['measurement']['allow_max']? $fixWorkflowProcess['measurement']['allow_min'].'～' : ''}}{{$fixWorkflowProcess['measurement']['allow_max']}}{{$fixWorkflowProcess['measurement']['unit']}}
                                                        <br>
                                                        测试值：{{$fixWorkflowProcess['measurement']['key']}}&nbsp;&nbsp;{{$fixWorkflowProcess['measured_value']}}{{$fixWorkflowProcess['measurement']['unit']}}
                                                        <br>
                                                        描述：{{$fixWorkflowProcess['description']}}
                                                        @if($key != count($fixWorkflowProcessValue)-1)
                                                            <hr>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                {{--                                                <div class="timeline-footer">--}}
                                                {{--                                                    <a class="btn btn-primary btn-xs">...</a>--}}
                                                {{--                                                </div>--}}
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
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
        });

        /**
         * 编辑
         */
        fnUpdate = function () {
            $.ajax({
                url: `{{url('warehouse/product/instance',$warehouseProductInstance->id)}}`,
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 打开设备安装出库窗口
         * @param {string} warehouseProductInstanceOpenCode 设备实例代码
         */
        fnCreateInstallOut = function (warehouseProductInstanceOpenCode) {
            $.ajax({
                url: `{{url('installOut')}}/${warehouseProductInstanceOpenCode}`,
                type: "get",
                data: {},
                async: false,
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    // location.reload();
                    $("#divModal").html(response);
                    $("#modalInstallOut").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 维修入库
         * @param warehouseProductInstanceOpenCode
         */
        fnCreateFixBySend = function (warehouseProductInstanceOpenCode) {
            $.ajax({
                url: `{{url('fixBySend')}}/${warehouseProductInstanceOpenCode}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    // location.reload();
                    $("#divModal").html(response);
                    $("#modalFixBySend").modal("show");
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 报废设备
         * @param {int} warehouseProductInstanceId 设备实例编号
         */
        fnScrap = function (warehouseProductInstanceId) {
            $.ajax({
                url: `{{url('scrapWarehouseProductInstance')}}/${warehouseProductInstanceId}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };
    </script>
@endsection
