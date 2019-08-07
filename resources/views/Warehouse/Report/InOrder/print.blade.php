<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>出库单</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">--}}
</head>
<body onload="window.print();">
<div class="wrapper">
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
    </section>
</div>
</body>
</html>
