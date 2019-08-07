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
    <form class="form-horizontal" id="frmCreate">
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    {{--基础信息--}}
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">新建型号</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">型号名称：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" class="form-control" placeholder="型号名称" onkeydown="if(event.keyCode==13){return false;}" required
                                           name="name" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">统一代码：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" class="form-control" placeholder="统一代码" onkeydown="if(event.keyCode==13){return false;}" required
                                           name="unique_code" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">设备类型：</label>
                                <div class="col-sm-10 col-md-8">
                                    <select name="category_open_code" class="form-control select2" style="width: 100%;" onchange="fnRefreshWarehouseProductPart(this.value)">
                                        <option value="">请选择</option>
                                        @foreach($categories as $categoryOpenCode => $categoryName)
                                            <option value="{{$categoryOpenCode}}">{{$categoryName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">维护周期类型：</label>
                                <div class="col-sm-10 col-md-8">
                                        <select name="fix_cycle_type" class="form-control select2" onchange="fnRefreshWarehouseProductPart(this.value)">
                                            @foreach(\App\Model\WarehouseProduct::$FIX_CYCLE_TYPE as $fixCycleTypeKey => $fixCycleTypeValue)
                                                <option value="{{$fixCycleTypeKey}}">{{$fixCycleTypeValue}}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">维护周期值：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="number" class="form-control" min="1" max="99" name="fix_cycle_value" value="" placeholder="维护周期值">
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{url('warehouse/products')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                <a href="javascript:" onclick="fnCreate()" class="btn btn-success pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{--绑定零件--}}
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">绑定零件</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" title="添加零件" onclick="fnCreateWarehouseProductPart()"><i class="fa fa-plus-square"></i></button>
                            </div>
                        </div>
                        <br>
                        <div class="box-body">
                            <div id="divWarehouseProductPart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        var lastCategoryOpenCode = '';
        /**
         * 刷新零件列表
         * @param {string} categoryOpenCode 设备代码
         */
        fnRefreshWarehouseProductPart = (categoryOpenCode) => {
            lastCategoryOpenCode = categoryOpenCode;
            $.ajax({
                url: `{{url('getWarehouseProductPartByCategoryOpenCode')}}/${categoryOpenCode}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    fnFillWarehouseProductPart(response);
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                },
            });
        };

        /**
         * 填充零件列表
         * @param {Array} data 待填充数据
         */
        fnFillWarehouseProductPart = function (data) {
            html = '';
            $.each(data, function (key, value) {
                subscript = value.subscript ? value.subscript : '';
                html += `
<div class="col-sm-6 col-md-6">
    <label class="control-label" style="text-align: left; font-weight: normal;">
        <input type="checkbox" class="minimal"
            name="warehouse_product_part_ids[]" value="${value.id}">&nbsp;&nbsp;${value.name}<sub>${subscript}</sub>（${value.character}）
    </label>
    &nbsp;&nbsp;
    <a href="javascript:" onclick="fnEditWarehouseProductPart(${value.id})"><i class="fa fa-pencil"></i></a>
    <a href="javascript:" class="text-danger" onclick="fnDeleteWarehouseProductPart(${value.id})"><i class="fa fa-trash"></i></a>
</div>`;
            });
            $("#divWarehouseProductPart").html(html);
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        };

        $(function () {
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
                url: "{{url('warehouse/products')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };

        /**
         * 新建零件
         */
        fnCreateWarehouseProductPart = function () {
            $.ajax({
                url: "{{url('warehouse/product/part/create')}}",
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    $("#divModal").html(response);
                    $("#modal").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                },
            });
        };

        /**
         * 编辑零件
         * @param {int} partId 零件编号
         */
        fnEditWarehouseProductPart = function (partId) {
            $.ajax({
                url: `{{url('warehouse/product/part')}}/${partId}/edit`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    $("#divModal").html(response);
                    $("#modal").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                },
            });
        };

        /**
         * 删除零件
         * @param partId
         */
        fnDeleteWarehouseProductPart = function (partId) {
            $.ajax({
                url: `{{url('warehouse/product/part')}}/${partId}`,
                type: "delete",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    // location.reload();
                    fnRefreshWarehouseProductPart(lastCategoryOpenCode);

                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                },
            });
        };

        /**
         * 根据设备类型编号获取零件列表
         * @param {string} categoryOpenCode 类目编码
         */
        fnGetWarehouseProductPartByCategoryId = (categoryOpenCode) => {
            $.ajax({
                url: `{{url('getWarehouseProductPartByCategoryOpenCode')}}/${categoryOpenCode}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                    // alert(response);
                    // location.reload();
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
