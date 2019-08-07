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
                <h3 class="box-title">设备实例列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('warehouse/product/instance/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>设备代码</th>
                        <th>整件</th>
                        <th>状态</th>
                        <th>供应商</th>
                        <th>厂家设备代码</th>
                        <th>台账位置</th>
                        <th>安装时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseProductInstances as $warehouseProductInstance)
                        <tr>
                            <td>{{$warehouseProductInstance->id}}</td>
                            <td>{{$warehouseProductInstance->open_code}}</td>
                            <td><a href="{{url('warehouse/products',$warehouseProductInstance->warehouse_product_unique_code)}}/edit">{{$warehouseProductInstance->warehouse_product_unique_code ? $warehouseProductInstance->warehouseProduct->name : ''}}</a></td>
                            <td>{{$warehouseProductInstance->status}}</td>
                            <td>{{$warehouseProductInstance->factory_unique_code ? $warehouseProductInstance->factory->name : ''}}</td>
                            <td>{{$warehouseProductInstance->factory_device_code}}</td>
                            <td>{{$warehouseProductInstance->maintain_unique_code}}</td>
                            <td>{{$warehouseProductInstance->installed_at}}</td>
{{--                            <td>{{$warehouseProductInstance}}</td>--}}
                            <td>
                                <a href="{{url('warehouse/product/instance',$warehouseProductInstance->id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$warehouseProductInstance->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
{{--                                @if($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'BUY_IN')--}}
{{--                                    --}}{{--采购入库--}}
{{--                                    <a href="javascript:" onclick="fnCreateInstallOut('{{$warehouseProductInstance->open_code}}')" class="btn btn-sm btn-default">安装出库</a>--}}
{{--                                @elseif($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'INSTALLED')--}}
{{--                                    --}}{{--已安装--}}
{{--                                    <a href="javascript:" onclick="fnCreateFixBySend('{{$warehouseProductInstance->open_code}}')" class="btn btn-sm btn-default">维修入库</a>--}}
{{--                                @elseif($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'FIX_BY_SEND')--}}
{{--                                    --}}{{--返修入库--}}
{{--                                    <a href="{{url('measurement/fixWorkflow',$warehouseProductInstance->fix_workflow_id)}}/edit" class="btn btn-sm btn-default">查看工单</a>--}}
{{--                                @elseif($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'FIX_TO_OUT')--}}
{{--                                    --}}{{--返修入库--}}
{{--                                    <a href="{{url('measurement/fixWorkflow',$warehouseProductInstance->fix_workflow_id)}}/edit" class="btn btn-sm btn-default">查看工单</a>--}}
{{--                                @endif--}}
{{--                                <a href="javascript:" class="btn btn-sm btn-danger" onclick="fnScrap({{$warehouseProductInstance->id}})"><i class="fa fa-times">&nbsp;</i>报废</a>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($warehouseProductInstances->hasPages())
                <div class="box-footer">
                    {{ $warehouseProductInstances->links() }}
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
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('warehouse/product/instance')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
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
