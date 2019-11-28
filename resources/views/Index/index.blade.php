@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/_all-skins.min.css">
    <script src="/incubator-echarts-4.2.1/test/lib/jquery.min.js"></script>
    <script src="/incubator-echarts-4.2.1/dist/echarts.min.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/esl.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/config.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/facePrint.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/testHelper.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

@endsection
@section('content')
    <section class="content">
        @include('Layout.alert')

        <div class="row">


            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><h3>出入库统计</h3></div>
                        </div>
                        <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                        <div id="main" style="width: 90%;height:350%"></div>
                        <script type="text/javascript">
                            // 基于准备好的dom，初始化echarts实例
                            var myChart = echarts.init(document.getElementById('main'));

                            // 指定图表的配置项和数据
                            option = {
                                legend: {},
                                tooltip: {},
                                // grid: {x:100,y:50,x2:50,y2:50},
                                dataset: {
                                    // 提供一份数据。
                                    source: [
                                        ['出入库统计', '入库', '出库'],
                                        ["{{$date3}}", "{{$stockinsum3}}", "{{$stockoutsum3}}"],  //当天
                                        ["{{$date2}}", "{{$stockinsum2}}", "{{$stockoutsum2}}"],  //昨天
                                        ["{{$date1}}", "{{$stockinsum1}}", "{{$stockoutsum1}}"],  //前天


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
                            // myChart.showLoading();

                            //
                            // fetchData(function (data) {
                            //     myChart.hideLoading();
                            //     myChart.setOption({
                            //         xAxis: {
                            //             type: 'category'
                            //         },
                            //         series: [{type: 'bar'},
                            //             {type: 'bar'}]
                            //     });
                            // });

                            // 使用刚指定的配置项和数据显示图表。
                            myChart.setOption(option);
                        </script>
                    </div>
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><h3>差异分析</h3></div>
                        </div>
                    </div>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="main1" style="width: 90%;height:350%"></div>
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
                                data: ['账物不一致','超期未出库','盘点正常']
                            },
                            series : [
                                {
                                    name: '差异分析',
                                    type: 'pie',
                                    radius : '55%',
                                    center: ['50%', '60%'],
                                    data:[
                                        {value:"{{$byz}}", name:'账物不一致', url: "{{url("measurement/fixWorkflow?date1=$date1&name=账物不一致")}}"},
                                        {value:"{{$cq}}", name:'超期未出库', url: "{{url("measurement/fixWorkflow?date1=$date1&name=超期未出库")}}"},
                                        {value:"{{$zc}}", name:'盘点正常', url: "{{url("measurement/fixWorkflow?date1=$date1&name=盘点正常")}}"}
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
                        myChart.on('click', function(param) {
                            //console.log(param);
                            var url = param.data.url;
                            window.location.href = url;
                        });
                    </script>
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
                    <div id="main2" style="width: 100%;height:500px"></div>
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
                            dataZoom: [
                                {
                                    type: 'slider',
                                    show: true,
                                    showDetail: true,
                                    start: 0,
                                    end: 30,
                                    handleSize: 8
                                },
                                {
                                    type: 'inside',
                                    start: 94,
                                    end: 100
                                },
                                {
                                    type: 'slider',
                                    show: true,
                                    yAxisIndex: 0,
                                    filterMode: 'empty',
                                    width: 12,
                                    height: '70%',
                                    handleSize: 8,
                                    showDataShadow: false,
                                    left: '93%'
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
    </section>
@endsection
@section('script')
@endsection
