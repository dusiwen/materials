@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
<div class="modal fade" id="modalInstallOut">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">安装出库</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmInstallOut">
                    <input type="hidden" name="out_person_id" value="{{session()->get('account.id')}}">
                    <input type="hidden" name="out_reason" value="INSTALL_OUT">
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">接收或安装人姓名：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="draw_person_name" placeholder="接收或安装人姓名" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">接收或安装人电话：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="draw_person_phone" placeholder="接收或安装人电话" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">安装位置：</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="maintain_id" class="form-control select2" style="width: 100%;">
                                @foreach($maintains as $maintain)
                                    <option value="{{$maintain->id}}">{{$maintain->address_nickname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">出库日期：</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input name="outed_at" type="text" class="form-control pull-right" id="datepicker" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">上线状态：</label>
                        <div class="col-sm-10 col-md-8">
                            <label><input name="is_using" value="1" type="radio" name="r1" class="minimal">主要设备</label>
                            <label><input name="is_using" value="0" type="radio" name="r1" class="minimal" checked>备用设备</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">备注：</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea name="description" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success" onclick="fnStoreInstallOut('{{$warehouseProductInstanceOpenCode}}')"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<div id="divCreateFactory"></div>
<script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
    $(function () {
        $('.select2').select2();
        // iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
        });
    });

    /*
     * 安装出库
     * @param {string} warehouseProductInstanceOpenCode 设备实例代码
     */
    fnStoreInstallOut = function (warehouseProductInstanceOpenCode) {
        $.ajax({
            url: `{{url('installOut')}}/${warehouseProductInstanceOpenCode}`,
            type: "post",
            data: $("#frmInstallOut").serialize(),
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
