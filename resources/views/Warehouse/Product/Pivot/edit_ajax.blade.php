<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">编辑零件数量</h4>
            </div>
            <div class="modal-body form-horizontal">
                <form id="frmUpdatePivot">
                    @foreach($pivots as $pivot)
                        <div class="form-group">
                            <label class="col-sm-2 control-label text-danger">{{$pivot->warehouseProductPart->name}}：</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="number" class="form-control" required autofocus onkeydown="if(event.keyCode==13){return false;}" placeholder="数量"
                                       name="number_{{$pivot->id}}" value="{{$pivot->number}}">
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>关闭</button>
                <button type="button" class="btn btn-warning" onclick="fnUpdateNumber()"><i class="fa fa-check">&nbsp;</i>保存</button>
            </div>
        </div>
    </div>
</div>
<script>
    /*
     * 编辑零件数量
     */
    fnUpdateNumber = function () {
        $.ajax({
            url: "{{url('warehouse/product/pivot',$warehouseProductId)}}",
            type: "put",
            data: $("#frmUpdatePivot").serialize(),
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
    };
</script>
