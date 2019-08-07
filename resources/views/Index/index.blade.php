@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/_all-skins.min.css">
    <script src="/AdminLTE/dist/js/echarts.min.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/esl.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/config.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/jquery.min.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/facePrint.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/testHelper.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />



    <script src="lib/jquery.min.js"></script>
    <script src="lib/facePrint.js"></script>
    <script src="lib/testHelper.js"></script>
@endsection
@section('content')
    <section class="content">
        @include('Layout.alert')

        <div class="row">
{{--            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">--}}
{{--                <div class="box box-info">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <h3 class="bot-title">快捷入口</h3>--}}
{{--                    </div>--}}
{{--                    <div class="box-body" style="height: 350px;">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="info-box bg-aqua" onclick="fnModalSearch()" style="cursor: pointer;">--}}
{{--                                    <span class="info-box-icon"><i class="fa fa-search"></i></span>--}}
{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text" style="font-size: 24px;">设备查询</span>--}}
{{--                                        <span class="info-box-number"></span>--}}

{{--                                        <div class="progress">--}}
{{--                                            <div class="progress-bar" style="width: 0%"></div>--}}
{{--                                        </div>--}}
{{--                                        <span class="progress-description"></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="info-box bg-green" onclick="location.href='{{url('measurement/fixWorkflow')}}'" style="cursor: pointer;">--}}
{{--                                    <span class="info-box-icon"><i class="fa fa-wrench"></i></span>--}}
{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text" style="font-size: 24px;">检修单&nbsp;&nbsp;{{$shortcutButtonsStatistics['fixWorkflow']['proportion']}}%</span>--}}
{{--                                        <span class="info-box-number"></span>--}}

{{--                                        <div class="progress">--}}
{{--                                            <div class="progress-bar" style="width: {{$shortcutButtonsStatistics['fixWorkflow']['proportion']}}%"></div>--}}
{{--                                        </div>--}}
{{--                                        <span class="progress-description" style="font-size: 12px;">本月共：{{$shortcutButtonsStatistics['fixWorkflow']['total']}}<br>已完成：{{$shortcutButtonsStatistics['fixWorkflow']['completed']}}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="info-box bg-yellow" onclick="location.href='{{url('entire/instance/create')}}'" style="cursor: pointer;">--}}
{{--                                    <span class="info-box-icon"><i class="fa fa-sign-in"></i></span>--}}
{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text" style="font-size: 24px;">新设备</span>--}}
{{--                                        <span class="info-box-number"></span>--}}

{{--                                        <div class="progress">--}}
{{--                                            <div class="progress-bar" style="width: 0%"></div>--}}
{{--                                        </div>--}}
{{--                                        <span class="progress-description" style="font-size: 12px;">本月新设备：{{$shortcutButtonsStatistics['new']['total']}}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="info-box bg-teal" onclick="location.href='{{url('warehouse/plan')}}'" style="cursor: pointer;">--}}
{{--                                    <span class="info-box-icon"><i class="fa fa-calendar"></i></span>--}}
{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text" style="font-size: 24px;">周期修&nbsp;&nbsp;{{$shortcutButtonsStatistics['fixCycle']['proportion']}}%</span>--}}
{{--                                        <span class="info-box-number"></span>--}}

{{--                                        <div class="progress">--}}
{{--                                            <div class="progress-bar" style="width: {{$shortcutButtonsStatistics['fixCycle']['proportion']}}%"></div>--}}
{{--                                        </div>--}}
{{--                                        <span class="progress-description" style="font-size: 12px;">本月共：{{$shortcutButtonsStatistics['fixCycle']['total']}}<br>已完成：{{$shortcutButtonsStatistics['fixCycle']['completed']}}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="info-box bg-red" onclick="location.href='{{url('report/quality')}}'" style="cursor: pointer;">--}}
{{--                                    <span class="info-box-icon"><i class="fa fa-recycle"></i></span>--}}
{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text" style="font-size: 24px;">返修查询</span>--}}
{{--                                        <span class="info-box-number"></span>--}}

{{--                                        <div class="progress">--}}
{{--                                            <div class="progress-bar" style="width: 0%"></div>--}}
{{--                                        </div>--}}
{{--                                        <span class="progress-description"></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="info-box bg-purple" onclick="location.href='{{url('measurement/fixWorkflow').'?status=CHECKED'}}'" style="cursor: pointer;">--}}
{{--                                    <span class="info-box-icon"><i class="fa fa-thumbs-o-up"></i></span>--}}
{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text" style="font-size: 24px;">抽验查询&nbsp;&nbsp;{{$shortcutButtonsStatistics['check']['fixed']}}%</span>--}}
{{--                                        <span class="info-box-number"></span>--}}

{{--                                        <div class="progress">--}}
{{--                                            <div class="progress-bar" style="width: {{$shortcutButtonsStatistics['check']['proportion']}}%"></div>--}}
{{--                                        </div>--}}
{{--                                        <span class="progress-description" style="font-size: 12px;">本月共：{{$shortcutButtonsStatistics['check']['fixed']}}<br>已验收：{{$shortcutButtonsStatistics['check']['checked']}}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><h3>出入库统计</h3></div>
{{--                            <div class="form-group col-sm-6 col-md-6">--}}
{{--                                <select id="selDeviceDynamicByCategoryUniqueCode" class="form-control select2" style="width:100%;" onchange="fnCurrentPage()">--}}
{{--                                    @foreach(\App\Model\Category::pluck('unique_code','name') as $categoryName => $categoryUniqueCode)--}}
{{--                                        <option value="{{$categoryUniqueCode}}" {{$categoryUniqueCode == request()->get('categoryUniqueCode','S03') ? 'selected' : ''}}>{{$categoryName}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                        </div>
                        <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                        <div id="main" style="width: 600px;height:350px;"></div>
                        <script type="text/javascript">
                            // 基于准备好的dom，初始化echarts实例
                            var myChart = echarts.init(document.getElementById('main'));

                            // 指定图表的配置项和数据
                            option = {
                                legend: {},
                                tooltip: {},
                                dataset: {
                                    // 提供一份数据。
                                    source: [
                                        ['出入库统计', '入库', '出库'],
                                        ["{{$date3}}", "{{$stockinsum3}}", "{{$stockoutsum3}}"],
                                        ["{{$date2}}", "{{$stockinsum2}}", "{{$stockoutsum2}}"],
                                        ["{{$date1}}", "{{$stockinsum1}}", "{{$stockoutsum1}}"],


                                    ]
                                },
                                // 声明一个 X 轴，类目轴（category）。默认情况下，类目轴对应到 dataset 第一列。
                                xAxis: {type: 'category'},
                                // 声明一个 Y 轴，数值轴。
                                yAxis: {
                                    // type: 'category',
                                    // boundaryGap: false,
                                    // data: ["0","5","10","15","20"],
                                },
                                // 声明多个 bar 系列，默认情况下，每个系列会自动对应到 dataset 的每一列。
                                series: [
                                    {type: 'bar'},
                                    {type: 'bar'}
                                ]
                            };

                            // 使用刚指定的配置项和数据显示图表。
                            myChart.setOption(option);
                        </script>
                    </div>
{{--                    <div class="box-body chart-responsive" style="height: 350px;">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-8 col-md-8">--}}
{{--                                <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-4 col-md-4">--}}
{{--                                <p>&nbsp;</p>--}}
{{--                                <br>--}}
{{--                                <p style="font-size: 20px;">今日入库：{{json_decode($deviceDynamicStatus,true)[1][2]['value']}}</p>--}}
{{--                                <p style="font-size: 20px;">今日出库：1</p>--}}
{{--                                <p style="font-size: 20px;">金额：</p>--}}
{{--                                <p style="font-size: 20px;">送检：{{json_decode($deviceDynamicStatus,true)[1][2]['value']}}</p>--}}
{{--                                <p style="font-size: 20px;">维修：{{json_decode($deviceDynamicStatus,true)[1][1]['value']}}</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><h3>差异分析</h3></div>
                            <div class="form-group col-sm-6 col-md-6">
{{--                                <select id="selDeviceDynamicByCategoryUniqueCode" class="form-control select2" style="width:100%;" onchange="fnCurrentPage()">--}}
{{--                                    @foreach(\App\Model\Category::pluck('unique_code','name') as $categoryName => $categoryUniqueCode)--}}
{{--                                        <option value="{{$categoryUniqueCode}}" {{$categoryUniqueCode == request()->get('categoryUniqueCode','S03') ? 'selected' : ''}}>{{$categoryName}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
                            </div>
                        </div>
                    </div>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="main1" style="width: 600px;height:350px;"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('main1'));

                        // 指定图表的配置项和数据
                        option = {
                            aria: {
                                show: true
                            },
                            title : {
                                text: '盘点差异分析',
                                subtext: '{{$date1}}',
                                x:'center'
                            },
                            tooltip : {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },
                            legend: {
                                orient: 'vertical',
                                left: 'left',
                                data: ['账务不一致','超期不出库','盘点正常']
                            },
                            series : [
                                {
                                    name: '差异分析',
                                    type: 'pie',
                                    radius : '55%',
                                    center: ['50%', '60%'],
                                    data:[
                                        {value:32, name:'账务不一致'},
                                        {value:20, name:'超期不出库'},
                                        {value:55, name:'盘点正常'}
                                    ],
                                    itemStyle: {
                                        emphasis: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                }
                            ]
                        };

                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
{{--                    <div class="box-body chart-responsive" style="height: 350px;">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-8 col-md-8">--}}
{{--                                <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-4 col-md-4">--}}
{{--                                <p>&nbsp;</p>--}}
{{--                                <br>--}}
{{--                                <p style="font-size: 20px;">今日入库：{{json_decode($deviceDynamicStatus,true)[0]}}</p>--}}
{{--                                <p style="font-size: 20px;">今日出库：{{json_decode($deviceDynamicStatus,true)[1][0]['value']}}</p>--}}
{{--                                <p style="font-size: 20px;">金额：{{json_decode($deviceDynamicStatus,true)[1][3]['value']}}</p>--}}
{{--                                <p style="font-size: 20px;">送检：{{json_decode($deviceDynamicStatus,true)[1][2]['value']}}</p>--}}
{{--                                <p style="font-size: 20px;">维修：{{json_decode($deviceDynamicStatus,true)[1][1]['value']}}</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-8 col-md-8"><h3>物资统计</h3></div>
                            <div class="form-group col-sm-4 col-md-4">
{{--                                <select id="selFixWorkflowCycleDate" name="fix_workflow_cycle_date" class="form-control select2" style="width:100%;" onchange="fnCurrentPage()">--}}
{{--                                    @if($fixingAndFixedDateList)--}}
{{--                                        @foreach($fixingAndFixedDateList as $fixingAndFixedDate)--}}
{{--                                        <option value="{{$fixingAndFixedDate}}">{{$fixingAndFixedDate}}</option>--}}
{{--                                    @endforeach--}}
{{--                                    @else--}}
{{--                                        <option value="">尚无总结</option>--}}
{{--                                    @endif--}}
{{--                                </select>--}}
                            </div>
                        </div>
                    </div>
{{--                    <style>--}}
{{--                        html, body, #main {--}}
{{--                            width: 100%;--}}
{{--                            height: 100%;--}}
{{--                        }--}}
{{--                    </style>--}}
{{--                    <div id="main1"></div>--}}
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="main2" style="width: 100%;height:500px;"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('main2'));

                        // 指定图表的配置项和数据
                        option = {
                            tooltip : {
                                trigger: 'axis'
                            },
                            legend: {
                                data:['数量','物资重量(kg)']
                            },
                            xAxis : [
                                {
                                    type : 'category',
                                    // data : ['物资1','物资2','物资3','物资4','物资5','物资6','物资7','物资8']
                                    data : JSON.parse('{!! $MaterialName !!}')
                                }
                            ],
                            yAxis : [
                                {
                                    type : 'value',
                                    name : '数量',
                                    axisLabel : {
                                        formatter: '{value} '
                                    }
                                },
                                {
                                    type : 'value',
                                    name : '重量',
                                    position: 'right',
                                    axisLabel : {
                                        formatter: '{value} kg'
                                    }
                                }
                            ],
                            series : [

                                {
                                    name:'数量',
                                    type:'bar',
                                    data:JSON.parse('{!! $MaterialSum !!}')
                                },
                                {
                                    name:'物资重量(kg)',
                                    type:'line',
                                    yAxisIndex: 1,
                                    // data:[2.0, 2.2,4.6,7.0,3.0,5.6,1.2,9.6]
                                    data:JSON.parse('{!! $MaterialEachWeight !!}')
                                }
                            ]
                        };

                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>

                </div>
            </div>
        </div>

{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="box box-success">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-8 col-md-8"><h3>一次过检</h3></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="box-body chart-responsive form-horizontal">--}}
{{--                        <div class="chart" id="onlyFixed-chart" style="height: 300px;"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="row" style="font-size: 20px;">--}}
{{--            <div class="col-md-3">--}}
{{--                <a href="javascript:" onclick="location.href='/report/workshop'" style="color: black;">--}}
{{--                    <div class="box box-solid">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <i class="fa fa-text-width"></i>--}}

{{--                            <h3 class="box-title">衡阳</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.box-header -->--}}
{{--                        <div class="box-body">--}}
{{--                            <dl class="dl-horizontal">--}}
{{--                                <dt>类型：</dt>--}}
{{--                                <dd>大修车间</dd>--}}
{{--                                <dt>设备总数：</dt>--}}
{{--                                <dd>100</dd>--}}
{{--                                <dt>备用：</dt>--}}
{{--                                <dd>50</dd>--}}
{{--                                <dt>送检：</dt>--}}
{{--                                <dd>10</dd>--}}
{{--                                <dt>维修：</dt>--}}
{{--                                <dd>30</dd>--}}
{{--                            </dl>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <a href="javascript:" onclick="location.href='/report/workshop'" style="color: black;">--}}
{{--                    <div class="box box-solid">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <i class="fa fa-text-width"></i>--}}

{{--                            <h3 class="box-title">衡阳</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.box-header -->--}}
{{--                        <div class="box-body">--}}
{{--                            <dl class="dl-horizontal">--}}
{{--                                <dt>类型：</dt>--}}
{{--                                <dd>大修车间</dd>--}}
{{--                                <dt>设备总数：</dt>--}}
{{--                                <dd>100</dd>--}}
{{--                                <dt>备用：</dt>--}}
{{--                                <dd>50</dd>--}}
{{--                                <dt>送检：</dt>--}}
{{--                                <dd>10</dd>--}}
{{--                                <dt>维修：</dt>--}}
{{--                                <dd>30</dd>--}}
{{--                            </dl>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <a href="javascript:" onclick="location.href='/report/workshop'" style="color: black;">--}}
{{--                    <div class="box box-solid">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <i class="fa fa-text-width"></i>--}}

{{--                            <h3 class="box-title">衡阳</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.box-header -->--}}
{{--                        <div class="box-body">--}}
{{--                            <dl class="dl-horizontal">--}}
{{--                                <dt>类型：</dt>--}}
{{--                                <dd>大修车间</dd>--}}
{{--                                <dt>设备总数：</dt>--}}
{{--                                <dd>100</dd>--}}
{{--                                <dt>备用：</dt>--}}
{{--                                <dd>50</dd>--}}
{{--                                <dt>送检：</dt>--}}
{{--                                <dd>10</dd>--}}
{{--                                <dt>维修：</dt>--}}
{{--                                <dd>30</dd>--}}
{{--                            </dl>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <a href="javascript:" onclick="location.href='/report/workshop'" style="color: black;">--}}
{{--                    <div class="box box-solid">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <i class="fa fa-text-width"></i>--}}

{{--                            <h3 class="box-title">衡阳</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.box-header -->--}}
{{--                        <div class="box-body">--}}
{{--                            <dl class="dl-horizontal">--}}
{{--                                <dt>类型：</dt>--}}
{{--                                <dd>大修车间</dd>--}}
{{--                                <dt>设备总数：</dt>--}}
{{--                                <dd>100</dd>--}}
{{--                                <dt>备用：</dt>--}}
{{--                                <dd>50</dd>--}}
{{--                                <dt>送检：</dt>--}}
{{--                                <dd>10</dd>--}}
{{--                                <dt>维修：</dt>--}}
{{--                                <dd>30</dd>--}}
{{--                            </dl>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <a href="javascript:" onclick="location.href='/report/workshop'" style="color: black;">--}}
{{--                    <div class="box box-solid">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <i class="fa fa-text-width"></i>--}}

{{--                            <h3 class="box-title">衡阳</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.box-header -->--}}
{{--                        <div class="box-body">--}}
{{--                            <dl class="dl-horizontal">--}}
{{--                                <dt>类型：</dt>--}}
{{--                                <dd>大修车间</dd>--}}
{{--                                <dt>设备总数：</dt>--}}
{{--                                <dd>100</dd>--}}
{{--                                <dt>备用：</dt>--}}
{{--                                <dd>50</dd>--}}
{{--                                <dt>送检：</dt>--}}
{{--                                <dd>10</dd>--}}
{{--                                <dt>维修：</dt>--}}
{{--                                <dd>30</dd>--}}
{{--                            </dl>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <a href="javascript:" onclick="location.href='/report/workshop'" style="color: black;">--}}
{{--                    <div class="box box-solid">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <i class="fa fa-text-width"></i>--}}

{{--                            <h3 class="box-title">衡阳</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.box-header -->--}}
{{--                        <div class="box-body">--}}
{{--                            <dl class="dl-horizontal">--}}
{{--                                <dt>类型：</dt>--}}
{{--                                <dd>大修车间</dd>--}}
{{--                                <dt>设备总数：</dt>--}}
{{--                                <dd>100</dd>--}}
{{--                                <dt>备用：</dt>--}}
{{--                                <dd>50</dd>--}}
{{--                                <dt>送检：</dt>--}}
{{--                                <dd>10</dd>--}}
{{--                                <dt>维修：</dt>--}}
{{--                                <dd>30</dd>--}}
{{--                            </dl>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
    </section>
@endsection
@section('script')
    <script>
        {{--$(function () {--}}
        {{--    if ($('.select2').length > 0) $('.select2').select2();--}}

        {{--    if (document.getElementById('table')) {--}}
        {{--        $('#table').DataTable({--}}
        {{--            'paging': false,--}}
        {{--            'lengthChange': false,--}}
        {{--            'searching': false,--}}
        {{--            'ordering': true,--}}
        {{--            'info': false,--}}
        {{--            'autoWidth': false--}}
        {{--        });--}}
        {{--    }--}}

        {{--    $('#reservation').daterangepicker();--}}

        {{--    //BAR CHART--}}
        {{--    var fixingAndFixed = new Morris.Bar({--}}
        {{--        element: 'bar-chart',--}}
        {{--        resize: true,--}}
        {{--        data: JSON.parse('{!! $fixingAndFixed !!}'),--}}
        {{--        barColors: ['#00a65a', '#3c8dbc'],--}}
        {{--        xkey: "entireModelName",--}}
        {{--        ykeys: ["goingToFixing", "fixed"],--}}
        {{--        labels: ['计划', '完成'],--}}
        {{--        hideHover: "auto"--}}
        {{--    });--}}

        {{--    console.log('{!! $onlyFixeds !!}');--}}
        {{--    var onlyFixed = new Morris.Bar({--}}
        {{--        element: 'onlyFixed-chart',--}}
        {{--        resize: true,--}}
        {{--        data: JSON.parse('{!! $onlyFixeds !!}'),--}}
        {{--        barColors: ['#00a65a', '#3c8dbc'],--}}
        {{--        xkey: "name",--}}
        {{--        ykeys: ["value","all"],--}}
        {{--        labels: ["通过","未通过"],--}}
        {{--        hideHover: "auto"--}}
        {{--    });--}}

        {{--    //DONUT CHART--}}
        {{--    new Morris.Donut({--}}
        {{--        element: 'sales-chart',--}}
        {{--        resize: true,--}}
        {{--        colors: ["#3c8dbc", "#f56954", "#00a65a", "#CA195A"],--}}
        {{--        data: JSON.parse('{!! $deviceDynamicStatus !!}')[1],--}}
        {{--        hideHover: 'auto'--}}
        {{--    });--}}
        {{--});--}}

        {{--/**--}}
        {{-- * 本业内刷新数据--}}
        {{-- */--}}
        {{--fnCurrentPage = () => {--}}
        {{--    location.href = `?categoryUniqueCode=${$('#selDeviceDynamicByCategoryUniqueCode').val()}&selFixWorkflowCycleDate=${$('#selFixWorkflowCycleDate').val()}`--}}
        {{--};--}}
    </script>
@endsection
