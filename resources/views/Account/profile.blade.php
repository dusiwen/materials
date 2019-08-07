@extends('Layout.index')
@section('content')
    <section class="content">
        <div class="row">
            @include('Layout.alert')
            <div class="col-md-3">
                {{--个人信息--}}
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <a href="javascript:" onclick="$('[name=image]').click()">
                            <img
                                id="imgAccountAvatar"
                                class="profile-user-img img-responsive img-circle"
                                src="{{$account->avatar
                                    ? '/'.$account->avatar
                                    :'/images/account-avatar-lack.jpeg'}}"
                                alt="点击上传图片">
                        </a>

                        <h3 class="profile-username text-center">{{$account->nickname}}</h3>

                        {{--<p class="text-muted text-center"><a href="javascript:" onclick="$('[name=image]').click()">上传头像</a></p>--}}

                        {{--个人信息展示--}}
                        <div id="divAccountShow">
                            <ul class="list-group list-group-unbordered" id="divAccountShow">
                                <li class="list-group-item">
                                    <b>手机号：</b> <a class="pull-right">{{$account->phone ?:'无'}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>邮箱：</b> <a class="pull-right">{{$account->email?:'无'}}</a>
                                </li>
                            </ul>

                            <a href="javascript:" onclick="showEdit()" class="btn btn-primary btn-block"><i class="fa fa-edit">&nbsp;</i><b>编辑资料</b></a>
                        </div>


                        {{--个人信息编辑--}}
                        <div id="divAccountEdit" style="display: none;">
                            <form id="frmAccountEdit">
                                <div class="form-group">
                                    <label>昵称：</label>
                                    <input name="nickname" type="text" class="form-control" placeholder="昵称" required value="{{$account->nickname}}">
                                </div>

                                <div class="form-group">
                                    <label>手机号：</label>
                                    <input name="phone" type="text" class="form-control" placeholder="手机号" required value="{{$account->phone}}">
                                </div>

                                <div class="form-group">
                                    <label>邮箱：</label>
                                    <input name="email" type="email" class="form-control" placeholder="邮箱" required value="{{$account->email}}">
                                </div>

                                <a href="javascript:" onclick="saveEdit()" class="btn btn-success btn-block"><i class="fa fa-check">&nbsp;</i><b>保存编辑</b></a>
                                {{--<a href="javascript:" onclick="fnShowProfile()" class="btn btn-danger pull-right"><i class="fa fa-times">&nbsp;</i>取消编辑</a>--}}
                            </form>
                            <hr>
                            <form id="divPasswordEdit">
                                <div class="form-group">
                                    <label>原密码：</label>
                                    <input name="password" class="form-control" type="password" required placeholder="原密码">
                                </div>
                                <div class="form-group">
                                    <label>新密码：</label>
                                    <input name="new_password" class="form-control" type="password" required placeholder="新密码">
                                </div>
                                <a href="javascript:" class="btn btn-danger btn-block" onclick="savePassword()"><i class="fa fa-exclamation">&nbsp;</i>修改密码</a>
                            </form>
                        </div>

                        <form id="uploadForm" enctype="multipart/form-data" style="display: inline;">　　<!-- 声明文件上传 -->
                            <input id="fileUpload" style="display: none; width: 1px;" type="file" name="image" onchange="fileChange('${base}')"/>　　<!-- 定义change事件,选择文件后触发 -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab">我的报警记录</a></li>
                        <li><a href="#timeline" data-toggle="tab">角色与权限</a></li>
                        <li><a href="#settings" data-toggle="tab">我的通知组</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="activity">
                            <!-- Post -->
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="/AdminLTE/dist/img/user1-128x128.jpg" alt="user image">
                                    <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                                    <span class="description">Shared publicly - 7:30 PM today</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore the hate as they create awesome
                                    tools to help create filler text for everyone from bacon lovers
                                    to Charlie Sheen fans.
                                </p>
                                <ul class="list-inline">
                                    <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                    <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                    </li>
                                    <li class="pull-right">
                                        <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                                            (5)</a></li>
                                </ul>

                                <input class="form-control input-sm" type="text" placeholder="Type a comment">
                            </div>
                            <!-- /.post -->

                            <!-- Post -->
                            <div class="post clearfix">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                    <span class="username">
                          <a href="#">Sarah Ross</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                                    <span class="description">Sent you a message - 3 days ago</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore the hate as they create awesome
                                    tools to help create filler text for everyone from bacon lovers
                                    to Charlie Sheen fans.
                                </p>

                                <form class="form-horizontal">
                                    <div class="form-group margin-bottom-none">
                                        <div class="col-sm-9">
                                            <input class="form-control input-sm" placeholder="Response">
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-danger pull-right btn-block btn-sm">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.post -->

                            <!-- Post -->
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image">
                                    <span class="username">
                          <a href="#">Adam Jones</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                                    <span class="description">Posted 5 photos - 5 days ago</span>
                                </div>
                                <!-- /.user-block -->
                                <div class="row margin-bottom">
                                    <div class="col-sm-6">
                                        <img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img class="img-responsive" src="../../dist/img/photo2.png" alt="Photo">
                                                <br>
                                                <img class="img-responsive" src="../../dist/img/photo3.jpg" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-sm-6">
                                                <img class="img-responsive" src="../../dist/img/photo4.jpg" alt="Photo">
                                                <br>
                                                <img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <ul class="list-inline">
                                    <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                    <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                    </li>
                                    <li class="pull-right">
                                        <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                                            (5)</a></li>
                                </ul>

                                <input class="form-control input-sm" type="text" placeholder="Type a comment">
                            </div>
                            <!-- /.post -->
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            <ul class="timeline timeline-inverse">
                                <!-- timeline time label -->
                                <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                                </li>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-envelope bg-blue"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                                        <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                        <div class="timeline-body">
                                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                            quora plaxo ideeli hulu weebly balihoo...
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-primary btn-xs">Read more</a>
                                            <a class="btn btn-danger btn-xs">Delete</a>
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-user bg-aqua"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                                        <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                                        </h3>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-comments bg-yellow"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                        <div class="timeline-body">
                                            Take me to your leader!
                                            Switzerland is small and neutral!
                                            We are more like Germany, ambitious and misunderstood!
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <!-- timeline time label -->
                                <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                                </li>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-camera bg-purple"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                                        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                        <div class="timeline-body">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                            </ul>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="settings">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputName" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputName" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        /**
         * 上传图片
         */
        fileChange = () => {
            var fileName = $('#fileUpload').val();　　　　　　　　　　　　　　　　　　//获得文件名称
            var fileType = fileName.substr(fileName.length - 4, fileName.length);　　//截取文件类型,如(.xls)
            $.ajax({
                url: "{{url('/avatar')}}",　　　　　　　　　　//上传地址
                type: 'POST',
                cache: false,
                data: new FormData($('#uploadForm')[0]),　　　　　　　　　　　　　//表单数据
                processData: false,
                contentType: false,
                success: res => {
                    // console.log(res);
                    alert(res);
                    location.reload();
                }, error: error => {
                    // console.log(error.responseJSON);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(responseJSON.message);
                    location.reload();
                }
            });
        };

        /**
         * 显示编辑窗口
         */
        showEdit = function () {
            $("#divAccountEdit").show();
            $("#divAccountShow").hide();
        };

        /**
         * 显示资料窗口
         */
        showProfile = function () {
            $("#divAccountEdit").hide();
            $("#divAccountShow").show();
        };

        /**
         * 保存编辑
         */
        saveEdit = function () {
            $.ajax({
                url: "{{url('account',session()->get('account.id'))}}",
                type: "put",
                data: $("#frmAccountEdit").serialize(),
                success: function (response) {
                    showProfile();
                    alert('保存成功');
                },
                error: function (error) {
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 保存密码
         */
        savePassword = function () {
            $.ajax({
                url: "{{url('password')}}",
                type: "put",
                data: $("#divPasswordEdit").serialize(),
                success: function (response) {
                    showProfile();
                    alert('保存成功');
                },
                error: function (error) {
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };
    </script>
@endsection
