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
                <h4 class="modal-title">编辑零件采购实例</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmUpdateWarehouseReportProductPart">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{$warehouseReportProductPart->warehouseProductPart->name}}：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="number" placeholder="{{$warehouseReportProductPart->number}}" value="{{$warehouseReportProductPart->number}}" min="1" max="{{$warehouseReportProductPart->number}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">入库日期：</label>
                        <div class="col-sm-10 col-md-9">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input name="in_at" type="text" class="form-control pull-right" id="datepicker" value="{{$warehouseReportProductPart->in_at}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">送货人姓名：</label>
                        <div class="col-sm-10 col-md-9">
                            <input type="text" name="send_person_name" class="form-control" value="{{$warehouseReportProductPart->send_person_name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">送货人电话：</label>
                        <div class="col-sm-10 col-md-9">
                            <input type="text" name="send_person_phone" class="form-control" value="{{$warehouseReportProductPart->send_person_phone}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success" onclick="fnUpdateWarehouseProcurementPartInstance()"><i class="fa fa-check">&nbsp;</i>保存</button>
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
     * 编辑零件采购实例
     */
    fnUpdateWarehouseProcurementPartInstance = function () {
        $.ajax({
            url: `{{url('warehouse/report/productPart',$warehouseReportProductPart->id)}}`,
            type: "put",
            data: $("#frmUpdateWarehouseReportProductPart").serialize(),
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
</script>
