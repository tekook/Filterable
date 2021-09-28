<?php


namespace Tekook\Filterable\Traits;


use Illuminate\Database\Eloquent\Builder;
use Tekook\Filterable\QueryFilter;

/**
 * Trait Filterable
 *
 * @package Tekook\Filterable\Traits
 * @method static Builder filter(QueryFilter $filter)
 */
trait Filterable
{
    /**
     * @param Builder $builder
     * @param QueryFilter $filter
     */
    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }
}
