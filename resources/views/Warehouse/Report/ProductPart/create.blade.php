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
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">新建零件入库</h4>
            </div>
            <div class="modal-body">
                <form id="frmStoreWarehouseReportProductPart">
                    <div class="form-group">
                        <label>入库日期：</label>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input name="in_at" type="text" class="form-control pull-right" id="datepicker" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>送货人姓名：</label>
                        <input type="text" name="send_person_name" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>送货人电话：</label>
                        <input type="text" name="send_person_phone" class="form-control" value="">
                    </div>
                    @foreach($warehouseProcurementPart->warehouseProcurementPartInstances as $warehouseProcurementPartInstance)
                        <div class="form-group" id="fgWarehouseProcurementPartInstance_{{$warehouseProcurementPartInstance->id}}">
                            <label>{{$warehouseProcurementPartInstance->warehouseProductPart->name}}：</label>
                            <div class="input-group">
                                <input class="form-control" type="number" required onkeydown="if(event.keyCode==13){return false;}" min="1" max="{{$warehouseProcurementPartInstance->number}}" step="1"
                                       name="{{$warehouseProcurementPartInstance->warehouse_product_part_id}}" placeholder="{{$warehouseProcurementPartInstance->number}}" value="{{$warehouseProcurementPartInstance->number}}">
                                <span class="input-group-addon"><a href="javascript:" class="text-danger" onclick="fnDeleteWarehouseProcurementPartInstance({{$warehouseProcurementPartInstance->id}})"><i class="fa fa-times"></i></a></span>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success" onclick="fnStoreWarehouseReportProductPart()"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
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

    /*
     * 新建零件入库
     */
    fnStoreWarehouseReportProductPart = function () {
        // 检查所有的入库数值是否合法
        var isLegal = true;
        var numbers = $('input[type=number]');
        for (let i = 0; i < numbers.length; i++) {
            item = numbers[i];
            if (item.value < 1) {
                isLegal = false;
                break;
            }
            if (item.value > item.max) item.value = item.max;
        }
        if (!isLegal) {
            alert('数值必须是正整数');
            return null;
        }

        $.ajax({
            url: `{{url('warehouse/report/productPart')}}?warehouseProcurementPartId=` + '{{$warehouseProcurementPart->id}}',
            type: "post",
            data: $("#frmStoreWarehouseReportProductPart").serialize(),
            async: false,
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

    /**
     * 删除零件采购输入框
     * @param {int} warehouseProcurementInstanceId 零件采购实例编号
     */
    fnDeleteWarehouseProcurementPartInstance = function (warehouseProcurementInstanceId) {
        $(`#fgWarehouseProcurementPartInstance_${warehouseProcurementInstanceId}`).remove();
    };
</script>
