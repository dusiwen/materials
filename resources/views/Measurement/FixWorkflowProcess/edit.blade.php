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
    <form class="form-horizontal" id="frmUpdateFixWorkflowProcess">
        <section class="content">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h1 class="box-title">检测记录</h1>
                    {{--右侧最小化按钮--}}
                    <div class="box-tools pull-right"></div>
                </div>
                <br>
                <div class="box-body" style="font-size: 18px;">
                    <input type="hidden" name="fix_workflow_serial_number" value="{{$fixWorkflowProcess->fix_workflow_serial_number}}">
                    <input type="hidden" name="type" value="{{request()->get('type')}}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">检测阶段：</label>
                        <label class="col-md-8 control-label" style="font-weight: normal; text-align: left;">{{$fixWorkflowProcess->stage}}</label>
{{--                        <div class="col-sm-8 col-md-8">--}}
{{--                            <select name="stage" class="form-control select2 input-lg" style="width: 100%;">--}}
{{--                                @if(\App\Model\FixWorkflow::flipType(request()->get('fixWorkflowType')) == 'FIX')--}}
{{--                                    <option value="FIX_BEFORE" {{$fixWorkflowProcess->prototype('stage') == 'FIX_BEFORE' ? 'select' : ''}}>修前检</option>--}}
{{--                                    <option value="FIX_AFTER" {{$fixWorkflowProcess->prototype('stage') == 'FIX_AFTER' ? 'select' : ''}}>修后检</option>--}}
{{--                                @else--}}
{{--                                    <option value="CHECKED" {{$fixWorkflowProcess->prototype('stage') == 'CHECKED' ? 'select' : ''}}>工区验收</option>--}}
{{--                                    <option value="WORKSHOP" {{$fixWorkflowProcess->prototype('stage') == 'WORKSHOP' ? 'select' : ''}}>车间抽验</option>--}}
{{--                                    <option value="SECTION" {{$fixWorkflowProcess->prototype('stage') == 'SECTION' ? 'select' : ''}}>段技术科抽验</option>--}}
{{--                                @endif--}}
{{--                            </select>--}}
{{--                        </div>--}}
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">检测人：</label>
                        <div class="col-sm-8 col-md-8">
                            <select name="processor_id" class="form-control select2" style="width:100%;">
                                <option value="">请选择</option>
                                @foreach(\App\Model\Account::orderByDesc('id')->get() as $account)
                                    <option value="{{$account->id}}" {{$account->id == $fixWorkflowProcess->processor_id ? 'selected' : ''}}>{{$account->nickname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">检测时间：</label>
                        <div class="col-sm-8 col-md-8">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="processed_at" type="text" class="form-control pull-right" id="datepicker" value="{{$fixWorkflowProcess->processed_at}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">备注：</label>
                        <div class="col-sm-8 col-md-8">
                            <textarea placeholder="备注" class="form-control input-lg" rows="5" type="text" name="note">{{$fixWorkflowProcess->note}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{{url('measurement/fixWorkflowRecord/bindingFixWorkflowProcess',$fixWorkflowProcess->serial_number)}}?type={{request()->get('type')}}&page={{request()->get('page',1)}}" class="btn btn-info btn-lg btn-flat pull-right">绑定数据</a>
                            <a href="{{url('measurement/fixWorkflowRecord/boundFixWorkflowProcess',$fixWorkflowProcess->serial_number)}}?type={{request()->get('type')}}&page={{request()->get('page',1)}}" class="btn btn-warning btn-lg btn-flat pull-right">取消绑定</a>
                        </div>
                    </div>
                    <table class="table table-condensed table-hover" style="font-size: 18px;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>出厂编号</th>
                            <th>型号</th>
                            <th>测试项</th>
                            <th>标准值</th>
                            <th>实测值</th>
                            {{--<th>检测人</th>--}}
                            {{--<th>检测时间</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0;?>
                        @foreach($fixWorkflowProcess->FixWorkflowRecords as $fixWorkflowRecord)
                            <tr>
                                <td>{{++$i}}</td>
                                {{--                                <td>{{$fixWorkflowRecord->serial_number}}</td>--}}
                                @if($type == 'ENTIRE')
                                    <td>{{$fixWorkflowRecord->EntireInstance->factory_device_code}}</td>
                                    <td>{{$fixWorkflowRecord->EntireInstance->EntireModel->name}}（{{$fixWorkflowRecord->EntireInstance->EntireModel->unique_code}}）</td>
                                @elseif($type == 'PART')
                                    <td>{{$fixWorkflowRecord->PartInstance->factory_device_code}}</td>
                                    <td>{{$fixWorkflowRecord->PartInstance->PartModel->name}}（{{$fixWorkflowRecord->PartInstance->PartModel->unique_code}}）</td>
                                @endif
                                <td>
                                    {{$fixWorkflowRecord->Measurement->character}}{{$fixWorkflowRecord->Measurement->key ? '（'.$fixWorkflowRecord->Measurement->key : ''}}{{$fixWorkflowRecord->Measurement->operation ? '：'.$fixWorkflowRecord->Measurement->operation : ''}}{{$fixWorkflowRecord->Measurement->key ? '）' : ''}}
                                    {{--                                    <a href="javascript:" onclick="fnDeleteFixWorkflowRecord('{{$fixWorkflowRecord->serial_number}}')" style="color: red;"><i class="fa fa-trash"></i></a>--}}
                                </td>
                                @if(($fixWorkflowRecord->Measurement->allow_min == null) && ($fixWorkflowRecord->Measurement->allow_max == null))
                                    <td>{{$fixWorkflowRecord->Measurement->allow_explain}}</td>
                                    <td>
                                        {{$fixWorkflowRecord->measured_value}}&nbsp;&nbsp;{!! $fixWorkflowRecord->measured_value != null ? $fixWorkflowRecord->is_allow == 1 ? '<span style="color:green">合格</span>' : '<span style="color:red">未通过</span>' : '未检测' !!}
                                        <a href="javascript:" onclick="fnCreateSaveMeasuredExplain('{{$fixWorkflowRecord->Measurement->identity_code}}','{{$fixWorkflowRecord->serial_number}}')">记录实测模糊描述</a>
                                    </td>
                                @else
                                    <td>
                                        @if($fixWorkflowRecord->Measurement->allow_min == null && $fixWorkflowRecord->Measurement->allow_max != null)
                                            ≤&nbsp;&nbsp;{{$fixWorkflowRecord->Measurement->allow_max}}&nbsp;&nbsp;{{$fixWorkflowRecord->Measurement->unit}}
                                        @elseif($fixWorkflowRecord->Measurement->allow_min != null && $fixWorkflowRecord->Measurement->allow_max == null)
                                            ≥&nbsp;&nbsp;{{$fixWorkflowRecord->Measurement->allow_min}}&nbsp;&nbsp;{{$fixWorkflowRecord->Measurement->unit}}
                                        @else
                                            {{$fixWorkflowRecord->Measurement->allow_min != $fixWorkflowRecord->Measurement->allow_max ? $fixWorkflowRecord->Measurement->allow_min.'～': ''}}{{$fixWorkflowRecord->Measurement->allow_max}}&nbsp;&nbsp;{{$fixWorkflowRecord->Measurement->unit}}
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="{{$fixWorkflowRecord->serial_number}}" value="{{$fixWorkflowRecord->measured_value}}" onchange="frmStoreFixWorkflowProcessPart(this.name,this.value,'{{$fixWorkflowRecord->Measurement->identity_code}}')">
                                        <span class="span-response" id="span_{{$fixWorkflowRecord->serial_number}}">{!! $fixWorkflowRecord->measured_value != null ? $fixWorkflowRecord->is_allow == 1 ? '<span style="color:green">合格</span>' : '<span style="color:red">未通过</span>' : '未检测' !!}</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{--                    <a href="{{url('measurement/fixWorkflow',$fixWorkflowProcess->fix_workflow_serial_number)}}/edit?type={{request()->get('type')}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>--}}
                    <a href="javascript:" onclick="fnUpdateFixWorkflowProcessProcess()" class="btn btn-warning btn-flat pull-right btn-lg"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </div>
        </section>
    </form>
    <section>
        <div id="divModalBindingFixWorkflowProcess"></div>
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

        /**
         * 保存
         */
        fnUpdateFixWorkflowProcessProcess = function () {
            $.ajax({
                url: `{{url('measurement/fixWorkflowProcess',$fixWorkflowProcess->serial_number)}}`,
                type: "put",
                data: $("#frmUpdateFixWorkflowProcess").serialize(),
                success: function (response) {
                    location.href = "{{url('measurement/fixWorkflow',$fixWorkflowProcess->fix_workflow_serial_number)}}/edit?type={{request()->get('type')}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 更新部件检测数据
         * @param {string} serialNumber 测试记录序列号
         * @param {string} measuredValue 测试值
         * @param {string} measurementIdentityCode 测试模板身份码
         */
        frmStoreFixWorkflowProcessPart = (serialNumber, measuredValue, measurementIdentityCode) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowRecord/saveMeasuredValue')}}`,
                type: "post",
                data: {serialNumber: serialNumber, measuredValue: measuredValue, measurementIdentityCode: measurementIdentityCode},
                async: true,
                success: function (response) {
                    console.log(response);
                    for (let i in response) {
                        html = response[i].measured_value != null ? response[i].is_allow == 1 ? `<span style="color: green;">合格</span>` : `<span style="color: red;">不合格</span>` : '未检测';
                        $(`#span_${response[i].serial_number}`).html(html);
                    }
                },
                error: function (error) {
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 打开记录实测模糊描述窗口
         * @param {string} measurementIdentityCode 测试模板身份码
         * @param {string} fixWorkflowRecordSerialNumber 实测记录流水号
         */
        fnCreateSaveMeasuredExplain = (measurementIdentityCode, fixWorkflowRecordSerialNumber) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowRecord/saveMeasuredExplain')}}`,
                type: "get",
                data: {
                    measurementIdentityCode: measurementIdentityCode,
                    fixWorkflowRecordSerialNumber: fixWorkflowRecordSerialNumber
                },
                async: true,
                success: function (response) {
                    $("#divModal").html(response);
                    $("#modal").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 选择要绑定的数据
         * @param fixWorkflowProcessSerialNumber
         */
        fnCreateBindingData = (fixWorkflowProcessSerialNumber) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowRecord')}}`,
                type: "get",
                data: {
                    type: "{{request()->get('type')}}",
                    page: "{{request()->get('page',1)}}",
                    fixWorkflowProcessSerialNumber: fixWorkflowProcessSerialNumber,
                },
                async: true,
                success: function (response) {
                    $("#divModalBindingFixWorkflowProcess").html(response);
                    $("#modalBindingFixWorkflowProcess").modal("show");
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 记录检测人
         * @param {string} fixWorkflowRecordSerialNumber
         * @param {int} processorId
         */
        fnStoreProcessor = (fixWorkflowRecordSerialNumber, processorId) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowRecord/saveProcessor')}}/${fixWorkflowRecordSerialNumber}`,
                type: "post",
                data: {processorId: processorId},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 记录检测时间
         * @param {string} fixWorkflowProcessSerialNumber
         * @param {string} processedAt
         */
        fnStoreProcessedAt = (fixWorkflowProcessSerialNumber, processedAt) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowRecord/saveProcessedAt')}}/${fixWorkflowProcessSerialNumber}`,
                type: "post",
                data: {processedAt: processedAt},
                async: true,
                success: function (response) {
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 删除检测记录
         * @param fixWorkflowRecordSerialNumber
         */
        fnDeleteFixWorkflowRecord = (fixWorkflowRecordSerialNumber) => {
            $.ajax({
                url: `{{url('measurement/fixWorkflowRecord')}}/${fixWorkflowRecordSerialNumber}`,
                type: "delete",
                data: {},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                    location.reload();
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
