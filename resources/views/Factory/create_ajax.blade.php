<div class="modal fade" id="modalCreateFactory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加工厂工厂</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmCreateFactory">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-10 col-md-9">
                            <input placeholder="名称" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="name" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-sm">联系电话：</label>
                        <div class="col-sm-10 col-md-9">
                            <input placeholder="联系电话" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="phone" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">网址：</label>
                        <div class="col-sm-10 col-md-9">
                            <input placeholder="网址" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                   name="official_home_link" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success" onclick="fnStoreFactory()"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    /*
     * 添加工厂
     */
    fnStoreFactory = function () {
        $.ajax({
            url: "{{url('factory')}}",
            type: "post",
            data: $("#frmCreateFactory").serialize(),
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

        // 刷新工厂列表
        fnGetFactory();
    };
</script>
