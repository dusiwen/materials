@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">保存工单操作</h4>
            </div>
            <div class="modal-body">
                <form id="frmUpdateFixWorkflowProcess" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">名称：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left;">{{$fixWorkflowProcess->measurement->warehouseProductPart->name}}{{$fixWorkflowProcess->measurement->operation ? '（'.$fixWorkflowProcess->measurement->operation.'）' : ''}}</label>
                            <label class="col-sm-3 control-label">标准值：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left;">{{$fixWorkflowProcess->measurement->allow_min != $fixWorkflowProcess->measurement->allow_max ? $fixWorkflowProcess->measurement->allow_min . $fixWorkflowProcess->measurement->unit . '～' : ''}}{{$fixWorkflowProcess->measurement->allow_max . $fixWorkflowProcess->measurement->unit}}</label>
                            <label class="col-sm-3 control-label">说明：</label>
                            <label class="col-sm-10 col-md-8 control-label" style="text-align: left;">{{$fixWorkflowProcess->measurement->description}}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">实际值：</label>
                            <div class="col-sm-10 col-md-8">
                                <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="measured_value" placeholder="{{$fixWorkflowProcess->measurement->allow_min != $fixWorkflowProcess->measurement->allow_max ? $fixWorkflowProcess->measurement->allow_min . $fixWorkflowProcess->measurement->unit . '～' : ''}}{{$fixWorkflowProcess->measurement->allow_max . $fixWorkflowProcess->measurement->unit}}" value="{{$fixWorkflowProcess->measured_value}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">描述：</label>
                            <div class="col-sm-10 col-md-8">
                                <textarea name="description" class="form-control" cols="30" rows="5">{{$fixWorkflowProcess->description}}</textarea>
                            </div>
                        </div>
                        <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">测试时间:</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="processed_at" type="text" class="form-control pull-right" id="datepicker" value="{{$fixWorkflowProcess->processed_at}}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-warning" onclick="fnUpdateFixWorkflowProcess({{$fixWorkflowProcess->id}})"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        if ($('.select2').length > 0) {
            $('.select2').select2();
        }
        // iCheck for checkbox and radio inputs
        if ($('input[type="checkbox"].minimal, input[type="radio"].minimal').length > 0) {
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        }

        $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
        });
    });

    /*
     * 保存工单操作
     */
    fnUpdateFixWorkflowProcess = function () {
        $.ajax({
            url: `{{url('measurement/fixWorkflowProcess',$fixWorkflowProcess->id)}}`,
            type: "put",
            data: $("#frmUpdateFixWorkflowProcess").serialize(),
            async: false,
            success: function (response) {
                // console.log('success:', response);
                alert(response);
                location.reload();
            },
            error: function (error) {
                // console.log('fail:', error);
                alert(error.responseText);
            },
        });
    };
</script>
