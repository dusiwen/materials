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
        <div class="row">
            <form action="">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">筛选</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right">
                                <button class="btn btn-info btn-flat">筛选</button>
                            </div>
                        </div>
                        <div class="box-body form-horizontal">
                            <div class="row">
                                {{--<div class="col-md-3">--}}
                                {{--<div class="input-group">--}}
                                {{--<div class="input-group-addon">种类</div>--}}
                                {{--<select id="selCategory" name="category_unique_code" class="form-control select2" style="width:100%;" onchange="fnGetEntireModelByCategoryUniqueCode()">--}}
                                {{--<option value="">全部</option>--}}
                                {{--@foreach(\App\Model\Category::all() as $category)--}}
                                {{--<option value="{{$category->unique_code}}" {{request()->get('category_unique_code') == $category->unique_code ? 'selected' : ''}}>{{$category->name}}</option>--}}
                                {{--@endforeach--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                {{--<div class="input-group">--}}
                                {{--<div class="input-group-addon">类型</div>--}}
                                {{--<select id="selEntireModel" name="entire_model_unique_code" class="form-control select2" style="width:100%;"></select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-addon">时间段</div>
                                        <input name="date" type="text" class="form-control pull-right" id="date" value="{{request()->get('date',date("Y-m-d").'~'.date("Y-m-d"))}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-addon">厂家</div>
                                        <select name="factory_name" class="form-control select2" style="width: 100%;">
                                            <option value="">全部</option>
                                            @foreach(\App\Model\Factory::all() as $factory)
                                                <option value="{{$factory->name}}" {{request()->get('factory_name') == $factory->name ? 'selected' : ''}}>{{$factory->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">质量报告</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>唯一编号</th>
                                <th>种类</th>
                                <th>类型</th>
                                <th>厂家</th>
                                <th>检修数</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($entireInstances as $entireInstance)
                                <tr style="cursor: pointer;" onclick="location.href='{{url('report/qualityShow',$entireInstance->identity_code)}}'">
                                    <td>{{$entireInstance->identity_code}}</td>
                                    <td>{{$entireInstance->Category->name}}</td>
                                    <td>{{$entireInstance->EntireModel->name}}</td>
                                    <td>{{$entireInstance->factory_name}}</td>
                                    <td>{{$entireInstance->fix_workflow_count}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            @if($entireInstances->hasPages())
                                <div class="box-footer">
                                    {{ $entireInstances->appends(['factory_name'=>request()->get('factory_name'),'date'=>request()->get('date')])->links() }}
                                </div>
                            @endif
                            </tfoot>
                        </table>
                    </div>
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

            fnGetEntireModelByCategoryUniqueCode();
        });

        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = id => {
            $.ajax({
                url: `{{url('report/quality')}}/${id}`,
                type: "delete",
                data: {},
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };

        /**
         * 根据种类获取型号
         */
        fnGetEntireModelByCategoryUniqueCode = () => {
            if ($("#selCategory").val()) {
                $.ajax({
                    url: `{{url('category')}}/${$("#selCategory").val()}`,
                    type: "get",
                    data: {},
                    async: true,
                    success: function (response) {
                        console.log('success:', response);
                        html = `<option value="">全部</option>`;
                        for (let key in response) {
                            html += `<option value="${response[key].unique_code}" ${response[key].unique_code == "{{request()->get('entire_model_unique_code')}}" ? 'selected' : ''}>${response[key].name}</option>`;
                        }
                        $("#selEntireModel").html(html);
                    },
                    error: function (error) {
                        // console.log('fail:', error);
                        if (error.status == 401) location.href = "{{url('login')}}";
                        alert(error.responseText);
                    },
                });
            }
        };
    </script>
@endsection
