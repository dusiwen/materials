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
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">保存测试模板</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form id="frmUpdate" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">设备型号：</label>
                        <div class="col-sm-10 col-md-9">
                            <select id="selEntireModel" name="entire_model_unique_code" class="form-control select2" style="width: 100%;" onchange="fnGetWarehouseProductPartByWarehouseProductId(this.value)">
                                @foreach($entireModels as $entireModelUniqueCode => $entireModelName)
                                    <option value="{{$entireModelUniqueCode}}" {{$entireModelUniqueCode == $measurement->entire_model_unique_code ? 'selected' : ''}}>{{$entireModelName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">部件型号：</label>
                        <div class="col-sm-10 col-md-9">
                            <select id="selPartModel" name="part_model_unique_code" class="form-control select2" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">测试项：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="key" placeholder="测试项" value="{{$measurement->key}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标准值：</label>
                        <div class="col-sm-10 col-md-9">
                            <div class="input-group">
                                <input class="form-control" type="number" onkeydown="if(event.keyCode==13){return false;}"
                                       name="allow_min" placeholder="最小值" value="{{$measurement->allow_min}}">
                                <div class="input-group-addon">～</div>
                                <input class="form-control" type="number" onkeydown="if(event.keyCode==13){return false;}"
                                       name="allow_max" placeholder="最大值" value="{{$measurement->allow_max}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标准值描述：</label>
                        <div class="col-sm-10 col-md-9">
                            <textarea name="allow_explain" class="form-control" cols="30" rows="5" placeholder="无法用数值描述的内容">{{$measurement->allow_explain}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">单位：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"
                                   name="unit" placeholder="单位" value="{{$measurement->unit}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">行为：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"
                                   name="operation" placeholder="行为" value="{{$measurement->operation}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-sm">特性：</label>
                        <div class="col-sm-10 col-md-9">
                            <input placeholder="例如：电气特性" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="character" value="{{$measurement->character}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">说明：</label>
                        <div class="col-sm-10 col-md-9">
                            <textarea name="explain" cols="30" rows="5" class="form-control">{{$measurement->explain}}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <a href="{{url('measurements')}}?page={{request()->get('page',1)}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right"><i class="fa fa-check">&nbsp;</i>保存</a>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            // 根据整件编号获取零件编号
            fnGetWarehouseProductPartByWarehouseProductId($("#selEntireModel").val());
        });

        /**
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('measurements',$measurement->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.href="{{url('category')}}?page{{request()->get('id')}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 根据整件编号获取零件列表
         * @param {int} entireModelUniqueCode 整件编号
         */
        fnGetWarehouseProductPartByWarehouseProductId = function (entireModelUniqueCode) {
            console.log(entireModelUniqueCode);
            $.ajax({
                url: `{{url('pivotEntireModelAndPartModel')}}`,
                type: "get",
                data: {
                    type: 'entire_model_unique_code',
                    entire_model_unique_code: entireModelUniqueCode,
                },
                async: true,
                success: function (response) {
                    html = '<option value="">整件测试</option>';
                    for (let key in response) {
                        html += `<option value="${response[key].unique_code}" ${response[key].unique_code == "{{$measurement->part_model_unique_code}}" ? 'selected' : ''}>${response[key].name}</option>`;
                    }
                    $("#selPartModel").html(html);
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };
    </script>
@endsection
