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
                <h3 class="box-title">排期列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>到期时间</th>
                        <th>设备类型</th>
                        <th>设备型号</th>
                        <th>工厂设备代码</th>
                        <th>编号</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseProductInstances as $warehouseProductInstance)
                        <tr>
                            <td>
                                {{date('Y-m-d H:i:s', strtotime("+{$warehouseProductInstance->warehouseProduct->fix_cycle_value} " . strtolower($warehouseProductInstance->warehouseProduct->flipFixCycleType()), $warehouseProductInstance->installed_at))}}
                            </td>
                            <td>{{$warehouseProductInstance->warehouseProduct ? $warehouseProductInstance->warehouseProduct->category->name : ''}}</td>
                            <td>{{$warehouseProductInstance->warehouseProduct->unique_code}}（{{$warehouseProductInstance->warehouseProduct->name}}）</td>
                            <td>{{$warehouseProductInstance->factory_device_code}}</td>
                            <td>{{$warehouseProductInstance->open_code}}</td>
                            {{--                            <td>--}}
                            {{--                                @if($warehouseProductInstance->warehouseProductPart)--}}
                            {{--                                    <a href="javascript:" class="btn btn-primary btn-sm" onclick="fnProcessWarehouseProductPlan({{$warehouseProductInstance->id}})"><i class="fa fa-wrench"></i></a>--}}
                            {{--                                    <a href="{{url('warehouse/product/planProcess')}}?warehouseProductPlanId={{$warehouseProductInstance->id}}" class="btn btn-sm btn-default"><i class="fa fa-bars"></i></a>--}}
                            {{--                                    <a href="javascript:" onclick="fnDelete({{$warehouseProductInstance->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>--}}
                            {{--                                @endif--}}
                            {{--                            </td>--}}
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
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('warehouse/product/plan')}}/${id}`,
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
         * 处理工单
         * @param {int} warehouseProductPlanId 维护排期编号
         */
        fnProcessWarehouseProductPlan = function (warehouseProductPlanId) {
            $.ajax({
                url: `{{url('processWarehouseProductPlan')}}/${warehouseProductPlanId}`,
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
