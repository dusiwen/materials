<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DeviceSpuRequest;
use App\Model\DeviceSpu;
use App\Transformers\DeviceSpuTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Jericho\Validate;

class DeviceSpuController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spus = DeviceSpu::with(['avatar', 'brand'])->orderByDesc('id')->paginate();
        return $this->response->paginator($spus, new DeviceSpuTransformer);
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
        try {
            $v = Validate::firstErrorByRequest($request, new DeviceSpuRequest);
            if ($v !== true) $this->response->errorForbidden($v);
            $spu = new DeviceSpu;
            $spu->fill($request->all());
            $spu->saveOrFail();

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $spu = DeviceSpu::with(['avatar', 'brand', 'skus'])->where('id', $id)->firstOrFail();
            return $this->response->item($spu, new DeviceSpuTransformer);
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
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
        try {
            $v = Validate::firstErrorByRequest($request, new DeviceSpuRequest);
            if ($v !== true) $this->response->errorForbidden($v);

            $spu = DeviceSpu::findOrFail($id);
            $spu->fill($request->all());
            $spu->saveOrFail();
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $spu = DeviceSpu::findOrFail($id);
            $spu->delete();
            if (!$spu->trashed()) $this->response->errorInternal('删除失败');
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
