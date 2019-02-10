<?php

namespace App\Dom;

class Node
{
    private $name;
    private $attr;

    public function __construct($name, $attr = [])
    {
        $this->name = $name;
        $this->attr = $attr;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAttributes()
    {
        return $this->attr;
    }

    public function getAttributesToString()
    {
        return collect($this->getAttributes())
            ->map(function ($value, $attr) {
                return sprintf('%s="%s"', $attr, $value);
            })
            ->implode(' ');
    }

    public function isShort()
    {
        return mb_strlen($this->toString()) <= 10;
    }
}
