<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrganizationRequest;
use App\Model\Organization;
use App\Transformers\OrganizationTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Jericho\Validate;

class OrganizationController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::where('id', '>', 0)->orderByDesc('id')->paginate();
        return $this->response->paginator($organizations, new OrganizationTransformer);
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
            $v = Validate::firstErrorByRequest($request, new OrganizationRequest);
            if ($v !== true) $this->response->errorForbidden($v);

            $organization = new Organization;

            $req = $request->all();
            if ($request->get('parent_id')) {
                $parent = Organization::where('id', $request->get('parent_id'))->first();
                if (!$parent) $this->response->errorNotFound('父级机构不存在');
                @$req['level'] = $parent['level'] ? $parent['level'] : 0;
            }

            $organization->fill($req);
            $organization->saveOrFail();
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
            $organization = Organization::where('id', $id)->firstOrFail();
            return $this->response->item($organization, new OrganizationTransformer);
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
            $v = Validate::firstErrorByRequest($request, new OrganizationRequest);
            if ($v !== true) $this->response->errorForbidden($v);

            if (Organization::where('id', '<>', $id)->where('name', $request->get('name'))->first()) $this->response->errorForbidden('名称被占用');

            $req = $request->all();
            if ($request->get('parent_id', null) == $id) $this->response->errorForbidden('父级机构不能是自己');
            if ($request->get('parent_id')) {
                $parent = Organization::where('id', $request->get('parent_id'))->first();
                if (!$parent) $this->response->errorNotFound('父级机构不存在');
                @$req['level'] = $parent['level'] ? $parent['level'] : 0;
            }

            $organization = Organization::where('id', $id)->firstOrFail();
            $organization->fill($req);
            $organization->saveOrFail();
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
            $organization = Organization::findOrFail($id);
            $organization->delete();
            if (!$organization->trashed()) $this->response->errorInternal();
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
