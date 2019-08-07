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
                <h3 class="modal-title">保存实测模糊描述</h3>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmStoreMeasuredExplain" style="font-size: 18px;">
                    <input type="hidden" name="serial_number" value="{{$fixWorkflowRecordSerialNumber}}">
                    <div class="form-group">
                        <label class="col-md-3 control-label">测试项：</label>
                        <label class="col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$measurement->key}}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">标准描述：</label>
                        <label class="col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$measurement->allow_explain}}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">实测描述：</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea name="measured_value" cols="30" rows="5" class="form-control input-lg" required autofocus placeholder="例如：无裂纹、无裂痕">{{$fixWorkflowRecord->measured_value}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">是否合格：</label>
                        <label class="control-label" style="text-align: left; font-weight: normal;"><input name="is_allow" type="radio" checked class="minimal input-lg" value="1">合格</label>
                        <label class="control-label" style="text-align: left; font-weight: normal;"><input name="is_allow" type="radio" class="minimal input-lg" value="0">不合格</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left btn-lg" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success btn-flat btn-lg" onclick="fnStoreMeasuredExplain()"><i class="fa fa-check">&nbsp;</i>保存</button>
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
     * 保存实测模糊描述
     */
    fnStoreMeasuredExplain = function () {
        $.ajax({
            url: `{{url('measurement/fixWorkflowRecord/saveMeasuredExplain')}}`,
            type: "post",
            data: $("#frmStoreMeasuredExplain").serialize(),
            async: false,
            success: function (response) {
                // console.log('success:', response);
                // alert(response);
                location.reload();
            },
            error: function (error) {
                // console.log('fail:', error);
                if(error.status == 401) location.href="{{url('login')}}";
                alert(error.responseText);
            },
        });
    };
</script>
