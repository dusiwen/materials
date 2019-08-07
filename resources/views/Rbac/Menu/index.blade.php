@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">菜单列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('rbac/menu/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>标题</th>
                        <th>父级</th>
                        <th>排序</th>
                        <th>图标&nbsp;<a href="http://www.fontawesome.com.cn/faicons/#icons" target="_blank">实例</a></th>
                        <th>uri</th>
                        <th>路由别名</th>
                        <th>子标题</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{$menu->title}}</td>
                            <td>@if($menu->parent){{$menu->parent->title}}@endif</td>
                            <td>{{$menu->sort}}</td>
                            <td><i class="fa fa-{{$menu->icon}}"></i></td>
                            <td>{{$menu->uri}}</td>
                            <td>{{$menu->action_as}}</td>
                            <td>{{$menu->sub_title}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{url('rbac/menu',$menu->id)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:" onclick="fnDelete({{$menu->id}})" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($menus->hasPages())
                <div class="box-footer">
                    {{ $menus->links() }}
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
                url: `{{url('rbac/menu')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    console.log('success:', response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };
    </script>
@endsection
