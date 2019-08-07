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
    <section class="content-header">
        @if(array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'BUY_IN'
                            && array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'INSTALLED'
                            && array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'INSTALLING')
            <a href="javascript:" class="btn btn-default btn-lg btn-flat">入所检修</a>
        @elseif(array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'FIXED')
            <a href="javascript:" class="btn btn-default btn-lg btn-flat" onclick="fnCreateInstall('{{$fixWorkflow->serial_number}}')">出所安装</a>
        @endif
        <a href="javascript:" class="btn btn-danger btn-flat btn-lg" onclick="fnScrapEntireInstance('{{$entireInstance->identity_code}}')">报废整件</a>
        <a href="{{url('measurement/fixWorkflow/create')}}?type=identity_code&identity_code={{$entireInstance->identity_code}}" class="btn btn-default btn-flat btn-lg">新建检修单</a>
    </section>
    <section class="content">
        <div class="row">
            {{--整件--}}
            <div class="col-md-6">
                @include('Layout.alert')
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">整件</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <br>
                    <div class="box-body">
                        <dl class="dl-horizontal" style="font-size: 18px;">
                            <dt>唯一标识：</dt>
                            <dd>{{$entireInstance->identity_code}}</dd>
                            @if($entireInstance->serial_number)
                                <dt>所编号：</dt>
                                <dd><a href="{{url('entire/instance',$entireInstance->identity_code)}}/edit">{{$entireInstance->serial_number}}</a></dd>
                            @endif
                            <dt>厂编号：</dt>
                            <dd>{{$entireInstance->factory_device_code}}</dd>
                            <dt>设备类型：</dt>
                            <dd>{{$entireInstance->EntireModel->name}}（{{$entireInstance->EntireModel->unique_code}}）</dd>
                            <dt>状态：</dt>
                            <dd>{{$entireInstance->status}}</dd>
                            <dt>在库状态：</dt>
                            <dd>{{$entireInstance->in_warehouse ? '在库' : '库外'}}</dd>
                            <dt>安装位置：</dt>
                            <dd>{{$entireInstance->maintain_station_name.'&nbsp;'.$entireInstance->maintain_location_code}}</dd>
                            <dt>主/备用状态：</dt>
                            <dd>{{$entireInstance->is_main ? '主用' : '备用'}}</dd>
                            <dt>供应商：</dt>
                            <dd>{{$entireInstance->factory_name}}</dd>
                            <dt>安装时间：</dt>
                            <dd>{{$entireInstance->last_installed_time ? date('Y-m-d',$entireInstance->last_installed_time) : ''}}</dd>
                            <dt>类型：</dt>
                            <dd>{{$entireInstance->Category->name}}（{{$entireInstance->Category->unique_code}}）</dd>
                        </dl>
                        <dl class="dl-horizontal" style="font-size: 18px;">
                            <dt>用途：</dt>
                            <dd>{{$entireInstance->purpose}}</dd>
                            <dt>仓库名称：</dt>
                            <dd>{{$entireInstance->warehouse_name}}</dd>
                            <dt>仓库位置：</dt>
                            <dd>{{$entireInstance->warehouse_location}}</dd>
                            <dt>去向：</dt>
                            <dd>{{$entireInstance->to_direction}}</dd>
                            <dt>岔道号：</dt>
                            <dd>{{$entireInstance->crossroad_number}}</dd>
                            <dt>牵引：</dt>
                            <dd>{{$entireInstance->traction}}</dd>
                            @if($entireInstance->source)
                                <dt>来源：</dt>
                                <dd>{{$entireInstance->source}}</dd>
                            @endif
                            @if($entireInstance->source_crossroad_number)
                                <dt>来源岔道号：</dt>
                                <dd>{{$entireInstance->source_crossroad_number}}</dd>
                            @endif
                            @if($entireInstance->source_traction)
                                <dt>来源牵引：</dt>
                                <dd>{{$entireInstance->source_traction}}</dd>
                            @endif
                            <dt>预计上道时间：</dt>
                            <dd>{{$entireInstance->forecast_install_at}}</dd>
                            <dt>线制：</dt>
                            <dd>{{$entireInstance->line_name}}</dd>
                            <dt>开向：</dt>
                            <dd>{{$entireInstance->open_direction}}</dd>
                            <dt>表示杆特征：</dt>
                            <dd>{{$entireInstance->said_rod}}</dd>
                            @if($entireInstance->scarped_note)
                                <dt>报废原因：</dt>
                                <dd>$entireInstance->scarped_note</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
            {{--部件--}}
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">部件</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <br>
                    <div class="box-body">
                        <div class="form-group form-horizontal" style="font-size: 18px;">
                            @if($fixWorkflow)
                                @foreach($entireInstance->PartInstances as $partInstance)
                                    <label class="control-label col-md-6" style="text-align: left; font-weight: normal;"><a href="javascript:">{{$partInstance->PartModel->name}}（{{$partInstance->PartModel->unique_code}}）</a></label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--检修单历史--}}
        <div class="row">
            {{--检修单列表--}}
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">检修单历史</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <br>
                    <div class="box-body">
                        <div class="form-group form-horizontal" style="font-size: 16px;">
                            @if($fixWorkflows)
                                @foreach($fixWorkflows as $fixWorkflow)
                                    <label class="control-label col-md-12" style="text-align: left; font-weight: normal;"><a href="{{url('measurement/fixWorkflow',$fixWorkflow->serial_number)}}/edit">{{$fixWorkflow->serial_number}}</a>&nbsp;&nbsp;{{$fixWorkflow->created_at}}&nbsp;&nbsp;{{$fixWorkflow->status}}</label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{--最后检测--}}
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">最后检测</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <br>
                    <div class="box-body table-responsive" style="font-size: 18px;">
                        <table class="table table-hover table-condensed" style="font-size: 16px;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>测试整/部件</th>
                                <th>测试项</th>
                                <th>标准值</th>
                                <th>操作</th>
                                <th>实测值</th>
                            </tr>
                            <?php $i = 0;?>
                            @if($lastFixWorkflowRecodePart)
                                @foreach($lastFixWorkflowRecodePart->FixWorkflowRecords as $key=>$fixWorkflowRecords)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>部件</td>
                                        <td>{{$fixWorkflowRecords->Measurement->key}}</td>
                                        <td>{{$fixWorkflowRecords->Measurement->allow_min == $fixWorkflowRecords->Measurement->allow_max ? $fixWorkflowRecords->Measurement->allow_min .' ~ ' : ''}}{{$fixWorkflowRecords->Measurement->allow_max.' '.$fixWorkflowRecords->Measurement->unit}}</td>
                                        <td>{{$fixWorkflowRecords->Measurement->operation}}</td>
                                        <td><span style="color: {{$fixWorkflowRecords->is_allow ? 'green' : 'red'}};">{{$fixWorkflowRecords->measured_value. ' '.$fixWorkflowRecords->Measurement->unit}}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($lastFixWorkflowRecodeEntire)
                                @foreach($lastFixWorkflowRecodeEntire->FixWorkflowRecords as $key=>$fixWorkflowRecords)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>整件</td>
                                        <td>{{$fixWorkflowRecords->Measurement->key}}</td>
                                        <td>{{$fixWorkflowRecords->Measurement->allow_explain}}</td>
                                        <td>{{$fixWorkflowRecords->Measurement->operation}}</td>
                                        <td><span style="color: {{$fixWorkflowRecords->is_allow ? 'green' : 'red'}};">{{$fixWorkflowRecords->measured_value. ' '.$fixWorkflowRecords->Measurement->unit}}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="divModalInstall"></div>
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
            if (document.getElementById('datepicker')) {
                $('#datepicker').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd'
                });
            }
        });

        /**
         * 打开安装出库窗口
         * @param {string} fixWorkflowSerialNumber 工单流水号
         */
        fnCreateInstall = (fixWorkflowSerialNumber) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflow/install')}}`,
                type: "get",
                data: {fixWorkflowSerialNumber: fixWorkflowSerialNumber},
                async: false,
                success: function (response) {
                    // console.log('success:', response);
                    // return null;
                    $("#divModalInstall").html(response);
                    $("#modalInstall").modal("show");
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 报废整件
         * @param {string} identityCode 整件身份码
         */
        fnScrapEntireInstance = identityCode => {
            $.ajax({
                url: `{{url('entire/instance/scrap')}}/${identityCode}`,
                type: "post",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.href = "{{url('entire/instance')}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };
    </script>
@endsection
