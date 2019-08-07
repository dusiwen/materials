@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{session()->get('current.menu.title')}}</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('/account/create')}}" class="btn btn-box-tool"><i class="fa fa-plus-square">&nbsp;</i></a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed" id="table">
                        <thead>
                            <tr>
                                <th>账号</th>
                                <th>名称</th>
                                <th>所属机构</th>
                                <th>邮箱</th>
                                <th>手机号</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td>{{$account->account}}</td>
                                <td>{{$account->nickname}}</td>
                                <td>
                                    @if($account->organization)
                                        {{$account->organization->name}}
                                    @endif
                                </td>
                                <td>{{$account->email}}</td>
                                <td>{{$account->phone}}</td>
                                <td>
                                    <a href="{{url('account',$account->open_id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:" onclick="fnDelete({{$account->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($accounts->hasPages())
                <div class="box-footer">
                    {{ $accounts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script>
        /**
         * 删除
         * @param {string} openId 开放编号
         */
        fnDelete = function (openId) {
            $.ajax({
                url: `{{url('account')}}/${openId}`,
                type: "delete",
                data: {id: openId},
                success: function (response) {
                    console.log('success:', response);
                    location.reload();
                },
                error: function (error) {
                    if (error.status == 401) location.href = "{{url('login')}}";
                    console.log('fail:', error);
                }
            });
        };
    </script>
@endsection
