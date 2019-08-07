<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\StatusRequest;
use App\Model\Account;
use App\Model\Status;
use App\Transformers\StatusTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jericho\Validate;

class StatusController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::where('id','>',0)->orderByDesc('id')->paginate();
        return $this->response->paginator($statuses,new StatusTransformer);
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
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request,new StatusRequest);
            if($v!==true) $this->response->errorForbidden($v);

            $status = new Status;
            $status->name = $request->get('name');
            $status->saveOrFail();

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
            $status = Status::find($id);
            return $this->response->item($status,new StatusTransformer);
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
            $v = Validate::firstErrorByRequest($request,new StatusRequest);
            if($v!==true) $this->response->errorForbidden($v);

            $status = Status::findOrFail($id);
            $status->name = $request->get('name');
            $status->saveOrFail();
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
            $status = Status::findOrFail($id);
            $status->delete();
            if(!$status->trashed()) $this->response->errorInternal('删除失败');

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
