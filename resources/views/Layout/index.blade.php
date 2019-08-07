<!DOCTYPE html>
<html>
<head>
    @include('Layout.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('style')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('Layout.main-header')
    @include('Layout.main-sidebar')
    <div class="content-wrapper">
        {{--<section class="content-header">--}}
        {{--<h1>--}}
        {{--Dashboard--}}
        {{--<small>Control panel</small>--}}
        {{--</h1>--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>--}}
        {{--<li class="active">Dashboard</li>--}}
        {{--</ol>--}}
        {{--</section>--}}
        @yield('content')
    </div>
    @include('Layout.footer')
    @include('Layout.sidebar')
    <section id="divModal"></section>
    <div class="modal fade" id="modalSearch">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">搜索</h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs" style="font-size: 18px;">
                            <li class="tab-pane @if(session()->get('searchCondition.search_type') != 'part' && session()->get('searchCondition.search_type') != 'fixWorkflow') active @endif"><a href="#tab_entire" data-toggle="tab" onclick="fnSearchType('Entire')">整件</a></li>
                            <li class="tab-pane {{session()->get('searchCondition.search_type') == 'part' ? 'active' : ''}}"><a href="#tab_part" data-toggle="tab" onclick="fnSearchType('Part')">部件</a></li>
                            <li class="tab-pane {{session()->get('searchCondition.search_type') == 'fixWorkflow' ? 'active' : ''}}"><a href="#tab_fix_workflow" data-toggle="tab" onclick="fnSearchType('FixWorkflow')">检修单</a></li>
                        </ul>
                        <div class="tab-content">
                            {{--整件--}}
                            <div class="tab-pane @if(session()->get('searchCondition.search_type') != 'part' && session()->get('searchCondition.search_type') != 'fixWorkflow') active @endif" id="tab_entire">
                                <form id="frmSearchEntire" style="font-size: 18px;">
                                    <input type="hidden" name="search_type" value="entire">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件所编号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input type="text" name="serial_number" class="form-control input-lg" placeholder="所编号"
                                                   value="@if(session()->get('searchCondition.search_type') == 'entire') {{session()->get('searchCondition.serial_number')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件厂编号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input type="text" name="factory_device_code" class="form-control input-lg" placeholder="厂编号"
                                                   value="@if(session()->get('searchCondition.search_type') == 'entire') {{session()->get('searchCondition.factory_device_code')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">车间：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select id="selMaintainWorkshop" name="maintain_workshop_name" class="form-control select2 input-lg" style="width:100%;" onchange="fnGetStationName(this.value)">
                                                <option value="" selected>未安装</option>
                                                @foreach(\App\Model\Maintain::orderByDesc('id')->where('type', 'WORKSHOP')->where(function ($q) {$q->where('parent_unique_code', null)->whereOr('parent_unique_code', '');})->get() as $workShop)
                                                    <option value="{{$workShop->unique_code}}"
                                                    @if(session()->get('searchCondition.search_type') == 'entire') {{session()->get('searchCondition.maintain_workshop_name') == $workShop->unique_code ? 'selected' : ''}} @endif>{{$workShop->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divStationName">
                                        <label class="col-sm-3 control-label">站名：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select id="selMaintainStation" name="maintain_station_name[]" class="form-control select2 input-lg" multiple="multiple" data-placeholder="站名" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divLocationCode">
                                        <label class="col-sm-3 control-label">位置代码：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input id="txtLocationCode" type="text" name="maintain_location_code" class="form-control input-lg" placeholder="位置代码"
                                                   value="@if(session()->get('searchCondition.search_type') == 'entire') {{session()->get('searchCondition.maintain_location_code')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件类型：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select name="unique_code" class="form-control select2 input-lg" style="width:100%;">
                                                <option value="" selected>全部</option>
                                                @foreach(\App\Model\Category::all() as $category)
                                                    <option value="{{$category->unique_code}}" @if(session()->get('searchCondition.search_type') == 'entire') {{session()->get('searchCondition.unique_code') == $category->unique_code ? 'selected' : ''}} @endif>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <label class="col-sm-3 control-label">时间：</label>--}}
                                    {{--                                        <div class="col-md-8">--}}
                                    {{--                                            <div class="input-group">--}}
                                    {{--                                                <div class="input-group-addon">--}}
                                    {{--                                                    <i class="fa fa-calendar"></i>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <input type="text" class="form-control pull-right" id="modalSearchDateRangePicker">--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </form>
                            </div>
                            {{--部件--}}
                            <div class="tab-pane {{session()->get('searchCondition.search_type') == 'part' ? 'active' : ''}}" id="tab_part">
                                <form id="frmSearchPart" style="font-size: 18px;">
                                    <input type="hidden" name="search_type" value="part">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件所编号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input type="text" name="serial_number" class="form-control input-lg" placeholder="所编号"
                                                   value="@if(session()->get('searchCondition.search_type') == 'part') {{session()->get('searchCondition.serial_number')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">部件厂编号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input type="text" name="factory_device_code" class="form-control input-lg" placeholder="厂编号"
                                                   value="@if(session()->get('searchCondition.search_type') == 'part') {{session()->get('searchCondition.factory_device_code')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">部件类型：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select id="selPartModelCategoryUniqueCode" name="category_unique_code" class="form-control select2 input-lg" style="width:100%;" onchange="fnGetPartModelUniqueCodeByCategoryUniqueCode(this.value)">
                                                <option value="" selected>全部</option>
                                                @foreach(\App\Model\Category::all() as $category)
                                                    <option value="{{$category->unique_code}}" @if(session()->get('searchCondition.search_type') == 'part') {{session()->get('searchCondition.category_unique_code') == $category->unique_code ? 'selected' : ''}} @endif>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">部件型号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select id="selPartModelUniqueCode" name="unique_code" class="form-control select2 input-lg" style="width:100%;"></select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{--检修单--}}
                            <div class="tab-pane {{session()->get('searchCondition.search_type') == 'fixWorkflow' ? 'active' : ''}}" id="tab_fix_workflow">
                                <form id="frmSearchFixWorkflow" style="font-size: 18px;">
                                    <input type="hidden" name="search_type" value="fixWorkflow">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件所编号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input type="text" name="serial_number" class="form-control input-lg" placeholder="所编号"
                                                   value="@if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.serial_number')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件厂编号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input type="text" name="factory_device_code" class="form-control input-lg" placeholder="厂编号"
                                                   value="@if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.factory_device_code')}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">整件型号：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select name="unique_code" class="form-control select2 input-lg" style="width:100%;">
                                                <option value="" selected>全部</option>
                                                @foreach(\App\Model\EntireModel::all() as $entireModel)
                                                    <option value="{{$entireModel->unique_code}}" @if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.unique_code') == $entireModel->unique_code ? 'selected' : ''}} @endif>{{$entireModel->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">检修单状态：</label>
                                        <div class="col-sm-10 col-md-8">
                                            <select name="status" class="form-control select2 input-lg" style="width:100%;">
                                                <option value="" selected>全部</option>
                                                <option value="FIXING" @if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.status') == 'FIXING' ? 'selected' : ''}} @endif>检修中</option>
                                                <option value="FIXED" @if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.status') == 'FIXED' ? 'selected' : ''}} @endif>检修完成</option>
                                                <option value="RETURN_FACTORY" @if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.status') == 'RETURN_FACTORY' ? 'selected' : ''}} @endif>返厂维修</option>
                                                <option value="FACTORY_RETURN" @if(session()->get('searchCondition.search_type') == 'fixWorkflow') {{session()->get('searchCondition.status') == 'FACTORY_RETURN' ? 'selected' : ''}} @endif>返厂入所</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div>
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-primary btn-flat btn-lg" onclick="fnSearch()"><i class="fa fa-search">&nbsp;</i>查询</button>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    {{--扫码模态框--}}
{{--    <div class="modal fade" id="modalScanQrCode">--}}
{{--        <div class="modal-dialog">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span></button>--}}
{{--                    <h4 class="modal-title">扫描二维码</h4>--}}
{{--                </div>--}}
{{--                <div class="modal-body form-horizontal">--}}
{{--                    <div class="form-group" style="font-size: 18px;">--}}
{{--                        <label class="col-sm-3 control-label">二维码：</label>--}}
{{--                        <div class="col-md-8">--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="text" class="form-control" name="qr_code" id="txtQrCode" onkeydown="if(event.keyCode==13){ fnScanQrCode();}">--}}
{{--                                <div class="input-group-btn">--}}
{{--                                    <a class="btn btn-primary btn-flat" onclick="fnScanQrCode()">扫码</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>

@include('Layout.script')
<script>
    $(function () {
        // 刷新站列表
        fnGetStationName($('#selMaintainWorkshop').val());
        // 刷新部件型号列表
        fnGetPartModelUniqueCodeByCategoryUniqueCode($("#selPartModelCategoryUniqueCode").val());
    });

    /**
     * 打开搜索窗口
     */
    fnModalSearch = () => {
        $("#modalSearch").modal("show");

        $('.select2').select2();

        // iCheck for checkbox and radio inputs
        if ($('input[type="checkbox"].minimal, input[type="radio"].minimal').length > 0) {
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        }

        if (document.getElementById('modalSearchDateRangePicker')) {
            $("#modalSearchDateRangePicker").daterangepicker();
        }
    };

    /**
     * 打开扫码输入窗口
     */
    fnModalScanQrCode = () => {
        $("#modalScanQrCode").modal("show");
        document.getElementById("txtQrCode").focus();
    };

    /**
     * 跳转到设备详情页面
     */
    fnScanQrCode = () => {
        $.ajax({
            url: `{{url('qrcode/parse')}}`,
            type: "get",
            data: {
                type: 'scan',
                params: JSON.parse($("#txtQrCode").val())
            },
            async: false,
            success: function (response) {
                switch (response.type) {
                    case "redirect":
                        location.href = response.url;
                        break;
                    default:
                        console.log('ok');
                        break;
                }
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };

    var searchType = 'Entire';

    /**
     * 切换搜索类型
     * @param searchType
     */
    fnSearchType = (searchType) => {
        this.searchType = searchType;
    };

    /**
     * 根据车间名称获取站名称
     * @param {string} workshopName 车间名称
     */
    fnGetStationName = (workshopName) => {
        if (workshopName != '') {
            $.ajax({
                url: `{{url('maintain')}}`,
                type: "get",
                data: {
                    'type': 'STATION',
                    workshopName: workshopName
                },
                async: false,
                success: function (response) {
                    console.log('success:', response);
                    html = '';
                    $.each(response, function (index, item) {
                        html += `<option value="${item.name}">${item.name}</option>`;
                    });
                    $("#selMaintainStation").html(html);
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        }
    };

    /**
     * 搜索
     */
    fnSearch = () => {
        $.ajax({
            url: `{{url('search')}}`,
            type: "post",
            data: $(`#frmSearch${this.searchType}`).serialize(),
            async: true,
            success: function (response) {
                console.log(response);
                location.href = response;
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };

    /**
     * 根据类型获取部件型号
     * @param categoryUniqueCode
     */
    fnGetPartModelUniqueCodeByCategoryUniqueCode = (categoryUniqueCode) => {
        $.ajax({
            url: `{{url('part/model')}}`,
            type: "get",
            data: {type: 'category_unique_code', category_unique_code: categoryUniqueCode},
            async: true,
            success: function (response) {
                console.log('success:', response);
                // alert(response);
                // location.reload();
                html = '<option value="">全部</option>';
                for (let i = 0; i < response.length; i++) {
                    html += `<option value="${response[i].unique_code}">${response[i].name}</option>`;
                }
                $("#selPartModelUniqueCode").html(html);
            },
            error: function (error) {
                // console.log('fail:', error);
                if (error.status == 401) location.href = "{{url('login')}}";
                alert(error.responseText);
            },
        });
    };
</script>
@yield('script')
</body>
</html>
