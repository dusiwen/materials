@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">项目列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
{{--                    @if($type == 'product')--}}
{{--                        <a href="{{url('measurements/create')}}?page={{request()->get('page',1)}}&warehouseProductId={{$warehouseProductId}}&type=product" class="btn btn-default btn-lg btn-flat">新建</a>--}}
{{--                    @elseif($type=='self')--}}
                        <a href="{{url('measurements/create')}}?page={{request()->get('page',1)}}&type=self" class="btn btn-default btn-lg btn-flat">新建</a>
{{--                    @endif--}}
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>项目名称</th>
                        <th>WBS元素</th>
                        <th>添加时间</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($project as $v)
                        <tr>
                            <td>{{$i--}}</td>
                            <td>{{$v->project_name}}</td>
                            <td>{{$v->WBS}}</td>
                            <td>{{date("Y-m-d H:i:s",$v->time)}}</td>
                            <td>{{$v->date}}</td>
                            <td class="btn-group btn-group-lg">
{{--                                <a href="{{url('measurements',$v->id)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat btn-sm">编辑</a>--}}
                                <a href="javascript:" onclick="fnDelete({{$v->id}})" class="btn btn-danger btn-flat btn-sm">删除</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
{{--            分页--}}
{{--            @if($measurements->hasPages())--}}
{{--                <div class="box-footer">--}}
{{--                    {{ $measurements->links() }}--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </section>
@endsection
@section('script')
    <script>
        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('measurements')}}/${id}`,
                type: "delete",
                data: {id: id},
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
         * 打开添加测试模板窗口
         */
        fnCreateMeasurement = function (warehouseProductId) {
            $.ajax({
                url: "{{url('measurements/create')}}",
                type: "get",
                data: {warehouseProductId: warehouseProductId, type: 'product'},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    // location.reload();
                    $("#divModal").html(response);
                    $("#modal").modal('show');
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                },
            });
        };
    </script>
@endsection
