<?php

namespace App\Services;

use App\Jobs\AlarmUseEmail;
use App\Model\Device;
use App\Model\PivotNoticeGroupAccount;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jericho\Email;

class AlarmService
{
    use Helpers;

    public function process(Device $device, array $alarmReports, string $datetime, $request)
    {
        $deviceOpenCode = $device->open_code;
        $accounts = $this->getAccounts($deviceOpenCode);
//        $official = app('wechat.official_account');

        foreach ($accounts as $account) {
            foreach (explode(',', $device->alarm_type) as $item) {
                switch ($item) {
                    case 'wechat':
//                            $this->wechat($account->wechat_official_open_id, $official->template_message, $device, $alarmReport['level']);
                        break;
                    case 'email':
                        return $this->email($account->email, $device, $account->nickname, $datetime, $request, $alarmReports);
                        break;
                }
            }
        }
    }

    /**
     * 通过设备获取报警组中的人员
     * @param $deviceOpenCode
     * @return array
     */
    public function getAccounts($deviceOpenCode)
    {
        $pivots = PivotNoticeGroupAccount::with(['account'])->whereIn('notice_group_id',
            function ($query) use ($deviceOpenCode) {
                $query->where('device_open_code', $deviceOpenCode)
                    ->from('pivot_notice_group_devices')
                    ->select('notice_group_id');
            })->get();
        $accounts = [];
        foreach ($pivots as $pivot) {
            if ($pivot->account) $accounts[] = $pivot->account;
        }
        return $accounts;
    }

    public function email(string $email, Device $device, string $accountNickname, string $datetime, $request, $alarmReports)
    {
        $data = json_decode($request['data'], true);
        foreach ($alarmReports as $alarmReport) {
            $content = "
<b>尊敬 {$accountNickname}先生/女士</b>，&nbsp;&nbsp;设备名称：{$device->deviceGroup->name}&nbsp;&nbsp;{$device->name}（ID：{$device->open_code}） 在{$datetime} 发生了：<b>{$data[$alarmReport]['condition']['description']}</b>&nbsp;&nbsp;报警，请及时处理！<br>
<br>
<p>
数据点类
</p>
<ul>
    <li>数据点ID：{$device->open_code}</li>
    <li>数据点名称：{$device->deviceGroup->name}&nbsp;&nbsp;{$device->sku->template->name}</li>
    <li>数据点值：{$data[$alarmReport]['value']}</li>
</ul>
<p>触发器类</p>
<ul>
    <li>触发器名称：{$device->deviceGroup->name}&nbsp;&nbsp;{$device->name}</li>
    <li>触发条件：{$data[$alarmReport]['condition']['origin']}{$data[$alarmReport]['unit']}&nbsp;≤&nbsp;{$data[$alarmReport]['value']}&nbsp;＜&nbsp;{$data[$alarmReport]['condition']['finish']}{$data[$alarmReport]['unit']}（{$data[$alarmReport]['condition']['level']}级报警）</li>
    <li>触发时间：{$datetime}</li>
</ul>
        ";
//            return Email::send('报警', $content, $email);
            AlarmUseEmail::dispatch('报警', $content, $email);
            return 'ok';
        }
    }

    public function wechat($wechatOfficialOpenId, $templateMessage, $device, $level)
    {
        $wechatTemplateId = 'hbMqJ_xSbKVr4PApNIeEhuemQV9AvTVfoLrfcQAu7lA';
        $result = $templateMessage->send([
            'touser' => $wechatOfficialOpenId,
            'template_id' => $wechatTemplateId,
            'url' => 'http://www.baidu.com',
            'data' => [
//                'system' => $device->toJson(),
                'system' => $device->name,
                'time' => date('Y-m-d H:i:s'),
                'account' => 'abc'
            ]
        ]);
    }

    /**
     * 通过设备获取报警类型
     * @param $deviceOpenCode
     * @return mixed
     */
    public function getDevice($deviceOpenCode)
    {
        try {
            return Device::where('open_code', $deviceOpenCode)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
        # 获取相应的数据：wechat_official_open_id、email
        # 执行相应的报警动作
    }

//    public function buildContent($alarmTemplateId)
//    {
//        $alarmTemplate = AlarmTemplate::findOrFail($alarmTemplateId);
//        $contentVar = json_decode($alarmTemplate->content_var, true);
//        return vsprintf($alarmTemplate->content, $contentVar);
//    }

    public function wechat2($wechatOfficialOpenId, $alarmTemplateId)
    {
        $wechatTemplateId = 'hbMqJ_xSbKVr4PApNIeEhuemQV9AvTVfoLrfcQAu7lA';
        $official = app('wechat.official_account');
        $result = $official->template_message->send([
            'touser' => $wechatOfficialOpenId,
            'template_id' => $wechatTemplateId,
            'url' => 'http://www.baidu.com',
            'data' => [
                'system' => '设备名称',
                'time' => date('Y-m-d H:i:s'),
                'account' => 'abc'
            ]
        ]);
//        $this->buildContent($alarmTemplateId);
    }

}
