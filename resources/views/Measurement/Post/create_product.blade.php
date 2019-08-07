@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">新建测试模板</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form id="frmStoreMeasurement" class="form-horizontal">
                    <input type="hidden" name="warehouse_product_id" value="{{$warehouseProduct->id}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">设备型号：</label>
                        <label class="control-label col-md-9" style="text-align: left; font-weight: normal;">{{$warehouseProduct->name}}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">零件：</label>
                        <div class="col-sm-10 col-md-9">
                            <select name="warehouse_product_part_id" class="form-control select2" style="width: 100%;">
                                @foreach($warehouseProductParts as $warehouseProductPart)
                                    <option value="{{$warehouseProductPart->id}}">{{$warehouseProductPart->name}}（{{$warehouseProductPart->character}}）</option>
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
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function(){
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });

        /**
         * 新建
         */
        fnCreate = function () {
            $.ajax({
                url: "{{url('measurements')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                }
            });
        };
    </script>
@endsection
