<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PartModelRequest;
use App\Model\Category;
use App\Model\PartModel;
use App\Model\PivotEntireModelAndPartModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ModelController extends Controller
{
    /**
     * 物资列表展现
     * Display a listing of the resource.
     *
     * @return PartModel[]|\Illuminate\Contracts\View\Factory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $materials = DB::table("materials")->orderByDesc("id")->get()->toArray();//获取所有物资
        $i = DB::table("materials")->count();
//        dd($materials);
            return view($this->view())
                ->with('materials', $materials)
                ->with('i', $i);

    }

    public function view(string $viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Part.Model.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderByDesc('id')->pluck('name', 'unique_code');
        if (\request()->ajax()) return view($this->view('create_ajax'))->with('categories', $categories);
        return view($this->view())
            ->with('categories', $categories);
    }

    /**
     * 新建物资
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $time = time();
            $MaterialCode = mt_rand(500000000,500099999);//随机生成物资编码
            $code = DB::table("materials")->get(["MaterialCode"])->toArray();
            foreach ($code as $v){
                if ($v =$MaterialCode){
                    $MaterialCode = mt_rand(500000000,500099999);//生成物资编码重复,重新生成新编码
                }
            }
//            return Response::make($MaterialCode, 404);
            if (empty($request->get("MaterialName"))){
                return Response::make('物资名称不能为空', 404);
            }
            if (empty($request->get("unit"))){
                return Response::make('单位不能为空', 404);
            }
            if (empty($request->get("EachWeight"))){
                return Response::make('重量不能为空', 404);
            }
            if (empty($request->get("ServiceLife"))){
                return Response::make('使用年限不能为空', 404);
            }
            $material = DB::table("materials")->insert(["MaterialCode"=>$MaterialCode,"MaterialName"=>$request->get("MaterialName"),"unit"=>$request->get("unit"),"EachWeight"=>$request->get("EachWeight"),"ServiceLife"=>$request->get("ServiceLife"),"remark"=>$request->get("remark"),"AddTime"=>$time]);
            return Response::make('新建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
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
        try {
            $partModel = PartModel::findOrFail($id);
            $categories = Category::orderByDesc('id')->pluck('name', 'unique_code');
            return view($this->view())
                ->with('partModel', $partModel)
                ->with('categories', $categories);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return back()->with('danger', '意外错误' . $exceptionFile . $exceptionLine);
        }
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
        try {
            $v = Validator::make($request->all(), PartModelRequest::$RULES, PartModelRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $partModel = PartModel::findOrFail($id);
            $partModel->fill($request->all())->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 删除物资
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::table("materials")->where("id",$id)->delete();

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误', 500);
        }
    }
}
