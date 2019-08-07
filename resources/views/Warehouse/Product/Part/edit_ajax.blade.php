<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">编辑零件</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmUpdateWarehouseProductPart">
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-danger">名称*：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control"
                                   name="name" type="text" placeholder="名称" value="{{$warehouseProductPart->name}}"
                                   required autofocus onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">下标：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control"
                                   name="subscript" type="text" placeholder="下标" value="{{$warehouseProductPart->subscript}}"
                                   required onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">所属设备型号：</label>
                        <div class="col-sm-10 col-md-8">
                            <select id="selCategory" name="category_open_code" class="form-control select2" style="width:100%;">
                                @foreach($categories as $categoryOpenCode => $categoryName)
                                    <option value="{{$categoryOpenCode}}" {{$categoryOpenCode == $warehouseProductPart->category_open_code ? 'selected' : ''}}>{{$categoryName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-3 control-label">定期维修：</label>--}}
{{--                        <div class="col-sm-10 col-md-8">--}}
{{--                            <select name="parent_id" class="form-control select2" style="width:100%;">--}}
{{--                                @foreach($fixCycleTypes as $key=>$fixCycleType)--}}
{{--                                    <option value="{{$key}}" {{$key == $warehouseProductPart->flipFixCycleType($warehouseProductPart->fix_cycle_type) ? 'selected' : ''}}>{{$fixCycleType}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-3 control-label text-sm">定期维修时间：</label>--}}
{{--                        <div class="col-sm-10 col-md-8">--}}
{{--                            <input class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                   placeholder="定期维修时间" value="{{$warehouseProductPart->fix_cycle_value}}" name="subscript">--}}
{{--                        </div>--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">特性：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control"
                                   name="character" type="text" placeholder="特性" value="{{$warehouseProductPart->character}}"
                                   required onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-warning" onclick="fnUpdateWarehouseProductPart()"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });

    /*
     * 编辑零件
     */
    fnUpdateWarehouseProductPart = function () {
        $.ajax({
            url: "{{url('warehouse/product/part',$warehouseProductPart->id)}}",
            type: "put",
            data: $("#frmUpdateWarehouseProductPart").serialize(),
            async: false,
            success: function (response) {
                console.log('success:', response);
                alert(response);
                // location.reload();
            },
            error: function (error) {
                // console.log('fail:', error);
                alert(error.responseText);
                if (error.status == 401) location.href = "{{url('login')}}";
            },
        });

        // 刷新零件列表
        fnRefreshWarehouseProductPart(document.getElementById('selCategory').value);
    };
</script>
