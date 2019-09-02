<?php

namespace App\Http\Controllers\Entire;

use App\Http\Controllers\Controller;
use App\Model\EntireModelIdCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ModelIdCodeController extends Controller
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
     * ajax 添加物资入库单->物资选择->物资列表
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = \request()->get("type");
        $materials = DB::table("materials")->get()->toArray();
//        dd($materials);
        if (\request()->ajax()) return view($this->view('create_ajax'))
            ->with("type",$type)
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

            return Response::make($request->get("type"));
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
