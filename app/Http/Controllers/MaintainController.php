<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\MaintainRequest;
use App\Model\Maintain;
use DemeterChain\Main;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MaintainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if(\request()->ajax()) {
            return Response::json(Maintain::where('type',\request()->type)->where('parent_unique_code',\request()->workshopName)->get());
        }
        $maintains = Maintain::with(['Parent'])->orderByDesc('id')->paginate();
//        dd($maintains);
        return view($this->view())->with('maintains', $maintains);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Maintain.{$viewName}";
    }

    /**
     * 报表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report()
    {
        $maintains = Maintain::with([
            'EntireInstances' => function ($query) {
                $query->where('status', '<>', 'SCRAP')
                    ->orderBy('status');
            }])
            ->orderByDesc('id')
            ->paginate();

        return view($this->view('report'), ['maintains' => $maintains]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $maintains = Maintain::orderByDesc('id')
            ->where('type', 'WORKSHOP')
            ->where(function ($q) {
                $q->where('parent_unique_code', null)
                    ->whereOr('parent_unique_code', '');
            })
            ->get();
        return view($this->view('create'))
            ->with('maintains', $maintains)
            ->with('page', \request()->page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
//        return $request->all();
        try {
            $v = Validator::make($request->all(), MaintainRequest::$RULES, MaintainRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $req = array_merge($request->all(), ['type'=> $request->parent_unique_code == '' ? 'WORKSHOP' : 'STATION']);

            $maintain = new Maintain;
            $maintain->fill($req)->saveOrFail();

            return Response::make('新建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误' . $exceptionMessage, 500);
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
     * @param string $uniqueCode
     * @return \Illuminate\Http\Response
     */
    public function edit($uniqueCode)
    {
        try {
            $maintains = Maintain::orderByDesc('id')
                ->where('type', 'WORKSHOP')
                ->where('unique_code','<>',$uniqueCode)
                ->where(function ($q) {
                    $q->where('parent_unique_code', null)
                        ->whereOr('parent_unique_code', '');
                })
                ->get();
            $maintain = Maintain::where('unique_code', $uniqueCode)->firstOrFail();
            return view($this->view())
                ->with('maintain', $maintain)
                ->with('maintains', $maintains)
                ->with('page', \request()->page);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return back()->with('danger', '意外错误');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uniqueCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uniqueCode)
    {
        try {
            $v = Validator::make($request->all(), MaintainRequest::$RULES, MaintainRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $req = array_merge($request->all(), ['type', $request->parent_unique_code == '' ? 'WORKSHOP' : 'STATION']);

            $maintain = Maintain::where('unique_code', $uniqueCode)->firstOrFail();
            $maintain->fill($req)->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误'.$exceptionMessage, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uniqueCode
     * @return \Illuminate\Http\Response
     */
    public function destroy($uniqueCode)
    {
        try {
            $maintain = Maintain::where('unique_code',$uniqueCode)->firstOrFail();
            $maintain->delete();
            if (!$maintain->trashed()) return Response::make('删除失败', 500);

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
