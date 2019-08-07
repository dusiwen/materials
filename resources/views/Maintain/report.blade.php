@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/_all-skins.min.css">
@endsection
@section('content')
    <section class="content">
        @include('Layout.alert')

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="bot-title">检修工作和计划统计</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="revenue-chart" style="height: 300px;"></div>
            </div>
        </div>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">设备状态</h3>
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="body">
                <div class="col-md-6">
                    <div class="form-group">
{{--                        <label>设备种类：</label>--}}
                        <select class="form-control select2" multiple="multiple" data-placeholder="设备种类"
                                style="width: 100%;">
                            <option>设备类型1</option>
                            <option>设备类型2</option>
                            <option>设备类型3</option>
                            <option>设备类型4</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="reservation">
                    </div>
                </div>
            </div>
            <div class="box-footer chart-responsive">
                <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">台账列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('maintains/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>统一代码</th>
                        <th>说明</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($maintains as $maintain)
                        <tr>
                            <td>{{$maintain->id}}</td>
                            <td>{{$maintain->unique_code}}</td>
                            <td>{{$maintain->explain}}</td>
                            <td>
                                <a href="{{url('maintains',$maintain->id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$maintain->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">
                                <div class="row">
                                    @foreach($maintain->warehouseProductInstances as $warehouseProductInstance)
                                        <div class="col-md-4">
                                            <div class="box box-{{$warehouseProductInstance->is_using ? 'info' : 'default'}}">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title"><a href="{{url('warehouse/product/instance',$warehouseProductInstance->id)}}/edit">{{$warehouseProductInstance->open_code}}</a></h3>
                                                    <div class="box-tools pull-right"></div>
                                                </div>
                                                <div class="box-body">
                                                    <table>
                                                        <tr>
                                                            <td style="text-align: right;"><b><i class="fa fa-clock-o">&nbsp;</i>安装时间：</b></td>
                                                            <td>{{$warehouseProductInstance->installed_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;"><b><i class="fa fa-cogs">&nbsp;</i>状态：</b></td>
                                                            <td>
                                                                {{$warehouseProductInstance->status}}
                                                                @if($warehouseProductInstance->flipStatus($warehouseProductInstance->status) == 'FIX_BY_SEND')
                                                                    &nbsp;&nbsp;
                                                                    <a href="{{url('measurement/fixWorkflow',$warehouseProductInstance->fix_workflow_id)}}/edit">查看工单</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;"><b><i class="fa fa-cog">&nbsp;</i>上线状态：</b></td>
                                                            <td>
                                                                @if($warehouseProductInstance->is_using)
                                                                    <label class="label label-success">使用中</label>
                                                                @else
                                                                    <a href="javascript:" onclick="fnSetWarehouseProductInstanceIsUsing({{$warehouseProductInstance->id}})" class="label label-default">
                                                                        备用设备
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($maintains->hasPages())
                <div class="box-footer">
                    {{ $maintains->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(function () {
            $('#reservation').daterangepicker();

            // AREA CHART
            var area = new Morris.Area({
                element: 'revenue-chart',
                resize: true,
                data: [
                    {y: '2011 Q1', item1: 2666, item2: 2666},
                    {y: '2011 Q2', item1: 2778, item2: 2294},
                    {y: '2011 Q3', item1: 4912, item2: 1969},
                    {y: '2011 Q4', item1: 3767, item2: 3597},
                    {y: '2012 Q1', item1: 6810, item2: 1914},
                    {y: '2012 Q2', item1: 5670, item2: 4293},
                    {y: '2012 Q3', item1: 4820, item2: 3795},
                    {y: '2012 Q4', item1: 15073, item2: 5967},
                    {y: '2013 Q1', item1: 10687, item2: 4460},
                    {y: '2013 Q2', item1: 8432, item2: 5713}
                ],
                xkey: 'y',
                ykeys: ['item1', 'item2'],
                labels: ['Item 1', 'Item 2'],
                lineColors: ['#a0d0e0', '#3c8dbc'],
                hideHover: 'auto'
            });

            //DONUT CHART
            var donut = new Morris.Donut({
                element: 'sales-chart',
                resize: true,
                colors: ["#3c8dbc", "#f56954", "#00a65a", "#CA195A"],
                data: [
                    {label: "在用", value: 100},
                    {label: "维修", value: 30},
                    {label: "送检", value: 10},
                    {label: "备用", value: 50},
                ],
                hideHover: 'auto'
            });
        });

        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('maintains')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    if (error.status == 401) location.href = "{{url('login')}}";
                    console.log('fail:', error);
                }
            });
        };

        /**
         * 设置设备实例为主要设备
         * @param {int} warehouseProductInstanceId 设备实例编号
         */
        fnSetWarehouseProductInstanceIsUsing = (warehouseProductInstanceId) => {
            $.ajax({
                url: `{{url('setWarehouseProductInstanceIsUsing')}}/${warehouseProductInstanceId}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    location.reload();
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
