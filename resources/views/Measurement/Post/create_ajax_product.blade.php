<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加测试模板</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmStoreMeasurement">
                    <input type="hidden" name="warehouse_product_id" value="{{$warehouseProduct->id}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">整件：</label>
                        <label class="control-label col-md-9" style="text-align: left; font-weight: normal;">{{$warehouseProduct->name}}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">零件：</label>
                        <div class="col-sm-10 col-md-9">
                            <select name="warehouse_product_part_id" class="form-control select2" style="width: 100%;">
                                @foreach($warehouseProductParts as $warehouseProductPart)
                                    <option value="{{$warehouseProductPart->id}}">{{$warehouseProductPart->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">测试项：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="key" placeholder="测试项" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">范围值：</label>
                        <div class="col-sm-10 col-md-9">
                            <div class="input-group">
                                <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"
                                       name="allow_min" placeholder="最小值" value="">
                                <div class="input-group-addon">～</div>
                                <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"
                                       name="allow_max" placeholder="最大值" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">单位：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"
                                   name="unit" placeholder="单位" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">行为：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"
                                   name="operation" placeholder="行为" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">说明：</label>
                        <div class="col-sm-10 col-md-9">
                            <textarea name="explain" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-success" onclick="fnStoreMeasurement()"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    /*
     * 添加测试模板
     */
    fnStoreMeasurement = function () {
        $.ajax({
            url: "{{url('measurements')}}",
            type: "post",
            data: $("#frmStoreMeasurement").serialize(),
            async: false,
            success: function (response) {
                // console.log('success:', response);
                alert(response);
                // location.reload();
            },
            error: function (error) {
                // console.log('fail:', error);
                alert(error.responseText);
                if(error.status == 401) location.href="{{url('login')}}";
            },
        });
    };
</script>
