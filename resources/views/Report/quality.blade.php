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
    @if($type =="stockin")
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
                    物料凭证号：<br>
                    库&nbsp;存&nbsp;地&nbsp;点：<br>
                    入&nbsp;库&nbsp;日&nbsp;期：{{date("Y-m-d H:i:s",$StockIn_time)}}<br>
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
{{--                <strong>安装位置信息</strong>--}}
                <address style="margin-left: 10px;">
                    收&nbsp;&nbsp;&nbsp;&nbsp;货&nbsp;&nbsp;&nbsp;方：<br>
                    采购订单号：<br>
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
        <div class="row no-print">
            <div class="col-xs-12">
                <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&created_at={{request()->get('created_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                <a href="{{url('warehouse/report',$time)}}?type=print&types=stockin" target="_blank" class="btn btn-primary pull-right btn-flat"><i class="fa fa-print"></i> 打印</a>
            </div>
        </div>
    </section>
    @elseif($type =="stockout")
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
                    <address style="margin-left: 10px">
                        单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：<br>
                        库&nbsp;存&nbsp;地&nbsp;点：<br>
                        采购订单号：<br>
                        项&nbsp;目&nbsp;名&nbsp;称：<br>
                        项&nbsp;目&nbsp;消&nbsp;耗：<br>
                        合&nbsp;同&nbsp;编&nbsp;号：<br>
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                    {{--                <strong>安装位置信息</strong>--}}
                    <address style="margin-left: 10px">
                        领&nbsp;用&nbsp;单&nbsp;位：<br>
                        会计凭证号：<br>
                        物料凭证号：<br>
                        出&nbsp;库&nbsp;日&nbsp;期：{{date("Y-m-d H:i:s",$StockIn_time)}}<br>
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
                    @foreach($stockin as $v)
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
                        <th style="text-align: center">{{$StockIn_Number}}</th>
                        <th style="text-align: center"></th>
                        <th style="text-align: center">{{$StockIn_Sum}}</th>
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

            {{--        <div class="row">--}}
            {{--            <div class="col-xs-6">--}}
            {{--            </div>--}}
            {{--            <div class="col-xs-6">--}}
            {{--                <p class="lead">统计</p>--}}
            {{--                <div class="table-responsive">--}}
            {{--                    <table class="table">--}}
            {{--                                                @foreach($ware as $warehouseProductUniqueCode => $warehouseProductInstanceCount)--}}
            {{--                                                    <tr>--}}
            {{--                                                        <th style="text-align: center">{{$warehouseProductUniqueCode}}</th>--}}
            {{--                                                        <td>{{$warehouseProductInstanceCount}}&nbsp;件</td>--}}
            {{--                                                    </tr>--}}
            {{--                                                @endforeach--}}
            {{--                        <tr>--}}
            {{--                            <th style="text-align: center">负责</th>--}}
            {{--                        </tr>--}}
            {{--                                                    <tr>--}}
            {{--                                                        <th style="text-align: center">保管</th>--}}
            {{--                                                    </tr>--}}
            {{--                    </table>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
            <div class="row no-print">
                <div class="col-xs-12">
                    <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&created_at={{request()->get('created_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="{{url('warehouse/report',$time)}}?type=print&types=stockout" target="_blank" class="btn btn-primary pull-right btn-flat"><i class="fa fa-print"></i> 打印</a>
                </div>
            </div>
        </section>
    @endif
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
