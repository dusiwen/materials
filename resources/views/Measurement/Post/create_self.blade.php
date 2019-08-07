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
                <h3 class="box-title">新建项目</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form id="frmCreate" class="form-horizontal">
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-2 control-label">项目名称：</label>--}}
{{--                        <div class="col-sm-10 col-md-9">--}}
{{--                            <select id="selEntireModel" name="entire_model_unique_code" class="form-control select2" style="width: 100%;" onchange="fnGetWarehouseProductPartByWarehouseProductId(this.value)">--}}
{{--                                @foreach($entireModels as $entireModelUniqueCode => $entireModelName)--}}
{{--                                    <option value="{{$entireModelUniqueCode}}">{{$entireModelName}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-2 control-label">部件型号：</label>--}}
{{--                        <div class="col-sm-10 col-md-9">--}}
{{--                            <select id="selPartModel" name="part_model_unique_code" class="form-control select2" style="width: 100%;"></select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目名称：</label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                   name="projectName" placeholder="项目名称" value="">
                        </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-2 control-label">标准值：</label>--}}
{{--                        <div class="col-sm-10 col-md-9">--}}
{{--                            <div class="input-group">--}}
{{--                                <input class="form-control" type="number" onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                       name="allow_min" placeholder="最小值" value="">--}}
{{--                                <div class="input-group-addon">～</div>--}}
{{--                                <input class="form-control" type="number" onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                       name="allow_max" placeholder="最大值" value="">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-2 control-label">标准值描述：</label>--}}
{{--                        <div class="col-sm-10 col-md-9">--}}
{{--                            <textarea name="allow_explain" class="form-control" cols="30" rows="5" placeholder="无法用数值描述的内容"></textarea>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="col-sm-2 control-label">WBS元素：</label>--}}
{{--                        <div class="col-sm-10 col-md-9">--}}
{{--                            <input class="form-control" type="text" onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                   name="WBS" placeholder="WBS元素" value="">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注：</label>
                        <div class="col-sm-10 col-md-9">
                            <textarea name="date" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <a href="{{url('measurements')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                <a href="javascript:" onclick="fnCreate()" class="btn btn-success btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
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
         * 新建
         */
        fnCreate = function () {
            $.ajax({
                url: "{{url('measurements')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.href = "{{url("/measurements")}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
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
                        html += `<option value="${response[key].unique_code}">${response[key].name}</option>`;
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
