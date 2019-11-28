@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <script src="/AdminLTE/dist/js/echarts.min.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/esl.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/config.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/jquery.min.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/facePrint.js"></script>
    <script src="/incubator-echarts-4.2.1/test/lib/testHelper.js"></script>
@endsection
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><h3>实时盘点</h3></div>
                            <div class="form-group col-sm-6 col-md-6">
                            </div>
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
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><h3>历史盘点</h3></div>
                            <div class="col-md-6">
                                <div class="form-group">
{{--                                    <label class="col-sm-1 col-md-4 control-label">日期:</label>--}}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="reservation">
{{--                                            <input name="updated_at" type="text" class="form-control pull-right" id="datepicker" value="{{request()->get('updated_at')}}">--}}
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="main2" style="width: 90%;height:350%"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('main2'));

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
                                        {value:32, name:'账物不一致'},
                                        {value:20, name:'超期未出库'},
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
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title">WM盘点列表</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    {{--                    <a href="{{url('fixWorkflow/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>--}}
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>物料编码</th>
                        <th>物料描述</th>
                        <th>仓储类型</th>
                        <th>仓位</th>
                        <th>计量单位</th>
                        <th>仓库号</th>
                        <th>账面数量</th>
                        <th>盘点数量</th>
                        <th>库存地点</th>
                        <th>状态</th>
                        <th>差异分析</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($wm as $v)
                        <tr>
                            <td>{{$v->id}}</td>
                            <td>{{$v->MaterialsCode}}</td>
                            <td>{{$v->MaterialsDescribe}}</td>
                            <td>{{$v->StorageType}}</td>
                            <td>{{$v->Positions}}</td>
                            <td>{{$v->Unit}}</td>
                            <td>{{$v->WarehouseNumber}}</td>
                            <td>{{$v->Number}}</td>
                            <td>{{$v->WMNumber}}</td>
                            <td>{{$v->Location}}</td>

{{--                            <td>--}}
{{--                                <a href="" class="btn btn-primary btn-flat">查看</a>--}}
{{--                            </td>--}}
                            @if($v->Number!=$v->WMNumber)
                            <?php \Illuminate\Support\Facades\DB::table("wm")->where("id",$v->id)->update(["WMStatus"=>"账物不一致"]) ?>
                            <td style="color: red">账物不一致</td>
                            <td>{{$v->Analyse}}</td>
                            <td>
                                <a href="{{url("measurement/fixWorkflow/B04920190527094547_03_1558921547/edit?id=$v->id")}}" class="btn btn-primary btn-flat">查看</a>
                            </td>
                            {{--strtotime("+物资使用年限 year","入库时间戳")与当前时间戳判断是否超期未出库--}}
                            @elseif(strtotime(+\Illuminate\Support\Facades\DB::table("materials")->where("MaterialName",$v->MaterialsDescribe)->value("ServiceLife"). "year", \Illuminate\Support\Facades\DB::table("stockin")->where("id",$v->pid)->value("StockIn_time")) >= time())
                            <?php \Illuminate\Support\Facades\DB::table("wm")->where("id",$v->id)->update(["WMStatus"=>"超期未出库"]) ?>
                            <td style="color: darkred">超期未出库</td>
                            <td></td>
                            <td></td>
                            @else
                            <?php \Illuminate\Support\Facades\DB::table("wm")->where("id",$v->id)->update(["WMStatus"=>"盘点正常"]) ?>
                            <td style="color: green">盘点正常</td>
                            <td></td>
                            <td></td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
{{--            @if($fixWorkflows->hasPages())--}}
{{--                <div class="box-footer">--}}
{{--                    {{ $fixWorkflows->links() }}--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            // if ($('.select2')) {
            //     $('.select2').select2();
            // }
            // iCheck for checkbox and radio inputs
            // if ($('input[type="checkbox"].minimal, input[type="radio"].minimal')) {
            //     $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            //         checkboxClass: 'icheckbox_minimal-blue',
            //         radioClass: 'iradio_minimal-blue'
            //     });
            // }
            if ($("#reservation")) {
                $('#reservation').daterangepicker({
                    locale: {
                        applyLabel: '确定',
                        cancelLabel: '取消',
                        fromLabel: '起始时间',
                        toLabel: '结束时间',
                        format: "YYYY-MM-DD",
                        separator: "~",
                        daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
                        monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"]
                    }

                });
            }
        });

        /**
         * 删除
         * @param {string} fixWorkflowSerialNumber 编号
         */
        fnDelete = function (fixWorkflowSerialNumber) {
            $.ajax({
                url: `{{url('measurement/fixWorkflow')}}/${fixWorkflowSerialNumber}`,
                type: "delete",
                data: {},
                success: function (response) {
                    console.log('success:', response);
                    // alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };
    </script>
@endsection

