<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$warehouseReport->prototype('direction') == 'IN' ? '入' : '出'}}库单</title>
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
                    用途：{{$warehouseReport->purpose}}<br>
                    仓库名称：{{$warehouseReport->warehouse_name}}<br>
                    仓库位置：{{$warehouseReport->warehouse_location}}<br>
                    去向：{{$warehouseReport->to_direction}}<br>
                    岔道号：{{$warehouseReport->crossroad_number}}<br>
                    牵引：{{$warehouseReport->traction}}<br>
                    {{$warehouseReport->source ? '来源：' . $warehouseReport->source . '<br>' : ''}}
                    {{$warehouseReport->source_crossroad_number ? '来源岔道号' . $warehouseReport->source_crossroad_number . '<br>' : ''}}
                    {{$warehouseReport->source_traction ? '来源牵引：' . $warehouseReport->source_traction . '<br>' : ''}}
                    预计上道时间：{{$warehouseReport->forecast_install_at}}<br>
                    线制：{{$warehouseReport->line_unique_code}}<br>
                    开向：{{$warehouseReport->open_direction}}<br>
                    表示杆特征：{{$warehouseReport->said_rod}}<br>
                    报废原因：{{$warehouseReport->scarped_note ? '报废原因：' . $warehouseReport->scarped_note . '<br>' : ''}}
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
                            <td>{{$WarehouseReportEntireInstance->EntireInstance->identity_code}}</td>
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
                        <tr>
                            <th>总计</th>
                            <td>{{count($warehouseReport->WarehouseReportEntireInstances)}}&nbsp;件</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
