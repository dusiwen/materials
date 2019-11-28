<!DOCTYPE html>
@if($types == "stockin")
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {{--    <title>{{$warehouseReport->prototype('direction') == 'IN' ? '入' : '出'}}库单</title>--}}
        <title>入库单</title>
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
        <section class="invoice" style="font-size: 10px;">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header" style="text-align: center">
                        <i class="fa fa-globe"></i> 物资入库单
                        {{--                    <small class="pull-right">日期：{{}}</small>--}}
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>基本信息</strong>--}}
                    <address style="margin-left: 10px;">
                        单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：<br>
                        资&nbsp;金&nbsp;来&nbsp;源：<br>
                        物料凭证号：aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa<br>
                        库&nbsp;存&nbsp;地&nbsp;点：<br>
                        入&nbsp;库&nbsp;日&nbsp;期：{{date("Y-m-d H:i:s",$StockIn_time)}}<br>
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>安装位置信息</strong>--}}
                    <address style="margin-left: 10px;">
                        收&nbsp;&nbsp;&nbsp;&nbsp;货&nbsp;&nbsp;&nbsp;方：<br>
                        采购订单号：aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa<br>
                        会计凭证号：<br>
                        供&nbsp;货&nbsp;单&nbsp;位：<br>
                        合&nbsp;同&nbsp;编&nbsp;号：<br>
                    </address>
                </div>
            </div>

            <div class="box-body table-responsive" style="font-size: 10px">
                <table class="table table-hover table-condensed" id="table" border="1" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th style="text-align: center">物资编码</th>
                        <th style="text-align: center">物资名称</th>
                        <th style="text-align: center">批次</th>
                        <th style="text-align: center">单位</th>
                        <th style="text-align: center">数量</th>
                        <th style="text-align: center">单价(元)</th>
                        <th style="text-align: center">金额(元)</th>
                        <th style="text-align: center">WBS元素</th>
                        <th style="text-align: center">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockin as $v)
                        <tr>
                            <th style="text-align: center">{{$v->StockIn_MaterialCode}}</th>
                            <th style="text-align: center">{{$v->StockIn_MaterialName}}</th>
                            <th style="text-align: center">{{$v->StockIn_Batch}}</th>
                            <th style="text-align: center">{{$v->StockIn_Unit}}</th>
                            <th style="text-align: center">{{$v->StockIn_Number}}</th>
                            <th style="text-align: center">{{$v->StockIn_Price}}</th>
                            <th style="text-align: center">{{$v->StockIn_Sum}}</th>
                            <th style="text-align: center">{{$v->WBS}}</th>
                            <th style="text-align: center">{{$v->StockIn_Remark}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th style="text-align: center"></th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">合计</th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">{{$StockIn_Number}}</th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">{{$StockIn_Sum}}</th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center"></th>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <b style="padding-left: 15.7%">负责:</b>
                    <b style="padding-left: 59.6%">保管:</b>
                </div>
            </div>
        </section>
    </div>
    </body>
    </html>
@elseif($types == "stockout")
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {{--    <title>{{$warehouseReport->prototype('direction') == 'IN' ? '入' : '出'}}库单</title>--}}
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
        <section class="invoice" style="font-size: 10px;">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header" style="text-align: center">
                        <i class="fa fa-globe"></i> 物资出库单
                        {{--                    <small class="pull-right">日期：{{}}</small>--}}
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>基本信息</strong>--}}
                    <address style="margin-left: 10px;">
                        单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa<br>
                        库&nbsp;存&nbsp;地&nbsp;点：<br>
                        采购订单号：<br>
                        项&nbsp;目&nbsp;名&nbsp;称：<br>
                        项&nbsp;目&nbsp;消&nbsp;耗：<br>
                        合&nbsp;同&nbsp;编&nbsp;号：<br>
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>安装位置信息</strong>--}}
                    <address style="margin-left: 10px;">
                        领&nbsp;用&nbsp;单&nbsp;位：aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa<br>
                        会计凭证号：<br>
                        物料凭证号：<br>
                        出&nbsp;库&nbsp;日&nbsp;期：{{date("Y-m-d H:i:s",$StockOut_time)}}<br>
                        预&nbsp;&nbsp;&nbsp;&nbsp;留&nbsp;&nbsp;&nbsp;号：<br>
                    </address>
                </div>
            </div>

            <div class="box-body table-responsive" style="font-size: 10px">
                <table class="table table-hover table-condensed" id="table" border="1" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th style="text-align: center">物资编码</th>
                        <th style="text-align: center">物资名称</th>
                        <th style="text-align: center">批次</th>
                        <th style="text-align: center">单位</th>
                        <th style="text-align: center">数量</th>
                        <th style="text-align: center">单价(元)</th>
                        <th style="text-align: center">金额(元)</th>
                        <th style="text-align: center">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockout as $v)
                        <tr>
                            <th style="text-align: center">{{$v->StockOut_MaterialCode}}</th>
                            <th style="text-align: center">{{$v->StockOut_MaterialName}}</th>
                            <th style="text-align: center">{{$v->StockOut_Batch}}</th>
                            <th style="text-align: center">{{$v->StockOut_Unit}}</th>
                            <th style="text-align: center">{{$v->StockOut_Number}}</th>
                            <th style="text-align: center">{{$v->StockOut_Price}}</th>
                            <th style="text-align: center">{{$v->StockOut_Sum}}</th>
                            <th style="text-align: center">{{$v->StockOut_Remark}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th style="text-align: center"></th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">合计</th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">{{$StockOut_Number}}</th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">{{$StockOut_Sum}}</th>
                        <th style="text-align: center"></th>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <b style="padding-left: 22.2%">负责:</b>
                    <b style="padding-left: 26.5%">保管:</b>
                    <b style="padding-left: 28.9%">领料:</b>
                </div>
            </div>

        </section>
    </div>
    </body>
    </html>
@endif

