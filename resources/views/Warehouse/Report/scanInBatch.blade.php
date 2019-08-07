@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@endsection
@section('content')
    <form class="form-horizontal" id="frmUpdate">
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">添加物资入库单</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">入库类型：</label>
                            <div class="col-sm-10 col-md-8">
                                <select id="selCategory" name="category_unique_code" class="form-control select2" style="width: 100%;" onchange="fnGetPartModelByCategoryUniqueCode(this.value)">
                                    <option value="采购物资入库">采购物资入库</option>
                                    <option value="调拨物资入库">调拨物资入库</option>
                                    <option value="工程结余物资退库">工程结余物资退库</option>
                                    <option value="退出退役资产保管入库">退出退役资产保管入库</option>
                                    <option value="其他单位委托代保管物资入库">其他单位委托代保管物资入库</option>
                                    <option value="废旧物资入库">废旧物资入库</option>
                                    <option value="供应商寄存物资入库">供应商寄存物资入库</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">物资编码：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="物资编码" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_MaterialCode" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">物资名称：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="物资名称" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_MaterialName" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">批次：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="批次" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_Batch" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">单位：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="单位" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_Unit" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">数量：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="数量" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_Number" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">单价：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="单价" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_Price" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">金额：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="金额" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_Sum" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">WBS元素：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="WBS元素" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_WBS" value="">
                            </div>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label class="col-sm-3 control-label">供应商：</label>--}}
{{--                            <div class="col-sm-10 col-md-8">--}}
{{--                                <input placeholder="供应商" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                       name="name" value="">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">备注：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="备注" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockIn_Remark" value="">
                            </div>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label class="col-sm-3 control-label text-sm">设备类型统一代码：</label>--}}
{{--                            <div class="col-sm-10 col-md-8">--}}
{{--                                <input placeholder="设备类型统一代码" class="form-control disabled" disabled type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                       name="unique_code" value="">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="col-sm-3 control-label">设备种类：</label>--}}
{{--                            <div class="col-sm-10 col-md-8">--}}
{{--                                <select id="selCategory" name="category_unique_code" class="form-control select2" style="width: 100%;" onchange="fnGetPartModelByCategoryUniqueCode(this.value)">--}}
{{--                                    @foreach($categories as $categoryUniqueCode => $categoryName)--}}
{{--                                        <option value="{{$categoryUniqueCode}}" {{$categoryUniqueCode == $entireModel->category_unique_code ? 'selected' : ''}}>{{$categoryUniqueCode .' ： '.$categoryName}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="col-sm-3 control-label text-sm">维修周期单位：</label>--}}
{{--                            <div class="col-sm-10 col-md-8">--}}
{{--                                <select name="fix_cycle_unit" class="form-control select2" style="width: 100%;">--}}
{{--                                    @foreach(\App\Model\EntireModel::$FIX_CYCLE_UNIT as $fixCycleUnitKey => $fixCycleUnitValue)--}}
{{--                                        <option value="{{$fixCycleUnitKey}}" {{$fixCycleUnitKey == $entireModel->prototype('fix_cycle_unit') ? 'selected' : ''}}>{{$fixCycleUnitValue}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="col-sm-3 control-label text-sm">维修周期长度：</label>--}}
{{--                            <div class="col-sm-10 col-md-8">--}}
{{--                                <input placeholder="维修周期长度" class="form-control" type="number" min="1" max="99" step="1" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                       name="fix_cycle_value" value="">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="box-footer">
                            <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                            <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>保存</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">智能托盘选择</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <div class="box-body">
                            <div class="row" id="divPartModel"></div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="box box-primary">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <h3 class="box-title">整件型号管理</h3>--}}
{{--                            --}}{{--右侧最小化按钮--}}
{{--                            <div class="box-tools pull-right">--}}
{{--                                <a href="javascript:" class="btn btn-flat btn-lg btn-default" onclick="fnCreateEntireModelIdCode()">新建</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <br>--}}
{{--                        <div class="box-body">--}}
{{--                            <div id="divEntireModelIdCode"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </section>
    </form>
    <section>
        <div id="divModalCreateModelIdCode"></div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
{{--        $(function () {--}}
{{--            $('.select2').select2();--}}
{{--            // iCheck for checkbox and radio inputs--}}
{{--            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({--}}
{{--                checkboxClass: 'icheckbox_minimal-blue',--}}
{{--                radioClass: 'iradio_minimal-blue'--}}
{{--            });--}}
{{--            //Date picker--}}
{{--            $('#datepicker').datepicker({--}}
{{--                autoclose: true,--}}
{{--                format: 'yyyy-mm-dd'--}}
{{--            });--}}

{{--            // 刷新部件列表--}}
{{--            fnGetPartModelByCategoryUniqueCode($('#selCategory').val());--}}

{{--            // 刷新整件型号列表--}}
{{--            fnGetEntireModelIdCodeByEntireModelUniqueCode();--}}
{{--        });--}}

        /**
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: `{{url('entire/model',$entireModel->id)}}`,
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href = "{{url('entire/model')}}?page{{request()->get('page',1)}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

{{--        var currentPartModels = {!! $partModels !!};--}}

{{--        /**--}}
{{--         * 根据设备类型获取零件类型--}}
{{--         * @param {string} categoryUniqueCode 设备类型统一代码--}}
{{--         */--}}
{{--        fnGetPartModelByCategoryUniqueCode = function (categoryUniqueCode) {--}}
{{--            $.ajax({--}}
{{--                url: `{{url('part/model')}}`,--}}
{{--                type: "get",--}}
{{--                data: {--}}
{{--                    type: 'category_unique_code',--}}
{{--                    category_unique_code: categoryUniqueCode,--}}
{{--                },--}}
{{--                async: true,--}}
{{--                success: function (response) {--}}
{{--                    html = '';--}}
{{--                    for (let key in response) {--}}
{{--                        html += `<div class="col-md-6">--}}
{{--    <label class="control-label" style="text-align: left; font-weight: normal;">--}}
{{--        <input--}}
{{--            name="part_model_unique_code[]"--}}
{{--            type="checkbox"--}}
{{--            class="minimal"--}}
{{--            value="${response[key].unique_code}"--}}
{{--            id="${response[key].unique_code}"--}}
{{--            ${currentPartModels.indexOf(response[key].unique_code) > -1 ? 'checked' : ''}>--}}
{{--                ${response[key].name}--}}
{{--    </label>--}}
{{--</div>`;--}}
{{--                    }--}}
{{--                    $('#divPartModel').html(html);--}}
{{--                    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({--}}
{{--                        checkboxClass: 'icheckbox_minimal-blue',--}}
{{--                        radioClass: 'iradio_minimal-blue'--}}
{{--                    });--}}
{{--                },--}}
{{--                error: function (error) {--}}
{{--                    // console.log('fail:', error);--}}
{{--                    if (error.status == 401) location.href = "{{url('login')}}";--}}
{{--                    alert(error.responseText);--}}
{{--                },--}}
{{--            });--}}
{{--        };--}}

{{--        /**--}}
{{--         * 根据类型获取型号列表--}}
{{--         */--}}
{{--        fnGetEntireModelIdCodeByEntireModelUniqueCode = () => {--}}
{{--            $.ajax({--}}
{{--                url: `{{url('entire/modelIdCode')}}`,--}}
{{--                type: "get",--}}
{{--                data: {--}}
{{--                    entire_model_unique_code: "{{$entireModel->unique_code}}",--}}
{{--                    category_unique_code: "{{$entireModel->category_unique_code}}"--}}
{{--                },--}}
{{--                async: true,--}}
{{--                success: function (response) {--}}
{{--                    html = '';--}}
{{--                    for (let key in response) {--}}
{{--                        console.log(response[key].code);--}}
{{--                        html += `--}}
{{--                        <label class="control-label" style="text-align: left; font-weight: normal;">${response[key].code}</label>--}}
{{--                        <a href="javascript:" onclick="fnDeleteEntireModelIdCode('${response[key].code}')"><i class="fa fa-times" style="color: red;"></i></a>--}}
{{--                        `;--}}
{{--                    }--}}
{{--                    $("#divEntireModelIdCode").html(html);--}}

{{--                },--}}
{{--                error: function (error) {--}}
{{--                    // console.log('fail:', error);--}}
{{--                    if (error.status == 401) location.href = "{{url('login')}}";--}}
{{--                    alert(error.responseText);--}}
{{--                },--}}
{{--            });--}}
{{--        };--}}

{{--        /**--}}
{{--         * 新建整件型号--}}
{{--         * @param categoryUniqueCode--}}
{{--         * @param entireModelUniqueCode--}}
{{--         */--}}
{{--        fnCreateEntireModelIdCode = (categoryUniqueCode, entireModelUniqueCode) => {--}}
{{--            $.ajax({--}}
{{--                url: `{{url('entire/modelIdCode/create')}}`,--}}
{{--                type: "get",--}}
{{--                data: {--}}
{{--                    categoryUniqueCode: categoryUniqueCode,--}}
{{--                    entireModelUniqueCode: entireModelUniqueCode--}}
{{--                },--}}
{{--                async: true,--}}
{{--                success: function (response) {--}}
{{--                    console.log(response);--}}
{{--                    $("#divModalCreateModelIdCode").html(response);--}}
{{--                    $("#modalStoreEntireModelIdCode").modal("show");--}}
{{--                },--}}
{{--                error: function (error) {--}}
{{--                    // console.log('fail:', error);--}}
{{--                    if (error.status == 401) location.href = "{{url('login')}}";--}}
{{--                    alert(error.responseText);--}}
{{--                },--}}
{{--            });--}}
{{--        };--}}
    </script>
@endsection
