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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-1 col-md-4 control-label">项目名称：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="project" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
{{--                                        <option value="IN" {{request()->get('direction') == 'IN' ? 'selected' : ''}}>入所</option>--}}
                                        @foreach(\App\Model\project::all() as $v)
                                            <option value="{{$v->project_name}}">{{$v->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-sm-1 col-md-4  control-label">物资名称：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="MaterialName" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
                                        @foreach(\App\Model\materials::all() as $v)
                                            <option value="{{$v->MaterialName}}">{{$v->MaterialName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-1 col-md-4 control-label">入库类型：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="stockin_type" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
                                        @foreach(\App\Model\stockin_type::all() as $v)
                                            <option value="{{$v->stockin_type}}">{{$v->stockin_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-sm-1 col-md-4 control-label">日期:</label>
                                <div class="col-sm-7 col-md-7">
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
{{--                    <a href="{{url('warehouse/report/scanInBatch')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default btn-lg btn-flat">添加物资入库单</a>--}}
                    <a href="{{url('entire/model/1/edit')}}" class="btn btn-default btn-lg btn-flat">添加物资入库单</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th style="text-align: center">#</th>
                        <th style="text-align: center">物资编码</th>
                        <th style="text-align: center">物资名称</th>
                        <th style="text-align: center">批次</th>
                        <th style="text-align: center">单位</th>
                        <th style="text-align: center">数量</th>
                        <th style="text-align: center">总重量(kg)</th>
                        <th style="text-align: center">单价(元)</th>
                        <th style="text-align: center">金额(元)</th>
                        <th style="text-align: center">项目名称</th>
{{--                        <th style=text-align: center;>时间</th>--}}
{{--                        <th style=text-align: center;>供应商</th>--}}
                        <th style="text-align: center">状态</th>
                        <th style="text-align: center">入库类型</th>
                        <th style="text-align: center">入库时间</th>
                        <th style="text-align: center">备注</th>
                        <th style="text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockinByTime as $value)
                        @foreach($value as $k=>$v)
                        <tr>
                            @if($k == 0)
                            <th rowspan="{{count([$v])}}">{{$v->id}}</th>
                            @else
                                <th style="text-align: center"></th>
                            @endif
                            <th style="text-align: center">{{$v->StockIn_MaterialCode}}</th>
                            <th style="text-align: center">{{$v->StockIn_MaterialName}}</th>
                            <th style="text-align: center">{{$v->StockIn_Batch}}</th>
                            <th style="text-align: center">{{$v->StockIn_Unit}}</th>
                            <th style="text-align: center">{{$v->StockIn_Number}}</th>
                            <th style="text-align: center">{{$v->StockIn_Weight}}</th>
                            <th style="text-align: center">{{$v->StockIn_Price}}</th>
                            <th style="text-align: center">{{$v->StockIn_Sum}}</th>
                            <th style="text-align: center">{{$v->StockIn_ProjectName}}</th>
{{--                            <th style=text-align: center;>宁夏</th>--}}
                            <th style="text-align: center">{{$v->StockIn_Status}}</th>
                            <th style="text-align: center">{{$v->StockIn_Type}}</th>
                            <th style="text-align: center">{{$v->StockIn_times}}</th>
                            <th style="text-align: center">{{$v->StockIn_Remark}}</th>
                            @if($k == 0)
                            @if($v->StockIn_Status == "未入库")
                                <td>
                                    <div class="btn-group btn-group-lg">
                                        {{--                                        <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-primary btn-flat">查看</a>--}}
                                        <a href="javascript:" onclick="stock({{$v->id}})" class="btn btn-primary btn-flat">入库</a>
                                        <a href="javascript:" onclick="fnDelete({{$v->time}})" class="btn btn-danger btn-flat">冲销</a>
                                    </div>
                                </td>
                            @elseif($v->StockIn_Status == "扫码确认")
                                <td>
                                    <div class="btn-group btn-group-lg">
                                        {{--                                        <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-primary btn-flat">查看</a>--}}
                                        <a href="{{url("warehouse/plan")}}?page={{$v->time}}" class="btn btn-primary btn-flat">扫码确认</a>
                                        <a href="javascript:" onclick="fnDelete({{$v->time}})" class="btn btn-danger btn-flat">冲销</a>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="btn-group btn-group-lg">
                                        <a href="{{url("/report/quality")}}?page={{$v->time}}&type=stockin" class="btn btn-primary btn-flat">查看</a>
                                    </div>
                                </td>
                            @endif
                            @endif
                            <td>

{{--                                <div class="btn-group btn-group-lg">--}}
{{--                                    <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-primary btn-flat">查看</a>--}}
{{--                                    <a href="javascript:" onclick="fnDelete({{$warehouseReport->serial_number}})" class="btn btn-danger btn-flat">删除</a>--}}
{{--                                </div>--}}
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
{{--            @if($stockinByTime->hasPages())--}}
{{--                <div class="box-footer">--}}
{{--                    {{ $stockinByTime->links() }}--}}
{{--                </div>--}}
{{--            @endif--}}
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
                    applyLabel: '确定',
                    cancelLabel: '取消',
                    fromLabel: '起始时间',
                    toLabel: '结束时间',
                    format: "YYYY-MM-DD",
                    separator: "~",
                    daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
                    monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"]
                }
            });
        });

        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (time) {
            $.ajax({
                url: `{{url('warehouse/report')}}/${time}`,
                type: "delete",
                data: {time: time},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };
        /**
         * 入库单入库操作
         * @param id
         */
        stock = function (id) {
            $.ajax({
                url: `{{url('warehouse/report')}}/${id}`,
                type: "put",
                data: {id: id},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
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
