@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="row">
            {{--        实时--}}
            <div class="col-md-4">
                <div class="box box-danger" style="height: 430px;">
                    <div class="box-header with-border">
                        <h3>WM盘点凭据（实时）</h3>
                    </div>
                    <div class="box-body chart-responsive" style="height: 350px;">

                    </div>
                </div>
            </div>
{{--        当月--}}
                    <div class="col-md-4">
                        <div class="box box-danger" style="height: 430px;">
                            <div class="box-header with-border">
                                <h3>WM盘点凭据（当月）</h3>
                            </div>
                            <div class="box-body chart-responsive" style="height: 350px;">

                            </div>
                        </div>
                    </div>
{{--                    近三个月--}}
                    <div class="col-md-4">
                        <div class="box box-warning" style="height: 430px;">
                            <div class="box-header with-border">
                                <h3>WM盘点凭据（近三个月）</h3>
                            </div>
                            <div class="box-body chart-responsive" style="height: 350px;">
                                <div class="row">
                                    <div class="col-sm-8 col-md-8">
                                        <div class="chart" id="chartDeviceDynamicStatusNearlyThreeMonth" style="height: 300px; position: relative;"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
        </div>
{{--        --}}{{--筛选--}}
{{--        <section class="content-header">--}}
{{--            <div class="box box-info">--}}
{{--                <div class="box-header with-border">--}}
{{--                    <h3 class="box-title">筛选</h3>--}}
{{--                </div>--}}
{{--                <div class="box-body">--}}
{{--                    <form action="" class="form-horizontal">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-2">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-5 col-md-5 control-label">单位：</label>--}}
{{--                                    <div class="col-sm-7 col-md-7">--}}
{{--                                        <select name="direction" class="form-control select2" style="width:100%;">--}}
{{--                                            --}}{{--                                        <option value="">全部</option>--}}
{{--                                            --}}{{--                                        <option value="IN" {{request()->get('direction') == 'IN' ? 'selected' : ''}}>入所</option>--}}
{{--                                            --}}{{--                                        <option value="OUT" {{request()->get('direction') == 'OUT' ? "selected" : ''}}>出所</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-3 col-md-3 control-label">日期:</label>--}}
{{--                                    <div class="col-sm-8 col-md-8">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <div class="input-group-addon">--}}
{{--                                                <i class="fa fa-calendar"></i>--}}
{{--                                            </div>--}}
{{--                                            <input name="updated_at" type="text" class="form-control pull-right" id="reservation" value="{{request()->get('updated_at')}}">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-2">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-5 col-md-5  control-label">库存地点：</label>--}}
{{--                                    <div class="col-sm-7 col-md-7">--}}
{{--                                        <select name="category_unique_code" class="form-control select2" style="width:100%;">--}}
{{--                                            <option value="">全部</option>--}}
{{--                                            --}}{{--                                        @foreach(\App\Model\Category::all() as $category)--}}
{{--                                            --}}{{--                                            <option value="{{$category->unique_code}}" {{request()->get('category_unique_code') == $category->unique_code ? 'selected' : ''}}>{{$category->name}}</option>--}}
{{--                                            --}}{{--                                        @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-3 col-md-3  control-label">物料凭证号：</label>--}}
{{--                                    <div class="col-sm-8 col-md-8">--}}
{{--                                        <select name="type" class="form-control select2" style="width:100%;">--}}
{{--                                            <option value="">全部</option>--}}
{{--                                            --}}{{--                                        @foreach(\App\Model\WarehouseReport::$TYPE as $typeKey => $typeValue)--}}
{{--                                            --}}{{--                                            <option value="{{$typeKey}}" {{request()->get('type') == $typeKey ? 'selected' : ''}}>{{$typeValue}}</option>--}}
{{--                                            --}}{{--                                        @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-1">--}}
{{--                                <button class="btn btn-info btn-flat">筛选</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
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
{{--                            <td>{{$fixWorkflow->serial_number}}</td>--}}
{{--                            <td>{{$fixWorkflow->updated_at}}</td>--}}
{{--                            <td>{{$fixWorkflow->EntireInstance->Category->name}}</td>--}}
{{--                            <td>{{$fixWorkflow->EntireInstance->EntireModel->name}}</td>--}}
{{--                            <td>{{$fixWorkflow->EntireInstance->serial_number ?: '新设备'}}</td>--}}
{{--                            <td>{{$fixWorkflow->status}}</td>--}}
{{--                            <td>{{$fixWorkflow->stage}}</td>--}}
{{--                            <td>--}}
{{--                                <div class="btn-group btn-group-lg">--}}
{{--                                    @if(array_flip(\App\Model\FixWorkflow::$STAGE)[$fixWorkflow->stage] == 'WAIT_CHECK')--}}
{{--                                        <a href="{{url('measurement/fixWorkflow/create')}}?page={{request()->get('page',1)}}&type=CHECK&identity_code={{$fixWorkflow->EntireInstance->identity_code}}" class="btn btn-warning btn-flat">验收</a>--}}
{{--                                        <a href="{{url('measurement/fixWorkflow',$fixWorkflow->serial_number)}}/edit?page={{request()->get('page',1)}}" class="btn btn-warning btn-flat">验收</a>--}}
{{--                                    @else--}}
{{--                                        <a href="{{url('measurement/fixWorkflow',$fixWorkflow->serial_number)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">详情</a>--}}
{{--                                    @endif--}}
{{--                                    <a href="javascript:" onclick="fnDelete('{{$fixWorkflow->serial_number}}')" class="btn btn-danger btn-flat">删除</a>--}}
{{--                                </div>--}}
{{--                            </td>--}}
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
                            <td>账物不一致</td>
                            <td>{{$v->Analyse}}</td>
                            <td>
                                <a href="{{url("measurement/fixWorkflow/B04920190527094547_03_1558921547/edit?id=$v->id")}}" class="btn btn-primary btn-flat">查看</a>
                            </td>
                            @else
                            <td>盘点正常</td>
                            @endif

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
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            if ($('.select2')) {
                $('.select2').select2();
            }
            // iCheck for checkbox and radio inputs
            if ($('input[type="checkbox"].minimal, input[type="radio"].minimal')) {
                $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue'
                });
            }
            if ($("#datapicker")) {
                $('#datepicker').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
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

