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
        <section class="invoice" style="font-size: 18px;">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> 物资入库单
                        {{--                    <small class="pull-right">日期：{{}}</small>--}}
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>基本信息</strong>--}}
                    <address>
                        单位：<br>
                        资金来源：<br>
                        物料凭证号：<br>
                        库存地点：<br>
                        入库日期：{{date("Y-m-d H:i:s",$StockIn_time)}}<br>
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>安装位置信息</strong>--}}
                    <address>
                        收货方：<br>
                        采购订单号：<br>
                        会计凭证号：<br>
                        供货单位：<br>
                        合同编号：<br>
                    </address>
                </div>
            </div>

            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>物资编码</th>
                        <th>物资名称</th>
                        <th>批次</th>
                        <th>单位</th>
                        <th>数量</th>
                        <th>单价(元)</th>
                        <th>金额(元)</th>
                        <th>WBS元素</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockin as $v)
                        <tr>
                            <th>{{$v->StockIn_MaterialCode}}</th>
                            <th>{{$v->StockIn_MaterialName}}</th>
                            <th>{{$v->StockIn_Batch}}</th>
                            <th>{{$v->StockIn_Unit}}</th>
                            <th>{{$v->StockIn_Number}}</th>
                            <th>{{$v->StockIn_Price}}</th>
                            <th>{{$v->StockIn_Sum}}</th>
                            <th>{{$v->WBS}}</th>
                            <th>{{$v->StockIn_Remark}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th>合计</th>
                        <th></th>
                        <th>{{$StockIn_Number}}</th>
                        <th></th>
                        <th>{{$StockIn_Sum}}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>负责:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>保管:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tbody>
                </table>
            </div>

            {{--        <div class="row">--}}
            {{--            <div class="col-xs-6">--}}
            {{--            </div>--}}
            {{--            <div class="col-xs-6">--}}
            {{--                <p class="lead">统计</p>--}}
            {{--                <div class="table-responsive">--}}
            {{--                    <table class="table">--}}
            {{--                                                @foreach($ware as $warehouseProductUniqueCode => $warehouseProductInstanceCount)--}}
            {{--                                                    <tr>--}}
            {{--                                                        <th>{{$warehouseProductUniqueCode}}</th>--}}
            {{--                                                        <td>{{$warehouseProductInstanceCount}}&nbsp;件</td>--}}
            {{--                                                    </tr>--}}
            {{--                                                @endforeach--}}
            {{--                        <tr>--}}
            {{--                            <th>负责</th>--}}
            {{--                        </tr>--}}
            {{--                                                    <tr>--}}
            {{--                                                        <th>保管</th>--}}
            {{--                                                    </tr>--}}
            {{--                    </table>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
            {{--        <div class="row no-print">--}}
            {{--            <div class="col-xs-12">--}}
            {{--                <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&created_at={{request()->get('created_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>--}}
            {{--                <a href="{{url('warehouse/report',$time)}}?type=print" target="_blank" class="btn btn-primary pull-right btn-flat"><i class="fa fa-print"></i> 打印</a>--}}
            {{--            </div>--}}
            {{--        </div>--}}
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
        <section class="invoice" style="font-size: 18px;">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> 物资出库单
                        {{--                    <small class="pull-right">日期：{{}}</small>--}}
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>基本信息</strong>--}}
                    <address>
                        单位：<br>
                        库存地点：<br>
                        采购订单号：<br>
                        项目名称：<br>
                        项目消耗：<br>
                        合同编号：<br>
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>安装位置信息</strong>--}}
                    <address>
                        领用单位：<br>
                        会计凭证号：<br>
                        物料凭证号：<br>
                        出库日期：{{date("Y-m-d H:i:s",$StockOut_time)}}<br>
                        预留号：<br>
                    </address>
                </div>
            </div>

            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>物资编码</th>
                        <th>物资名称</th>
                        <th>批次</th>
                        <th>单位</th>
                        <th>数量</th>
                        <th>单价(元)</th>
                        <th>金额(元)</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockout as $v)
                        <tr>
                            <th>{{$v->StockOut_MaterialCode}}</th>
                            <th>{{$v->StockOut_MaterialName}}</th>
                            <th>{{$v->StockOut_Batch}}</th>
                            <th>{{$v->StockOut_Unit}}</th>
                            <th>{{$v->StockOut_Number}}</th>
                            <th>{{$v->StockOut_Price}}</th>
                            <th>{{$v->StockOut_Sum}}</th>
                            <th>{{$v->StockOut_Remark}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th>合计</th>
                        <th></th>
                        <th>{{$StockOut_Number}}</th>
                        <th></th>
                        <th>{{$StockOut_Sum}}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>负责:</th>
                        <th>保管:</th>
                        <th></th>
                        <th>领料:</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tbody>
                </table>
            </div>

            {{--        <div class="row">--}}
            {{--            <div class="col-xs-6">--}}
            {{--            </div>--}}
            {{--            <div class="col-xs-6">--}}
            {{--                <p class="lead">统计</p>--}}
            {{--                <div class="table-responsive">--}}
            {{--                    <table class="table">--}}
            {{--                                                @foreach($ware as $warehouseProductUniqueCode => $warehouseProductInstanceCount)--}}
            {{--                                                    <tr>--}}
            {{--                                                        <th>{{$warehouseProductUniqueCode}}</th>--}}
            {{--                                                        <td>{{$warehouseProductInstanceCount}}&nbsp;件</td>--}}
            {{--                                                    </tr>--}}
            {{--                                                @endforeach--}}
            {{--                        <tr>--}}
            {{--                            <th>负责</th>--}}
            {{--                        </tr>--}}
            {{--                                                    <tr>--}}
            {{--                                                        <th>保管</th>--}}
            {{--                                                    </tr>--}}
            {{--                    </table>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
            {{--        <div class="row no-print">--}}
            {{--            <div class="col-xs-12">--}}
            {{--                <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&created_at={{request()->get('created_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>--}}
            {{--                <a href="{{url('warehouse/report',$time)}}?type=print" target="_blank" class="btn btn-primary pull-right btn-flat"><i class="fa fa-print"></i> 打印</a>--}}
            {{--            </div>--}}
            {{--        </div>--}}
        </section>
    </div>
    </body>
    </html>
@endif

