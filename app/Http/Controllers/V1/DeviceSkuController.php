<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\DeviceSkuRequest;
use App\Model\DeviceAttribute;
use App\Model\DeviceSku;
use App\Transformers\DeviceSkuTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jericho\Model\Log;
use Jericho\Validate;

class DeviceSkuController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skus = DeviceSku::with(['template'])->where('id','>',0)->orderByDesc('id')->paginate();
        return $this->response->paginator($skus,new DeviceSkuTransformer);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request,new DeviceSkuRequest);
            if($v!==true) $this->response->errorForbidden($v);
            $sku = new DeviceSku;
            $sku->fill($request->all());
            $sku->saveOrFail();

            return $this->response->created();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $sku = DeviceSku::with(['template'])->where('id',$id)->firstOrFail();
            return $this->response->item($sku,new DeviceSkuTransformer);
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $v = Validate::firstErrorByRequest($request,new DeviceSkuRequest);
            if($v!==true) $this->response->errorForbidden($v);

            $sku = DeviceSku::findOrFail($id);
            $sku->fill($request->all());
            $sku->saveOrFail();
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $sku = DeviceSku::findOrFail($id);
            $sku->delete();
            if(!$sku->trashed()) $this->response->errorInternal('删除失败');
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * 通过属性码定位SKU
     * @param $ids
     * @return \Dingo\Api\Http\Response
     */
    public function attribute($ids)
    {
        try {
            $skuId = DeviceAttribute::whereIn('id',explode(',',$ids))->select('sku_id')->firstOrFail()->sku_id;
            $sku = DeviceSku::with(['template'])->where('id',$skuId)->firstOrFail();
            return $this->response->item($sku,new DeviceSkuTransformer);
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
