<?php

namespace App\Http\Controllers\Maintain;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MaintainRequest;
use App\Model\EntireModelIdCode;
use App\Model\Maintain;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $type = \request()->get('type');
        return Response::json(EntireModelIdCode::where(
            'category_unique_code',
            \request()->get('category_unique_code')
        )
            ->where(
                'entire_model_unique_code',
                \request()->get('entire_model_unique_code')
            )
            ->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $materials = DB::table("materials")->get()->toArray();
//        dd($materials);
        if (\request()->ajax()) return view($this->view('create_ajax'))
            ->with("materials",$materials);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Entire.ModelIdCode.{$viewName}";
    }

    /**
     * ajax 物资选择弹窗(选择相应的物资)
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
//            $entireModelIdCode = new EntireModelIdCode;
//            $entireModelIdCode->fill([
//                'id' => $request->get("id"),
//                'category_unique_code' => $request->get('category_unique_code'),
//                'entire_model_unique_code' => $request->get('entire_model_unique_code'),
//                'code' => $request->get('code'),
//            ])
//                ->saveOrFail();
            session(["MaterialsId"=>$request->get("id")]);//获取选择物资的id并存入session中
//            $request->session()->flash("MaterialsId",$request->get("id"));

            return Response::make('选择成功');
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
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
