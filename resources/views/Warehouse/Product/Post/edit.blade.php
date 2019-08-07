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
    <form class="form-horizontal" id="frmUpdate">
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    {{--基础信息--}}
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">编辑型号</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" title="用料表" onclick="fnEditNumber({{$warehouseProduct->id}})"><i class="fa fa-link"></i></button>
                            </div>
                        </div>
                        <br>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">型号名称：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" class="form-control" placeholder="类型名称" onkeydown="if(event.keyCode==13){return false;}" required
                                           name="name" value="{{$warehouseProduct->name}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">统一代码：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" class="form-control" placeholder="统一代码" onkeydown="if(event.keyCode==13){return false;}" required
                                           name="unique_code" value="{{$warehouseProduct->unique_code}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">设备类型：</label>
                                <div class="col-sm-10 col-md-8">
                                    <select id="selCategory" name="category_open_code" class="form-control select2" style="width: 100%;" onchange="fnRefreshWarehouseProductPart(this.value)">
                                        @foreach($categories as $categoryOpenCode => $categoryName)
                                            <option value="{{$categoryOpenCode}}" {{$categoryOpenCode == $warehouseProduct->category_open_code ? 'selected' : ''}}>{{$categoryName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{url('warehouse/products')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right"><i class="fa fa-check">&nbsp;</i>编辑</a>
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
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
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
         * @param {string} categoryOpenCode 设别类型代码
         */
        fnRefreshWarehouseProductPart = function (categoryOpenCode) {
            lastCategoryOpenCode = categoryOpenCode;
            $.ajax({
                url: "{{url('warehouse/product/part')}}",
                type: "get",
                data: {warehouseProductId: "{{$warehouseProduct->id}}"},
                async: false,
                success: function (response) {
                    html = '';
                    $.each(response, function (key, value) {
                        subscript = value.subscript ? value.subscript : '';
                        html += `
<div class="col-sm-6 col-md-6">
    <label class="control-label text-left" style="font-weight: normal;">
        <input type="checkbox" class="minimal"
            name="warehouse_product_part_ids[]" value="${value.id}" ${value.is_checked}>&nbsp;&nbsp;${value.name}<sub>${subscript}</sub>（${value.character}）
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
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                },
            });
        };

        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            // 刷新列表
            fnRefreshWarehouseProductPart(document.getElementById('selCategory').value);
        });

        /**
         * 编辑
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('warehouse/products',$warehouseProduct->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    // location.reload();
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
                    if (error.status == 401) location.href = "{{url('login')}}";
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
                    if (error.status == 401) location.href = "{{url('login')}}";
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
                    fnRefreshWarehouseProductPart();

                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                },
            });
        };

        /**
         * 打开用料表编辑窗口
         * @param {int} productId 型号编号
         */
        fnEditNumber = function (productId) {
            $.ajax({
                url: "{{url('warehouse/product/pivot')}}",
                type: "get",
                data: {warehouseProductId: productId},
                async: true,
                success: function (response) {
                    $("#divModal").html(response);
                    $("#modal").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                },
            });
        };
    </script>
@endsection
