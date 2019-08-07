<?php

namespace App\Http\Controllers;

use App\Facades\Rbac;
use App\Http\Requests\V1\AccountUpdateRequest;
use App\Http\Requests\V1\ForgetPasswordRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\UpdatePasswordRequest;
use App\Model\Account;
use App\Model\Organization;
use App\Model\PivotRoleAccount;
use App\Model\RbacRole;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Jericho\Redis\Hashs;
use Jericho\Validate;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::with(["status", "organization"])->orderBy('id', 'desc')->paginate();
        return Response::view('Account.index', ['accounts' => $accounts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::orderBy('id', 'desc')->get();
        return Response::view('Account.create', ['organizations' => $organizations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new RegisterRequest);
            if ($v !== true) return Response::make($v, 422);

            $account = new Account;
            $req = $request->all();
            $req['open_id'] = md5(Hashs::ins()->setIncr('count', 'account') . time() . $req['account']);
            $req['password'] = bcrypt($req['password']);
            $account->fill($req);
            $account->saveOrFail();

            return Response::make('新建成功');
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $openId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($openId)
    {
        try {
            $account = Account::with(['organization', 'roles'])->where('open_id', $openId)->firstOrFail();
            return Response::view('Account.show', ['account' => $account]);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('意外错误', 500);
        }
    }

    /**
     * 个人中心
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function profile()
    {
        try {
            $account = Account::with(['organization', 'roles'])->findOrFail(session()->get('account.id'));
            return Response::view('Account.profile', ['account' => $account]);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('意外错误', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $openId
     * @return \Illuminate\Http\Response
     */
    public function edit($openId)
    {
        try {
            $roles = RbacRole::all();
            $account = Account::with(['organization', 'roles'])->where('open_id', $openId)->firstOrFail();
            $roleIds = [];
            foreach ($account->roles as $role) {
                $roleIds[] = $role->id;
            }

            return Response::view('Account.edit', ['account' => $account, 'roles' => $roles, 'roleIds' => $roleIds]);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('意外错误', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $openId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $openId)
    {
        try {
            $account = Account::where('open_id', $openId)->firstOrFail();
            $req = $request->all();
            foreach ($req as $key => $value) {
                if ($account[$key] == $req[$key]) unset($req[$key]);
            }
            $v = Validate::firstError($req, new AccountUpdateRequest);
            if ($v !== true) return \response($v, 422);
            $account->fill($request->all());
            $account->saveOrFail();

            return \response('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return \response('数据不存在', 404);
        } catch (\Exception $exception) {
            return \response('意外错误', 500);
        }
    }

    /**
     * 忘记密码
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function forget(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new ForgetPasswordRequest);
            if ($v !== true) return back()->withInput()->with('danger', $v);

            switch ($request->get('type')) {
                case 'email':
                default:
                    $account = Account::where('account', $request->get('account'))
                        ->where('email_code', $request->get('code'))
                        ->where('email_code_exp', '>', date('Y-m-d H:i:s'))
                        ->first();
                    if (!$account) return back()->withInput()->with('danger', '验证码错误或验证码过期');
                    $account->email_code = null;
                    $account->email_code_exp = null;
                    break;
                case 'sms':
                    $account = Account::where('account', $request->get('account'))
                        ->where('sms_code', $request->get('sms'))
                        ->where('sms_code_exp', '>', date('Y-m-d H:i:s'))
                        ->first();
                    if (!$account) return back()->withInput()->with('danger', '验证码错误或验证码过期');
                    $account->sms_code = null;
                    $account->sms_code_exp = null;
                    break;
            }

            $account->password = bcrypt($request->get('password'));
            $account->saveOrFail();

            return redirect('/login')->withInput()->with('success', '密码修改成功，请使用新密码登陆');
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('意外错误', 500);
        }
    }

    /**
     * 忘记密码页面
     * @return \Illuminate\Http\Response
     */
    public function getForget()
    {
        return Response::view('Account.forget');
    }

    /**
     * 修改密码
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new UpdatePasswordRequest);
            if ($v !== true) return Response::make($v, 422);

            $account = Account::where('id', session()->get('account.id'))->firstOrFail();
            if (!Hash::check($request->get('password'), $account->password)) return Response::make('账号或密码不匹配', 500);;
            $account->password = bcrypt($request->get('new_password'));
            $account->saveOrFail();

            return Response::make();
        } catch (ModelNotFoundException $exception) {
            return Response::make('资源不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $openId
     * @return \Illuminate\Http\Response
     */
    public function destroy($openId)
    {
        try {
            $account = Account::where('open_id', $openId)->firstOrFail();
            $account->delete();
            if (!$account->trashed()) return Response::make('删除失败', 500);

            return Response::make();
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 登陆
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new LoginRequest);
            if ($v !== true) return back()->withInput()->with('danger', $v);

            # 验证密码
            $account = Account::where('account', $request->get('account'))->firstOrFail()->toArray();
            if (!Hash::check($request->get('password'), $account['password'])) return back()->withInput()->with('danger', '账号密码错误');

            # 获取用户权限相关信息
            $account['menus'] = Rbac::getMenus($account['id'])->toArray();  # 获取用户菜单
            $account['treeJson'] = json_encode(Rbac::toTree($account['menus'], 0), 256);
            $account['permissionIds'] = Rbac::getPermissionIds($account['id'])->toArray();  # 获取权限编号

            # 记录用户数据
            session()->put('account', $account);
            return redirect('/')->with('登陆成功');
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            dd($exception->getMessage() . ':' . $exception->getFile() . ':' . $exception->getLine());
            return back()->withInput()->with('danger', '意外错误');
        }
    }

    /**
     * 登录页
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return Response::view('Account.login');
    }

    /**
     * 注册
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|null
     * @throws \Throwable
     */
    public function register(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new RegisterRequest);
            if ($v !== true) return back()->withInput()->with('danger', $v);

            $account = new Account;
            $req = $request->all();
            $req['open_id'] = md5(Hashs::ins()->setIncr('count', 'account') . time() . $req['account']);
            $req['password'] = bcrypt($req['password']);
            $account->fill($req);
            $account->saveOrFail();

            return redirect('/login')->withInput()->with('success', '注册成功，请登录');
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('意外错误', 500);
        }
    }

    /**
     * 注册页
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return Response::view('Account.register');
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        session()->forget('account');
        return redirect('/login')->with('success', '退出成功');
    }

    /**
     * 上传头像
     * @return \Illuminate\Http\Response
     */
    public function avatar()
    {
        try {
            if (!request()->hasFile('image')) return Response::make('上传头像失败', 403);

            $avatar = request()->file('image');
            $extension = $avatar->getClientOriginalExtension();

            $savePath = 'uploads/account/avatar';
            $saveName = session()->get('account.id') . '.' . $extension;

            $result = $avatar->move($savePath, $saveName);

            if ($result) {
                $account = Account::findOrFail(session()->get('account.id'));
                $account->avatar = $result;
                $account->save();

                $session = session()->get('account');
                $session['avatar'] = $result->getPathname();
                session()->put('account', $session);

                return Response::make('上传成功');
            } else {
                return Response::make('上传失败', 500);
            }
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 绑定用户到角色
     * @param string $accountOpenId 用户开放编号
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function bindRoles($accountOpenId)
    {
        try {
            $accountId = Account::select('id')->where('open_id', $accountOpenId)->firstOrFail()['id'];
            PivotRoleAccount::where('account_id', $accountId)->delete();  # 删除原绑定信息

            # 绑定新关系
            $insertData = [];
            foreach (request()->get('role_ids') as $item) {
                $insertData[] = ['account_id' => $accountId, 'rbac_role_id' => $item];
            }
            $insertResult = DB::table('pivot_role_accounts')->insert($insertData);
            if (!$insertResult) return Response::make('绑定失败', 500);

            return Response::make('绑定成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误:' . $exception->getMessage(), 500);
        }
    }
}
