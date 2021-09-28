<?php


namespace Tekook\Filterable;

use Illuminate\Support\Str;

class AttributeOptions
{
    /**
     * Name of the Attribute
     *
     * @var string
     */
    public string $name;
    public string $originalName;
    public bool $not = false;
    public bool $greater = false;
    public bool $less = false;
    public bool $equals = false;
    protected array $options = [
        'not__'     => 'not',
        'greater__' => 'greater',
        'less__'    => 'less',
        'equals__'  => 'equals',
    ];

    public function __construct(string $name)
    {
        $this->name = $this->originalName = Str::snake($name);
        for ($i = 0; $i < count($this->options); $i++) {
            $this->scanAttribute();
        }
    }

    protected function scanAttribute()
    {
        foreach ($this->options as $str => $attr) {
            if (Str::startsWith($this->name, $str)) {
                $this->{$attr} = true;
                $this->name = Str::substr($this->name, Str::length($str));
            }
        }
    }

    public function operator(): string
    {
        if ($this->greater) {
            return $this->equals ? '>=' : '>';
        } else {
            if ($this->less) {
                return $this->equals ? '<=' : '<';
            } else {
                return $this->not ? 'not like' : 'like';
            }
        }
    }

    public function applies(array $attributes): bool
    {
        return in_array($this->name, $attributes);
    }


}
