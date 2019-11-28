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
{{--        筛选--}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">筛选</h3>
            </div>
            <div class="box-body">
                <form action="" class="form-horizontal">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-sm-5 col-md-5 control-label">托盘编码：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="direction" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
{{--                                        <option value="IN" {{request()->get('direction') == 'IN' ? 'selected' : ''}}>入所</option>--}}
{{--                                        <option value="OUT" {{request()->get('direction') == 'OUT' ? "selected" : ''}}>出所</option>--}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 control-label">日期:</label>
                                <div class="col-sm-8 col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="updated_at" type="text" class="form-control pull-right" id="reservation" value="{{request()->get('updated_at')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-sm-5 col-md-5  control-label">位置：</label>
                                <div class="col-sm-7 col-md-7">
                                    <select name="category_unique_code" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
{{--                                        @foreach(\App\Model\Category::all() as $category)--}}
{{--                                            <option value="{{$category->unique_code}}" {{request()->get('category_unique_code') == $category->unique_code ? 'selected' : ''}}>{{$category->name}}</option>--}}
{{--                                        @endforeach--}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3  control-label">物资编码：</label>
                                <div class="col-sm-8 col-md-8">
                                    <select name="type" class="form-control select2" style="width:100%;">
                                        <option value="">全部</option>
{{--                                        @foreach(\App\Model\WarehouseReport::$TYPE as $typeKey => $typeValue)--}}
{{--                                            <option value="{{$typeKey}}" {{request()->get('type') == $typeKey ? 'selected' : ''}}>{{$typeValue}}</option>--}}
{{--                                        @endforeach--}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info btn-flat">筛选</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            {{--当月--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="box box-danger" style="height: 430px;">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <h3>WM盘点凭据（当月）</h3>--}}
{{--                    </div>--}}
{{--                    <div class="box-body chart-responsive" style="height: 350px;">--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            --}}{{--近三个月--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="box box-warning" style="height: 430px;">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <h3>WM盘点凭据（近三个月）</h3>--}}
{{--                    </div>--}}
{{--                    <div class="box-body chart-responsive" style="height: 350px;">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-8 col-md-8">--}}
{{--                                <div class="chart" id="chartDeviceDynamicStatusNearlyThreeMonth" style="height: 300px; position: relative;"></div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            {{--筛选条件--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="box box-info" style="height: 430px;">--}}
{{--                    <div class="box-header with-border">--}}
{{--                        <h3>查询</h3>--}}
{{--                    </div>--}}
{{--                    <div class="box-body">--}}
{{--                        <div class="form-horizontal" style="font-size: 18px;">--}}
{{--                            <p>&nbsp;</p>--}}
{{--                            <p>&nbsp;</p>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">种类：</label>--}}
{{--                                <div class="col-sm-8 col-md-8">--}}
{{--                                    <select id="selCategoryUniqueCode" class="form-control select2" style="width:100%;" onchange="fnCurrentPage()">--}}
{{--                                        <option value="">请选择</option>--}}
{{--                                        @foreach($categories as $category)--}}
{{--                                            <option value="{{$category->unique_code}}" {{request()->get("categoryUniqueCode") == $category->unique_code ? "selected" : ""}}>{{$category->name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <p>&nbsp;</p>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">型号：</label>--}}
{{--                                <div class="col-sm-8 col-md-8">--}}
{{--                                    <select id="selEntireModelUniqueCode" class="form-control select2" style="width:100%;" onchange="fnCurrentPage()">--}}
{{--                                        <option value="">请选择</option>--}}
{{--                                        @foreach($entireModels as $entireModel)--}}
{{--                                            <option value="{{$entireModel->unique_code}}" {{request()->get("entireModelUniqueCode") == $entireModel->unique_code ? "selected" : ""}}>{{$entireModel->name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <p>&nbsp;</p>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">日期：</label>--}}
{{--                                <div class="col-sm-8 col-md-8">--}}
{{--                                    <select id="selUpdatedAt" class="form-control select2" style="width:100%;" onchange="fnCurrentPage()">--}}
{{--                                        <option value="0" {{request()->get('updatedAt') == 0 ? 'selected' : ''}}>当月</option>--}}
{{--                                        <option value="1" {{request()->get('updatedAt') == 1 ? 'selected' : ''}}>上月</option>--}}
{{--                                        <option value="3" {{request()->get('updatedAt') == 3 ? 'selected' : ''}}>近三个月</option>--}}
{{--                                        <option value="6" {{request()->get('updatedAt') == 6 ? 'selected' : ''}}>近六个月</option>--}}
{{--                                        <option value="12" {{request()->get('updatedAt') == 12 ? 'selected' : ''}}>近十二个月</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <p>&nbsp;</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">托盘列表</h1>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right">
{{--                            <a href="{{url('warehouse/report/scanInBatch')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-lg btn-flat">批量扫码入所</a>--}}
                            <a href="{{url('entire/instance/create')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-lg btn-flat">添加</a>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-condensed" id="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>托盘编码</th>
                                <th>位置</th>
                                <th>承重范围（kg）</th>
                                <th>项目编号</th>
                                <th>物资编码</th>
                                <th>重量（kg）</th>
                                <th>剩余重量（kg）</th>
                                <th>电压V</th>
                                <th>信号强度</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tray as $v)
                                <tr>
                                    <th>{{$v->id}}</th>
                                    <th>{{$v->tray_code}}</th>
                                    <th>{{$v->place}}</th>
                                    <th>{{$v->min}}~{{$v->max}}</th>
                                    <th>{{$v->ProjectName}}</th>
                                    <th>{{$v->MaterialCode}}</th>
                                    <th>{{$v->weight}}</th>
                                    <th>{{$v->ResidueWeight}}</th>
                                    <th>{{$v->BatteryV}}V</th>
                                    <th>{{$v->CSQ}}dBm</th>
                                    @if($v->ResidueWeight == 0)
                                    <th>已满</th>
                                    @elseif($v->ResidueWeight == $v->max)
                                    <th>空置</th>
                                    @else
                                    <th>未满</th>
                                    @endif
                                    <td>
{{--                                        <div class="btn-group">--}}
{{--                                            <a href="{{url('entire/instance',$entireInstance->identity_code)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">修改</a>--}}
{{--                                            @if($entireInstance->FixWorkflow)--}}
{{--                                                @if($entireInstance->FixWorkflow->status != 'FIXED')--}}
{{--                                                    --}}{{--查看检修单--}}
{{--                                                    <a href="{{url('measurement/fixWorkflow',$entireInstance->fix_workflow_serial_number)}}/edit?page={{request()->get('page',1)}}" class="btn btn-warning btn-flat">检修</a>--}}
{{--                                                @endif--}}
{{--                                            @else--}}
{{--                                                --}}{{--新建检修单--}}
{{--                                                <a href="{{url('measurement/fixWorkflow/create')}}?page={{request()->get('page',1)}}&type=FIX&identity_code={{$entireInstance->identity_code}}" class="btn btn-warning btn-flat">新检修</a>--}}
{{--                                            @endif--}}
{{--                                            @if(array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'INSTALLED' || array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'INSTALLING')--}}
{{--                                                <a href="javascript:" onclick="fnFixingIn('{{$entireInstance->identity_code}}')" class="btn btn-default btn-flat">入所</a>--}}
{{--                                            @else--}}
{{--                                                <a href="javascript:" class="btn btn-default btn-flat disabled" disabled="disabled">入所</a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-primary btn-flat">查看</a>--}}
{{--                                        <a href="javascript:" onclick="fnDelete({{$warehouseReport->serial_number}})" class="btn btn-danger btn-flat">删除</a>--}}

{{--                                        <a href="{{url('warehouse/report')}}" class="btn btn-primary btn-flat">查看</a>--}}
                                        <a href="javascript:" onclick="fnDelete({{$v->id}})" class="btn btn-danger btn-flat">删除</a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($entireInstances->hasPages())
                        <div class="box-footer">
                            {{ $entireInstances->appends(['categoryUniqueCode'=>request()->get('categoryUniqueCode'),'entireModelUniqueCode'=>request()->get('entireModelUniqueCode'),'updatedAt'=>request()->get('updatedAt')])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{--模态框--}}
        <div class="divModalEntireInstanceFixing"></div>
        <div id="divModalFixWorkflowInOnce"></div>
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

            // 当月报表
            new Morris.Donut({
                element: 'chartDeviceDynamicStatusCurrentMonth',
                resize: true,
                colors: ["#3c8dbc", "#f56954", "#00a65a", "#CA195A"],
                data: JSON.parse('{!! $deviceDynamicStatusCurrentMonth !!}')[1],
                hideHover: 'auto'
            });

            // 近三个月报表
            new Morris.Donut({
                element: 'chartDeviceDynamicStatusNearlyThreeMonth',
                resize: true,
                colors: ["#3c8dbc", "#f56954", "#00a65a", "#CA195A"],
                data: JSON.parse('{!! $deviceDynamicStatusNearlyThreeMonth !!}')[1],
                hideHover: 'auto'
            });
        });

        /**
         * 打开入所窗口
         * @param entireInstanceIdentityCode
         */
        fnFixingIn = (entireInstanceIdentityCode) => {
            $.ajax({
                url: `{{url('entire/instance/fixingIn')}}/${entireInstanceIdentityCode}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                    // alert(response);
                    // location.reload();
                    $("#divModalFixWorkflowInOnce").html(response);
                    $("#modalFixWorkflowInOnce").modal("show");
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        fnCurrentPage = () => {
            location.href = `?categoryUniqueCode=${$("#selCategoryUniqueCode").val()}&entireModelUniqueCode=${$("#selEntireModelUniqueCode").val()}&updatedAt=${$("#selUpdatedAt").val()}`;
        };
    </script>
    <script>
        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('entire/instance')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };
    </script>
@endsection
