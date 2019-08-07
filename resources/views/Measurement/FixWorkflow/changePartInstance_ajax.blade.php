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
                <h3 class="modal-title">部件更换管理</h3>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmChangePartInstance" style="font-size: 18px;">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">部件型号：</label>
                        <div class="col-sm-10 col-md-8">
                            <select id="selPartModel" name="part_model_unique_code" class="form-control select2 input-lg" style="width:100%;" onchange="fnGetPartInstanceByPartModelUniqueCode(this.value)">
                                @foreach($partModels as $partModel)
                                    <option value="{{$partModel->unique_code}}">{{$partModel->name}}（{{$partModel->unique_code}}）</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">部件实例：</label>
                        <div class="col-sm-10 col-md-8">
                            <select id="selPartInstance" name="part_instance_identity_code" class="form-control select2 input-lg" style="width:100%;"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">备注：</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea name="note" cols="30" rows="3" class="form-control input-lg"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-lg btn-flat" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success btn-lg btn-flat" onclick="fnChangePartInstance()"><i class="fa fa-check">&nbsp;</i>保存</button>
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

        // 根据部件类型获取部件实例
        fnGetPartInstanceByPartModelUniqueCode($('#selPartModel').val());
    });

    /*
     * 更换部件实例
     */
    fnChangePartInstance = function () {
        $.ajax({
            url: `{{route('changePartInstance.post')}}`,
            type: "post",
            data: {
                entire_instance_identity_code: "{{$fixWorkflow->EntireInstance->identity_code}}",
                part_instance_identity_code: $('#selPartInstance').val(),
                fix_workflow_serial_number: "{{$fixWorkflow->serial_number}}"
            },
            async: false,
            success: function (response) {
                // console.log('success:', response);
                alert(response);
                location.href="?page={{request()->get('page',1)}}";
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };

    /**
     * 根据部件类型获取部件实例
     * @param partModelUniqueCode
     */
    fnGetPartInstanceByPartModelUniqueCode = partModelUniqueCode => {
        console.log(partModelUniqueCode);
        $.ajax({
            url: `{{url('part/instance')}}`,
            type: "get",
            data: {
                type: 'part_model_unique_code',
                part_model_unique_code: partModelUniqueCode,
                'status': ['BUY_IN', 'FIXED'],
            },
            async: true,
            success: function (response) {
                // console.log('success:', response);
                html = '';
                for (key in response) {
                    html += `<option value="${response[key].identity_code}">${response[key].factory_device_code}</option>`;
                }
                $('#selPartInstance').html(html);
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };
</script>
