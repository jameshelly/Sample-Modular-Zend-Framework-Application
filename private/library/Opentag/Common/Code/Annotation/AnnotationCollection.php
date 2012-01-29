<?php

namespace Opentag\Common\Code\Annotation;

use ArrayObject;

class AnnotationCollection extends ArrayObject
{
    public function hasAnnotation($class)
    {
        foreach ($this as $annotation) {
            if (get_class($annotation) == $class) {
                return true;
            }
        }
        return false;
    }
}
