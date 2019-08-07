<?php

namespace App\Http\Controllers\V1\Wechat;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $official = app('wechat.official_account');
        $expires = env('WECHAT_OFFICIAL_ACCOUNT_QRCODE_TEMPORARY_EXPIRES', 2592000);
//        $expires = 15;

        switch ($request->get('type')) {
            case 'temporary':
            default:
                $qrcode = $official->qrcode->temporary($request->get('content'), $expires);
                break;
            case 'forever':
                $qrcode = $official->qrcode->forever($request->get('content'));
                break;
        }
        $qrcodeUrl = $official->qrcode->url($qrcode['ticket']);
        return $qrcodeUrl;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
