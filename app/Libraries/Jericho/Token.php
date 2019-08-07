<?php

namespace Jericho;

use Firebase\JWT\JWT;

class Token
{
    private static $_ins = null;
    private $_key = null;

    private function __construct()
    {
        $this->_key = env('JWT_KEY');
    }

    public static function ins()
    {
        if (!self::$_ins) self::$_ins = new self;
        return self::$_ins;
    }

    public function check($jwt)
    {
        try {
            $this->parse($jwt);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function parse($jwt)
    {
        return JWT::decode($jwt, $this->_key, ['HS256']);
    }

    public function create(string $account, string $openId, string $nickname = null, string $macAddress = null)
    {
        $time = time();
        $nbf = env('JWT_NBF');
        $exp = env('JWT_EXP');
        $token = array(
            'iss' => env('JWT_ISS', null),  # 发送人
            'aud' => $account,  # 接收人
            'iat' => $time,  # 签发时间
            'nbf' => $time + $nbf,  # 立即可用
            'exp' => $time + $exp,  # 14天后过期
            'payload' => [
                'account' => $account,
                'nickname' => $nickname,
                'open_id' => $openId,
                'mac_address' => $macAddress
            ]
        );

        return JWT::encode($token, $this->_key);  # 生成JWT
    }
}
