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
                <h3 class="box-title">入库单列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('warehouse/report/inOrder/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>处理人</th>
                        <th>入库时间</th>
                        <th>送货人姓名</th>
                        <th>送货人电话</th>
                        <th>入库单类型</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseReportInOrders as $warehouseReportInOrder)
                        <tr>
                            <td>{{$warehouseReportInOrder->processor->nickname}}</td>
                            <td>{{$warehouseReportInOrder->processed_at}}</td>
                            <td>{{$warehouseReportInOrder->send_processor_name}}</td>
                            <td>{{$warehouseReportInOrder->send_processor_phone}}</td>
                            <td>{{$warehouseReportInOrder->type}}</td>
                            <td>
                                <a href="{{url('warehouse/report/inOrder',$warehouseReportInOrder->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$warehouseReportInOrder->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($warehouseReportInOrders->hasPages())
                <div class="box-footer">
                    {{ $warehouseReportInOrders->links() }}
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
                url: `{{url('warehouse/report/inOrder')}}/${id}`,
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
    </script>
@endsection
