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
        <div class="row no-print">
            <div class="col-xs-12">
                <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&created_at={{request()->get('created_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-default pull-left btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                <a href="{{url('warehouse/report',$time)}}?type=print&types=stockin" target="_blank" class="btn btn-primary pull-right btn-flat"><i class="fa fa-print"></i> 打印</a>
            </div>
        </div>
    </section>
    @elseif($type =="stockout")
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
                        出库日期：{{date("Y-m-d H:i:s",$StockIn_time)}}<br>
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
                    @foreach($stockin as $v)
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
                        <th>{{$StockIn_Number}}</th>
                        <th></th>
                        <th>{{$StockIn_Sum}}</th>
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
