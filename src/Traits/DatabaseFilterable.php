<?php


namespace Tekook\Filterable\Traits;


use Exception;
use Illuminate\Support\Str;
use Tekook\Filterable\AttributeOptions;

/**
 * Trait DatabaseFilterable
 *
 * @property \Illuminate\Http\Request $request
 * @property array $filterableAttributes
 * @property bool $allowSearch
 * @property array $nullableAttributes
 * @property array $dateAttributes
 * @property array $exactAttributes
 * @property \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $builder
 * @package Tekook\Filterable\Traits
 */
trait DatabaseFilterable
{

    protected function order($name)
    {
        $snakeName = Str::snake($name);
        if (!in_array($snakeName, $this->filterableAttributes)) {
            return;
        }
        $by = Str::lower($this->request->get('by')) == 'desc' ? 'desc' : 'asc';
        $this->builder->orderBy($snakeName, $by);
    }

    protected function searchTerm($value)
    {
        if (!$this->allowSearch) {
            return;
        }
        $this->builder->where(function ($query) use ($value) {
            /** @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query */
            foreach ($this->filterableAttributes as $relation => $attr) {
                if (is_array($attr)) {
                    foreach ($attr as $relation_attr) {
                        $query->orWhereHas($relation, function ($q) use ($value, $relation_attr) {
                            if(in_array($relation_attr, $this->exactAttributes)) {
                                $q->where($relation_attr, $value);
                            } else {
                                $q->where($relation_attr, 'like', '%' . $value . '%');
                            }
                        });
                    }
                } else {
                    if(in_array($attr, $this->exactAttributes)) {
                        $query->orWhere($attr, $value);
                    } else {
                        $query->orWhere($attr, 'like', '%' . $value . '%');
                    }
                }
            }
        });
    }

    protected function filterGeneric($name, $value)
    {
        $snakeName = Str::snake($name);
        $options = new AttributeOptions($name);
        if (!$options->applies($this->filterableAttributes)) {
            return;
        }
        if ($value != null) {
            if ($options->applies($this->dateAttributes ?? [])) {
                $this->dateFilter($value, $options);
            } else {
                if (is_array($value)) {
                    if ($options->not) {
                        $this->builder->whereNotIn($options->name, $value);
                    } else {
                        $this->builder->whereIn($options->name, $value);
                    }
                } else {
                    if ($options->greater || $options->less) {
                        $this->builder->where($options->name, $options->operator(), (int)$value);
                    } else {
                        $this->builder->where($options->name, $options->operator(), '%' . $value . '%');
                    }
                }
            }
        } else {
            if (!$options->applies($this->nullableAttributes ?? [])) {
                return;
            }
            $this->builder->whereNull($options->name, 'and', $options->not);
        }
    }

    /**
     * @param $value
     * @param AttributeOptions $options
     */
    protected function dateFilter($value, AttributeOptions $options): void
    {
        try {
            if (Str::contains($value, '||')) {
                $exp = explode('||', $value);

                $this->builder->whereBetween($options->name, [
                    carbon($exp[0])->toDateString(),
                    carbon($exp[1])->addDay()->toDateString(),
                ]);
            } else {
                $this->builder->whereDate($options->name, $options->operator(),
                    carbon($value)->toDateString());
            }
        } catch (Exception $e) {
        }
    }
}
