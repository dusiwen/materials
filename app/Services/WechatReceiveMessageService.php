<?php

namespace App\Services;

use App\Model\Account;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WechatReceiveMessageService
{
    public function processType(array $message)
    {
        switch ($message['MsgType']) {
            case 'event':
                # 收到事件消息
                return $this->processEvent($message);
                break;
            case 'text':
                # 收到文字消息
                return $this->processText($message);
                break;
            case 'image':
                # 收到图片消息
                break;
            case 'voice':
                # 收到语音消息
                break;
            case 'video':
                # 收到视频消息
                break;
            case 'location':
                # 收到坐标消息
                break;
            case 'link':
                # 收到链接消息
                break;
            case 'file':
                # 收到文件消息
                break;
            default:
                # 收到其它消息
                break;
        }
    }

    public function processEvent(array $message)
    {
        try {
            switch ($message['Event']) {
                case 'subscribe':
                    return '欢迎关注中呈可视！';
                    break;
                case 'SCAN':
//                    return '扫码';
                    $wechatOfficialOpenId = $message['FromUserName'];  # 用户的wechat_official_open_id
                    $accountOpenId = $message['EventKey']; #  需要绑定的用户open_id
//                    $wechatOfficialOpenId = 'ob2xK1PDBZj0nV2TTcxVXZyryGOk';
//                    $accountOpenId = 'c7d81bfd07c043c997d33dbf2c0bb8b6';
                    $account = Account::where('open_id', $accountOpenId)->where('wechat_official_open_id', null)->firstOrFail();
                    $account->wechat_official_open_id = $wechatOfficialOpenId;
                    $account->saveOrFail();
                    return "绑定成功，被绑定人信息 → 昵称：{$account->nickname}，邮箱：{$account->email}，手机号：{$account->phone}";
                    break;
            }
        } catch (ModelNotFoundException $exception) {
            return '被绑定用户不存在，或已经被绑定过微信';
        } catch (\Exception $exception) {
            return '意外错误：' . $exception->getMessage();
        }
    }

    public function processText(array $message)
    {
        try {
            $account = Account::where('account', $message['Content'])->where('wechat_official_open_id', null)->firstOrFail();
            $account->wechat_official_open_id = $message['FromUserName'];
            $account->saveOrFail();
            return "绑定成功 → 昵称：{$account->nickname}，邮箱：{$account->email}，手机号：{$account->phone}";
        } catch (ModelNotFoundException $exception) {
            return '被绑定用户不存在，或已经被绑定过微信';
        } catch (\Exception $exception) {
            return '意外错误：' . $exception->getMessage();
        }


    }
}
