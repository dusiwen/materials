<div class="modal fade" id="modalFixBySend">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加返修入库</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmStoreFixBySend">
                    <input type="hidden" name="in_person_id" value="{{session()->get('account.id')}}">
                    <input type="hidden" name="in_reason" value="FIX_BY_SEND">
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">报修或送货人姓名：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="send_person_name" placeholder="报修或送货人姓名" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">报修或送货人电话：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="send_person_phone" placeholder="报修或送货人电话" value="">
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
                <button type="button" class="btn btn-success" onclick="fnStoreFixBySend('{{$warehouseProductInstanceOpenCode}}')"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    /*
     * 添加返修入库
     * @param {string} warehouseProductInstanceOpenCode 设备实例代码
     */
    fnStoreFixBySend = function (warehouseProductInstanceOpenCode) {
        $.ajax({
            url: `{{url('fixBySend')}}/${warehouseProductInstanceOpenCode}`,
            type: "post",
            data: $("#frmStoreFixBySend").serialize(),
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
