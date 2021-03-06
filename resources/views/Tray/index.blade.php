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
    {{--筛选--}}
    <section class="content-header">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">筛选</h3>
            </div>
            <div class="box-body">
                <form action="" class="form-horizontal">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-sm-5 col-md-5 control-label">单位：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="direction" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
                                        <option value="IN" {{request()->get('direction') == 'IN' ? 'selected' : ''}}>入所</option>
                                        <option value="OUT" {{request()->get('direction') == 'OUT' ? "selected" : ''}}>出所</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 control-label">日期:</label>
                                <div class="col-sm-8 col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="updated_at" type="text" class="form-control pull-right" id="reservation" value="{{request()->get('updated_at')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-sm-5 col-md-5  control-label">库存地点：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="category_unique_code" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
                                        @foreach(\App\Model\Category::all() as $category)
                                            <option value="{{$category->unique_code}}" {{request()->get('category_unique_code') == $category->unique_code ? 'selected' : ''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3  control-label">物料凭证号：</label>
                                <div class="col-sm-8 col-md-8">
                                    <select name="type" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
                                        @foreach(\App\Model\WarehouseReport::$TYPE as $typeKey => $typeValue)
                                            <option value="{{$typeKey}}" {{request()->get('type') == $typeKey ? 'selected' : ''}}>{{$typeValue}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info btn-flat">筛选</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    {{--出入所单列表--}}
    <section class="content">
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title">入库单列表</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
{{--                    <a href="{{url('warehouse/report/scanInBatch')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default btn-lg btn-flat">批量扫码入所</a>--}}
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>物资编码</th>
                        <th>物资名称</th>
                        <th>批次</th>
                        <th>单位</th>
                        <th>数量</th>
                        <th>单价</th>
                        <th>金额</th>
                        <th>WBS元素</th>
                        <th>时间</th>
                        <th>供应商</th>
                        <th>状态</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseReports as $warehouseReport)
                        <tr>
                            <th>1</th>
                            <th>500082631</th>
                            <th>针式瓷绝缘子,P-10T</th>
                            <th>000876297</th>
                            <th>只</th>
                            <th>1410.000</th>
                            <th>9.40</th>
                            <th>13254.00</th>
                            <th>1829GY1500HQ00A2150000</th>
                            <th>2019.6.2</th>
                            <th>宁夏</th>
                            <th>状态</th>
                            <th></th>
                            <td>
                                <div class="btn-group btn-group-lg">
                                    <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-primary btn-flat">查看</a>
                                    <a href="javascript:" onclick="fnDelete({{$warehouseReport->serial_number}})" class="btn btn-danger btn-flat">删除</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($warehouseReports->hasPages())
                <div class="box-footer">
                    {{ $warehouseReports->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Date picker
            // $('#datepicker').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });

            $('#reservation').daterangepicker({

                locale: {
                    format: "YYYY-MM-DD",
                    separator: "~",
                    daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
                    monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"]
                }
            });
        });

        /**
         * 删除
         * @param {string} serialNumber 编号
         */
        fnDelete = serialNumber => {
            $.ajax({
                url: `{{url('warehouse/report')}}/${serialNumber}`,
                type: "delete",
                data: {},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };
    </script>
@endsection
