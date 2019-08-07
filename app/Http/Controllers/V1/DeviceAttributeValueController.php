<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\DeviceAttributeValueRequest;
use App\Model\DeviceAttributeValue;
use App\Transformers\DeviceAttributeValueTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jericho\Validate;

class DeviceAttributeValueController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributeValue = DeviceAttributeValue::where('id','>',0)->orderByDesc('id')->paginate();
        return $this->response->paginator($attributeValue,new DeviceAttributeValueTransformer);
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
            $v = Validate::firstErrorByRequest($request,new DeviceAttributeValueRequest);
            if($v!==true) $this->response->errorForbidden($v);
            $attributeValue = new DeviceAttributeValue;
            $attributeValue->fill($request->all());
            $attributeValue->saveOrFail();

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
            $attribute = DeviceAttributeValue::findOrFail($id);
            return $this->response->item($attribute,new DeviceAttributeValueTransformer);
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
            $v = Validate::firstErrorByRequest($request,new DeviceAttributeValueRequest);
            if($v!==true) $this->response->errorForbidden($v);

            $attributeValue = DeviceAttributeValue::findOrFail($id);
            $attributeValue->fill($request->all());
            $attributeValue->saveOrFail();
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
            $attributeValue = DeviceAttributeValue::findOrFail($id);
            $attributeValue->delete();
            if(!$attributeValue->trashed()) $this->response->errorInternal('删除失败');
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
