<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Model\Account;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jericho\Email;
use Jericho\Text;

class CodeController extends Controller
{
    use Helpers;

    public function email($acc)
    {
        try {
            $account = Account::where('account', $acc)->firstOrFail();
            if (!$account->email) $this->response->errorNotFound('用户未填写邮箱');

            $code = Text::rand('num', 4);
            $result = Email::send('验证码', '您的验证码是：' . $code . '<br>有效期：20分钟', $account->email);
            if (!$result) $this->response->errorInternal();
            $account->email_code = $code;
            $account->email_code_exp = date('Y-m-d H:i:s', strtotime('+20 minute'));
            $account->saveOrFail();

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
