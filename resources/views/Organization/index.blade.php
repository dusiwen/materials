@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">机构列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('organization/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                        <tr>
                            <th>编号</th>
                            <th>名称</th>
                            {{--<th>主账号</th>--}}
                            <th>父级</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$organization->id}}</td>
                        <td>{{$organization->name}}</td>
{{--                        <td>{!! $organization->is_main ? '<i class="fa fa-check></i>️' : '<i class="fa fa-times"></i>' !!}</td>--}}
                        <td>所在机构</td>
                        <td>
                            <a href="{{url('organization',$organization->id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:" onclick="fnDelete({{$organization->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @foreach($subOrganizations as $subOrganization)
                        <tr>
                            <td>{{$subOrganization->id}}</td>
                            <td>{{$subOrganization->name}}</td>
{{--                            <td>{!! $subOrganization->is_main ? '<i class="fa fa-check></i>️' : '<i class="fa fa-times"></i>' !!}</td>--}}
                            <td>{{$subOrganization->parent ? $subOrganization->parent->name : ''}}</td>
                            <td>
                                <a href="{{url('organization',$subOrganization->id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$subOrganization->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($subOrganizations->hasPages())
                <div class="box-footer">
                    {{ $subOrganizations->links() }}
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
                url: `{{url('organization')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };
    </script>
@endsection
