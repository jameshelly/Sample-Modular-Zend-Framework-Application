<?php

namespace Opentag\Common\Code\Reflection\DocBlock;

interface Tag
{
    public function getName();
    public function initialize($content);
}