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
                            <h3 class="box-title">添加物资出库单</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">物资名称：</label>
                            <div class="col-sm-10 col-md-8">
                                <a href="javascript:" class="btn btn-flat btn-lg btn-default" onclick="fnCreateEntireModelIdCode()">物资选择</a>&nbsp;&nbsp;&nbsp;{{$MaterialName}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">出库类型：</label>
                            <div class="col-sm-10 col-md-8">
                                <select id="selCategory" name="StockOut_Type" class="form-control select2" style="width: 100%;" onchange="fnGetPartModelByCategoryUniqueCode(this.value)">
                                    @foreach($stockout_type as $v)
                                        <option value="{{$v->stockout_type}}">{{$v->stockout_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">项目名称：</label>
                            <div class="col-sm-10 col-md-8">
                                <select id="selCategory" name="project_name" class="form-control select2" style="width: 100%;" onchange="fnGetPartModelByCategoryUniqueCode(this.value)">
                                    @foreach($project as $v)
                                        <option value="{{$v->project_name}}">{{$v->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">总数量：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="数量" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockOut_Number" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">单价：</label>
                            <div class="col-sm-10 col-md-8">
                                <input placeholder="单价" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                       name="StockOut_Price" value="">
                            </div>
                        </div>


                        {{--                        <div class="form-group">--}}
                        {{--                            <label class="col-sm-3 control-label">金额：</label>--}}
                        {{--                            <div class="col-sm-10 col-md-8">--}}
                        {{--                                <input placeholder="金额" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"--}}
                        {{--                                       name="StockOut_Sum" value="">--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
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
                                       name="StockOut_Remark" value="">
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                            <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>添加</a>
                        </div>
                    </div>
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">物资列表</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>


                        <div class="box-body table-responsive">
                            <table class="table table-hover table-condensed" id="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>物资编码</th>
                                    <th>物资名称</th>
                                    <th>单位</th>
                                    <th>数量</th>
                                    <th>总重量(kg)</th>
                                    <th>单价(元)</th>
                                    <th>金额(元)</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stockouttest as $v)
                                    <tr>
                                        <th>{{$i++}}</th>
                                        <th>{{$v->StockOut_MaterialCode}}</th>
                                        <th>{{$v->StockOut_MaterialName}}</th>
                                        <th>{{$v->StockOut_Unit}}</th>
                                        <th>{{$v->StockOut_Number}}</th>
                                        <th>{{$v->StockOut_Weight}}</th>
                                        <th>{{$v->StockOut_Price}}</th>
                                        <th>{{$v->StockOut_Sum}}</th>
                                        <th>{{$v->StockOut_Remark}}</th>
                                        <td>

                                            <div class="btn-group btn-group-lg">
                                                <a href="javascript:" onclick="fnDelete({{$v->id}})" class="btn btn-danger btn-flat">删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <div class="box-footer">
                            {{--                        <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>--}}
                            <a href="javascript:" onclick="fn()" class="btn btn-warning btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>保存</a>
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
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-condensed" id="table" >
                                <thead>
                                <tr>
                                    <th>选择</th>
                                    {{--                                    <th>托盘编码</th>--}}
                                    <th>托盘位置</th>
                                    <th>物资名称</th>
                                    <th>可出库数量</th>
                                    <th>出库数量</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tray as $v)
                                    <tr>
                                        <th>
                                            <input name="tray[]" id="${response[key].unique_code}" type="checkbox" value="{{$v->id}}">
                                        </th>
                                        {{--                                        <th>{{$v->tray_code}}</th>--}}
                                        <th>{{$v->place}}</th>
                                        <th>{{$v->MaterialName}}</th>
                                        @if(!empty($EachWeight) && !empty($v->ResidueWeight))
                                            <th>{{intval($v->weight/$EachWeight)}}</th>
                                        @else
                                            <th></th>
                                        @endif
                                        <th>
                                            <input placeholder="数量" class="form-control" type="text" name="Numbers[]" value="">
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
         * 添加对应物资到物资列表(出库)
         */
        fnUpdate = function () {
            $.ajax({
                url: `{{url('category',$MaterialName)}}`,
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.href = "{{url('category')}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };




        /**
         * 将物资列表中的数据存入  出库单中
         */
        fn = function () {
            $.ajax({
                url: `{{url('category/create')}}`,
                type: "get",
                data: {},
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    location.href = "{{url('factory')}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        {{--var currentPartModels = {!! $partModels !!};--}}

        /**
         * 根据设备类型获取零件类型
         * @param {string} categoryUniqueCode 设备类型统一代码
         */
        {{--fnGetPartModelByCategoryUniqueCode = function (categoryUniqueCode) {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('part/model')}}`,--}}
        {{--        type: "get",--}}
        {{--        data: {--}}
        {{--            type: 'category_unique_code',--}}
        {{--            category_unique_code: categoryUniqueCode,--}}
        {{--        },--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            html = '';--}}
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

        /**
         * 物资选择
         * @param categoryUniqueCode
         * @param entireModelUniqueCode
         */
        fnCreateEntireModelIdCode = () => {
            $.ajax({
                {{--url: `{{url('maintain/post')}}`,--}}
                url: `{{url('entire/modelIdCode/create')}}`,
                type: "get",
                data: {type:"stockout"
                },
                async: true,
                success: function (response) {
                    // console.log(response);
                    $("#divModalCreateModelIdCode").html(response);
                    $("#modalStoreEntireModelIdCode").modal("show");
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 物资列表删除对应物资
         * @param id
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('category')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };

    </script>
@endsection
