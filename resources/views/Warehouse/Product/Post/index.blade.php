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
                <h3 class="box-title">整件列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('/warehouse/products/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>类目</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseProducts as $warehouseProduct)
                        <tr>
                            <td>{{$warehouseProduct->name}}</td>
                            <td>{{$warehouseProduct->category->name}}</td>
                            <td>
                                <a href="{{url('/warehouse/products',$warehouseProduct->unique_code)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$warehouseProduct->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                <a href="{{url('measurements')}}?warehouseProductId={{$warehouseProduct->id}}" class="btn btn-sm btn-info">测试模板列表</a>
                                <a href="javascript:" onclick="fnCreateMeasurement({{$warehouseProduct->id}})" class="btn btn-sm btn-info">添加测试模板</a>
                                {{--                                <a href="javascript:" onclick="fnCreateBuyIn({{$warehouseProduct->id}})" class="btn btn-sm btn-default">采购入库</a>--}}
                                <a href="{{url('warehouse/product/instance')}}?warehouseProductUniqueCode={{$warehouseProduct->unique_code}}" class="btn btn-sm btn-default">设备实例列表</a>
                                <a href="javascript:" onclick="fnEditWarehouseProductPartPivot({{$warehouseProduct->id}})" class="btn btn-sm btn-default">零件表</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($warehouseProducts->hasPages())
                <div class="box-footer">
                    {{ $warehouseProducts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('/warehouse/products')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };

        /**
         * 打开添加测试模板窗口
         */
        fnCreateMeasurement = function (warehouseProductId) {
            $.ajax({
                url: "{{url('measurements/create')}}",
                type: "get",
                data: {warehouseProductId: warehouseProductId, type: 'product'},
                async: true,
                success: function (response) {
                    $("#divModal").html(response);
                    $("#modal").modal('show');

                    $('.select2').select2();
                    // iCheck for checkbox and radio inputs
                    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                        checkboxClass: 'icheckbox_minimal-blue',
                        radioClass: 'iradio_minimal-blue'
                    });
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                },
            });
        };

        /**
         * 打开采购入库页面
         * @param {int} warehouseProductId 整件编号
         */
        fnCreateBuyIn = function (warehouseProductId) {
            $.ajax({
                url: `{{url('buyIn')}}/${warehouseProductId}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    $("#divModal").html(response);
                    $("#modal").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 打开零件用料窗口
         * @param warehouseProductId
         */
        fnEditWarehouseProductPartPivot = (warehouseProductId) => {
            $.ajax({
                url: `{{url('warehouse/product/pivot')}}`,
                type: "get",
                data: {warehouseProductId: warehouseProductId},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                    // alert(response);
                    // location.reload();
                    $("#divModal").html(response);
                    $("#modal").modal('show');
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
