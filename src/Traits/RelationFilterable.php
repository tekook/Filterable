<?php


namespace Tekook\Filterable\Traits;

/**
 * Trait RelationFilterable
 *
 * @property \Illuminate\Database\Eloquent\Builder $builder
 * @property array $filterableRelations
 * @package Tekook\Filterable\Traits
 */
trait RelationFilterable
{

    public function with($relations)
    {
        if (!is_array($relations)) {
            $relations = [$relations];
        }
        $this->builder->with(array_intersect($this->filterableRelations, $relations));
    }

    public function has($relations)
    {
        if (!is_array($relations)) {
            $relations = [$relations];
        }
        $relations = array_intersect($this->filterableRelations, $relations);
        foreach ($relations as $relation) {
            $this->builder->has($relation);
        }

    }
}
