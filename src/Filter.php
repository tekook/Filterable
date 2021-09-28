<?php


namespace Tekook\Filterable;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filter
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Applies the request to this Filter.
     */
    protected function applyRequest()
    {
        foreach ($this->fields() as $field => $value) {
            $method = Str::camel($field);
            if (method_exists($this, $method)) {
                call_user_func([$this, $method], $value);
            } else {
                $this->filterGeneric($field, $value);
            }
        }
    }

    /**
     * @return array
     */
    protected function fields(): array
    {
        return $this->request->all();
    }

    /**
     * Method for filtering generic fields.
     *
     * @param $field
     * @param $value
     *
     * @return null
     */
    protected function filterGeneric($field, $value)
    {
        return null;
    }
}
