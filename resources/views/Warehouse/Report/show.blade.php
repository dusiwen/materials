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
    @include('Layout.alert')
    <section class="invoice" style="font-size: 18px;">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> 电务检修作业管理信息平台
                    <small class="pull-right">日期：{{$warehouseReport->processed_at}}</small>
                </h2>
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                <strong>基本信息</strong>
                <address>
                    序列号：{{$warehouseReport->serial_number}}<br>
                    经手人：{{$warehouseReport->Processor ? $warehouseReport->Processor->nickname : ''}}<br>
                    联系人姓名：{{$warehouseReport->connection_name}}<br>
                    联系电话：{{$warehouseReport->connection_phone}}<br>
                    时间：{{$warehouseReport->processed_at}}<br>
                    类型：{{$warehouseReport->type}}
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
                <strong>设备类型</strong>
                <address>
                    @foreach($warehouseReport->WarehouseReportEntireInstances as $warehouseReportEntireInstance)
                        {{$warehouseReportEntireInstance->EntireInstance->EntireModel->name}}&nbsp;&nbsp;{{$warehouseReportEntireInstance->EntireInstance->EntireModel->unique_code}}<br>
                    @endforeach
                </address>
                <strong>安装位置信息</strong>
                <address>
                    用途：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->purpose}}<br>
                    仓库名称：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->warehouse_name}}<br>
                    仓库位置：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->warehouse_location}}<br>
                    去向：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->to_direction}}<br>
                    岔道号：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->crossroad_number}}<br>
                    牵引：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->traction}}<br>
                    {{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->source ? '来源：' . $warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->source . '<br>' : ''}}
                    {{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->source_crossroad_number ? '来源岔道号' . $warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->source_crossroad_number . '<br>' : ''}}
                    {{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->source_traction ? '来源牵引：' . $warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->source_traction . '<br>' : ''}}
                    预计上道时间：{{$warehouseReport->forecast_install_at}}<br>
                    线制：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->line_name}}<br>
                    开向：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->open_direction}}<br>
                    表示杆特征：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->said_rod}}<br>
                    报废原因：{{$warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->scarped_note ? '报废原因：' . $warehouseReport->WarehouseReportEntireInstances[0]->EntireInstance->scarped_note . '<br>' : ''}}
                </address>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>供应商</th>
                        <th>厂家编号</th>
                        <th>出入所流水号</th>
                        <th>设备编号</th>
                        <th>设备型号</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseReport->WarehouseReportEntireInstances as $WarehouseReportEntireInstance)
                        <tr>
                            <td>{{$WarehouseReportEntireInstance->EntireInstance->factory_name}}</td>
                            <td>{{$WarehouseReportEntireInstance->EntireInstance->factory_device_code}}</td>
                            <td>{{$WarehouseReportEntireInstance->EntireInstance->serial_number}}</td>
                            <td>
                                <a href="{{url('search',$WarehouseReportEntireInstance->EntireInstance->identity_code)}}">
                                    {{$WarehouseReportEntireInstance->EntireInstance->identity_code}}
                                </a>
                            </td>
                            <td>{{$warehouseReportEntireInstance->EntireInstance->EntireModel->name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-6">
                <p class="lead">统计</p>
                <div class="table-responsive">
                    <table class="table">
                        {{--                        @foreach($ware as $warehouseProductUniqueCode => $warehouseProductInstanceCount)--}}
                        {{--                            <tr>--}}
                        {{--                                <th>{{$warehouseProductUniqueCode}}</th>--}}
                        {{--                                <td>{{$warehouseProductInstanceCount}}&nbsp;件</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endforeach--}}
                        <tr>
                            <th>总计</th>
                            <td>{{count($warehouseReport->WarehouseReportEntireInstances)}}&nbsp;件</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&created_at={{request()->get('created_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?type=print" target="_blank" class="btn btn-primary pull-right btn-flat"><i class="fa fa-print"></i> 打印</a>
            </div>
        </div>
    </section>
    <div class="clearfix"></div>
    </div>
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
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
