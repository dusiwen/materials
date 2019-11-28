@extends('Layout.index')
@section('content')
    <section class="content-header">
        <div class="btn-group btn-group-lg">
            <a href="{{url('measurement/fixWorkflow')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-lg btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
{{--            @if($fixWorkflow->prototype('status') != 'RETURN_FACTORY')--}}
{{--                @if(count($partModels))--}}
{{--                    <a href="javascript:" onclick="fnCreateFixWorkflowProcess('{{$fixWorkflow->serial_number}}','PART','{{$fixWorkflow->type}}')" class="btn btn-default btn-flat">部件检测</a>--}}
{{--                @endif--}}
{{--                <a href="javascript:" onclick="fnCreateFixWorkflowProcess('{{$fixWorkflow->serial_number}}','ENTIRE','{{$fixWorkflow->type}}')" class="btn btn-default btn-flat">整件检测</a>--}}
{{--            @endif--}}
{{--            @if($fixWorkflow->prototype('status') == 'FIXED' && !(array_flip(\App\Model\EntireInstance::$STATUS)[$fixWorkflow->EntireInstance->status] == 'INSTALLING' || array_flip(\App\Model\EntireInstance::$STATUS)[$fixWorkflow->EntireInstance->status] == 'INSTALLED'))--}}
{{--                <a href="javascript:" class="btn btn-default btn-flat" onclick="fnCreateInstall('{{$fixWorkflow->serial_number}}')">安装出库</a>--}}
{{--            @elseif($fixWorkflow->prototype('status') == 'RETURN_FACTORY')--}}
{{--                <a href="javascript:" class="btn btn-default btn-flat" onclick="fnCreateFactoryReturn('{{$fixWorkflow->serial_number}}')">返厂入所</a>--}}
{{--            @elseif(array_flip(\App\Model\EntireInstance::$STATUS)[$fixWorkflow->EntireInstance->status] == 'INSTALLED' || array_flip(\App\Model\EntireInstance::$STATUS)[$fixWorkflow->EntireInstance->status] == 'INSTALLING')--}}
{{--                <a href="{{url('warehouse/report',$fixWorkflow->EntireInstance->last_warehouse_report_serial_number_by_out)}}" class="btn btn-default btn-flat">已安装</a>--}}
{{--            @else--}}
{{--                <a href="javascript:" class="btn btn-default btn-flat" onclick="fnCreateReturnFactory('{{$fixWorkflow->serial_number}}')">返厂维修</a>--}}
{{--            @endif--}}
{{--            @if(count($partModels))--}}
{{--                <a href="javascript:" class="btn btn-default btn-flat" onclick="fnModalChangePartInstance()">部件更换管理</a>--}}
{{--            @endif--}}
{{--            <a href="javascript:" class="btn btn-danger btn-flat" onclick="fnScrapEntireInstance('{{$fixWorkflow->EntireInstance->identity_code}}')">报废整件</a>--}}
        </div>
        {{--        <ol class="breadcrumb" style="font-size: 18px;">--}}
        {{--            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>--}}
        {{--            <li class="active">Dashboard</li>--}}
        {{--        </ol>--}}
    </section>
    <section class="content">
        @include('Layout.alert')
        <div class="row">
            <div class="col-md-6">
                <div class="nav-tabs-custom" style="font-size: 18px;">
                    <ul class="nav nav-tabs pull-right">
{{--                        <li class="{{request()->get('type') == 'ENTIRE' ? 'active' : ''}}"><a href="#tabFixWorkflowProcessEntire" data-toggle="tab">整件检测</a></li>--}}
{{--                        @if(count($partModels))--}}
{{--                            <li class="{{request()->get('type') == 'PART' ? 'active' : ''}}"><a href="#tabFixWorkflowProcessPart" data-toggle="tab">部件检测</a></li>--}}
{{--                        @endif--}}
                        <li class="{{request()->get('type') != 'ENTIRE' && request()->get('type') != 'PART' ? 'active' : ''}}"><a href="#tabFixWorkflowInfo" data-toggle="tab">盘点差异</a></li>
                        <li class="pull-left header"><i class="fa fa-wrench"></i>盘点差异分析报告</li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{request()->get('type') != 'ENTIRE' && request()->get('type') != 'PART' ? 'active' : ''}}" id="tabFixWorkflowInfo">
                            <div class="table-responsive">
                                <h3>
                                    <small><i class="fa fa-cog">&nbsp;</i>盘点数据</small>
                                </h3>
                                <table class="table table-hover table-condensed" style="font-size: 18px;">
                                    <tbody>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>单位：</b></td>
                                        <td>{{$differ[0]->Company}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>盘点凭证：</b></td>
                                        <td>{{$differ[0]->WMcode}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>物料编码：</b></td>
                                        <td>{{$differ[0]->MaterialsCode}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>物料描述：</b></td>
                                        <td>{{$differ[0]->MaterialsDescribe}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>仓储类型：</b></td>
                                        <td>{{$differ[0]->StorageType}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>仓位号：</b></td>
                                        <td>{{$differ[0]->Positions}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>计量单位：</b></td>
                                        <td>{{$differ[0]->Unit}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>仓库号：</b></td>
                                        <td>{{$differ[0]->WarehouseNumber}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>账面数量：</b></td>
                                        <td>{{$differ[0]->Number}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>盘点数量：</b></td>
                                        <td>{{$differ[0]->WMNumber}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>库存地点：</b></td>
                                        <td>{{$differ[0]->Location}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>状态：</b></td>
                                        <td>账物不一致</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; text-align: right;"><b>盘点日期：</b></td>
                                        <td>{{date("Y-m-d H:i:s",$differ[0]->WMDate)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <form id="frmUpdateFixWorkflowNote">
                                <h3>
                                    <small><i class="fa fa-pencil">&nbsp;</i>盘点差异分析：</small>
                                </h3>
                                <div class="form-group">
                                    <textarea name="note" cols="30" rows="3" class="form-control">{{$differ[0]->Analyse}}</textarea>
                                </div>
                                <div class="form-group">
                                    <a href="javascript:" class="btn btn-warning btn-flat" onclick="fnUpdateFixWorkflowNote()"><i class="fa fa-check">&nbsp;</i>保存</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div id="divModalInstall"></div>
        <div id="divModalFixWorkflowInOnce"></div>
        <div id="divModalReturnFactory"></div>
        <div id="divModalFactoryReturn"></div>
        <div id="divModalCreateFixWorkflowProcess"></div>
    </section>
@endsection
@section('script')
    <script>
        {{--/**--}}
        {{-- * 打开安装出库窗口--}}
        {{-- * @param {string} fixWorkflowSerialNumber 检修单流水号--}}
        {{-- */--}}
        {{--fnCreateInstall = (fixWorkflowSerialNumber) => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflow/install')}}`,--}}
        {{--        type: "get",--}}
        {{--        data: {fixWorkflowSerialNumber: fixWorkflowSerialNumber},--}}
        {{--        async: false,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log(response);--}}
        {{--            // return null;--}}
        {{--            $("#divModalInstall").html(response);--}}
        {{--            $("#modalInstall").modal("show");--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 标记检修单：已完成--}}
        {{-- * @param {string} fixWorkflowSerialNumber 检修单序列号--}}
        {{-- */--}}
        {{--fnFixedFixWorkflow = function (fixWorkflowSerialNumber) {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflow/fixed')}}/${fixWorkflowSerialNumber}`,--}}
        {{--        type: "put",--}}
        {{--        data: {},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log('success:', response);--}}
        {{--            alert(response);--}}
        {{--            location.reload();--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        /**
         * 保存检修单备注
         */
        fnUpdateFixWorkflowNote = function () {
            $.ajax({
                url: "{{url('measurement/fixWorkflow',$differ[0]->id)}}",
                type: "put",
                data: $("#frmUpdateFixWorkflowNote").serialize(),
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    // location.reload();
                    location.href = "{{url('measurement/fixWorkflow')}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        {{--/**--}}
        {{-- * 打开更换部件窗口--}}
        {{-- */--}}
        {{--fnModalChangePartInstance = () => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{route('changePartInstance.get')}}`,--}}
        {{--        type: "get",--}}
        {{--        data: {fix_workflow_serial_number: "{{$fixWorkflow->serial_number}}"},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log('success:', response);--}}
        {{--            $("#divModal").html(response);--}}
        {{--            $("#modal").modal("show");--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 卸载部件--}}
        {{-- * @param {string} partInstanceIdentityCode 部件身份码--}}
        {{-- */--}}
        {{--fnUninstallPartInstance = partInstanceIdentityCode => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('uninstallPartInstance')}}`,--}}
        {{--        type: "post",--}}
        {{--        data: {--}}
        {{--            partInstanceIdentityCode: partInstanceIdentityCode,--}}
        {{--            entireInstanceIdentityCode: "{{$fixWorkflow->EntireInstance->identity_code}}",--}}
        {{--            fixWorkflowSerialNumber: "{{$fixWorkflow->serial_number}}",--}}
        {{--        },--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log('success:', response);--}}
        {{--            alert(response);--}}
        {{--            location.href = "?page={{request()->get('page',1)}}";--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 报废部件--}}
        {{-- * @param identityCode--}}
        {{-- */--}}
        {{--fnScrapPartInstance = identityCode => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('scrapPartInstance')}}`,--}}
        {{--        type: "post",--}}
        {{--        data: {--}}
        {{--            fixWorkflowSerialNumber: "{{$fixWorkflow->serial_number}}",--}}
        {{--            entireInstanceIdentityCode: "{{$fixWorkflow->EntireInstance->identity_code}}",--}}
        {{--            partInstanceIdentityCode: identityCode--}}
        {{--        },--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log('success:', response);--}}
        {{--            alert(response);--}}
        {{--            location.reload();--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 报废整件--}}
        {{-- * @param {string} identityCode 整件身份码--}}
        {{-- */--}}
        {{--fnScrapEntireInstance = identityCode => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('entire/instance/scrap')}}/${identityCode}`,--}}
        {{--        type: "post",--}}
        {{--        data: {},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log('success:', response);--}}
        {{--            alert(response);--}}
        {{--            location.href = "{{url('measurement/fixWorkflow')}}?page={{request()->get('page',1)}}";--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        // /**
        //  * 安装出库窗口
        //  */
        // fnCreateInstall2 = fixWorkflowSerialNumber => {
        //     $("#modalInstall").show("show");
        // };

        {{--/**--}}
        {{-- * 打开返厂维修窗口--}}
        {{-- */--}}
        {{--fnCreateReturnFactory = fixWorkflowSerialNumber => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflow/returnFactory')}}/${fixWorkflowSerialNumber}`,--}}
        {{--        type: "get",--}}
        {{--        data: {},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            $('#divModalReturnFactory').html(response);--}}
        {{--            $('#modalReturnFactory').modal("show");--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 返厂入所--}}
        {{-- */--}}
        {{--fnCreateFactoryReturn = fixWorkflowSerialNumber => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflow/factoryReturn')}}/${fixWorkflowSerialNumber}`,--}}
        {{--        type: "get",--}}
        {{--        data: {},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            console.log('success:', response);--}}
        {{--            // alert(response);--}}
        {{--            // location.reload();--}}
        {{--            $("#divModalFactoryReturn").html(response);--}}
        {{--            $("#modalFactoryReturn").modal("show");--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 检修单：入所--}}
        {{-- */--}}
        {{--fnCreateFixWorkflowInOnce = (fixWorkflowSerialNumber) => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflow/in')}}/${fixWorkflowSerialNumber}`,--}}
        {{--        type: "get",--}}
        {{--        data: {},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            console.log('success:', response);--}}
        {{--            // alert(response);--}}
        {{--            // location.reload();--}}
        {{--            $("#divModalFixWorkflowInOnce").html(response);--}}
        {{--            $("#modalFixWorkflowInOnce").modal("show");--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 打开创建检测记录窗口--}}
        {{-- * @param {string} fixWorkflowSerialNumber 检修单流水号--}}
        {{-- * @param {string} type 检测单类型--}}
        {{-- * @param {string} fixWorkflowType 检测单类型--}}
        {{-- */--}}
        {{--fnCreateFixWorkflowProcess = (fixWorkflowSerialNumber, type, fixWorkflowType) => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflowProcess/create')}}`,--}}
        {{--        type: "get",--}}
        {{--        data: {fixWorkflowSerialNumber: fixWorkflowSerialNumber, type: type, fixWorkflowType: fixWorkflowType},--}}
        {{--        async: false,--}}
        {{--        success: function (response) {--}}
        {{--            $("#divModalCreateFixWorkflowProcess").html(response);--}}
        {{--            $("#modalStoreFixWorkflowProcess").modal("show");--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}

        {{--/**--}}
        {{-- * 删除检测单--}}
        {{-- * @param fixWorkflowProcessSerialNumber--}}
        {{-- */--}}
        {{--fnDeleteFixWorkflowProcess = (fixWorkflowProcessSerialNumber) => {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{url('measurement/fixWorkflowProcess')}}/${fixWorkflowProcessSerialNumber}`,--}}
        {{--        type: "delete",--}}
        {{--        data: {},--}}
        {{--        async: true,--}}
        {{--        success: function (response) {--}}
        {{--            // console.log('success:', response);--}}
        {{--            // alert(response);--}}
        {{--            location.reload();--}}
        {{--        },--}}
        {{--        error: function (error) {--}}
        {{--            // console.log('fail:', error);--}}
        {{--            if (error.status == 401) location.href = "{{url('login')}}";--}}
        {{--            alert(error.responseText);--}}
        {{--        },--}}
        {{--    });--}}
        {{--};--}}
    </script>
@endsection
