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
    <section class="content">
        @include('Layout.alert')
        {{--检修工作和计划统计--}}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">检修工作和计划统计</h3>
                        <div class="box-tools pull-right">总数：{{$statusCount}}</div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{--搜索结果列表--}}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">搜索结果列表</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    @if($searchType == 'fixWorkflow')
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                                <thead>
                                <tr>
                                    <th>所编号</th>
                                    <th>型号</th>
                                    <th>类型</th>
                                    <th>厂编号</th>
                                    <th>安装位置</th>
                                    <th>安装时间</th>
                                    <th>状态</th>
                                    <th>在库</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fixWorkflows as $fixWorkflow)
                                    <tr>
                                        <td><a href="{{url('search',$fixWorkflow->EntireInstance->identity_code)}}">{{$fixWorkflow->EntireInstance->serial_number}}</a></td>
                                        <td>{{$fixWorkflow->EntireInstance->EntireModel ? $fixWorkflow->EntireInstance->EntireModel->name : ''}}</td>
                                        <td>{{$fixWorkflow->EntireInstance->Category ? $fixWorkflow->EntireInstance->Category->name : ''}}</td>
                                        <td><a href="{{url('search',$fixWorkflow->EntireInstance->identity_code)}}">{{$fixWorkflow->EntireInstance->factory_device_code}}</a></td>
                                        <td>{{$fixWorkflow->EntireInstance->maintain_station_name.'：'.$fixWorkflow->EntireInstance->maintain_location_code}}</td>
                                        <td>{{$fixWorkflow->EntireInstance->last_installed_time > 0 ? date('Y-m-d',$fixWorkflow->EntireInstance->last_installed_time) : ''}}</td>
                                        <td>{{$fixWorkflow->EntireInstance->status}}</td>
                                        <td>{{$fixWorkflow->EntireInstance->in_warehouse ? '在库' : '库外'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($fixWorkflows->hasPages())
                            <div class="box-footer">
                                {{ $fixWorkflows->links() }}
                            </div>
                        @endif
                    @else
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                                <thead>
                                <tr>
                                    <th>所编号</th>
                                    <th>型号</th>
                                    <th>类型</th>
                                    <th>厂编号</th>
                                    <th>安装位置</th>
                                    <th>安装时间</th>
                                    <th>状态</th>
                                    <th>在库</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($entireInstances as $entireInstance)
                                    <tr>
                                        <td><a href="{{url('search',$entireInstance->identity_code)}}">{{$entireInstance->serial_number}}</a></td>
                                        <td>{{$entireInstance->EntireModel ? $entireInstance->EntireModel->name : ''}}</td>
                                        <td>{{$entireInstance->Category ? $entireInstance->Category->name : ''}}</td>
                                        <td><a href="{{url('search',$entireInstance->identity_code)}}">{{$entireInstance->factory_device_code}}</a></td>
                                        <td>{{$entireInstance->maintain_station_name.'：'.$entireInstance->maintain_location_code}}</td>
                                        <td>{{$entireInstance->last_installed_time ? date('Y-m-d',$entireInstance->last_installed_time) : ''}}</td>
                                        <td>{{$entireInstance->status}}</td>
                                        <td>{{$entireInstance->in_warehouse ? '在库' : '库外'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($entireInstances->hasPages())
                            <div class="box-footer">
                                {{ $entireInstances->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
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

        //BAR CHART
        var bar = new Morris.Bar({
            element: 'bar-chart',
            resize: true,
            data:  {!! $statusCounts !!},
            barColors: ['#3c8dbc', '#00c0ef', '#00c0ef', '#f39c12', '#D81B60', '#39CCCC', '#605ca8', '#ff851b'],
            xkey: 'y',
            ykeys: ['BUY_IN', 'INSTALLING', 'INSTALLED', 'FIXING', 'FIXED', 'RETURN_FACTORY', 'FACTORY_RETURN', 'SCRAP'],
            labels: ["采购入库", "安装中", "已安装", "检修中", "检修完成", "返厂中", "返厂入所", "报废"],
            hideHover: 'auto'
        });
    </script>
@endsection
