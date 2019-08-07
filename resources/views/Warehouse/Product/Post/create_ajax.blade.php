<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加型号</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmCreateWarehouseProduct">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-danger">名称*：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control"
                                   name="name" type="text" placeholder="名称" value=""
                                   required autofocus onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">统一代码：</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" placeholder="统一代码" onkeydown="if(event.keyCode==13){return false;}" required
                                   name="unique_code" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success" onclick="fnCreateWarehouseProduct()"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    fnCreateWarehouseProduct = function () {
        $.ajax({
            url: "{{url('warehouse/products')}}",
            type: "post",
            data: $("#frmCreateWarehouseProduct").serialize(),
            async: false,
            success: function (response) {
                // console.log('success:', response);
                alert(response);
                // location.reload();
            },
            error: function (error) {
                // console.log('fail:', error);
                alert(error.responseText);
                if (error.status == 401) location.href = "{{url('login')}}";
            },
        });

        // 刷新型号列表
        fnRefreshWarehouseProduct();
    };
</script>
