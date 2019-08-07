<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->resource('/', 'App\Http\Controllers\V1\IndexController');  # 入口
    $api->post('/register', 'App\Http\Controllers\V1\AccountController@register')->name('register.post');  # 注册
    $api->post('/login', 'App\Http\Controllers\V1\AccountController@login')->name('login.post');  # 登陆
    $api->get('/emailCode/{account}', 'App\Http\Controllers\V1\CodeController@email')->name('emailCode.get');  # 申请验证码
    $api->get('/smsCode/{account}', 'App\Http\Controllers\V1\CodeController@sms')->name('smsCode.get');  # 申请验证码
    $api->put('/forget', 'App\Http\Controllers\V1\AccountController@forget')->name('forget.put');  # 忘记密码
    $api->put('/password', 'App\Http\Controllers\V1\AccountController@password')->name('password.put');  # 修改密码
    $api->get('/profile', 'App\Http\Controllers\V1\AccountController@profile')->middleware('check-jwt')->name('profile.get');  # 个人信息

    $api->group(['middleware' => ['check-jwt', 'check-permission'],'namespace'=>'App\Http\Controllers\V1'], function ($api) {
        $api->resource('/account', 'AccountController');  # 账号
        $api->resource('/organization', 'OrganizationController');  # 机构
        $api->resource('/status', 'StatusController');  # 状态

        $api->resource('/menu', 'RbacMenuController');  # 菜单
        $api->resource('/permission', 'RbacPermissionController');  # 权限
        $api->resource('/role', 'RbacRoleController');  # 角色
        $api->resource('/roleAccount', 'PivotRoleAccountController');  # 角色 → 用户
        $api->resource('/rolePermission', 'PivotRolePermissionController');  # 角色 → 权限
        $api->resource('/roleMenu','PivotRoleMenuController');  # 角色 → 路由

        $api->resource('/deviceBrand', 'DeviceBrandController');  # 设备品牌
        $api->resource('/deviceCategory', 'DeviceCategoryController');  # 设备类目
        $api->resource('/deviceImage', 'DeviceImageController');  # 设备图片
        $api->resource('/deviceSpu', 'DeviceSpuController');  # SPU
        $api->resource('/deviceSku', 'DeviceSkuController');  # SKU
        $api->resource('/deviceAttribute', 'DeviceAttributeController');  # 设备属性
        $api->resource('/deviceAttributeKey', 'DeviceAttributeKeyController');  # 设备属性键
        $api->resource('/deviceAttributeValue', 'DeviceAttributeValueController');  # 设备属性值
        $api->get('/getDeviceSkuByAttribute/{ids}', 'DeviceSkuController@attribute');  # 通过属性定位SKU
        $api->resource('/device', 'DeviceController');  # 设备
        $api->resource('/template', 'TemplateController');  # 模板
        $api->resource('/report/sensor', 'ReportSensorController');  # 数据:传感器
        $api->resource('/noticeGroup', 'NoticeGroupController');  # 通知组
        $api->resource('/noticeGroupAccount', 'PivotNoticeGroupAccountController');  # 通知组 → 用户
        $api->resource('/noticeGroupDevice', 'PivotNoticeGroupDeviceController');  # 通知组 → 设备
        $api->resource('/alarmTemplate', 'AlarmTemplateController');  # 报警模板
        $api->resource('/alarmTemplateDevice', 'AlarmTemplateDeviceController');  # 报警模板 → 设备
        $api->resource('/deviceGroup','DeviceGroupController');  # 设备分组
        $api->resource('/deviceGroupDevice','DeviceGroupDeviceController');  # 设备分组 → 设备
        $api->resource('/wechat/qrcode','\Wechat\QrCodeController');  # 微信二维码
        $api->resource('/rfid/qrcode','\Wechat\QrCodeController');  # rfid


    });
});
