<?php


namespace Tekook\Filterable;


use Illuminate\Database\Eloquent\Relations\Relation;

abstract class RelationFilter extends Filter
{
    /**
     * @var \Illuminate\Database\Eloquent\Relations\Relation
     */
    protected Relation $builder;

    public function apply(Relation $relation)
    {
        $this->builder = $relation;

        $this->applyRequest();
    }

    public function tap(Relation $relation): Relation
    {
        $this->apply($relation);

        return $relation;
    }
}
