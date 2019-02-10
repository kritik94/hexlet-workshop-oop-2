<?php

namespace App\Dom;

class SingleTag extends Node
{
    private $name;
    private $attr;

    public function __construct($name, $attr = [])
    {
        parent::__construct($name, $attr);
    }

    public function toString()
    {
        return sprintf(
            '<%s %s/>',
            $this->getName(),
            $this->getAttributesToString()
        );
    }
}
