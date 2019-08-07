<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class QrCodeController extends Controller
{
    public function show($entireInstanceIdentityCode)
    {
        $qrCodeContent = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(512)->encoding('UTF-8')->errorCorrection('H')->generate($entireInstanceIdentityCode);
        return view($this->view())->with("qrCodeContent", $qrCodeContent);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: request()->route()->getActionMethod();
        return "QrCode.{$viewName}";
    }

    /**
     * 解析二维码扫码请求
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function parse(Request $request)
    {
        try {
            switch (request()->type) {
                case 'scan':
                    return Response::json([
                        'type' => 'redirect',
                        'url' => url('search', $request->params['identity_code'])
                    ]);
                case 'buy_in':
                    break;
                case 'fixing':
                    break;
                case 'return_factory':
                    break;
                case 'factory_return':
                    break;
            }
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }
}
