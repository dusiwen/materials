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
                <h3 class="box-title">零件入库列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('warehouse/report/productPart/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>零件名称</th>
                        <th>数量</th>
                        <th>入库日期</th>
                        <th>入库人</th>
                        <th>送货人姓名</th>
                        <th>送货人电话</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseProcurementPart->warehouseReportProductParts as $warehouseReportProductPart)
                        <tr>
                            <td>{{$warehouseReportProductPart->warehouseProductPart->name}}</td>
                            <td>{{$warehouseReportProductPart->number}}</td>
                            <td>{{$warehouseReportProductPart->in_at}}</td>
                            <td>{{$warehouseReportProductPart->inPerson->nickname}}</td>
                            <td>{{$warehouseReportProductPart->send_person_name}}</td>
                            <td>{{$warehouseReportProductPart->send_person_phone}}</td>
                            <td>
                                <a href="javascript:" class="btn btn-primary btn-sm" onclick="fnEditWarehouseReportProductPart({{$warehouseReportProductPart->id}})"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$warehouseReportProductPart->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('warehouse/report/productPart')}}/${id}`,
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
         * 打开编辑零件入库窗口
         * @param {int} warehouseReportProductPartId 零件入库编号
         */
        fnEditWarehouseReportProductPart = function (warehouseReportProductPartId) {
            $.ajax({
                url: `{{url('warehouse/report/productPart')}}/${warehouseReportProductPartId}/edit`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    $("#divModal").html(response);
                    $("#modal").modal("show");
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
