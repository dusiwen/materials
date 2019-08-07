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
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> 电务检修作业管理信息平台
                    <small class="pull-right">日期：{{$warehouseReportInOrder->processed_at}}</small>
                </h2>
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                <strong>基本信息</strong>
                <address>
                    订单序列号：{{$warehouseReportInOrder->serial_number}}<br>
                    入库人：{{$warehouseReportInOrder->processor->nickname}}<br>
                    送货人姓名：{{$warehouseReportInOrder->send_processor_name}}<br>
                    送货人电话：{{$warehouseReportInOrder->send_processor_phone}}<br>
                    入库时间：{{$warehouseReportInOrder->processed_at}}<br>
                    入库类型：{{$warehouseReportInOrder->type}}
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
                <strong>设备类型</strong>
                <address>
                    @foreach($count as $warehouseProductUniqueCode => $warehouseProductInstanceCount)
                        {{$warehouseProductUniqueCode}}<br>
                    @endforeach
                </address>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>设备编号</th>
                        <th>供应商</th>
                        <th>厂家编号</th>
                        <th>设备型号</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseReportInOrder->warehouseReportInProductInstances as $warehouseReportInProductInstance)
                        <tr>
                            <td>{{$warehouseReportInProductInstance->warehouseProductInstance->open_code}}</td>
                            <td>{{$warehouseReportInProductInstance->factory->name}}</td>
                            <td>{{$warehouseReportInProductInstance->warehouseProductInstance->factory_device_code}}</td>
                            <td>{{$warehouseReportInProductInstance->warehouseProductInstance->warehouse_product_unique_code}}</td>
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
                        @foreach($count as $warehouseProductUniqueCode => $warehouseProductInstanceCount)
                            <tr>
                                <th>{{$warehouseProductUniqueCode}}</th>
                                <td>{{$warehouseProductInstanceCount}}&nbsp;件</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>总计</th>
                            <td>{{count($warehouseReportInOrder->warehouseReportInProductInstances)}}&nbsp;件</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <a href="{{url('warehouse/report/inOrder')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                <a href="{{url('warehouse/report/inOrder',$warehouseReportInOrder->id)}}?type=print" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> 打印</a>
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
