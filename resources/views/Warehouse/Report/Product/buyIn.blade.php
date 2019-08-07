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
                <h4 class="modal-title">采购入库</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmBuyIn">
                    <input type="hidden" name="in_person_id" value="{{session()->get('account.id')}}">
                    <input type="hidden" name="in_reason" value="BUY_IN">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">数量：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="number" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="number" placeholder="数量" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">送货人姓名：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="send_person_name" placeholder="送货人姓名" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">送货人手机号：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="send_person_phone" placeholder="送货人手机号" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">生产厂家：</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="input-group">
                                <select id="selFactory" name="factory_id" class="form-control select2" style="width:100%;"></select>
                                <div class="input-group-addon"><a href="javascript:" onclick="fnCreateFactory()">添加工厂</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">厂家设备号：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="factory_device_code" placeholder="厂家设备号" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">入库日期：</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input name="in_at" type="text" class="form-control pull-right" id="datepicker" value="{{date('Y-m-d')}}">
                            </div>
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
                <button type="button" class="btn btn-success" onclick="fnStoreBuyIn({{$warehouseProductId}})"><i class="fa fa-check">&nbsp;</i>保存</button>
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

        // 获取工厂列表
        fnGetFactory();
    });
    /**
     * 获取工厂列表
     */
    fnGetFactory = function () {
        $.ajax({
            url: "{{url('factory')}}",
            type: "get",
            data: {},
            async: false,
            success: function (response) {
                html = '';
                $.each(response, function (key, value) {
                    html += `<option value="${value.id}">${value.name}</option>`;
                });
                $("#selFactory").html(html);
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };

    /*
     * 采购入库
     * @param {int} warehouseProductId 整件编号
     */
    fnStoreBuyIn = function (warehouseProductId) {
        $.ajax({
            url: `{{url('buyIn')}}/${warehouseProductId}`,
            type: "post",
            data: $("#frmBuyIn").serialize(),
            async: false,
            success: function (response) {
                // console.log('success:', response);
                alert(response);
                // location.reload();
            },
            error: function (error) {
                // console.log('fail:', error);
                alert(error.responseText);
            },
        });
    };

    /**
     * 打开添加工厂窗口
     */
    fnCreateFactory = function () {
        $.ajax({
            url: "{{url('factory/create')}}",
            type: "get",
            data: {},
            async: true,
            success: function (response) {
                $("#divCreateFactory").html(response);
                $("#modalCreateFactory").modal('show');
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };
</script>
