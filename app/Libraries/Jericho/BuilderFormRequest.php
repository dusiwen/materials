<?php

namespace Jericho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BuilderFormRequest
{
    private static $_ins;
    private $_model;
    private $_request;

    /**
     * BuilderFormRequest constructor.
     * @param \Illuminate\Http\Request $request
     * @param Builder $model
     */
    private function __construct(\Illuminate\Http\Request $request, Builder $model)
    {
        $this->_model = $model;
        $this->_request = $request;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Builder $model
     * @return BuilderFormRequest
     */
    public static function ins(\Illuminate\Http\Request $request, Builder $model): BuilderFormRequest
    {
        return self::$_ins ?: new self($request, $model);
    }

    public function parse()
    {
        # where条件
        if (is_array(explode(',', $this->_request->get('whereField')))) {
            foreach (explode(',', $this->_request->get('whereField')) as $item) {
                if (is_array($this->_request->get($item))) {
                    $this->_model->whereBetween($item, $item);
                } else {
                    $this->_model->where($item, $this->_request->get($item, null));
                }
            }
        } else {
            $this->_model->where($this->_request->get('whereField'), $this->_request->get($this->_request->get('whereField')));
        }

        # select
        $this->_model->select($this->_request->get('select', '*'));

        # order
        $this->_model->orderBy($this->_request->get('orderField', null), $this->_request->get('orderDirection', null));

        switch ($this->_request->get('format', 'get')) {
            case 'paginate':
                return $this->_model->paginate($this->_request->get('paginate'));
                break;
            case 'get':
                return $this->_model->get();
                break;
            case 'first':
                return $this->_model->first();
                break;
        }
    }
}
