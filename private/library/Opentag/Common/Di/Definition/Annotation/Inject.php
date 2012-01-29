<?php

namespace Opentag\Common\Di\Definition\Annotation;

use Zend\Code\Annotation\Annotation;

class Inject implements Annotation
{

    protected $content = null;

    public function initialize($content)
    {
        $this->content = $content;
    }
}