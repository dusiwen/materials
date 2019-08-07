@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">零件列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('warehouse/product/part/create')}}" class="btn btn-box-tool" title="添加零件"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>库存</th>
{{--                        <th>维护周期</th>--}}
                        <th>所属设备类型</th>
                        <th>特性</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseProductParts as $warehouseProductPart)
                        <tr>
                            <td>{{$warehouseProductPart->id}}</td>
                            <td>{{$warehouseProductPart->name}}<sub>{{$warehouseProductPart->subscript}}</sub></td>
                            <td>{{$warehouseProductPart->inventory}}</td>
{{--                            <td>{{$warehouseProductPart->fix_cycle_value.$warehouseProductPart->fix_cycle_type}}</td>--}}
                            <td>{{$warehouseProductPart->category->name}}</td>
                            <td>{{$warehouseProductPart->character}}</td>
                            <td>
                                <a href="{{url('warehouse/product/part',$warehouseProductPart->id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$warehouseProductPart->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($warehouseProductParts->hasPages())
                <div class="box-footer">
                    {{ $warehouseProductParts->links() }}
                </div>
            @endif
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
                url: `{{url('warehouse/product/part')}}/${id}`,
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
