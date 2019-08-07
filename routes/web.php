<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

# 不需要登录
Route::post('register', 'AccountController@register');  # 注册 √
Route::get('register', 'AccountController@getRegister');  # 注册页 √
Route::post('login', 'AccountController@login');  # 登陆 √
Route::get('login', 'AccountController@getLogin');  # 登陆页 √
Route::get('code/email', 'CodeController@email');  # 申请邮件验证码 √
Route::put('forget', 'AccountController@forget');  # 忘记密码 √
Route::get('forget', 'AccountController@getForget');  # 忘记密码页面 √
Route::put('password', 'AccountController@password');  # 修改密码 √
Route::get('logout', 'AccountController@logout');  # 退出登录 √
Route::get('profile', 'AccountController@profile');  # 个人中心 √
Route::group(['prefix' => 'download'], function () {
    #  下载
    Route::get('in', 'DownloadController@in');
    Route::get('out', 'DownloadController@out');
});

# 需要登录
Route::group(['middleware' => ['web-check-login', 'web-check-permission', 'web-get-current-menu']], function () {
//Route::group(['middleware' => ['web-check-permission', 'web-get-current-menu']], function () {

    Route::resource('organization', 'OrganizationController');  # 机构 √
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'CategoryController@index')->name('category.index');  # 列表
        Route::get('create', 'CategoryController@create')->name('category.create');  # 新建页面
        Route::post('/', 'CategoryController@store')->name('category.store');  # 新建
        Route::get('/{id}', 'CategoryController@show')->name('categoryItem.show');  # 详情页面
        Route::get('/{id}/edit', 'CategoryController@edit')->name('category.edit');  # 编辑页面
        Route::put('/{id}', 'CategoryController@update')->name('category.update');  # 编辑
        Route::delete('/{id}', 'CategoryController@destroy')->name('category.destroy');  # 删除
    });
    Route::resource('factory', 'FactoryController');  # 工厂 √

    Route::group(['prefix' => ''], function () {
        Route::get('/', 'IndexController@index')->name('index.index');  # 列表
        Route::get('test', 'IndexController@test')->name('index.test');
    });

    Route::resource('account', 'AccountController');  # 账号 √
    Route::get('profile', 'AccountController@profile')->name('profile.get');  # 个人信息 √
    Route::resource('organization', 'OrganizationController');  # 机构 √
    Route::resource('status', 'StatusController');  # 状态 √
    Route::post('avatar', 'AccountController@avatar')->name('avatar.post');  # 上传头像 √
    Route::post('accountBindRoles/{accountOpenId}', 'AccountController@bindRoles')->name('accountBindRoles.name');  # 用户绑定角色 √
    Route::post('roleBindPermissions/{roleId}', 'Rbac\RoleController@bindPermissions')->name('roleBindPermissions.post');  # 角色绑定权限 √
    Route::post('menuBindRoles/{menuId}', 'Rbac\MenuController@bindRoles')->name('menuBindRoles.post');  # 菜单绑定角色 √
    Route::post('setDeviceSpuAvatarImage/{deviceSpuId}', 'Device\SpuController@setAvatarImage')->name('setDeviceSpuAvatarImage.post');  # 设置设备封面图 √
    Route::get('getDeviceGroupByOrganizationId', 'Device\GroupController@getDeviceGroupByOrganizationId')->name('getDeviceGroupByOrganizationId.get');  # 根据机构编号获取设备分组 √
    Route::get('getDeviceSpuByDeviceCategoryId', 'Device\SpuController@getDeviceSpuByDeviceCategoryId')->name('getDeviceSpuByDeviceCategoryId.get');  # 根据类目获取SPU √
    Route::get('getDeviceSkuByDeviceSpuId', 'Device\SkuController@getDeviceSkuByDeviceSpuId')->name('getDeviceSkuByDeviceSpuId.get');  # 根据SpuId 获取Sku √
    Route::get('getDeviceAttributeKeyByDeviceCategoryId', 'Device\AttributeKeyController@deviceCategory')->name('getDeviceAttributeKeyByDeviceCategoryId.get');  # 根据设备类目获取设备属性健 √
    Route::get('getDeviceAttributeValueByDeviceCategoryId', 'Device\AttributeValueController@deviceCategory')->name('getDeviceAttributeValueByDeviceCategoryId.get');  # 根据设备类目获取设备属性值 √
    Route::get('getWarehouseProductPartByWarehouseProductId/{warehouseProductId}', 'Warehouse\Product\PartController@byWarehouseProductId')->name('getWarehouseProductPartByWarehouseProductId.get');  # 根据整件编号获取零件列表 √
    Route::put('spotCheckFailedFixWorkflow/{fixWorkflowId}', 'Measurement\FixWorkflowController@spotCheckFailed')->name('spotCheckFailedFixWorkflow.put');  # 标记工单抽检不通过 √
    Route::get('changePartInstance', 'Measurement\FixWorkflowController@getChangePartInstance')->name('changePartInstance.get');  # 修改配件所属页面 √
    Route::post('changePartInstance', 'Measurement\FixWorkflowController@postChangePartInstance')->name('changePartInstance.post');  # 修改派件所属 √
    Route::get('downloadProcurementPartTemplateExcel', 'Warehouse\Procurement\PartController@downloadProcurementPartTemplateExcel')->name('downloadProcurementPartTemplateExcel.get');  # 下载零件采购单模板 √
    Route::get('scrapWarehouseProductInstance/{warehouseProductInstanceId}', 'Warehouse\Product\InstanceController@getScrapWarehouseProductInstance')->name('scrapWarehouseProductInstance.get');  # 设备报废 √
    Route::get('processWarehouseProductPlan/{warehouseProductPlanId}', 'Warehouse\Product\PlanController@getProcessWarehouseProductPlan')->name('processWarehouseProductPlan.get');  # 处理排期 √
    Route::get('fixToOut/{fixWorkflowId}', 'Warehouse\Report\ProductController@getFixToOut')->name('fixToOut.get');  # 送检出所 √
    Route::get('fixToOutFinish/{fixWorkflowId}', 'Warehouse\Report\ProductController@getFixToOutFinish')->name('fixToOutFinish.get');  # 送检出所完成 √
    Route::get('setWarehouseProductInstanceIsUsing/{warehouseProductInstanceId}', 'Warehouse\Product\InstanceController@getWarehouseProductInstance')->name('setWarehouseProductInstanceIsUsing.get');  # 设置设备为主要设备 √
    Route::get('getWarehouseProductPartByCategoryOpenCode/{categoryOpenCode}', 'Warehouse\Product\PartController@byCategoryOpenCode')->name('getWarehouseProductPartByCategoryId.get');  # 根据设备类型编号获取零件列表
    Route::get('downloadWarehouseReportInOrderTemplateExcel', 'Warehouse\Report\InOrderController@downloadTemplateExcel')->name('downloadWarehouseReportInOrderTemplateExcel.get');  # 下载入库单Excel模板 √
    Route::get('downloadWarehouseReportOutOrderTemplateExcel', 'Warehouse\Report\OutOrderController@downloadTemplateExcel')->name('downloadWarehouseReportOutOrderTemplateExcel.get');  # 下载出库单Excel模板 √
    Route::get('downloadConfirmTemplateExcel', 'Warehouse\Report\OutOrderController@downloadConfirmTemplateExcel')->name('downloadConfirmTemplateExcel.get');  # 下载出库安装确认Excel模板 √
    Route::post('confirmWarehouseReportOutProductInstance', 'Warehouse\Report\OutOrderController@confirmWarehouseReportOutProductInstance')->name('confirmWarehouseReportOutProductInstance.post');  # 上传出库安装确认单 √
    Route::post('uninstallPartInstance', 'Measurement\FixWorkflowController@postUninstallPartInstance')->name('uninstallPartInstance.post');  # 卸载部件
    Route::post('scrapPartInstance', 'Measurement\FixWorkflowController@postScrapPartInstance')->name('scrapPartInstance.post');  # 报废部件

    # 权限
    Route::group(['prefix' => 'rbac', 'namespace' => 'Rbac'], function () {
        Route::resource('menu', 'MenuController');  # 菜单 √
        Route::resource('permission', 'PermissionController');  # 权限 √
        Route::resource('permissionGroup', 'PermissionGroupController');  # 权限分组 √
        Route::resource('role', 'RoleController');  # 角色 √
        Route::resource('roleAccount', 'PivotRoleAccountController');  # 角色 → 用户 √
        Route::resource('rolePermission', 'PivotRolePermissionController');  # 角色 → 权限 √
        Route::resource('roleMenu', 'PivotRoleMenuController');  # 角色 → 菜单 √
    });

    Route::group(['prefix' => 'maintain'], function () {  # 台账
        Route::get('/report', 'MaintainController@report')->name('maintain.report');  # 统计列表页
        Route::get('/', 'MaintainController@index')->name('maintain.index');  # 列表
        Route::get('create', 'MaintainController@create')->name('maintain.create');  # 新建页面
        Route::post('/', 'MaintainController@store')->name('maintain.store');  # 新建
        Route::get('/{identityCode}', 'MaintainController@show')->name('maintain.show');  # 详情页面
        Route::get('/{identityCode}/edit', 'MaintainController@edit')->name('maintain.edit');  # 编辑页面
        Route::put('/{identityCode}', 'MaintainController@update')->name('maintain.update');  # 编辑
        Route::delete('/{identityCode}', 'MaintainController@destroy')->name('maintain.destroy');  # 删除
    });

    Route::group(['prefix' => 'warehouse', 'namespace' => 'Warehouse'], function () {  # 仓库 √
        Route::resource('products', 'Product\PostController');  # 成品 √
        Route::group(['prefix' => 'product', 'namespace' => 'Product'], function () {
            Route::resource('part', 'PartController');  # 零件 √
            Route::resource('pivot', 'PivotController');  # 中间表 √
            Route::resource('instance', 'InstanceController');  # 设备实例表 √
            Route::resource('planProcess', 'PlanProcessController');  # 排期维护记录 √
        });

        Route::group(['prefix' => 'procurement', 'namespace' => 'Procurement'], function () {
            Route::resource('part', 'PartController');  # 零件采购单 √
            Route::resource('partInstance', 'PartInstanceController');  # 零件采购单实例 √
        });

        Route::group(['prefix' => 'plan'], function () {
            Route::any('/', 'PlanController@index')->name('plan.index');  # 列表
            Route::get('in/{entireInstanceIdentityCode}', 'PlanController@getIn')->name('plan.in.get');  # 入库页面
            Route::post('in/{entireInstanceIdentityCode}', 'PlanController@postIn')->name('plan.in.post');  # 入库
            Route::get('makeFixWorkflow/{entireInstanceIdentityCode}', 'PlanController@getMakeFixWorkflow')->name('plan.makeFixWorkflow.get');  # 生成检修单页面
            Route::post('makeFixWorkflow/{entireInstanceIdentityCode}', 'PlanController@postMakeFixWorkflow')->name('plan.makeFixWorkflow.post');  # 生成检修单
            Route::put('/{warehouseReportSerialNumber}', 'PlanController@update')->name('plan.update');  # 编辑
        });

        Route::group(['prefix' => 'report'], function () {
            Route::get('quality', 'ReportController@quality')->name('report.quality');  # 质量报告
            Route::get('print/{warehouseReportSerialNumber}', 'ReportController@print')->name('report.print');  # 打印页面
            Route::get('scanInBatch', 'ReportController@getScanInBatch')->name('report.scanInBatch.get');  # 批量扫码入所 页面
            Route::post('scanInBatch', 'ReportController@postScanInBatch')->name('report.scanInBatch.post');  # 批量扫码入所
            Route::post('cleanBatch', 'ReportController@postCleanBatch')->name('report.cleanBatch');  # 清空批量表
            Route::post('makeFixWorkflow', 'ReportController@postMakeFixWorkflow')->name('report.makeFixWorkflow');  # 生成检修单
            Route::post('deleteBatch', 'ReportController@postDeleteBatch')->name('report.deleteBatch');  # 删除批量单项
            Route::post('inBatch', 'ReportController@postInBatch')->name('report.inBatch');  # 批量入所
            Route::post('makeFixWorkflowBatch', 'ReportController@postMakeFixWorkflowBatch')->name('report.makeFixWorkflowBatch');  # 批量生成检修单
            Route::get('/', 'ReportController@index')->name('report.index');  # 列表
            Route::get('create', 'ReportController@create')->name('report.create');  # 新建页面
            Route::post('/', 'ReportController@store')->name('report.store');  # 新建
            Route::get('/{warehouseReportSerialNumber}', 'ReportController@show')->name('report.show');  # 详情页面
            Route::get('/{warehouseReportSerialNumber}/edit', 'ReportController@edit')->name('report.edit');  # 编辑页面
            Route::put('/{warehouseReportSerialNumber}', 'ReportController@update')->name('report.update');  # 编辑
            Route::delete('/{warehouseReportSerialNumber}', 'ReportController@destroy')->name('report.destroy');  # 删除
            Route::get('/{warehouseReportSerialNumber}', 'ReportController@stockin')->name('report.stockin');  # 入库
        });
    });

    Route::resource('measurements', 'Measurement\PostController');  # 测试模板 √
    Route::group(['prefix' => 'measurement', 'namespace' => 'Measurement'], function () {
        Route::group(['prefix' => 'fixWorkflow'], function () {
            Route::put('fixed/{fixWorkflowSerialNumber}', 'FixWorkflowController@fixed')->name('fixWorkflow.fixed.put');  # 标记工单完成
            Route::get('install', 'FixWorkflowController@getInstall')->name('fixWorkflow.install.get');  # 出库安装页面
            Route::post('install/{serialNumber}', 'FixWorkflowController@postInstall')->name('fixWorkflow.install.post');  # 出库安装
            Route::get('returnFactory/{fixWorkflowSerialNumber}', 'FixWorkflowController@getReturnFactory')->name('fixWorkflow.returnFactory.get');  # 返厂维修页面
            Route::post('returnFactory/{fixWorkflowSerialNumber}', 'FixWorkflowController@postReturnFactory')->name('fixWorkflow.returnFactory.post');  # 返厂维修
            Route::get('factoryReturn/{fixWorkflowSerialNumber}', 'FixWorkflowController@getFactoryReturn')->name('fixWorkflow.factoryReturn.get');  # 返厂回所页面
            Route::post('factoryReturn/{fixWorkflowSerialNumber}', 'FixWorkflowController@postFactoryReturn')->name('fixWorkflow.factoryReturn.post');  # 返厂回所
            Route::get('in/{fixWorkflowSerialNumber}', 'FixWorkflowController@getIn')->name('fixWorkflow.in.get');  # 检修单：入所 页面
            Route::post('in/{fixWorkflowSerialNumber}', 'FixWorkflowController@postIn')->name('fixWorkflow.in.post');  # 检修单：入所
            Route::get('check', 'FixWorkflowController@check')->name('fixWorkflow.check');  # 验收页面
            Route::get('/', 'FixWorkflowController@index')->name('fixWorkflow.index');  # 列表
            Route::get('create', 'FixWorkflowController@create')->name('fixWorkflow.create');  # 新建页面
            Route::post('/', 'FixWorkflowController@store')->name('fixWorkflow.store');  # 新建
            Route::get('/{serialNumber}', 'FixWorkflowController@show')->name('fixWorkflow.show');  # 详情页面
            Route::get('/{serialNumber}/edit', 'FixWorkflowController@edit')->name('fixWorkflow.edit');  # 编辑页面
            Route::put('/{serialNumber}', 'FixWorkflowController@update')->name('fixWorkflow.update');  # 编辑
            Route::delete('/{serialNumber}', 'FixWorkflowController@destroy')->name('fixWorkflow.destroy');  # 删除
        });

        Route::group(['prefix' => 'fixWorkflowProcess'], function () {
            Route::get('part', 'FixWorkflowProcessController@getPart')->name('fixWorkflowProcess.part');  # 部件检测页面
            Route::get('/', 'FixWorkflowProcessController@index')->name('fixWorkflowProcess.index');  # 列表
            Route::get('create', 'FixWorkflowProcessController@create')->name('fixWorkflowProcess.create');  # 新建页面
            Route::post('/', 'FixWorkflowProcessController@store')->name('fixWorkflowProcess.store');  # 新建
            Route::get('/{fixWorkflowProcessSerialNumber}', 'FixWorkflowProcessController@show')->name('fixWorkflowProcess.show');  # 详情页面
            Route::get('/{fixWorkflowProcessSerialNumber}/edit', 'FixWorkflowProcessController@edit')->name('fixWorkflowProcess.edit');  # 编辑页面
            Route::put('/{fixWorkflowProcessSerialNumber}', 'FixWorkflowProcessController@update')->name('fixWorkflowProcess.update');  # 编辑
            Route::delete('/{fixWorkflowProcessSerialNumber}', 'FixWorkflowProcessController@destroy')->name('fixWorkflowProcess.destroy');  # 删除
        });

        Route::group(['prefix' => 'fixWorkflowRecord'], function () {
            Route::post('/saveMeasuredValue', 'FixWorkflowRecordController@saveMeasuredValue')->name('fixWorkflowRecord.saveMeasuredValue');  # 保存部件检测数据
            Route::get('/saveMeasuredExplain', 'FixWorkflowRecordController@getSaveMeasuredExplain')->name('fixWorkflowRecord.saveMeasuredExplain.get');  # 保存部件实测模糊描述
            Route::post('/saveMeasuredExplain', 'FixWorkflowRecordController@postSaveMeasuredExplain')->name('fixWorkflowRecord.saveMeasuredExplain.post');  # 保存部件实测模糊描述
            Route::get('/bindingFixWorkflowProcess/{fixWorkflowProcessSerialNumber}', 'FixWorkflowRecordController@getBindingFixWorkflowProcess')->name('fixWorkflowRecord.bindingFixWorkflowProcess.get');  # 测试数据绑定到测试单页面
            Route::post('/bindingFixWorkflowProcess/{fixWorkflowProcessSerialNumber}', 'FixWorkflowRecordController@postBindingFixWorkflowProcess')->name('fixWorkflowRecord.bindingFixWorkflowProcess.post');  # 测试数据绑定到测试单
            Route::get('/boundFixWorkflowProcess/{fixWorkflowProcessSerialNumber}', 'FixWorkflowRecordController@getBoundFixWorkflowProcess')->name('fixWorkflowRecord.boundFixWorkflowProcess.get');  # 解除测试单与测试数据关系页面
            Route::post('/cancelBoundFixWorkflowProcess/{fixWorkflowProcessSerialNumber}', 'FixWorkflowRecordController@postCancelBoundFixWorkflowProcess')->name('fixWorkflowRecord.cancelBoundFixWorkflowProcess.post');  # 解除测试单与测试数据关系页面
            Route::post('/saveProcessor/{fixWorkflowProcessSerialNumber}', 'FixWorkflowRecordController@postSaveProcessor')->name('fixWorkflowRecord.saveProcessor.post');  # 记录检测人
            Route::post('/saveProcessedAt/{fixWorkflowProcessSerialNumber}', 'FixWorkflowRecordController@postSaveProcessedAt')->name('fixWorkflowRecord.saveProcessedAt.post');  # 记录检测时间
            Route::get('/', 'FixWorkflowRecordController@index')->name('fixWorkflowRecord.index');  # 列表
            Route::get('create', 'FixWorkflowRecordController@create')->name('fixWorkflowRecord.create');  # 新建页面
            Route::post('/', 'FixWorkflowRecordController@store')->name('fixWorkflowRecord.store');  # 新建
            Route::get('/{serialNumber}', 'FixWorkflowRecordController@show')->name('fixWorkflowRecord.show');  # 详情页面
            Route::get('/{serialNumber}/edit', 'FixWorkflowRecordController@edit')->name('fixWorkflowRecord.edit');  # 编辑页面
            Route::put('/{serialNumber}', 'FixWorkflowRecordController@update')->name('fixWorkflowRecord.update');  # 编辑
            Route::delete('/{serialNumber}', 'FixWorkflowRecordController@destroy')->name('fixWorkflowRecord.destroy');  # 删除
        });
    });

    Route::resource('pivotEntireModelAndPartModel', 'PivotEntireModelAndPartModelController');  # 整件型号与部件关系 √

    Route::group(['prefix' => 'entire', 'namespace' => 'Entire'], function () {
        Route::group(['prefix' => 'model'], function () {  # 整件模型
            Route::get('/', 'ModelController@index')->name('entire-model.index');  # 列表
            Route::get('create', 'ModelController@create')->name('entire-model.create');  # 新建页面
            Route::post('/', 'ModelController@store')->name('entire-model.store');  # 新建
            Route::get('/{uniqueCode}', 'ModelController@show')->name('entire-model.show');  # 详情页面
            Route::get('/{uniqueCode}/edit', 'ModelController@edit')->name('entire-model.edit');  # 编辑页面
            Route::put('/{uniqueCode}', 'ModelController@update')->name('entire-model.update');  # 编辑
            Route::delete('/{uniqueCode}', 'ModelController@destroy')->name('entire-model.destroy');  # 删除
        });

        Route::group(['prefix' => 'instance'], function () {  # 整件实例
            Route::get('fixing', 'InstanceController@getFixing')->name('entire-instance.fixing.get');  # 检修入所页面
            Route::post('fixing', 'InstanceController@postFixing')->name('entire-instance.fixing.post');  # 检修入所页面
            Route::get('fixingIn/{identityCode}', 'InstanceController@getFixingIn')->name('entire-instance.fixingIn.get');  # 检修入所页面
            Route::post('fixingIn/{identityCode}', 'InstanceController@postFixingIn')->name('entire-instance.fixingIn.post');  # 检修入所页面
            Route::any('scrap/{identityCode}', 'InstanceController@scrap')->name('entire-instance.scrap');  # 报废整件
            Route::get('install', 'InstanceController@getInstall')->name('entire-instance.install.get');  # 安装出库页面
            Route::post('install/{entireInstanceIdentityCode}', 'InstanceController@postInstall')->name('entire-instance.install.post');  # 安装出库
            Route::get('deviceDynamicStatus', 'InstanceController@getDeviceDynamicStatus')->name('getDeviceDynamicStatus.get');  # 动态设备状态
            Route::get('/', 'InstanceController@index')->name('entire-instance.index');  # 列表
            Route::get('create', 'InstanceController@create')->name('entire-instance.create');  # 新建页面
            Route::post('/', 'InstanceController@store')->name('entire-instance.store');  # 新建
//            Route::get('/{identityCode}', 'InstanceController@show')->name('entire-instance.show');  # 详情页面
            Route::get('/{identityCode}', 'InstanceController@show')->name('entire-instance.show');  # 详情页面
            Route::get('/{identityCode}/edit', 'InstanceController@edit')->name('entire-instance.edit');  # 编辑页面
            Route::put('/{identityCode}', 'InstanceController@update')->name('entire-instance.update');  # 编辑
            Route::delete('/{identityCode}', 'InstanceController@destroy')->name('entire-instance.destroy');  # 删除
        });

        Route::group(['prefix' => 'modelIdCode'], function () {
            Route::get('/', 'ModelIdCodeController@index')->name('modelIdCode.index');  # 列表
            Route::get('create', 'ModelIdCodeController@create')->name('modelIdCode.create');  # 新建页面
            Route::post('/', 'ModelIdCodeController@store')->name('modelIdCode.store');  # 新建
            Route::get('/{code}', 'ModelIdCodeController@show')->name('modelIdCode.show');  # 详情页面
            Route::get('/{code}/edit', 'ModelIdCodeController@edit')->name('modelIdCode.edit');  # 编辑页面
            Route::put('/{code}', 'ModelIdCodeController@update')->name('modelIdCode.update');  # 编辑
            Route::delete('/{code}', 'ModelIdCodeController@destroy')->name('modelIdCode.destroy');  # 删除
        });
        Route::resource('instances', 'InstancesController');  # 整件批量实例 √
    });

    Route::group(['prefix' => 'part', 'namespace' => 'Part'], function () {
        Route::group(['prefix' => 'model'], function () {  # 部件型号
            Route::get('/', 'ModelController@index')->name('part-model.index');  # 列表
            Route::get('create', 'ModelController@create')->name('part-model.create');  # 新建页面
            Route::post('/', 'ModelController@store')->name('part-model.store');  # 新建
            Route::get('/{uniqueCode}', 'ModelController@show')->name('part-model.show');  # 详情页面
            Route::get('/{uniqueCode}/edit', 'ModelController@edit')->name('part-model.edit');  # 编辑页面
            Route::put('/{uniqueCode}', 'ModelController@update')->name('part-model.update');  # 编辑
            Route::delete('/{uniqueCode}', 'ModelController@destroy')->name('part-model.destroy');  # 删除
        });
        Route::group(['prefix' => 'instance'], function () {  # 部件实例
            Route::get('/', 'InstanceController@index')->name('part-instance.index');  # 列表
            Route::get('create', 'InstanceController@create')->name('part-instance.create');  # 新建页面
            Route::post('/', 'InstanceController@store')->name('part-instance.store');  # 新建
            Route::get('/{identityCode}', 'InstanceController@show')->name('part-instance.show');  # 详情页面
            Route::get('/{identityCode}/edit', 'InstanceController@edit')->name('part-instance.edit');  # 编辑页面
            Route::put('/{identityCode}', 'InstanceController@update')->name('part-instance.update');  # 编辑
            Route::delete('/{identityCode}', 'InstanceController@destroy')->name('part-instance.destroy');  # 删除
        });
    });

    Route::group(['prefix' => 'search'], function () {
        Route::get('/', 'SearchController@index')->name('search.index');  # 搜索列表页面
        Route::post('/', 'SearchController@store')->name('search.store');  # 搜索条件
        Route::get('/{entireInstanceIdentityCode}', 'SearchController@show')->name('search.show');  # 搜索结果详情页面
    });

    Route::group(['prefix' => 'qrcode'], function () {
        Route::get('/parse', 'QrCodeController@parse')->name('qrcode.index');  # 解析二维码
        Route::get('/make', 'QrCodeController@make')->name('qrcode.create');  # 创建二维码
        Route::get('/{entireInstanceIdentityCode}', 'QrCodeController@show')->name('qrcode.show');  # 展示二维码
    });

    Route::group(['prefix' => 'barcode'], function () {
        Route::get('/parse', 'BarCodeController@parse')->name("barcode.index");  # 解析条形码
        Route::get('/{entireInstanceIdentityCode}', "BarCodeController@show")->name("barcode.show");  # 展示条形码
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'ReportController@index')->name('report.index');  # 列表
        Route::get('/workshop', 'ReportController@workshop')->name('report.workshop');  # 现场车间
        Route::get('/station/{stationName}', 'ReportController@station')->name('report.station');  # 车站
        Route::get('/onlyOnceFixed', 'ReportController@onlyOnceFixed')->name('report-onlyOnceFixed');  # 单次检修统计
        Route::get('/quality', 'ReportController@quality')->name('report-quality');  # 质检报告
        Route::get('quality/{entireModelUniqueCode}', 'ReportController@qualityItem')->name('report.qualityItem');  # 确定某一类型的设备后的设备列表
        Route::get('qualityShow/{entireInstanceIdentityCode}', 'ReportController@qualityShow')->name('report.qualityShow');  # 确定某一设备后的设备详情
        Route::get('/work', 'ReportController@work')->name('report-work');  # 工作报表
    });
});
