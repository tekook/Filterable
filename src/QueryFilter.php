<?php

namespace Tekook\Filterable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class QueryFilter extends Filter
{
    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * if search is allowed.
     *
     * @var bool
     */
    protected bool $allowSearch = false;

    /**
     * Taps a relation and returns the filtered query.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function tapRelation(Relation $relation) : Relation
    {
        $this->apply($relation->getQuery());
        return $relation;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        $this->applyRequest();
    }

    /**
     * Taps the query and returns it with filters applied.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function tap(Builder $builder): Builder
    {
        $this->apply($builder);
        return $builder;
    }
}
