<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Model\PartInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) {
            $type = \request()->get('type');
            $status = \request()->get('status', ['BUY_IN', 'INSTALLING', 'INSTALLED', 'FIXING', 'FIXED', 'RETURN_FACTORY', 'FACTORY_RETURN', 'SCRAP']);
//            $partInstances = PartInstance::where('part_model_unique_code', \request()->get($type))->whereIn('status', $status)->where('entire_instance_identity_code',\request()->get('entire_instance_identity_code',null))->get();
            $partInstances = PartInstance::where('part_model_unique_code', \request()->get($type))->where('entire_instance_identity_code',\request()->get('entire_instance_identity_code',null))->get();
            return Response::json($partInstances);
        }
        $partInstances = PartInstance::orderByDesc('id')->paginate();
        return view($this->view(''))
            ->with('$partInstances', $partInstances);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
