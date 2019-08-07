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
                    <small class="pull-right">日期：{{$warehouseReportOutOrder->processed_at}}</small>
                </h2>
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                <strong>基本信息</strong>
                <address>
                    订单序列号：{{$warehouseReportOutOrder->serial_number}}<br>
                    出库人：{{$warehouseReportOutOrder->processor->nickname}}<br>
                    领用人姓名：{{$warehouseReportOutOrder->draw_processor_name}}<br>
                    领用人电话：{{$warehouseReportOutOrder->draw_processor_phone}}<br>
                    出库时间：{{$warehouseReportOutOrder->processed_at}}<br>
                    出库类型：{{$warehouseReportOutOrder->type}}
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
                        <th>台账位置</th>
                        <th>安装时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseReportOutOrder->warehouseReportOutProductInstances as $warehouseReportOutProductInstance)
                        <tr>
                            <td>{{$warehouseReportOutProductInstance->warehouseProductInstance->open_code}}</td>
                            <td>{{$warehouseReportOutProductInstance->factory->name}}</td>
                            <td>{{$warehouseReportOutProductInstance->warehouseProductInstance->factory_device_code}}</td>
                            <td>{{$warehouseReportOutProductInstance->warehouseProductInstance->warehouse_product_unique_code}}</td>
                            <td>{{$warehouseReportOutProductInstance->warehouseProductInstance->maintain_unique_code}}</td>
                            <td>{{$warehouseReportOutProductInstance->warehouseProductInstance->installed_at}}</td>
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
                            <td>{{count($warehouseReportOutOrder->warehouseReportOutProductInstances)}}&nbsp;件</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <a href="{{url('warehouse/report/outOrder')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                @if($warehouseReportOutOrder->flipType() == 'INSTALL')
                    <a href="javascript:" class="btn btn-success pull-right" style="margin-left: 5px;" onclick="$('[name=confirmWarehouseReportOutProductInstanceFile]').click()"><i class="fa fa-check">&nbsp;</i>上传出库设备安装单</a>
                @endif
                <a href="{{url('warehouse/report/outOrder',$warehouseReportOutOrder->id)}}?type=print" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> 打印</a>
            </div>
        </div>
    </section>
    <div class="clearfix"></div>
    <form id="uploadForm" enctype="multipart/form-data" style="display: inline;">　　<!-- 声明文件上传 -->
        <input id="fileUpload" style="display: none; width: 1px;" type="file" name="confirmWarehouseReportOutProductInstanceFile" onchange="fileChange('${base}')"/>　　<!-- 定义change事件,选择文件后触发 -->
    </form>
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

        /**
         * 上传图片
         */
        fileChange = () => {
            var fileName = $('#fileUpload').val();　　　　　　　　　　　　　　　　　　//获得文件名称
            var fileType = fileName.substr(fileName.length - 4, fileName.length);　　//截取文件类型,如(.xls)
            $.ajax({
                url: "{{url('confirmWarehouseReportOutProductInstance')}}",　　　　　　　　　　//上传地址
                type: 'POST',
                cache: false,
                data: new FormData($('#uploadForm')[0]),　　　　　　　　　　　　　//表单数据
                // dataType: 'json',
                processData: false,
                contentType: false,
                success: res => {
                    // console.log(res);
                    alert(res);
                    location.reload();
                }, error: error => {
                    // console.log(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                    location.reload();
                }
            });
        };
    </script>
@endsection
