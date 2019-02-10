<?php

namespace App\Dom;

class PairTag extends Node
{
    private $content;

    public function __construct($name, $attr = [], $content)
    {
        parent::__construct($name, $attr);

        $this->content = $content;
    }

    public function toString()
    {
        return sprintf(
            '<%s %s>%s</%s>',
            $this->getName(),
            $this->getAttributesToString(),
            $this->getContent(),
            $this->getName()
        );
    }

    public function getContent()
    {
        return $this->content;
    }
}
